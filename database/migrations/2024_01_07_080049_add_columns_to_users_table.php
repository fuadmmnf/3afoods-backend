<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('company_name')->nullable();
            $table->string('avn')->nullable();

            // Make existing columns nullable
            $table->string('name')->nullable()->change();
            $table->string('phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes made in the 'up' method
            $table->dropColumn('company_name');
            $table->dropColumn('avn');

            // Reset the changes to existing columns
            $table->string('name')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
        });
    }
};
