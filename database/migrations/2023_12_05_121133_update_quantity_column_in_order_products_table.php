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
        Schema::table('order_products', function (Blueprint $table) {
            $table->decimal('quantity', 10, 2)->change(); // Change the column type to decimal
        });
    }

    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('quantity')->change(); // Change back to integer on rollback if needed
        });
    }
};
