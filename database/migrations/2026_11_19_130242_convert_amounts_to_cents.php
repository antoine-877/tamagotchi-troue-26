<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Every amount in euros becomes a whole number of cents.
     */
    public function up(): void
    {
        Schema::table('users', fn(Blueprint $table) => $table->integer('balance_cents')->default(5000));
        DB::table('users')->update(['balance_cents' => DB::raw('ROUND(balance * 100)')]);
        Schema::table('users', fn(Blueprint $table) => $table->dropColumn('balance'));

        Schema::table('items', fn(Blueprint $table) => $table->integer('price_cents')->default(0));
        DB::table('items')->update(['price_cents' => DB::raw('ROUND(price * 100)')]);
        Schema::table('items', fn(Blueprint $table) => $table->dropColumn('price'));

        Schema::table('purchases', fn(Blueprint $table) => $table->integer('total_cents')->default(0));
        DB::table('purchases')->update(['total_cents' => DB::raw('ROUND(total * 100)')]);
        Schema::table('purchases', fn(Blueprint $table) => $table->dropColumn('total'));

        Schema::table('gift_codes', fn(Blueprint $table) => $table->integer('amount_cents')->default(0));
        DB::table('gift_codes')->update(['amount_cents' => DB::raw('ROUND(amount * 100)')]);
        Schema::table('gift_codes', fn(Blueprint $table) => $table->dropColumn('amount'));
    }

    public function down(): void
    {
        Schema::table('users', fn(Blueprint $table) => $table->float('balance')->default(50));
        DB::table('users')->update(['balance' => DB::raw('balance_cents / 100.0')]);
        Schema::table('users', fn(Blueprint $table) => $table->dropColumn('balance_cents'));

        Schema::table('items', fn(Blueprint $table) => $table->float('price')->default(0));
        DB::table('items')->update(['price' => DB::raw('price_cents / 100.0')]);
        Schema::table('items', fn(Blueprint $table) => $table->dropColumn('price_cents'));

        Schema::table('purchases', fn(Blueprint $table) => $table->float('total')->default(0));
        DB::table('purchases')->update(['total' => DB::raw('total_cents / 100.0')]);
        Schema::table('purchases', fn(Blueprint $table) => $table->dropColumn('total_cents'));

        Schema::table('gift_codes', fn(Blueprint $table) => $table->float('amount')->default(0));
        DB::table('gift_codes')->update(['amount' => DB::raw('amount_cents / 100.0')]);
        Schema::table('gift_codes', fn(Blueprint $table) => $table->dropColumn('amount_cents'));
    }
};
