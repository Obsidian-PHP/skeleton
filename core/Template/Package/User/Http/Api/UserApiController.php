<?php
namespace App\Http\Api;

use Core\Controller;
use Core\Http\Request;
use Core\Http\Router\Route;

class UserApiController extends Controller
{
    #[Route('/api/users/login', 'POST', 'default')]
    public function login(Request $_request): void
    {
        $req = $this->container->authService->authenticate($_request);
        if ($req) {$this->redirect('/');} else {$this->redirect('/users/login');}
    }

    #[Route('/api/users/logout', 'GET', 'auth')]
    public function logout(): void
    {
        $req = $this->container->authService->signout();
        if ($req) {$this->redirect('/');}
    }
}