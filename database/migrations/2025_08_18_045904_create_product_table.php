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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // Stock Keeping Unit (unique code)
            $table->string('name'); // Product name
            $table->text('description')->nullable(); // Optional description
            $table->decimal('price', 12, 2); // Selling price
            $table->integer('stock')->default(0); // Current stock
            $table->string('unit')->default('pcs'); // Unit of measure (e.g., pcs, box, kg)
            $table->decimal('cost', 12, 2)->nullable(); // Purchase cost
            $table->longText('image')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); 
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
