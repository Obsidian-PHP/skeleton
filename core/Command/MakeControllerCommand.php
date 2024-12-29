<?php
namespace Core\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:controller',
    hidden: false,
)]
class MakeControllerCommand extends \Core\Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initObsidian();
        $io = new SymfonyStyle($input, $output);
        $fileName = $io->ask('What is the controller name ?');
        
        // Make controller
        $controllerPath = dirname(__DIR__, 2) . '/App/Http/Controller/' . $fileName . 'Controller.php';
        $controllerRoute = strtolower($fileName);
        $placeholders = [
            '{{name}}' => $fileName,
            '{{view}}' => $fileName,
            '{{route}}' => $controllerRoute,
        ];
        $createController = $this->generateClass($this->getFileTemplate('controller'), $controllerPath, $placeholders);

        // Make view
        $viewPath = dirname(__DIR__, 2) . '/App/View/' . strtolower($fileName) . '.view.php';
        $placeholders = [
            '{{viewPath}}' => $viewPath,
            '{{controllerPath}}' => $controllerPath,
            '{{name}}' => $fileName,
        ];
        $createView = $this->generateClass($this->getFileTemplate('view'), $viewPath, $placeholders);

        if ($createController && $createView)
        {
            $io->success('Successfull creating controller');
            $io->success('Successfull creating view');
        } else {
            $io->error('Hmmm ! Error');
        }

        return COMMAND::SUCCESS;
    }
}