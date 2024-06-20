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
        
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id") ;
            $table->string('name', 100);
            $table->string('state')->default('inactive');
            $table->string('brand');
            $table->longText('image');
            $table->longText('address');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
