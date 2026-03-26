<?php

use App\Models\Role;
use App\Models\UserAccessItem;
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
        Schema::create('user_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserAccessItem::class);
            $table->foreignIdFor(Role::class);
            $table->boolean('access_create')->default(false);
            $table->boolean('access_read')->default(false);
            $table->boolean('access_update')->default(false);
            $table->boolean('access_delete')->default(false);
            $table->boolean('access_member')->default(false);
            $table->boolean('access_menu')->default(false);
            $table->boolean('access_approve')->default(false);

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
        Schema::dropIfExists('user_accesses');
    }
};
