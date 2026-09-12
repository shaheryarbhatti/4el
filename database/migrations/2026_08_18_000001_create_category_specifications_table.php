<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Category Specifications — admin defines what fields sellers must/can fill
 * when listing an item in a given category.
 *
 * field_type options:
 *   text       → single-line text input
 *   textarea   → multi-line text area
 *   select     → single-select dropdown (options stored as JSON array)
 *   multiselect→ multi-select dropdown (options stored as JSON array)
 *   toggle     → Yes / No radio pills
 *   range      → Min/Max numeric range (two inputs)
 *   number     → numeric text input
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('name');                          // e.g. "Brand", "Screen Size"
            $table->string('field_label')->nullable();       // display label (defaults to name)
            $table->string('field_type')->default('text');   // text|textarea|select|multiselect|toggle|range|number
            $table->json('options')->nullable();             // JSON array of options for select/multiselect
            $table->string('placeholder')->nullable();       // hint text inside input
            $table->string('unit')->nullable();              // e.g. "in", "GHz", "GB"

            $table->boolean('is_required')->default(false);
            $table->boolean('is_essential')->default(true);  // "Essential" vs "Optional" section
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['category_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_specifications');
    }
};
