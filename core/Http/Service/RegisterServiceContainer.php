<?php
namespace Core\Http\Service;

use App\Registry\RegisterContainer;
use Core\File;
use Core\Http\Listener\EventDispatcher;
use Core\Http\Listener\ListenerProvider;
use Core\Http\Security\Csrf;
use Core\Http\Register;
use Core\Http\Security\Voter\Permission;
use Core\Http\User\LoggedUser;
use Core\Http\Service\ServiceContainer;
use Core\Http\User\UserInterface;
use Core\Session\Flash;
use Core\Session\SessionManager;
use Symfony\Component\Console\Application;

class RegisterServiceContainer extends RegisterContainer
{
    public static array $_instance = [];
    public Csrf $csrf;
    public Flash $flash;
    public File $file;
    public SessionManager $session;
    public UserInterface $loggedUser;
    public EventDispatcher $dispatcher;

    public function registerService(ServiceContainer $container): void
    {
        $folderPath = dirname(__DIR__, 3) . '/app/Domain';
        $classes = getClassesWithNamespacesRecursively($folderPath);
        $container = new ServiceContainer();

        foreach ($classes as $class)
        {
            if (str_contains($class, "Service") or str_contains($class, "Repository")) {
                $finished = new $class();
                $reflection = new \ReflectionClass($finished);
                $attributes = $reflection->getAttributes(Register::class);
                
                foreach ($attributes as $attribute) {
                    $instance = $attribute->newInstance();
                    $varName = $instance->getName();
                    $className = $instance->getClass();

                    $this->$varName = $container->get($className);
                }   
            }
        }
    }

    public function registerVoter(Permission $permission): void
    {
        $folderPath = dirname(__DIR__, 3) . '/app/Http/Security';
        $classes = getClassesWithNamespacesRecursively($folderPath);
        foreach ($classes as $class)
        {
            $finished = new $class();           
            $permission->addVoter($finished);  
        }
    }

    public function registerCommand(Application $command)
    {
        $folderPath = dirname(__DIR__, 3) . '/app/Command';
        $classes = getClassesWithNamespacesRecursively($folderPath);
        foreach ($classes as $class)
        {
            $finished = new $class();
            $command->add($finished);
        }
        
    }
    
    public function registerOther(ServiceContainer $container): void
    {
        $this->session = $container->get(SessionManager::class);
        $this->flash = $container->get(Flash::class);
        $this->csrf = $container->get(Csrf::class);
        $this->loggedUser = $container->get(LoggedUser::class);
        $this->file = $container->get(File::class);
    }

    public function registerListener(): void
    {
        $listenerProvider = (new ListenerProvider());
        $this->dispatcher = new EventDispatcher($listenerProvider);
    }
}