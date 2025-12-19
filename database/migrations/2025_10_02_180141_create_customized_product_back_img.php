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
       Schema::create('customized_product_back_img', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customized_product_id')->constrained('customized_products')->onDelete('cascade');
    $table->longText('back_img')->nullable(); 
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customized_product_back_img');
    }
};
