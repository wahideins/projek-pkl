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
        Schema::create('navmenu', function (Blueprint $table) {
            $table->increments('menu_id');
            $table->string('menu_nama', 50);
            $table->string('menu_link', 100);
            $table->string('menu_icon', 30);
            $table->unsignedInteger('menu_child');
            $table->integer('menu_order');
            $table->integer('menu_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navmenu');
    }
};
