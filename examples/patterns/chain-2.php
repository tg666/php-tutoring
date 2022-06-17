<?php

namespace Examples\Chain2;

use RuntimeException;

interface LocaleResolverInterface
{
	public function resolve(): string;
}

interface ChainableLocaleResolverInterface extends LocaleResolverInterface
{
	public function canResolve(): bool;
}

class DefaultLocaleResolver implements ChainableLocaleResolverInterface
{
	private string $locale;

	public function __construct(string $locale)
	{
		$this->locale = $locale;
	}

	public function canResolve(): bool
	{
		return TRUE;
	}

	public function resolve(): string
	{
		return $this->locale;
	}
}

class UrlLocaleResolver implements ChainableLocaleResolverInterface
{
	public function canResolve(): bool
	{
		return isset($_GET['locale']);
	}

	public function resolve(): string
	{
		return $_GET['locale'];
	}
}

class SessionLocaleResolver implements ChainableLocaleResolverInterface
{
	public function canResolve(): bool
	{
		return FALSE;
	}

	public function resolve(): string
	{
	}
}

class CookieLocaleResolver implements ChainableLocaleResolverInterface
{
	public function canResolve(): bool
	{
		return FALSE;
	}

	public function resolve(): string
	{
	}
}

class LoggedInUserLocaleResolver implements ChainableLocaleResolverInterface
{
	public function canResolve(): bool
	{
		return FALSE;
	}

	public function resolve(): string
	{
	}
}

class LocaleResolverChain implements LocaleResolverInterface
{
	private array $resolvers;

	public function __construct(array $resolvers)
	{
		$this->resolvers = (static fn (ChainableLocaleResolverInterface ...$resolvers): array => $resolvers)(...$resolvers);
	}

	public function resolve(): string
	{
		foreach ($this->resolvers as $resolver) {
			if ($resolver->canResolve()) {
				return $resolver->resolve();
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
