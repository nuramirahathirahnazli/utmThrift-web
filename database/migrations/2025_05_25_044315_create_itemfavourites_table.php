<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ni untuk kalau user ada suka item,
     * akan simpan dalam table ni.
     * Ini untuk fungsi like pada item.
     */
    public function up(): void
    {
        Schema::create('itemfavourites', function (Blueprint $table) {
             $table->id();

            $table->unsignedBigInteger('user_id')->comment('User who liked the item');
            $table->unsignedBigInteger('item_id')->comment('Item that was liked');

            $table->timestamps();

            $table->unique(['user_id', 'item_id']); // prevent duplicate likes

            // foreign key constraints if needed:
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemfavourites');
    }
};
