<?php
namespace Core;
use Core\Command\MakeAuthPackage;
use Core\Command\MakeControllerCommand;
use Core\Command\MakeEntityCommand;
use Core\Command\MigrateCommand;
use Core\Http\Service\Container;
use Symfony\Component\Console\Application;

class RegisterCommand {
    public function run()
    {
        $commmandManager = new Application();
        $commmandManager->add(new MigrateCommand());
        $commmandManager->add(new MakeControllerCommand());
        $commmandManager->add(new MakeEntityCommand());
        $commmandManager->add(new MakeAuthPackage());

        // Auto register command
        Container::get()->registerCommand($commmandManager);
        $commmandManager->run();
    }
}