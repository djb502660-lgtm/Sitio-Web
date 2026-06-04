<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $this->cafeesquinaUserColumns($table);
            });

            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name', 100)->nullable();
            }
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }
            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['client', 'admin'])->default('client');
            }
        });
    }

    public function down(): void
    {
        // No revertir en producción para evitar pérdida de datos
    }

    private function cafeesquinaUserColumns(Blueprint $table): void
    {
        $table->id();
        $table->string('username', 50)->unique();
        $table->string('email', 100)->unique();
        $table->string('password');
        $table->string('full_name', 100)->nullable();
        $table->string('phone', 20)->nullable();
        $table->enum('role', ['client', 'admin'])->default('client');
        $table->rememberToken()->nullable();
        $table->timestamps();
    }
};
