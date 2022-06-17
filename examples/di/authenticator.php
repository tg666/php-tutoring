<?php

namespace Examples\DI;

interface AuthenticatorInterface
{
	public function authenticate(string $username, string $password): ?UserIdentity;
}

final class DatabaseAuthenticator implements AuthenticatorInterface
{
	private Connection $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	public function authenticate(string $username, string $password): ?UserIdentity
	{
		$user = $this->connection->executeQuery('SELECT * FROM "user" WHERE username = :username', [
			'username' => $username,
		]);

		if (NULL === $user || !password_verify($password, $user['password'])) {
			return NULL;
		}

		return new UserIdentity($user['id'], $user['role'], $user);
	}
}

final class ApiAuthenticator implements AuthenticatorInterface
{
	private object $client;

	public function __construct(object $client)
	{
		$this->client = $client;
	}

	public function authenticate(string $username, string $password): ?UserIdentity
	{
		return new UserIdentity(1, 'admin', []);
	}
}
