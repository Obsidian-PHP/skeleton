<?php

namespace Core;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class File
{
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function createAndWriteFile(string $filePath, string $content): bool
    {
        if ($this->fileExist($filePath)) return false;
        try {
            $this->filesystem->dumpFile($filePath, $content);
            return true;
        } catch (IOExceptionInterface $exception) {
            return false;
        }
    }

    public function fileExist(string $filePath): bool
    {
        if ($this->filesystem->exists($filePath)) return true;
        return false;
    }
}