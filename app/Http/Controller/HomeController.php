<?php
namespace App\Http\Controller;

use Core\Controller;
use Core\Http\Request;
use Core\Http\Router\Route;
use Core\View;

class HomeController extends Controller
{
    #[Route('/', 'GET', 'default')]
    public function home(Request $_request): view
    {
        return $this->view('home');
    }

    #[Route('/crud', 'GET', 'default')]
    public function list(Request $_request): view
    {
        return $this->view('Crud/create');
    }
}