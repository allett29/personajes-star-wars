<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personajes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->unsignedSmallInteger('altura')->nullable();
            $table->unsignedSmallInteger('masa')->nullable();
            $table->string('color_cabello', 100)->nullable();
            $table->string('color_ojos', 100)->nullable();
            $table->string('genero', 20);
            $table->string('planeta_origen', 100)->nullable();
            $table->boolean('origen_swapi')->default(false);
            $table->unsignedTinyInteger('swapi_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personajes');
    }
};