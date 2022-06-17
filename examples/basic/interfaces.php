<?php

namespace Examples\Basic;

use LogicException;

interface VehicleInterface
{
	public function getId(): int;

	public function getColor(): string;

	public function getBrand(): string;

	public function drive(): void;

	public function park(): void;
}

interface LockableVehicleInterface extends VehicleInterface
{
	public function lock(): void;

	public function unlock(): void;
}

abstract class Vehicle implements VehicleInterface
{
	private int $id;

	private string $color;

	private string $brand;

	public function __construct(int $id, string $color, string $brand)
	{
		$this->id = $id;
		$this->color = $color;
		$this->brand = $brand;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getColor(): string
	{
		return $this->color;
	}

	public function getBrand(): string
	{
		return $this->brand;
	}
}

class Car extends Vehicle implements LockableVehicleInterface
{
	public function drive(): void
	{
		$this->unlock();
		# ....
	}

	public function park(): void
	{
		# ...
		$this->lock();
	}

	public function lock(): void
	{
	}

	public function unlock(): void
	{
	}
}

class Bike extends Vehicle
{
	public function drive(): void
	{
	}

	public function park(): void
	{
	}
}

class Garage
{
	private array $vehicles = [];

	public function park(VehicleInterface $vehicle): void
	{
		if (isset($this->vehicles[$vehicle->getId()])) {
			throw new LogicException('The car is already parked.');
		}

		$vehicle->park();
		$this->vehicles[$vehicle->getId()] = $vehicle;

		if ($vehicle instanceof LockableVehicleInterface) {
			$vehicle->lock();
		}
	}

	public function leave(VehicleInterface $vehicle): void
	{
		if (!isset($this->vehicles[$vehicle->getId()])) {
			throw new LogicException('The car is not in the garage.');
		}

		$vehicle->drive();
		unset($this->vehicles[$vehicle->getId()]);
	}
}

$mercedes = new Car(1, '#fff', 'Mercedes');
$audi = new Car(2, '#OOO', 'Audi');
$hd = new Bike(3, 'red', 'Harley');

$garage = new Garage();

$garage->park($mercedes);
$garage->park($audi);
$garage->park($hd);

$garage->leave($mercedes);
