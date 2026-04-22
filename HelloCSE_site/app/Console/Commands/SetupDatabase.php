<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PDO;
use PDOException;

class SetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database if not exists and run migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //pour éviter un bug au lancement de la commande la premiere fois car la bdd n'est pas encore creer
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        try {
            $pdo = new PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $this->info("Database `$database` created or already exists.");

            $this->call('migrate');

            return Command::SUCCESS;
        } catch (PDOException $e) {
            $this->error('Database setup failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
