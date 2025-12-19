<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount', 12, 2)->nullable()->after('total_amount');   // any discount applied
            $table->decimal('tax', 12, 2)->nullable()->after('discount');            // tax or vat
            $table->decimal('grand_total', 12, 2)->nullable()->after('tax');         // final amount due
            $table->decimal('amount_paid', 12, 2)->nullable()->after('grand_total'); // money received
            $table->decimal('change_due', 12, 2)->nullable()->after('amount_paid');  // change to return
            $table->decimal('delivery_fee', 12, 2)->nullable()->after('change_due'); // delivery fee
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'discount', 
                'tax', 
                'grand_total', 
                'amount_paid', 
                'change_due', 
                'delivery_fee'
            ]);
        });
    }
};
