<?php

namespace App\Enums;

enum RsvpStatus: string
{
    case Confirmed = 'confirmed';
    case Declined = 'declined';
}
