<?php
namespace Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'make:package:auth',
    hidden: false,
)]
class MakeAuthPackage extends \Core\Command
{
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $this->initObsidian();        
        $this->createFileAndFolder($io);
        $this->createProperty($io);

        return COMMAND::SUCCESS;
    }

    public function createFileAndFolder(SymfonyStyle $io): void
    {
        $filesystem = new Filesystem();
        $sourceFolder = dirname(__DIR__, 1).'/Template/Package/User/';
        $finalFolder = dirname(__DIR__, 2).'/App';

        try {
            $filesystem->mirror($sourceFolder, $finalFolder);
            $io->success('Successfully created Package');
        } catch (\Throwable $th) {
            $io->error('Error: ' . $th);
        }
    }

    public function createProperty(SymfonyStyle $io): void
    {
        $filesystem = new Filesystem();
        $filename = dirname(__DIR__, 2) . '/app/Registry/RegisterContainer.php';
        $currentContent = file_get_contents($filename);
        $newPropertyCode = "    public \$authService;\n    public \$userService;\n    public \$userRepository;";

        $updatedContent = preg_replace(
            '/class\s+(\w+)\s*\{/',
            "class $1 {\n$newPropertyCode",
            $currentContent
        );

        try {
            $filesystem->dumpFile($filename, $updatedContent);
            $io->success('Successfully created Properties');
        } catch (\Throwable $th) {
            $io->error('Error: ' . $th);
        }
    }
}