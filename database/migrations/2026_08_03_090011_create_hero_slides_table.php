<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('eyebrow')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->enum('button_style', ['dark', 'white'])->default('dark');
            $table->string('bg_color_start', 20)->default('#f5af02');
            $table->string('bg_color_end', 20)->default('#f9e07a');
            $table->enum('text_color', ['dark', 'light'])->default('dark');
            // Up to 3 right-side feature images
            $table->string('img1_path')->nullable();
            $table->string('img1_label')->nullable();
            $table->string('img1_url')->nullable();
            $table->string('img2_path')->nullable();
            $table->string('img2_label')->nullable();
            $table->string('img2_url')->nullable();
            $table->string('img3_path')->nullable();
            $table->string('img3_label')->nullable();
            $table->string('img3_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
