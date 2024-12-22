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
class MigrateCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'File migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        $db = new Database();
        $db->connection();

        $req = DB::table('migrations')
                ->where(['file' => $file])
                ->get();
        
        if ($req[0])
        {
            if (class_exists($req[0]->class))
            {
                $migrate = new $req[0]->class();
                $migrate->up();
                
                $io->success('Migration successful');
            } else {
                $io->error('Migration class does not exist');
            }
        } else {
            $io->error('Migration does not exist');
        }

        return COMMAND::SUCCESS;
    }
}