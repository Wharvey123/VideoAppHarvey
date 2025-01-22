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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('url');
            $table->timestamp('published_at')->nullable();
            $table->integer('previous')->nullable();
            $table->integer('next')->nullable();
            $table->unsignedBigInteger('series_id');
            $table->timestamps();
            //$table->foreign('series_id')->references('id')->on('series');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
