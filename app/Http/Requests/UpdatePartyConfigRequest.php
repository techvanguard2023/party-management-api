<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartyConfigRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'childName' => ['required', 'string', 'max:100'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'dateStr' => ['required', 'date'],
            'dateDisplay' => ['required', 'string', 'max:150'],
            'timeDisplay' => ['required', 'string', 'max:100'],
            'venueName' => ['required', 'string', 'max:200'],
            'venueAddress' => ['required', 'string', 'max:255'],
            'googleMapsQuery' => ['required', 'string', 'max:255'],
            'dressCode' => ['required', 'string', 'max:2000'],
            'giftPreferences' => ['required', 'array'],
            'giftPreferences.clothingSize' => ['required', 'string', 'max:50'],
            'giftPreferences.shoeSize' => ['required', 'string', 'max:50'],
            'giftPreferences.favoriteThemes' => ['present', 'array', 'max:20'],
            'giftPreferences.favoriteThemes.*' => ['string', 'max:100'],
            'giftPreferences.wishlistUrl' => ['nullable', 'url', 'max:500'],
            'contactPhone' => ['required', 'string', 'max:30'],
            'deadlineRsvp' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Map the camelCase payload the front sends onto the model's columns.
     *
     * @return array<string, mixed>
     */
    public function toModelAttributes(): array
    {
        $gifts = $this->validated('giftPreferences');

        return [
            'child_name' => $this->validated('childName'),
            'age' => $this->validated('age'),
            'starts_at' => $this->validated('dateStr'),
            'date_display' => $this->validated('dateDisplay'),
            'time_display' => $this->validated('timeDisplay'),
            'venue_name' => $this->validated('venueName'),
            'venue_address' => $this->validated('venueAddress'),
            'google_maps_query' => $this->validated('googleMapsQuery'),
            'dress_code' => $this->validated('dressCode'),
            'gift_preferences' => [
                'clothingSize' => $gifts['clothingSize'],
                'shoeSize' => $gifts['shoeSize'],
                'favoriteThemes' => array_values($gifts['favoriteThemes']),
                'wishlistUrl' => $gifts['wishlistUrl'] ?? '',
            ],
            'contact_phone' => $this->validated('contactPhone'),
            'deadline_rsvp' => $this->validated('deadlineRsvp'),
        ];
    }
}
