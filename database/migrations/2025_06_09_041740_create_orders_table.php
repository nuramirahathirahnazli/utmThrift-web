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

            $table->unsignedBigInteger('buyer_id');      // references users.id
            $table->unsignedBigInteger('item_id');       // references items.id
            $table->unsignedBigInteger('seller_id');     // references sellers.id

            $table->integer('quantity')->default(1);
            $table->enum('payment_method', ['Online Banking', 'QR Code', 'Meet Up']);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
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
