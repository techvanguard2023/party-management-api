<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRsvpRequest;
use App\Http\Resources\RsvpResource;
use App\Models\Rsvp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RsvpController extends Controller
{
    /**
     * List every RSVP, newest first, for the parents' panel.
     */
    public function index(): AnonymousResourceCollection
    {
        return RsvpResource::collection(Rsvp::query()->latest()->get());
    }

    /**
     * Record a guest's answer. Public, and also used by the panel's manual add.
     */
    public function store(StoreRsvpRequest $request): JsonResponse
    {
        $rsvp = Rsvp::create($request->toModelAttributes());

        return (new RsvpResource($rsvp))->response()->setStatusCode(201);
    }

    /**
     * Remove an RSVP.
     */
    public function destroy(Rsvp $rsvp): Response
    {
        $rsvp->delete();

        return response()->noContent();
    }
}
