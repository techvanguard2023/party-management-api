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
        Schema::create('rsvps', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('guest_name');
            $table->string('phone', 30)->nullable();
            $table->string('status', 20)->index();
            $table->unsignedTinyInteger('adults_count')->default(0);
            $table->unsignedTinyInteger('kids_count')->default(0);
            $table->string('favorite_character', 50)->nullable();
            $table->text('dietary_notes')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvps');
    }
};
