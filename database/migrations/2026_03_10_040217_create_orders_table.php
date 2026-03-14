<?php

use App\Models\Store;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class);
            $table->integer('user_id')->nullable();
            $table->string('order_id', 50);
            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->dateTime('booking_date');
            $table->integer('customer_total');
            $table->text('note')->nullable();
            $table->bigInteger('total');
            $table->bigInteger('payment_total');
            $table->enum('payment_method', ['full', 'dp'])->default('dp');
            $table->string('payment_image');
            $table->enum('status', ['completed', 'confirmed', 'pending', 'canceled'])->default('pending');
            $table->string('device_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
