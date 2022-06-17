<?php

namespace Examples\DI;

use RuntimeException;

final class Container1
{
	private array $parameters;

	private array $services = [];

	/**
	 * @param array $parameters
	 */
	public function __construct(array $parameters = [])
	{
		$this->parameters = $parameters;
	}

	public function getUser(): User
	{
		if ($this->services['user']) {
			return $this->services['user'];
		}

		return $this->services['user'] = new User($this->getAuthenticator());
	}

	public function getAuthenticator(): AuthenticatorInterface
	{
		if ($this->services['authenticator']) {
			return $this->services['authenticator'];
		}

		return $this->services['authenticator'] = new DatabaseAuthenticator($this->getConnection());
	}

	public function getConnection(): Connection
	{
		if ($this->services['connection']) {
			return $this->services['connection'];
		}

		return $this->services['connection'] = new Connection($this->getParam('connection.dns'));
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

$container = new Container1([
	'connection.dns' => '...',
]);

$user = $container->getUser();
$user->login('foo', 'bar');

$identity = $user->getIdentity(); // identity

$container->getUser();
$container->getConnection()->executeQuery('...', []);
