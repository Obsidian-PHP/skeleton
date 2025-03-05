<?php
namespace Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:migration',
    hidden: false,
)]
class MakeMigrationCommand extends \Core\Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initObsidian();
        $io = new SymfonyStyle($input, $output);
        $fileName = $io->ask('What is the migration name ?');

        if ($this->CreateMigrationFile($fileName))
        {
            $io->success('Success');
        }

        

        return COMMAND::SUCCESS;
    }

    public function CreateMigrationFile(string $fileName)
    {
        $filePath = dirname(__DIR__, 2) . '/App/Migration/' . ucfirst($fileName) . 'Migration.php';
        $placeholders = [
            '{{name}}' => ucfirst($fileName),
            '{{tableName}}' => strtolower($fileName),
        ];
        return $this->generateClass($this->getFileTemplate('migration'), $filePath, $placeholders);
    }
}