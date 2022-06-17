<?php

namespace Examples\Basic;

abstract class AbstractMyClass
{
	abstract protected function doPublicMethod(int $x): int;

	public function publicMethod(): int
	{
		$x = 15;
		$x = $this->doPublicMethod($x);

		return $x;
	}

	protected function protectedMethod(): void
	{
	}

	private function privateMethod(): void
	{
	}
}

class MySuperClass extends AbstractMyClass
{
	private bool $enabled = TRUE;

	public function publicMethod2()
	{

	}

	protected function doPublicMethod(int $x): int
	{
		return $x * 3;
	}
}

//$class = new AbstractMyClass();

$extendedClass = new MySuperClass();
$extendedClass->publicMethod(); // 45
$extendedClass->publicMethod2();
