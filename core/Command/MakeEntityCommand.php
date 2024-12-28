<?php
namespace Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'make:entity',
    hidden: false,
)]
class MakeEntityCommand extends \Core\Command
{
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initObsidian();
        $filesystem = new Filesystem();        

        $io = new SymfonyStyle($input, $output);
        $entityName = $io->ask('What is the entity name ?');

        if ($entityName)
        {
            // Make Repository
            $templatePath = dirname(__DIR__, 1).'/Template/template_repository.php';
            $repositoryPath = dirname(__DIR__, 2) . '/app/Domain/' . $entityName . '/' . $entityName . 'Repository.php';
            $propertyName = strtolower($entityName) . 'Repository';
            $className = $entityName . 'Repository';
            $tableName = strtolower($entityName) . 's';

            $placeholders = [
                '{{propertyName}}' => $propertyName,
                '{{className}}' => $className,
                '{{tableName}}' => $tableName,
                '{{entityName}}' => $entityName
            ];
            $req = $this->generateClass($templatePath, $repositoryPath, $placeholders);

            
            if ($req)
            {
                // Create property
                $filename = dirname(__DIR__, 2) . '/app/Registry/RegisterContainer.php';
                $currentContent = file_get_contents($filename);
                $newPropertyCode = "\n    public \$".$propertyName.";\n";
                $updatedContent = preg_replace(
                    '/class\s+(\w+)\s*\{/',
                    "class $1 {\n$newPropertyCode",
                    $currentContent
                );
                $filesystem->dumpFile($filename, $updatedContent);
            }
        }
        
        return COMMAND::SUCCESS;
    }
}