<?php

namespace App\Http\Requests;

use App\Enums\AttendeeType;
use App\Enums\RsvpStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
            // Guests must leave a phone; parents adding someone by hand may skip it.
            'phone' => [
                Rule::requiredIf(fn () => ! $this->isAdmin()),
                'nullable',
                'string',
                'min:8',
                'max:30',
                'regex:/^[\d\s()+\-]+$/',
            ],
            'status' => ['required', Rule::enum(RsvpStatus::class)],
            // Guests who confirm must name everyone coming; parents may send just the counts.
            'attendees' => [
                Rule::requiredIf(fn () => ! $this->isAdmin() && ! $this->isDeclined()),
                'nullable',
                'array',
                'max:20',
            ],
            'attendees.*.name' => ['required', 'string', 'max:100'],
            'attendees.*.type' => ['required', Rule::enum(AttendeeType::class)],
            'attendees.*.age' => ['required_if:attendees.*.type,child', 'nullable', 'integer', 'min:0', 'max:17'],
            'adultsCount' => [Rule::requiredIf(fn () => $this->needsCounts()), 'nullable', 'integer', 'min:0', 'max:20'],
            'kidsCount' => [Rule::requiredIf(fn () => $this->needsCounts()), 'nullable', 'integer', 'min:0', 'max:20'],
            'favoriteCharacter' => ['nullable', 'string', 'max:50'],
            'dietaryNotes' => ['nullable', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'attendees.required' => 'Informe o nome de todas as pessoas que vão à festa.',
            'attendees.*.name.required' => 'Preencha o nome de todas as pessoas.',
            'attendees.*.age.required_if' => 'Informe a idade de todas as crianças.',
            'attendees.*.age.max' => 'A idade das crianças deve ser de até 17 anos.',
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty() || ! $this->filled('attendees') || $this->isDeclined()) {
                    return;
                }

                $hasAdult = collect($this->input('attendees'))
                    ->contains(fn ($attendee) => ($attendee['type'] ?? null) === AttendeeType::Adult->value);

                if (! $hasAdult) {
                    $validator->errors()->add('attendees', 'Inclua pelo menos um adulto responsável.');
                }
            },
        ];
    }

    /**
     * Map the camelCase payload the front sends onto the model's columns.
     *
     * Counts come from the attendee list when there is one.
     *
     * @return array<string, mixed>
     */
    public function toModelAttributes(): array
    {
        $attendees = collect($this->attendees());

        if ($this->isDeclined()) {
            [$adults, $kids] = [0, 0];
        } elseif ($attendees->isNotEmpty()) {
            $adults = $attendees->where('type', AttendeeType::Adult->value)->count();
            $kids = $attendees->where('type', AttendeeType::Child->value)->count();
        } else {
            [$adults, $kids] = [$this->validated('adultsCount'), $this->validated('kidsCount')];
        }

        return [
            'guest_name' => $this->validated('guestName'),
            'phone' => $this->validated('phone'),
            'status' => $this->validated('status'),
            'adults_count' => $adults,
            'kids_count' => $kids,
            'favorite_character' => $this->validated('favoriteCharacter'),
            'dietary_notes' => $this->validated('dietaryNotes'),
            'message' => $this->validated('message'),
        ];
    }

    /**
     * The attendee rows to store. A declined RSVP has no one coming.
     *
     * @return array<int, array{name: string, type: string, age: int|null}>
     */
    public function attendees(): array
    {
        if ($this->isDeclined()) {
            return [];
        }

        return collect($this->validated('attendees', []))
            ->map(fn (array $attendee) => [
                'name' => trim($attendee['name']),
                'type' => $attendee['type'],
                'age' => $attendee['type'] === AttendeeType::Child->value ? $attendee['age'] : null,
            ])
            ->values()
            ->all();
    }

    private function isAdmin(): bool
    {
        return $this->user('sanctum') !== null;
    }

    /**
     * A confirmed RSVP without names (parents' manual add) must say how many are coming.
     */
    private function needsCounts(): bool
    {
        return ! $this->isDeclined() && ! $this->filled('attendees');
    }

    private function isDeclined(): bool
    {
        return $this->input('status') === RsvpStatus::Declined->value;
    }
}
