<?php

use Core\Http\Security\Csrf;
use Core\Http\Service\Container;
use Core\Http\User\LoggedUser;

function loggedUser(): LoggedUser
{
    return Container::get()->loggedUser;
}

function flashRender(): void
{
    if (isset($_SESSION['flash']))
    {
        echo Container::get()->flash->render();
        Container::get()->flash->clear(); 
    }
}

function getCsrf(): string
{
    return Csrf::render();
}

function getClassesWithNamespacesRecursively(string $folderPath): array
{
    $classes = [];
    if (!is_dir($folderPath)) {
        throw new Exception("Le dossier spécifié n'existe pas : $folderPath");
    }
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));
    foreach ($files as $file) {
        if ($file->getExtension() === 'php') {
            $fileContent = file_get_contents($file->getPathname());
            $namespace = null;
            if (preg_match('/namespace\s+([^;]+);/', $fileContent, $matches)) {
                $namespace = $matches[1];
            }
            if (preg_match('/class\s+([^\s{]+)/', $fileContent, $matches)) {
                $className = $matches[1];
                $fullClassName = $namespace ? "$namespace\\$className" : $className;
                $classes[] = $fullClassName;
            }
        }
    }
    return $classes;
}

function getString($string, $delimiter) {
    // Find the position of the last occurrence of /
    $position = strrpos($string, $delimiter);
    
    // Check if / is found in the string
    if ($position !== false) {
        // Get the substring starting from the character after the last /
        return substr($string, $position + 1);
    } else {
        // If / is not found, return the original string
        return $string;
    }
}