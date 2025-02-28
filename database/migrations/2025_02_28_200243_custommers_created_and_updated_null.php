<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Робимо існуючі колонки nullable
            $table->foreignIdFor(User::class, 'created_by')->nullable()->change();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Повертаємо nullable у попередній стан (якщо треба)
            $table->foreignIdFor(User::class, 'created_by')->nullable(false)->change();
            $table->foreignIdFor(User::class, 'updated_by')->nullable(false)->change();
        });
    }
};
