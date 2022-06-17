<?php

namespace Examples\Patterns;

use InvalidArgumentException;

interface RendererInterface
{
	public function render(string $content, array $parameters): void;
}

class Renderer implements RendererInterface
{
	public function render(string $content, array $parameters): void
	{
		# ...
	}
}

class CachedRenderer implements RendererInterface
{
	public function render(string $content, array $parameters): void
	{
		# ...
	}
}

interface TemplateInterface
{
	public static function create(RendererInterface $renderer, string $content, array $parameters): self;

	public function addParameter(string $name, $value): void;

	public function render(): void;
}

class Template implements TemplateInterface
{
	private RendererInterface $renderer;

	private string $content;

	private array $parameters;

	public function __construct(RendererInterface $renderer, string $content, array $parameters)
	{
		$this->renderer = $renderer;
		$this->content = $content;
		$this->parameters = $parameters;
	}

	public static function create(RendererInterface $renderer, string $content, array $parameters): TemplateInterface
	{
		return new static($renderer, $content, $parameters);
	}

	public function addParameter(string $name, $value): void
	{
		$this->parameters[$name] = $value;
	}

	public function render(): void
	{
		$this->renderer->render($this->content, $this->parameters);
	}
}

interface TemplateFactoryInterface
{
	public function create(string $filename, array $parameters = [], string $templateClassname = Template::class): TemplateInterface;
}

class TemplateFactory implements TemplateFactoryInterface
{
	private RendererInterface $renderer;

	public function __construct(RendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}

	public function create(string $filename, array $parameters = [], string $templateClassname = Template::class): TemplateInterface
	{
		if (!is_subclass_of($templateClassname, TemplateInterface::class, TRUE)) {
			throw new InvalidArgumentException(sprintf(
				'Template classname must implement interface %s.',
				TemplateInterface::class
			));
		}

		if (!file_exists($filename)) {
			throw new InvalidArgumentException('File not exists.');
		}

		$content = @file_get_contents($filename);

		if (FALSE === $content) {
			throw new InvalidArgumentException('File is not readable.');
		}

		return $templateClassname::create($this->renderer, $content, $parameters);
	}
}

/******************/

$renderer = new Renderer();
$filename = __DIR__ . '/file.latte';

if (!file_exists($filename)) {
	throw new InvalidArgumentException('File not exists.');
}

$content = @file_get_contents($filename);

if (FALSE === $content) {
	throw new InvalidArgumentException('File is not readable.');
}

$template = new Template($renderer, $content, []);
$template->addParameter('foo', 15);
$template->render();

$filename = __DIR__ . '/file2.latte';

if (!file_exists($filename)) {
	throw new InvalidArgumentException('File not exists.');
}

$content = @file_get_contents($filename);

if (FALSE === $content) {
	throw new InvalidArgumentException('File is not readable.');
}

$template = new Template($renderer, $content, []);
$template->addParameter('foo', 15);
$template->render();

/******************/
$templateFactory = new TemplateFactory(new Renderer());

$template = $templateFactory->create(__DIR__ . '/tpl.latte');
$template->addParameter('foo', 15);
$template->render();

$template2 = $templateFactory->create(__DIR__ . '/tpl2.latte');
$template2->addParameter('foo', 15);
$template2->render();


/******************/

class AppTemplate extends Template
{
	public string $a;
}

class TemplateExampleApplication
{
	private TemplateFactoryInterface $templateFactory;

	public function __construct(TemplateFactoryInterface $templateFactory)
	{
		$this->templateFactory = $templateFactory;
	}

	public function run(): void
	{
		$template = $this->templateFactory->create(__DIR__ . '/tpl.latte', [
			'foo' => 15,
		], AppTemplate::class);

		assert($template instanceof AppTemplate);

		$template->a = 'something';

		$template->render();
	}
}

$application = new TemplateExampleApplication(new TemplateFactory(new CachedRenderer()));
$application->run();
