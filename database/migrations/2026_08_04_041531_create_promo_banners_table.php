<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_banners', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow', 80)->nullable();
            $table->string('title', 120);
            $table->string('subtitle', 255)->nullable();
            $table->string('button_text', 60)->nullable();
            $table->string('button_url', 255)->nullable();
            $table->enum('button_style', ['dark', 'white', 'outline'])->default('white');
            $table->string('bg_color_start', 20)->default('#3d1c02');
            $table->string('bg_color_end', 20)->default('#6b3a1f');
            $table->enum('text_color', ['dark', 'light'])->default('light');
            $table->string('image_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_banners');
    }
};
