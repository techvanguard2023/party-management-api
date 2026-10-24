<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Parents' admin account
    |--------------------------------------------------------------------------
    |
    | Created by the database seeder. There is no sign-up screen: this is the
    | only account that can open the guest list and edit the party.
    |
    */

    'admin' => [
        'name' => env('PARTY_ADMIN_NAME', 'Pais do Lorenzo'),
        'email' => env('PARTY_ADMIN_EMAIL'),
        'password' => env('PARTY_ADMIN_PASSWORD'),
    ],

];
