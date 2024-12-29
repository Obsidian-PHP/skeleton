<?php

namespace Core;

use Core\Database\Database;
use Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

class Command extends \Symfony\Component\Console\Command\Command
{
    public function initObsidian(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
        $dotenv->load();
        
        $db = new Database();
        $db->connection();
    }

    public function generateClass(string $sourceFile, string $targetFile, array $placeholders): bool
    {
        $filesystem = new Filesystem();        
        try {
            $content = file_get_contents($sourceFile);
            $updatedContent = str_replace(array_keys($placeholders), array_values($placeholders), $content);
            $filesystem->dumpFile($targetFile, $updatedContent);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getFileTemplate(string $name): string
    {
        try {
            return sprintf(__DIR__.'/Template/%s.template', $name);
        } catch (\Exception $th) {
            return sprintf('Error : %s', $name);  
        }
        
    }
}
