<?php

namespace App\Models;

use App\Enums\RsvpStatus;
use Database\Factories\RsvpFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'guest_name',
    'phone',
    'status',
    'adults_count',
    'kids_count',
    'favorite_character',
    'dietary_notes',
    'message',
])]
class Rsvp extends Model
{
    /** @use HasFactory<RsvpFactory> */
    use HasFactory, HasUlids;

    /**
     * Everyone coming under this RSVP, in the order they were entered.
     *
     * @return HasMany<RsvpAttendee, $this>
     */
    public function attendees(): HasMany
    {
        return $this->hasMany(RsvpAttendee::class)->orderBy('id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => RsvpStatus::class,
            'adults_count' => 'integer',
            'kids_count' => 'integer',
        ];
    }
}
