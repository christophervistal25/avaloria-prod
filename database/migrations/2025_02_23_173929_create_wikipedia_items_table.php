<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wikipedia_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wikipedia_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wikipedia_items');
    }
};