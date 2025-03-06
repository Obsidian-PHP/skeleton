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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initObsidian();     
        $io = new SymfonyStyle($input, $output);

        $entityName = $io->ask('What is the entity name ?');
        $propertyName = strtolower($entityName) . 'Repository';
        $tableName = strtolower($entityName);

        $createController = $this->createRepositoryFile($entityName, $propertyName, $tableName);
        $createMigration = $this->createMigrationFile($entityName, $tableName);
        
        if ($createController && $createMigration)
        {
            $this->createProperty($propertyName);
            $io->success('Successfull creating repository');
            $io->success('Successfull creating migration');
        } else {
            $io->error('Hmmm ! Error');
        }

        return COMMAND::SUCCESS;
    }

    public function createRepositoryFile(string $entityName, string $propertyName, string $tableName): bool
    {
        $filePath = dirname(__DIR__, 2) . '/app/Domain/' . ucfirst($entityName) . '/' . ucfirst($entityName) . 'Repository.php';
        $className = $entityName . 'Repository';

        $placeholders = [
            '{{propertyName}}' => $propertyName,
            '{{className}}' => ucfirst($className),
            '{{tableName}}' => strtolower($tableName),
            '{{entityName}}' => ucfirst( $entityName)
        ];
        return $this->generateClass($this->getFileTemplate('repository'), $filePath, $placeholders);
    }

    public function createMigrationFile(string $entityName, string $tableName): bool
    {
        $filePath = dirname(__DIR__, 2) . '/App/Migration/' . ucfirst($entityName) . 'Migration.php';
        $placeholders = [
            '{{name}}' => ucfirst($entityName),
            '{{tableName}}' => strtolower($tableName)
        ];
        return $this->generateClass($this->getFileTemplate('migration'), $filePath, $placeholders);
    }

    public function createProperty(string $propertyName): void
    {
        $filesystem = new Filesystem();
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