<?php

namespace Examples\Patterns;

class Notebook
{
	private string $brand;

	public function __construct(string $brand)
	{
		$this->brand = $brand;
	}

	public function getBrand(): string
	{
		return $this->brand;
	}
}

$notebook = new Notebook('Lenovo');

class NotebookFactory
{
	public function create(string $brand): Notebook
	{
		return new Notebook($brand);
	}
}

$factory = new NotebookFactory();

$notebook1 = $factory->create('Lenovo');
$notebook2 = $factory->create('HP');
