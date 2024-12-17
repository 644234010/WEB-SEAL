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
        Schema::create('productshoes', function (Blueprint $table) {
            $table->id();
            $table-> string('pd_name');
            $table-> text('pd_detail');
            $table-> integer('pd_price');
            $table->string('pd_image')->nullable();
            $table->string('pd_image_1')->nullable();
            $table->string('pd_image_2')->nullable();
            $table->string('pd_image_3')->nullable();
            $table->integer('pd_stock'); 
            $table->integer('pd_Size'); 
            $table->integer('category_id'); 
            $table->string('pd_color'); 
            $table->timestamps();

          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productshoes');
    }
};
