<?php

namespace App\Http\Requests;

use App\Enums\RsvpStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRsvpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'guestName' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'min:8', 'max:30', 'regex:/^[\d\s()+\-]+$/'],
            'status' => ['required', Rule::enum(RsvpStatus::class)],
            'adultsCount' => ['required', 'integer', 'min:0', 'max:20'],
            'kidsCount' => ['required', 'integer', 'min:0', 'max:20'],
            'favoriteCharacter' => ['nullable', 'string', 'max:50'],
            'dietaryNotes' => ['nullable', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Map the camelCase payload the front sends onto the model's columns.
     *
     * @return array<string, mixed>
     */
    public function toModelAttributes(): array
    {
        return [
            'guest_name' => $this->validated('guestName'),
            'phone' => $this->validated('phone'),
            'status' => $this->validated('status'),
            'adults_count' => $this->validated('adultsCount'),
            'kids_count' => $this->validated('kidsCount'),
            'favorite_character' => $this->validated('favoriteCharacter'),
            'dietary_notes' => $this->validated('dietaryNotes'),
            'message' => $this->validated('message'),
        ];
    }
}
