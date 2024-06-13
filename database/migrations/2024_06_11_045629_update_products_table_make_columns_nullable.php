<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('desc')->nullable()->change();
            $table->string('img')->nullable()->change();
            $table->string('unit')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('desc')->nullable(false)->change();
            $table->string('img')->nullable(false)->change();
            $table->string('unit')->nullable(false)->change();
        });
    }
};
