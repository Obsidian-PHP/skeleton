<?php
namespace App\Http\Controller;

use Core\Controller;
use Core\Http\Request;
use Core\Http\Router\Route;
use Core\View;

class UserController extends Controller
{
    #[Route('/users/login', 'GET', 'default')]
    public function login(Request $_request): View
    {
        return $this->view('user/login', 'main');
    }

    #[Route('/users/account', 'GET', 'default')]
    public function account(Request $_request): void
    {
        dump(loggedUser()->getUser());
    }

    #[Route('/users', 'GET', 'default')]
    public function all(Request $_request): View
    {
        $query = $this->container->userRepository->getAll();
        return $this->view('user/all', 'main', [
            'users' => $query
        ]);
    }

    #[Route('/users/{username}', 'GET', 'default')]
    public function show(Request $_request): void
    {
        $query = $this->container->userRepository->getByUsername($_request->getParams('username'));
        dump($query);
    }
}