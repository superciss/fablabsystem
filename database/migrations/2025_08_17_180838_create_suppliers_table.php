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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Supplier name
            $table->string('contact_person')->nullable(); // Person to contact
            $table->string('email')->nullable()->unique(); // Email (optional but unique)
            $table->string('phone')->nullable(); // Phone number
            $table->string('address')->nullable(); // Supplier address
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
