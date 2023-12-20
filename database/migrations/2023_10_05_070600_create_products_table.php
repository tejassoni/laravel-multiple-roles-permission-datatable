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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',150)->nullable();            
            $table->text('description')->nullable();
            $table->integer('parent_category_id')->unsigned()->nullable();
            $table->integer('sub_category_id')->unsigned()->nullable();
            $table->string('image')->nullable();
            $table->double('price',12,2)->nullable();
            $table->integer('qty')->unsigned()->nullable();
            $table->tinyInteger('status')->comment('1 = Active, 0 = In-Active')->nullable()->default(1);
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
