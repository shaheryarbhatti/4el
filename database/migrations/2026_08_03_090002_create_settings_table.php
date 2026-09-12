<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "settings" is a simple key/value store that powers the whole
 * admin Settings area (Stripe / PayPal keys, Google Maps key, SMTP,
 * site name, logo, etc.).
 *
 *   key    -> unique string identifier, e.g. "stripe_secret_key"
 *   value  -> the stored value (nullable, text so it fits long keys)
 *   group  -> which settings tab it belongs to (general|payment|map|smtp)
 *
 * Read anywhere with the global helper:  setting('stripe_secret_key')
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
