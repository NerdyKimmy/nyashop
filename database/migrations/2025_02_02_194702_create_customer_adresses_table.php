<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_adresses', function (Blueprint $table) {
            $table->id();
            $table->string('type',45);
            $table->string('address',255);
            $table->string('address2',255)->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zipcode',45);
            $table->string('country_code',3);
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->timestamps();
            $table->foreign('country_code')->references('code')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_adresses');
    }
};
