<?php
namespace Core\Command;

use PhpParser\Node\Expr\Cast\Bool_;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

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

        $inFolder = $io->ask('View in folder ? | yes or no');

        $createView = '';

        if ($inFolder === 'yes')
        {
            $createView = $this->createViewFile($fileName, true);
            $createController = $this->createControllerFile($fileName, true);
        }
        if ($inFolder === 'no')
        {
            $createView = $this->createViewFile($fileName, false);
            $createController = $this->createControllerFile($fileName, false);
        }

        if ($createController && $createView)
        {
            $io->success('Successfull creating controller');
            $io->success('Successfull creating view');
        } else {
            $io->error('Hmmm ! Error');
        }

        return COMMAND::SUCCESS;
    }

    public function createControllerFile(string $fileName, bool $inFolder): bool
    {
        $controllerPath = dirname(__DIR__, 2) . '/App/Http/Controller/' . ucfirst($fileName) . 'Controller.php';
        $controllerRoute = strtolower($fileName);

        if ($inFolder)
        {
            $viewPath = ucfirst($fileName) . '/home';
        } else {
            $viewPath = strtolower($fileName);
        }

        $placeholders = [
            '{{name}}' => ucfirst($fileName),
            '{{view}}' => $viewPath, 
            '{{route}}' => strtolower($controllerRoute),
        ];

        return $this->generateClass($this->getFileTemplate('controller'), $controllerPath, $placeholders);
    }

    public function createViewFile(string $fileName, bool $inFolder): bool
    {
        $viewFolder = dirname(__DIR__, 2) . '/App/View/';

        if ($inFolder) {
            $fileSystem = new Filesystem();

            try {
                $fileSystem->mkdir($viewFolder . '/' . ucfirst($fileName), 0700);
            } catch (\Throwable $th) {
                // Error
            }

            $viewPath = $viewFolder . ucfirst($fileName) . '/home.view.php';
        } else {
            $viewPath = $viewFolder . strtolower($fileName) . '.view.php';
        }

        $placeholders = [
            '{{viewPath}}' => $viewPath,
            '{{controllerPath}}' => 'NaN',
            '{{name}}' => $fileName,
        ];
        return $this->generateClass($this->getFileTemplate('view'), $viewPath, $placeholders);
    }
}