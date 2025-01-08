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
        $this->initObsidian();
        $io = new SymfonyStyle($input, $output);
        
        $this->createFileAndFolder();
        $this->createProperty();

        return COMMAND::SUCCESS;
    }

    public function createFileAndFolder(): void
    {
        $filesystem = new Filesystem();
        $sourceFolder = dirname(__DIR__, 1).'/Template/Package/User/';
        $finalFolder = dirname(__DIR__, 2).'/App';
        $filesystem->mirror($sourceFolder, $finalFolder);
    }

    public function createProperty(): void
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
        
        $filesystem->dumpFile($filename, $updatedContent);
    }
}