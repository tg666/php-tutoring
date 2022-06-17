<?php

namespace Examples\Patterns;

use Exception;

class FileNotFound extends Exception
{}

class FileNotReadable extends Exception
{}

interface FileManagerInterface
{
	public function read(string $path): string;

	public function save(string $path, string $content): void;

	public function delete(string $path): void;

	public function withAdapter(FileStorageAdapterInterface $adapter): self;
}

interface FileStorageAdapterInterface
{
	public function read(string $path): string;

	public function create(string $path, string $content): void;

	public function update(string $path, string $content): void;

	public function delete(string $path): void;

	public function has(string $path): bool;
}

class LocalFileStorageAdapter implements FileStorageAdapterInterface
{
	public function read(string $path): string
	{
		if (!file_exists($path)) {
			throw new FileNotFound(sprintf(
				'File %s not found.',
				$path
			));
		}

		$content = @file_get_contents($path);

		if (FALSE === $content) {
			throw new FileNotReadable(sprintf(
				'File %s is not readable.',
				$path
			));
		}

		return $content;
	}

	public function create(string $path, string $content): void
	{
		file_put_contents($path, $content);
	}

	public function update(string $path, string $content): void
	{
		$this->create($path, $content);
	}

	public function delete(string $path): void
	{
		if (!file_exists($path)) {
			throw new FileNotFound(sprintf(
				'File %s not found.',
				$path
			));
		}

		unlink($path);
	}

	public function has(string $path): bool
	{
		return file_exists($path);
	}
}

class AwsFileStorageAdapter implements FileStorageAdapterInterface
{
	public function __construct(object $awsClient)
	{
	}

	public function read(string $path): string
	{
		// TODO: Implement read() method.
	}

	public function create(string $path, string $content): void
	{
		// TODO: Implement create() method.
	}

	public function update(string $path, string $content): void
	{
		// TODO: Implement update() method.
	}

	public function delete(string $path): void
	{
		// TODO: Implement delete() method.
	}

	public function has(string $path): bool
	{
		// TODO: Implement has() method.
	}
}

final class FileManager implements FileManagerInterface
{
	private FileStorageAdapterInterface $adapter;

	public function __construct(FileStorageAdapterInterface $adapter)
	{
		$this->adapter = $adapter;
	}

	public function read(string $path): string
	{
		return $this->adapter->read($path);
	}

	public function save(string $path, string $content): void
	{
		if ($this->adapter->has($path)) {
			$this->adapter->update($path, $content);

			return;
		}

		$this->adapter->create($path, $content);
	}

	public function delete(string $path): void
	{
		$this->adapter->delete($path);
	}

	public function withAdapter(FileStorageAdapterInterface $adapter): FileManagerInterface
	{
		return new self($adapter);
	}
}

$fileManager = new FileManager(new LocalFileStorageAdapter());

$fileManager->save('./file.txt', 'text');
$content = $fileManager->read('./file.txt');
$fileManager->delete('./file.txt');

$awsFileManager = $fileManager->withAdapter(new AwsFileStorageAdapter(new \stdClass()));

$awsFileManager->save('./file.txt', 'text');
$content = $awsFileManager->read('./file.txt');
$awsFileManager->delete('./file.txt');
