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
        $controllerContent = sprintf("<?php
namespace App\Http\Controller;

use Core\Controller;
use Core\Http\Router\Route;
use Core\View;

class %sController extends Controller
{
    #[Route('/$controllerRoute', 'GET')]
    public function list(): view
    {
        return view('$fileName');
    }
}",$fileName, $fileName);

        // Make view
        $viewPath = dirname(__DIR__, 2) . '/App/View/' . strtolower($fileName) . '.view.php';
        $viewContent = sprintf('<div class="hero bg-base-200 min-h-screen">
    <div class="hero-content text-center">
        <div>
            <h1 class="text-4xl font-bold">Hello %sController ✅</h1>
            
            <p class="text-left mt-5">Your controller at</p>
            <div class="mockup-code mt-2 text-left">
                <pre><code>%s</code></pre>
            </div>

            <p class="text-left mt-5">Your template at</p>
            <div class="mockup-code mt-2 text-left">
                <pre><code>%s</code></pre>
            </div>
        </div>
    </div>
</div>',$fileName, $controllerPath, $viewPath);

        $createController = Container::get()->file->createAndWriteFile($controllerPath, $controllerContent);
        $createView = Container::get()->file->createAndWriteFile($viewPath, $viewContent);

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