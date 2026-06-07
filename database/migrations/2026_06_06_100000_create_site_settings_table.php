<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->string('key', 64)->primary();
                $table->text('value');
                $table->timestamp('updated_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
