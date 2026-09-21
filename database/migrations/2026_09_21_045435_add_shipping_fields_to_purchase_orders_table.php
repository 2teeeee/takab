<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->date('shipped_at')
                ->nullable()
                ->after('order_date');

            $table->string('vehicle_type', 100)
                ->nullable()
                ->after('shipped_at');

            $table->string('vehicle_plate', 50)
                ->nullable()
                ->after('vehicle_type');

            $table->string('driver_name', 150)
                ->nullable()
                ->after('vehicle_plate');

            $table->string('driver_mobile', 30)
                ->nullable()
                ->after('driver_name');

            $table->text('shipping_note')
                ->nullable()
                ->after('driver_mobile');
        });

    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipped_at',
                'vehicle_type',
                'vehicle_plate',
                'driver_name',
                'driver_mobile',
                'shipping_note',
            ]);
        });
    }
};
