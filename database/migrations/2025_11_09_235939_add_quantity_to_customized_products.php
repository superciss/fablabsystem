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
                  $table->integer('quantity')->nullable()->after('total_price'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customized_products', function (Blueprint $table) {
                $table->dropColumn('quantity');
        });
    }
};
