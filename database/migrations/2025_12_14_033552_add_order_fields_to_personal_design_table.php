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
        Schema::table('personal_design', function (Blueprint $table) {
            $table->enum('deliver', ['is_ongoing','is_upcoming','for_pickup','for_delivery'])
                  ->nullable()
                  ->after('total_price');

            $table->enum('design_status', ['pending','processing','completed','cancelled'])
                  ->nullable()
                  ->after('deliver');

            $table->dateTime('estimate_date_design')->nullable()->after('design_status');
        });
    }

    public function down(): void
    {
        Schema::table('personal_design', function (Blueprint $table) {
            $table->dropColumn(['deliver', 'design_status', 'estimate_date_design']);
        });
    }
};
