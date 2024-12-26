<?php
namespace App\Http\Controller;

use Core\Controller;
use Core\Http\Router\Route;
use Core\View;

class TestController extends Controller
{
    #[Route('/test', 'GET')]
    public function list(): view
    {
        return view('Test');
    }
}