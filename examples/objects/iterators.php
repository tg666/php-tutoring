<?php

namespace Examples\Iterators;

use IteratorAggregate;
use ArrayIterator;
use Generator;

// array
// interface Traversable
// Generator

class Collection implements IteratorAggregate
{
	private array $items;

	public function __construct(array $items)
	{
		$this->items = $items;
	}

	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->items);
	}
}

class Product
{
}

class ProductCollection implements IteratorAggregate
{
	private array $items;

	public function __construct(array $items)
	{
		foreach ($items as $product) {
			$this->add($product);
		}
	}

	public function add(Product $product): void
	{
		$this->items[] = $product;
	}

	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->items);
	}
}


class IteratorExample
{
	// string, int, float, boolean, ?string, ?int, ?float, ?string
	// object
	// MyClass, MyInterface
	// iterable

	private iterable $iterator;

	public function __construct(iterable $iterator)
	{
		$this->iterator = $iterator;
	}

	public function run(): void
	{
		foreach ($this->iterator as $k => $v) {
			var_dump([$k => $v]);
		}
	}
}


$arrayIteratorExample = new IteratorExample([
	'a', 'b', 'c',
]);

$arrayIteratorExample->run();

/*
 * 0 => a
 * 1 => b
 * 2 => c
 */

$iteratorIteratorExample = new IteratorExample(new Collection([
	'a', 'b', 'c',
]));

$iteratorIteratorExample->run();

/*
 * 0 => a
 * 1 => b
 * 2 => c
 */

$productIteratorExample = new IteratorExample(new ProductCollection([
	new Product(),
	new Product(),
	new Product(),
]));

$productIteratorExample->run();

/*
 * 0 => Product instance
 * 1 => Product instance
 * 2 => Product instance
 */

$generator = static function (array $items): Generator
{
	foreach ($items as $item) {
		yield $item;
	}
};

$generatorExample = new IteratorExample($generator([
	new Product(),
	new Product(),
	new Product()
]));

$generatorExample->run();

/*
 * 0 => Product instance
 * 1 => Product instance
 * 2 => Product instance
 */

