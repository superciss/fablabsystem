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
        Schema::create('machine_product', function (Blueprint $table) {
            $table->id();
            $table->string('machine_name');
            $table->string('brand');
            $table->text('property_no');
            $table->enum('status', ['serviceable', 'non serviceable', 'return to supplier for repairing', 'functional'])->default('functional');
             $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
            $table->decimal('cost', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_product');
    }
};
