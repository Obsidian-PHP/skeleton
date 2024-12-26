<?php
namespace Core\Command;

use Core\Http\Service\Container;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($input, $output);
        $entityName = $io->ask('What is the entity name ?');

        if ($entityName)
        {
            // Make Repository
            $repositoryPath = dirname(__DIR__, 2) . '/App/Domain/' . $entityName . '/' . $entityName . 'Repository.php';
            $propertyName = strtolower($entityName) . 'Repository';
            $className = $entityName . 'Repository';
            $tableName = strtolower($entityName) . 's';
            $repositoryContent = sprintf('<?php namespace App\Domain\%s;

use Core\Http\Register;
use Core\Repository;
use Illuminate\Support\Collection;

#[Register("%s", %s::class)]
class %s extends Repository
{
    public function getAll(): Collection
    {
        return $this->table("%s")
            ->get();
    }

    public function getSingle(int $id): object
    {
        return $this->table("%s")
            ->where("id", $id)
            ->first();
    }
}',$entityName, $propertyName, $className, $className, $tableName, $tableName);
            $crateRepository = Container::get()->file->createAndWriteFile($repositoryPath, $repositoryContent);
            
            if ($crateRepository)
            {
                $io->success('Successfull make repository : ' . $className);
            }
        }
        
        return COMMAND::SUCCESS;
    }
}