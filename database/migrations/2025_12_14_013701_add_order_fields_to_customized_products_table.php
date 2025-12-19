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
            $table->enum('delivery_customized', ['is_ongoing','is_upcoming','for_pickup','for_delivery'])
                  ->nullable()
                  ->after('quantity');

            $table->enum('customized_status', ['pending','processing','completed','cancelled'])
                  ->nullable()
                  ->after('delivery_customized');

            $table->dateTime('estimate_date_custom')->nullable()->after('customized_status');
        });
    }

    public function down(): void
    {
        Schema::table('customized_products', function (Blueprint $table) {
            $table->dropColumn(['customized_products', 'customized_status', 'estimate_date_custom']);
        });
    }
};
