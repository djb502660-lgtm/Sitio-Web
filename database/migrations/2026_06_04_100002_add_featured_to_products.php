<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            $this->createProductsTable();

            return;
        }

        if (Schema::hasColumn('products', 'featured')) {
            return;
        }

        if (Schema::hasColumn('products', 'nombre')) {
            Schema::disableForeignKeyConstraints();
            Schema::drop('products');
            Schema::enableForeignKeyConstraints();
            $this->createProductsTable();

            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'status')) {
                $table->enum('status', ['available', 'unavailable'])->default('available');
            }
            $table->boolean('featured')->default(false);
        });
    }

    public function down(): void
    {
        // No revertir: la tabla legacy no es compatible con CAFEESQUINA
    }

    private function createProductsTable(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->boolean('featured')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }
};
