<?php

namespace Core\Http\Service;

use App\Registry\RegisterContainer;
use Core\Http\Service\RegisterServiceContainer;
use Core\Http\Test;

class Container extends RegisterServiceContainer
{
    public function __construct()
    {
        $container = new ServiceContainer();
        $this->registerListener();
        $this->registerService($container);
        $this->registerOther($container);
    }

    public static function get(): RegisterServiceContainer
    {
        $class = get_called_class();
        if(!isset(self::$_instance[$class]))
        {
            self::$_instance[$class] = new $class;
        }
        return self::$_instance[$class];
    }
}