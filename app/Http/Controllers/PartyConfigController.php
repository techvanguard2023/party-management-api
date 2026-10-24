<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePartyConfigRequest;
use App\Http\Resources\PartyConfigResource;
use App\Models\PartyConfig;

class PartyConfigController extends Controller
{
    /**
     * Return the party details shown on the invitation.
     */
    public function show(): PartyConfigResource
    {
        return new PartyConfigResource(PartyConfig::current());
    }

    /**
     * Update the party details.
     */
    public function update(UpdatePartyConfigRequest $request): PartyConfigResource
    {
        $config = PartyConfig::current();
        $config->update($request->toModelAttributes());

        return new PartyConfigResource($config);
    }
}
