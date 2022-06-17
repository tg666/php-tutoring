<?php

namespace Examples\Basic;

interface MyStaticInterface
{
	public static function doSomething(): void;
}

class MyStaticClass implements MyStaticInterface
{
	public static string $foo = 'foo';

	protected static int $bar = 10;

	private static float $baz = 15.0;

	//self;
	//static;
	//parent;

	public static function doSomething(): void
	{
		// TODO: Implement doSomething() method.
	}

	/**
	 * SELF
	 *
	 * @return int
	 */
	public static function staticPublicMethod(): int
	{
		return self::staticProtectedMethod();
	}

	/**
	 * STATIC
	 *
	 * @return int
	 */
	public static function staticPublicMethod2(): int
	{
		return static::staticProtectedMethod();
	}

	public static function getBar(): int
	{
		return static::$bar;
	}

	protected static function staticProtectedMethod(): int
	{
		return 15;
	}

	private static function staticPrivateMethod(): void
	{

	}
}

final class MyExtendedStaticClass extends MyStaticClass
{
	protected static int $bar = 20;

	protected static function staticProtectedMethod(): int
	{
		return 20;
	}

	public static function summary(): int
	{
		return self::staticProtectedMethod() + parent::staticProtectedMethod();
	}
}

MyStaticClass::doSomething();

$x = new MyStaticClass();
$x::doSomething();

$a = MyStaticClass::staticPublicMethod(); // 15
$b = MyExtendedStaticClass::staticPublicMethod(); // 15

$c = MyStaticClass::staticPublicMethod2(); // 15
$d = MyExtendedStaticClass::staticPublicMethod2(); // 20

$sum = MyExtendedStaticClass::summary(); // 35

# bar
$bar1 = MyStaticClass::getBar(); // 10
$bar2 = MyExtendedStaticClass::getBar(); // 20

/********************************************************/

final class Strings
{
	private function __construct()
	{
	}

	public static function lower(string $s): string
	{
		return strtolower($s);
	}
}

$lowercaseString = Strings::lower('ABC'); // abc
