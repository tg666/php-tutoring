<?php

namespace Examples\DI;

use RuntimeException;

interface ApplicationInterface
{
	public function run(): void;
}

final class Application implements ApplicationInterface
{
	private User $user;

	private Connection $connection;

	public function __construct(User $user, Connection $connection)
	{
		$this->user = $user;
		$this->connection = $connection;
	}

	public function run(): void
	{
		$this->user->login($_POST['username'], $_POST['password']);

		if (!$this->user->isLoggedIn()) {
			throw new RuntimeException('Invalid credentials.');
		}

		$orders = $this->connection->executeQuery('SELECT * FROM "order" WHERE customer_id = :user_id', [
			'user_id' => $this->user->getId(),
		]);

		# ...
	}
}
