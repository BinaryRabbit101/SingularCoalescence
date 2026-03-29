<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->renameColumn('profile_image_1', 'profile_image');
            $table->renameColumn('body_1_name', 'body_name');
            $table->renameColumn('body_1_hair_color', 'body_hair_color');
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->dropColumn(['profile_image_2', 'body_2_name', 'body_2_hair_color']);
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->renameColumn('profile_image', 'profile_image_1');
            $table->renameColumn('body_name', 'body_1_name');
            $table->renameColumn('body_hair_color', 'body_1_hair_color');
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->string('profile_image_2')->nullable();
            $table->string('body_2_name')->nullable();
            $table->string('body_2_hair_color')->nullable();
        });
    }
};
