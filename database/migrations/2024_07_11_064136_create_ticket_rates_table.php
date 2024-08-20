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
        Schema::create('ticket_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_time_id');
            $table->integer('ticket_rate');
            $table->foreign('movie_time_id')->references('id')->on('movietimes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_rates');
    }
};
