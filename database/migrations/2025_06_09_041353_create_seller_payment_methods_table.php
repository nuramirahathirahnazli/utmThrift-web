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
        Schema::create('seller_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->enum('method', ['Banking', 'QR', 'Cash']);
            $table->text('account_info')->nullable(); // Bank acc or payment notes
            $table->string('qr_code_path')->nullable(); // QR image filename or Cloudinary URL
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_payment_methods');
    }
};
