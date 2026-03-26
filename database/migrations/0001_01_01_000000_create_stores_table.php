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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('logo')->nullable();
            $table->string('area')->nullable();
            $table->text('address')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('qris')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
