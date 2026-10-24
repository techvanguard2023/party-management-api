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
        Schema::create('rsvp_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('rsvp_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('type', 10);
            $table->unsignedTinyInteger('age')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvp_attendees');
    }
};
