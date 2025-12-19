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
        Schema::table('user_information', function (Blueprint $table) {
         $table->boolean('phone_verified')->default(false)->after('contact_number');
         $table->string('phone_verification_code', 6)->nullable()->after('phone_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_information', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'phone_verification_code']);
        });
    }
};
