<?php
namespace Core\Command;

use Core\Http\Service\Container;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'test',
    hidden: false,
)]
class TestCommand extends \Core\Command
{
    protected function configure(): void
    {
        //$this->addArgument('name', InputArgument::REQUIRED, 'Create controller file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initObsidian();
        $io = new SymfonyStyle($input, $output);

        $io->confirm('Restart the web server?', true);
        
        return COMMAND::SUCCESS;
    }
}