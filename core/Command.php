<?php

namespace Core;

use Core\Database\Database;
use Dotenv\Dotenv;

class Command extends \Symfony\Component\Console\Command\Command
{
    public function initObsidian(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
        $dotenv->load();
        
        $db = new Database();
        $db->connection();
    }
}
