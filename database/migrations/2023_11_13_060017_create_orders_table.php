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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Use unsignedBigInteger for foreign key reference
            $table->string('type');
            $table->string('fname');
            $table->string('lname');
            $table->string('company_name');
            $table->string('address');
            $table->string('phone_num');
            $table->string('email');
            $table->string('additional_info')->nullable();
            $table->float('total_price')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable(); // Use unsignedBigInteger for foreign key reference
            $table->boolean('paid')->default('False'); // Changed data type to boolean
//            ['draft','pending','completed']
            $table->timestamps();

            // Foreign key relationships with cascade options
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade')->onUpdate('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
