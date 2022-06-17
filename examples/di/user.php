<?php

namespace Examples\DI;

class UserIdentity
{
	private int $id;

	private string $role;

	private array $data;

	public function __construct(int $id, string $role, array $data)
	{
		$this->id = $id;
		$this->role = $role;
		$this->data = $data;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function getData(): array
	{
		return $this->data;
	}
}

class User
{
	private AuthenticatorInterface $authenticator;

	private ?UserIdentity $identity = NULL;

	public function __construct(AuthenticatorInterface $authenticator)
	{
		$this->authenticator = $authenticator;
	}

	public function getId(): ?int
	{
		return NULL !== $this->identity ? $this->identity->getId() : NULL;
	}

	public function getRole(): ?string
	{
		return NULL !== $this->identity ? $this->identity->getRole() : NULL;
	}

	public function isLoggedIn(): bool
	{
		return NULL !== $this->identity;
	}

	public function getIdentity(): ?UserIdentity
	{
		return $this->identity;
	}

	public function login(string $username, string $password): void
	{
		$this->identity = $this->authenticator->authenticate($username, $password);
	}
}

$connection = new Connection('db_string');
$authenticator = new DatabaseAuthenticator($connection);

$user = new User($authenticator);

$user->isLoggedIn(); // false
$user->getIdentity(); // null

$user->login('foo', 'bar');

$user->isLoggedIn(); // true
$user->getIdentity(); // UserIdentity object
