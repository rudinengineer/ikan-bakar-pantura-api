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
        Schema::create('user_access_items', function (Blueprint $table) {
            $table->id();
            $table->string('access_name', 50);
            $table->string('access_link', 50);
            $table->boolean('access_create')->default(true);
            $table->boolean('access_read')->default(true);
            $table->boolean('access_update')->default(true);
            $table->boolean('access_delete')->default(true);
            $table->boolean('access_member')->default(true);
            $table->boolean('access_menu')->default(true);
            $table->boolean('access_approve')->default(true);

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
        Schema::dropIfExists('user_access_items');
    }
};
