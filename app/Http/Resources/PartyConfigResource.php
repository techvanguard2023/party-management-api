<?php

namespace App\Http\Resources;

use App\Models\PartyConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PartyConfig
 */
class PartyConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * Keys are camelCase to match the front's PartyConfig type. `dateStr`
     * has no timezone on purpose: the countdown reads it as the party's
     * local time.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'childName' => $this->child_name,
            'age' => $this->age,
            'dateStr' => $this->starts_at->format('Y-m-d\TH:i:s'),
            'dateDisplay' => $this->date_display,
            'timeDisplay' => $this->time_display,
            'venueName' => $this->venue_name,
            'venueAddress' => $this->venue_address,
            'googleMapsQuery' => $this->google_maps_query,
            'dressCode' => $this->dress_code,
            'giftPreferences' => $this->gift_preferences,
            'contactPhone' => $this->contact_phone,
            'deadlineRsvp' => $this->deadline_rsvp,
        ];
    }
}
