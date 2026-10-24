<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRsvpRequest;
use App\Http\Resources\RsvpResource;
use App\Models\Rsvp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RsvpController extends Controller
{
    /**
     * List every RSVP, newest first, for the parents' panel.
     */
    public function index(): AnonymousResourceCollection
    {
        return RsvpResource::collection(Rsvp::query()->with('attendees')->latest()->get());
    }

    /**
     * Record a guest's answer. Public, and also used by the panel's manual add.
     */
    public function store(StoreRsvpRequest $request): JsonResponse
    {
        $rsvp = DB::transaction(function () use ($request) {
            $rsvp = Rsvp::create($request->toModelAttributes());
            $rsvp->attendees()->createMany($request->attendees());

            return $rsvp;
        });

        return (new RsvpResource($rsvp->load('attendees')))->response()->setStatusCode(201);
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
