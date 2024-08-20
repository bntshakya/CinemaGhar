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
        Schema::create('scannedcustomers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('movie');
            $table->string('movietime');
            $table->string('location');
            $table->string('hall');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scannedcustomers');
    }
};
