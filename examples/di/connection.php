<?php

namespace Examples\DI;

final class Connection
{
	public function __construct(string $dns)
	{
	}

	public function executeQuery(string $sql, array $params): ?array
	{
		return [];
	}
}
