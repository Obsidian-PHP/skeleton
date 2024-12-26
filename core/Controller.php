<?php

namespace Core;

use Core\Http\Security\voter\RegisterVoter;
use Core\Http\Security\Voter\Security;
use Core\Http\Service\Container;
use Core\Http\User\LoggedUser;
use Core\Http\User\UserInterface;
use Core\View;
use Symfony\Component\Finder\Exception\AccessDeniedException;

abstract class Controller
{
    protected Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function getUserOrThrow(): UserInterface
    {
        $user = new LoggedUser();

        if (!$user instanceof UserInterface)
        {
            throw new AccessDeniedException();
        }
        return $user;
    }

    public function view(string $name, ?string $layout = null, array $params = []): View
    {
        $layout = $layout ?? View::$MAIN_LAYOUT;
        return new View($name, $layout, $params);
    }

    public function redirect(string $location): void
    {
        header('location:'.$location);
    }

    public static function isGranted(mixed $attribute, mixed $subject = null) {
        $security = new RegisterVoter();
        if (!$security->authorizationChecker($attribute, $subject)) trigger_error('Access error'); 
    }

    public function getControllerReflector()
    {
        return new \ReflectionClass($this);
    }
}
