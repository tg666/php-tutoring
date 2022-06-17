<?php

namespace Examples\DI;

use RuntimeException;
use Exception;

final class ServiceNotFound extends Exception
{}

interface ArgumentInterface
{}

class Reference implements ArgumentInterface
{
	public string $nameOrType;

	public function __construct(string $nameOrType)
	{
		$this->nameOrType = $nameOrType;
	}
}

class Parameter implements ArgumentInterface
{
	public string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}
}

class ServiceDefinition
{
	public string $name;

	public string $classname;

	/** @var \Examples\DI\ArgumentInterface[] */
	public array $arguments;

	public string $type;

	public function __construct(string $name, string $classname, array $arguments = [], ?string $type = NULL)
	{
		$this->name = $name;
		$this->classname = $classname;
		$this->arguments = $arguments;
		$this->type = $type ?? $classname;
	}
}

interface ContainerInterface
{
	public function has(string $name): bool;

	public function get(string $name);
}

final class Container2 implements ContainerInterface
{
	private array $parameters;

	/** @var \Examples\DI\ServiceDefinition[] */
	private array $definitions;

	private array $services = [];

	public function __construct(array $definitions, array $parameters = [])
	{
		$this->definitions = $definitions;
		$this->parameters = $parameters;
	}

	public function has(string $name): bool
	{
		foreach ($this->definitions as $definition) {
			if ($definition->name === $name || $definition->type === $name) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public function get(string $name)
	{
		if (isset($this->services[$name])) {
			return $this->services[$name];
		}

		foreach ($this->definitions as $definition) {
			if ($definition->name === $name || $definition->type === $name) {
				$service = $this->createService($definition);
				$this->services[$definition->name] = $service;
				$this->services[$definition->type] = $service;

				return $service;
			}
		}

		throw new ServiceNotFound(sprintf(
			'Service with name %s not found.',
			$name
		));
	}

	private function createService(ServiceDefinition $definition)
	{
		$args = [];

		foreach ($definition->arguments as $argument) {
			if ($argument instanceof Parameter) {
				$args[] = $this->getParam($argument->name);

				continue;
			}

			if ($argument instanceof Reference) {
				$args[] = $this->get($argument->nameOrType);
			}
		}

		$classname = $definition->classname;

		return $classname(...$args);
	}

	private function getParam(string $name)
	{
		if (!array_key_exists($name, $this->parameters)) {
			throw new RuntimeException(sprintf(
				'Param %s not found.',
				$name
			));
		}

		return $this->parameters[$name];
	}
}

$container = new Container2([
	new ServiceDefinition('connection', Connection::class, [
		new Parameter('connection.dns'),
	]),
	new ServiceDefinition('authenticator', DatabaseAuthenticator::class, [
		new Reference(Connection::class),
	], AuthenticatorInterface::class),
	new ServiceDefinition('user', User::class, [
		new Reference(AuthenticatorInterface::class),
	]),
	new ServiceDefinition('application', Application::class, [
		new Reference(User::class),
		new Reference(Connection::class),
	], ApplicationInterface::class)
], [
	'connection.dns' => '...',
]);

$container->get(ApplicationInterface::class)->run();
