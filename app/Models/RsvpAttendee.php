<?php

namespace App\Models;

use App\Enums\AttendeeType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'type', 'age'])]
class RsvpAttendee extends Model
{
    /**
     * @return BelongsTo<Rsvp, $this>
     */
    public function rsvp(): BelongsTo
    {
        return $this->belongsTo(Rsvp::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => AttendeeType::class,
            'age' => 'integer',
        ];
    }
}
