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

        Schema::create('shipping_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('business_name');
            $table->string('avn')->nullable();
            $table->string('contact_info');
            $table->string('website_name')->nullable();
            $table->string('file'); //store the file path
            $table->text('additional_information')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_products');
    }
};
