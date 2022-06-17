<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace Examples\Basic;

trait ColorTrait
{
	private string $color;

	abstract protected function getName(): string;

	public function stringify(): string
	{
		return sprintf(
			'%s(%s)',
			static::class,
			$this->getName()
		);
	}

	public function getColor(): string
	{
		return $this->color;
	}
}

class Car
{
	use ColorTrait {
		getColor as getColorOriginal;
	}

	private string $name;

	public function __construct(string $name, string $color)
	{
		$this->name = $name;
		$this->color = $color;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getColor(): string
	{
		$color = $this->getColorOriginal();

		return 'color(' . $color . ')';
	}
}

class Building
{
	use ColorTrait;

	private int $height;

	private string $street;

	public function __construct(int $height, string $color, string $street)
	{
		$this->height = $height;
		$this->color = $color;
		$this->street = $street;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	public function getStreet(): string
	{
		return $this->street;
	}

	protected function getName(): string
	{
		return $this->getStreet();
	}
}

$car = new Car('Mercedes', '#456acd');
$car->getColor(); // color(#456acd)
$car->getName(); // Mercedes
$car->stringify(); // Car(Mercedes)

$building = new Building(300, '#ad6788', 'Praha 3');
$building->getColor(); // #ad6788
$building->getHeight(); // 300
$building->getStreet(); // Praha 3
$building->stringify(); // Building(Praha 3)
