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
        Schema::create('category_subcategory_pivot', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_category_id')->unsigned()->nullable();
            $table->integer('sub_category_id')->unsigned()->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_subcategory_pivot');
    }
};
