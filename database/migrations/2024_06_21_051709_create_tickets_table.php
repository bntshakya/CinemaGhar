<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('movie_name');
            $table->time('movie_time');
            $table ->string('status')->default('free');
            $table->integer('ticket_seat');
            $table -> string('email');
            $table -> foreign('email')->references('email')->on('registers')->onDelete('cascade');
            $table->foreign(['movie_name', 'movie_time'])->references(['movie_name', 'timing'])->on('movies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
