<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products') || Schema::hasColumn('products', 'featured')) {
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
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'featured')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('featured');
            });
        }
    }
};
