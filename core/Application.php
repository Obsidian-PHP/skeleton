<?php

declare(strict_types=1);

namespace Core;

use Core\Database\Database;
use Core\Http\Middleware\MiddlewareStack;
use Core\Http\Response;
use Core\Http\Router\Route;
use Dotenv\Dotenv;

final class Application
{
    public Router $router;

    public Response $response;

    public MiddlewareStack $middlewares;

    public Database $database;

    public static string $ROOT_DIR;

    public function __construct()
    {
        $this->router = new Router();
        $this->response = new Response();
        $this->middlewares = new MiddlewareStack();
        $this->database = new Database();

        self::$ROOT_DIR = dirname(__DIR__);
    }

    public function run(): void
    {
        session_start();

        $dotenv = Dotenv::createImmutable(self::$ROOT_DIR);
        $dotenv->load();

        $this->database->connection();
        
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();

        echo $this->router->resolve();
    }

    function registerRoute($app, $controller)
    {
        $class = new \ReflectionClass($controller);
    
        $methods = $class->getMethods();
    
        foreach ($methods as $mehtod)
        {
            $attributes = $mehtod->getAttributes(Route::class);
        
            foreach ($attributes as $attribute)
            {
                $instace = $attribute->newInstance();
    
                if ($instace->getMethod() === 'GET') {
                    $app->router->get($instace->getRoute(), [$controller, $mehtod->getName()], $instace->getMiddleware());
                }
    
                if ($instace->getMethod() === 'POST') {
                    $app->router->post($instace->getRoute(), [$controller, $mehtod->getName()]);
                }
            }
        }
    }

    public function registerController($app): void
    {
        $folderPath = dirname(__DIR__, 1) . '/app/Http/Controller';
        try {
            $classes = getClassesWithNamespacesRecursively($folderPath);
            foreach ($classes as $class)
            {
                $test = new $class();
                $this->registerRoute($app, $test);
            }
            
        } catch (\Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    public function registerControllerApi($app): void
    {
        $folderPath = dirname(__DIR__, 1) . '/app/Http/Api';
        try {
            $classes = getClassesWithNamespacesRecursively($folderPath);
            foreach ($classes as $class)
            {
                $test = new $class();
                $this->registerRoute($app, $test);
            }
            
        } catch (\Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    public function registerMiddleware($app)
    {
        $folderPath = dirname(__DIR__, 1) . '/app/Http/Middleware';
        try {
            $classes = getClassesWithNamespacesRecursively($folderPath);
            foreach ($classes as $class)
            {
                $finalClass = new $class();
                $middlewareName = strtolower(explode('Middleware',  getString($class, '\\'))[0]);
                $app->middlewares->add($middlewareName, get_class($finalClass));
            }
            
        } catch (\Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}