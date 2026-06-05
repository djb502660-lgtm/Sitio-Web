<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        // Tabla legacy de Laravel (name) sin columnas CAFEESQUINA
        if (Schema::hasColumn('users', 'name') && ! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 50)->nullable()->unique()->after('id');
                $table->string('full_name', 100)->nullable();
                $table->string('phone', 20)->nullable();
                $table->enum('role', ['client', 'admin'])->default('client');
            });

            return;
        }

        // Instalaciones antiguas: añadir columnas que falten
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
};
