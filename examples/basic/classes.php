<?php

namespace Examples\Basic;

class MyClass
{
	public const MY_PUBLIC = 15;
	protected const MY_PROTECTED = 20;
	private const MY_PRIVATE = ['a', 'b', 'c'];

	public string $foo;

	protected int $bar;

	private float $baz;

	public function __construct(string $foo, int $bar, float $baz = 15.0)
	{
		$this->foo = $foo;
		$this->bar = $bar;
		$this->baz = $baz;
	}

	public function publicMethod(): void
	{

	}

	protected function protectedMethod(): void
	{

	}

	private function privateMethod(): void
	{

	}
}

class MyExtendedClass extends MyClass
{
	private bool $enabled;

	public function __construct(string $foo, int $bar, float $baz = 15.0, bool $enabled = TRUE)
	{
		parent::__construct($foo, $bar, $baz);

		$this->enabled = $enabled;
	}

	public function publicMethod2()
	{

	}
}

$class = new MyClass('foo', 15, 30.0);
$class->foo = 'a';
$class->publicMethod();

$extendedClass = new MyExtendedClass('bar', 10);
$extendedClass->publicMethod();
$extendedClass->publicMethod2();
