<?php

namespace Examples\MutableVsImmutable;

use DateTime;
use DateTimeImmutable;

$mutableDt = new DateTime('2022-01-01 12:00:00');
$immutableDt = new DateTimeImmutable('2022-01-01 12:00:00');

$modifiedMutableDt = $mutableDt->modify('+1 hour');
$modifiedImmutableDt = $immutableDt->modify('+1 hour');

$mutableDt->format('H:i:s'); // 13:00:00
$modifiedMutableDt->format('H:i:s'); // 13:00:00

$immutableDt->format('H:i:s'); // 12:00:00
$modifiedImmutableDt->format('H:i:s'); // 13:00:00

$a = new DateTime('2022-01-01 12:00:00');
$b = $a->modify('+1 day');

if ($a === $b) {
	echo 'ano';
}

$a = new DateTimeImmutable('2022-01-01 12:00:00');
$b = $a->modify('+1 day');

if ($a !== $b) {
	echo 'ne';
}

interface CarInterface
{
	public function getColor(): string;

	public function changeColor(string $color): self;
}

class Car implements CarInterface
{
	private string $color;

	public function __construct(string $color)
	{
		$this->color = $color;
	}

	public function getColor(): string
	{
		return $this->color;
	}

	public function changeColor(string $color): self
	{
		$this->color = $color;

		return $this;
	}
}

class CarImmutable implements CarInterface
{
	private string $color;

	public function __construct(string $color)
	{
		$this->color = $color;
	}

	public function getColor(): string
	{
		return $this->color;
	}

	public function changeColor(string $color): self
	{
		return new self($color);
	}
}

class CarService
{
	public function paint(CarInterface $car, string $color): CarInterface
	{
		// ....
		return $car->changeColor($color);
	}
}

$car = new Car('#fff');
$car->setColor('#000');
$car->getColor(); // #000

$car = new CarImmutable('#fff');
$newCar = $car->withColor('#000');


$carService = new CarService();
$mutableCar = new Car('#fff');
$immutableCar = new CarImmutable('#fff');

$blackMutableCar = $carService->paint($mutableCar, '#000');
$blackImmutableCar = $carService->paint($immutableCar, '#000');

if ($mutableCar->getColor() === $blackMutableCar->getColor()) { // true

}

if ($immutableCar->getColor() === $blackImmutableCar->getColor()) { // false

}
