<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->longText('description')->nullable();
            $table->string('body_1_name')->nullable();
            $table->string('body_1_hair_color')->nullable();
            $table->string('body_2_name')->nullable();
            $table->string('body_2_hair_color')->nullable();
            $table->string('profile_image_1')->nullable();
            $table->string('profile_image_2')->nullable();
            $table->json('traits')->nullable();
            $table->json('abilities')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
