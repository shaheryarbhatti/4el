<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SUPERSEDED — no-op.
 * ------------------------------------------------------------------
 * The `settings` table is already created by
 * 2026_08_03_090002_create_settings_table (key / value / group schema),
 * which is the schema the whole app actually uses (Setting model,
 * SettingController, seeders and views all work off key/value/group).
 *
 * This duplicate file would have tried to Schema::create('settings') a
 * second time and crashed `php artisan migrate`. It is intentionally a
 * guarded no-op so migrations run cleanly on both existing and fresh
 * databases without touching any settings data.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Only create if it somehow does not exist (it always will, from the
        // Aug-03 migration that runs first) — never recreate/overwrite it.
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('group')->default('general');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // No-op: this migration does not own the settings table.
    }
};
