<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Verifică dacă coloana există deja
        if (!Schema::hasColumn('categories', 'user_id')) {
            // Pasul 1: Adaugă coloana ca nullable
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });

            // Pasul 2: Setează user_id = 1 pentru categoriile existente
            // (presupunem că user-ul cu id=1 există - john@example.com)
            DB::table('categories')->whereNull('user_id')->update(['user_id' => 1]);

            // Pasul 3: Fă coloana NOT NULL și adaugă foreign key
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('categories', 'user_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
