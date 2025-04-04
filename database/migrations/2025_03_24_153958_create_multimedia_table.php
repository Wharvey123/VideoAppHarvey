<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Executa les migracions */
    public function up(): void
    {
        Schema::create('multimedia', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('path'); // Ruta relativa a l'arxiu
            $table->enum('type', ['video', 'image']); // Tipus d'arxiu
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /** Reverteix les migracions */
    public function down(): void
    {
        Schema::dropIfExists('multimedia');
    }
};
