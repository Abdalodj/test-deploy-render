<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('template_type');
            $table->json('sections'); // Stockera l'ordre dans chaque section
            $table->timestamps();
        });



    }


    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
};