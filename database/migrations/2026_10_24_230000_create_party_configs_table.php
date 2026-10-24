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
        Schema::create('party_configs', function (Blueprint $table) {
            $table->id();
            $table->string('child_name');
            $table->unsignedTinyInteger('age');
            $table->dateTime('starts_at');
            $table->string('date_display');
            $table->string('time_display');
            $table->string('venue_name');
            $table->string('venue_address');
            $table->string('google_maps_query');
            $table->text('dress_code');
            $table->json('gift_preferences');
            $table->string('contact_phone');
            $table->string('deadline_rsvp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('party_configs');
    }
};
