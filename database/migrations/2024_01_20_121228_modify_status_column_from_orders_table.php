<?php

use App\Http\Enums\OrderStatusEnum;
use App\Models\Order;
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
            $table->string('status')->default('opened')->change();
        });

        $orders = Order::all();

        $orders->each(function ($order) {
            if ($order->status === 'pending') {
                $order->status = OrderStatusEnum::Opened->value;
            } else if ($order->status === 'approved') {
                $order->status = OrderStatusEnum::Accomplished->value;
            } else if ($order->status === 'canceled') {
                $order->status = OrderStatusEnum::Cancelled->value;
            } else if ($order->status === 'reimbursed') {
                $order->status = OrderStatusEnum::Cancelled->value;
            }

            $order->save();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('opened')->change();
        });
    }
};
