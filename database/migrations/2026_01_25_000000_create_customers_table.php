<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            $table->decimal('delivery_price', 10, 2)->default(0);
            $table->unsignedBigInteger('driver_id')->nullable();
            
            $table->date('date_order')->nullable();
            
            $table->date('delivery_date')->nullable();
            
            $table->decimal('discount', 10, 2)->default(0);
            $table->boolean('blocked')->default(0);
            
            $table->unsignedBigInteger('invoice_id')->nullable();
            
            $table->foreign('driver_id')->references('id')->on('drivers')->nullOnDelete();
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};