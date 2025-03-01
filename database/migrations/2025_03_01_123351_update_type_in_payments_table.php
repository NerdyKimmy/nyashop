<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Варіант А: робимо поле nullable
            $table->string('type')->nullable()->change();

            // Або Варіант Б: встановити default
            // $table->string('type')->default('cc')->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Повертаємо назад, як було (якщо треба)
            $table->string('type')->nullable(false)->change();
        });
    }
};
