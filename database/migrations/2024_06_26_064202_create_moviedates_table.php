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
        Schema::create('moviedates', function (Blueprint $table) {
            $table->id();
            $table -> unsignedBigInteger('movie_id');
            $table -> string('movie_location');
            $table -> date('startdate');
            $table -> date('enddate');
            $table -> foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('movie_location')->references('location')->on('locations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moviedates');
    }
};
