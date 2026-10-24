<?php

namespace App\Http\Resources;

use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Rsvp
 */
class RsvpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Keys are camelCase to match the front's RsvpEntry type.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'guestName' => $this->guest_name,
            'phone' => (string) $this->phone,
            'status' => $this->status,
            'adultsCount' => $this->adults_count,
            'kidsCount' => $this->kids_count,
            'attendees' => $this->attendees->map(fn ($attendee) => [
                'name' => $attendee->name,
                'type' => $attendee->type,
                'age' => $attendee->age,
            ]),
            'favoriteCharacter' => (string) $this->favorite_character,
            'dietaryNotes' => (string) $this->dietary_notes,
            'message' => (string) $this->message,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
