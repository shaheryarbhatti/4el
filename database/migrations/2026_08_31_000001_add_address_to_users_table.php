<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A saved default address on the user's account (My Account > Profile).
 * Also used to pre-fill the checkout shipping form.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'address_line')) $table->string('address_line')->nullable()->after('phone');
            if (! Schema::hasColumn('users', 'city'))         $table->string('city')->nullable()->after('address_line');
            if (! Schema::hasColumn('users', 'state'))        $table->string('state')->nullable()->after('city');
            if (! Schema::hasColumn('users', 'postal_code'))  $table->string('postal_code')->nullable()->after('state');
            if (! Schema::hasColumn('users', 'country'))      $table->string('country')->nullable()->after('postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address_line', 'city', 'state', 'postal_code', 'country']);
        });
    }
};
