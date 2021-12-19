<?php

declare(strict_types=1);

namespace Lex\Mock\Driver;

use Lex\Mock\MockInterface;
use RuntimeException;

final class FileMock implements MockInterface
{
    public string $dirPath;

    public function __construct()
    {
        $this->dirPath = '';
    }

    private function getFilePath(array $keys): string
    {
        $fileName = implode('_', $keys);
        $this->dirPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $this->dirPath);
        if ($this->dirPath[mb_strlen($this->dirPath) - 1] !== DIRECTORY_SEPARATOR) {
            $this->dirPath .= DIRECTORY_SEPARATOR;
        }
        return $this->dirPath . $fileName;
    }

    public function acquire(array $keys): bool
    {
        if (!file_exists($this->dirPath) && !mkdir($concurrentDirectory = $this->dirPath) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        if (!$this->isAcquired($keys)) {
            touch($this->getFilePath($keys));
        }
        return false;
    }

    public function isAcquired(array $keys): bool
    {
        return file_exists($this->getFilePath($keys));
    }

    public function release(array $keys): bool
    {
        if (!$this->isAcquired($keys)) {
            return false;
        }
        return unlink($this->getFilePath($keys));
    }
}
