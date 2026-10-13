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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_name')->nullable()->after('order_status');
            $table->string('consignment_id')->nullable()->index()->after('courier_name');
            $table->string('tracking_code')->nullable()->after('consignment_id');
            $table->string('courier_status')->nullable()->after('tracking_code');
            $table->text('courier_response')->nullable()->after('courier_status');
            $table->timestamp('shipped_at')->nullable()->after('courier_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier_name',
                'consignment_id',
                'tracking_code',
                'courier_status',
                'courier_response',
                'shipped_at',
            ]);
        });
    }
};
