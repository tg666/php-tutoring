<?php

namespace Examples\Basic;

abstract class AbstractCommand
{
	abstract protected function doRun(array $args, callable $output): void;

	final public function run(array $args, callable $output): void
	{
		$output('Running command ' . $args['name'] . '.');
		# ...

		$this->doRun($args, $output);
	}
}

final class CreateFileCommand extends AbstractCommand
{
	protected function doRun(array $args, callable $output): void
	{
		# ... create file
	}

	/*public function run(array $args, callable $output): void
	{
		parent::run($args, $output);
	}*/
}

class CreateDirectoryCommand extends AbstractCommand
{
	protected function doRun(array $args, callable $output): void
	{
		# ... create directory
	}
}

/*class CreateFile2Command extends CreateFileCommand
{

}*/
