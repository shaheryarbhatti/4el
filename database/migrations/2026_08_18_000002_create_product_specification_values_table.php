<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_specification_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specification_id')
                  ->constrained('category_specifications')
                  ->cascadeOnDelete();
            $table->text('value')->nullable();   // stored as text; JSON for multiselect
            $table->timestamps();

            $table->unique(['product_id', 'specification_id']);
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specification_values');
    }
};
