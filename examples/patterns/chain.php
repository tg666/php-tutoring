<?php

namespace Examples\Chain;

use RuntimeException;

interface LocaleResolverInterface
{
	public function resolve(): ?string;
}

class DefaultLocaleResolver implements LocaleResolverInterface
{
	private string $locale;

	public function __construct(string $locale)
	{
		$this->locale = $locale;
	}

	public function resolve(): ?string
	{
		return $this->locale;
	}
}

class UrlLocaleResolver implements LocaleResolverInterface
{
	public function resolve(): ?string
	{
		return $_GET['locale'] ?? NULL;
	}
}

class SessionLocaleResolver implements LocaleResolverInterface
{
	public function resolve(): ?string
	{
	}
}

class CookieLocaleResolver implements LocaleResolverInterface
{
	public function resolve(): ?string
	{
	}
}

class LoggedInUserLocaleResolver implements LocaleResolverInterface
{
	public function resolve(): ?string
	{
	}
}

class LocaleResolverChain implements LocaleResolverInterface
{
	private array $resolvers;

	public function __construct(array $resolvers)
	{
		$this->resolvers = (static fn (LocaleResolverInterface ...$resolvers): array => $resolvers)(...$resolvers);
	}

	public function resolve(): ?string
	{
		foreach ($this->resolvers as $resolver) {
			$locale = $resolver->resolve();

			if (NULL !== $locale) {
				return $locale;
			}
		}

		throw new RuntimeException('Can\'t resolve locale.');
	}
}

class LocaleExampleApplication
{
	private LocaleResolverInterface $localeResolver;

	public function __construct(LocaleResolverInterface $localeResolver)
	{
		$this->localeResolver = $localeResolver;
	}

	public function run(): void
	{
		$locale = $this->localeResolver->resolve();
	}
}

$resolver = new LocaleResolverChain([
	new LoggedInUserLocaleResolver(),
	new UrlLocaleResolver(),
	new CookieLocaleResolver(),
	new DefaultLocaleResolver('en')
]);

$app = new LocaleExampleApplication($resolver);
$app->run();
