<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();

            $table->unsignedBigInteger('user_id')->default(1);

            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('id_truck')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_active')->default(0);
            $table->date('today_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
