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
       Schema::create('product_options', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
    $table->string('type'); // color, texture, material, etc.
    $table->string('name'); // e.g. "Glossy Red", "Metallic Blue"
    $table->decimal('extra_price', 12, 2)->default(0);
    
    
    $table->foreignId('texture_id')->nullable()->constrained('textures')->onDelete('cascade');
    
    $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
