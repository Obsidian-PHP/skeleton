<?php
namespace application\migrations;

use Core\Database\Migration\Migration;
use Core\Database\Migration\MigrationInterface;
use Illuminate\Database\Schema\Blueprint;

class UserMigration extends Migration implements MigrationInterface
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('role');
            $table->dateTime('login_attempt');
            $table->integer('last_login');
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {

    }
}