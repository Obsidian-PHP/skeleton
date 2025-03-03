<?php
namespace Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Illuminate\Database\Capsule\Manager as DB;
use Core\Database\Database;

#[AsCommand(
    name: 'migration:migrate',
    hidden: false,
)]
class MigrateCommand extends \Core\Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initObsidian();
        $io = new SymfonyStyle($input, $output);

        $folderPath = dirname(__DIR__, 2) . '/app/Migration';
        $classes = getClassesWithNamespacesRecursively($folderPath);
        foreach ($classes as $class)
        {
            $class = new $class();           
            try {
                $class->up();
                $io->success(get_class($class));
            } catch (\Throwable $th) {
                $io->error(get_class($class));
            }
        }

        return COMMAND::SUCCESS;
    }
}