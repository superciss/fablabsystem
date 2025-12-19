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
        Schema::create('customized_products', function (Blueprint $table) {
         $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
           $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
            $table->longText('front_image')->nullable(); // base64 PNG snapshot
            $table->longText('back_image')->nullable();
             $table->boolean('approved')->default(false);
            $table->text('description')->nullable(); // added description
            $table->decimal('total_price', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customized_products');
    }
};