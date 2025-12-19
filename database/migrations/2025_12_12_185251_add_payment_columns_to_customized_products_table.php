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
        Schema::table('customized_products', function (Blueprint $table) {
            $table->enum('payment_type', ['partial', 'full'])->nullable()->after('total_price');
            $table->decimal('partial_amount', 8, 2)->nullable()->after('payment_type');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customized_products', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'partial_amount', 'payment_status']);
        });
    }
};
