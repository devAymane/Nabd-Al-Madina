<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('incident_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('departement_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->text('description');
            $table->string('photo')->nullable();

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->string('category')->nullable();

            $table->enum('priority', [
                'low',
                'medium',
                'high',
            ])->nullable();

            $table->unsignedTinyInteger('urgency')->nullable();

            $table->text('summary')->nullable();

            $table->enum('status', [
                'nouveau',
                'en_cours',
                'resolu',
                'rejete',
            ])->default('nouveau');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};