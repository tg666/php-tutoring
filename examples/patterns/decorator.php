<?php

namespace Examples\Patterns;

class MarkdownParser
{
	public function parse(string $text): string
	{
		return $text;
	}
}

/*****************/

interface TextFormatterInterface
{
	/**
	 * @param string $text
	 *
	 * @return string
	 */
	public function format(string $text): string;
}

class BaseTextFormatter implements TextFormatterInterface
{
	public function format(string $text): string
	{
		# ...
		return $text;
	}
}

class EscapedTextFormatter implements TextFormatterInterface
{
	private TextFormatterInterface $inner;

	public function __construct(TextFormatterInterface $inner)
	{
		$this->inner = $inner;
	}

	public function format(string $text): string
	{
		return htmlspecialchars($this->inner->format($text));
	}
}

class UppercaseTextFormatter implements TextFormatterInterface
{
	private TextFormatterInterface $inner;

	public function __construct(TextFormatterInterface $inner)
	{
		$this->inner = $inner;
	}

	public function format(string $text): string
	{
		return strtoupper($this->inner->format($text));
	}
}

class MarkdownTextFormatter implements TextFormatterInterface
{
	private TextFormatterInterface $inner;

	private MarkdownParser $markdownParser;

	public function __construct(TextFormatterInterface $inner, MarkdownParser $markdownParser)
	{
		$this->inner = $inner;
		$this->markdownParser = $markdownParser;
	}

	public function format(string $text): string
	{
		$text = $this->markdownParser->parse($text);

		return $this->inner->format($text);
	}
}

final class DecoratorExampleApplication
{
	private TextFormatterInterface $textFormatter;

	public function __construct(TextFormatterInterface $textFormatter)
	{
		$this->textFormatter = $textFormatter;
	}

	public function run(): void
	{
		$text = <<<EOT
<ul>
	<li>foo</li>
	<li>bar</li>
	<li>baz</li>
</ul>

# Title

- foo
- bar
- baz

EOT;

		$formattedText = $this->textFormatter->format($text);
		# ...
	}
}

$formatter = new UppercaseTextFormatter(
	new MarkdownTextFormatter(
		new EscapedTextFormatter(
			new BaseTextFormatter()
		),
		new MarkdownParser()
	)
);

$app = new DecoratorExampleApplication($formatter);
$app->run();
