<?php

namespace App\Models;

use Database\Factories\PartyConfigFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'child_name',
    'age',
    'starts_at',
    'date_display',
    'time_display',
    'venue_name',
    'venue_address',
    'google_maps_query',
    'dress_code',
    'gift_preferences',
    'contact_phone',
    'deadline_rsvp',
])]
class PartyConfig extends Model
{
    /** @use HasFactory<PartyConfigFactory> */
    use HasFactory;

    /**
     * The app hosts a single party, so there is only ever one row.
     */
    public static function current(): self
    {
        return static::query()->oldest('id')->firstOrFail();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'starts_at' => 'datetime',
            'gift_preferences' => 'array',
        ];
    }
}
