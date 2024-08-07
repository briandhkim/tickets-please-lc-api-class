<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(
            Ticket::filter($filters)
                ->paginate()
        );
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        //policy
        if ($this->isAble('store', Ticket::class)) {
            return new TicketResource(Ticket::create($request->mappedAttributes()));
        }

        return $this->notAuthorized('Current user is not authorized to create this resource');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        if ($this->include('author')) {
            return new TicketResource($ticket->load('author'));
        }

        return new TicketResource($ticket);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Ticket $ticket)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     * PATCH
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        /**
         * PATCH - partial update - e.g. title or description or both
         * PUT - replacement - fetch a ticket and replace all of the data
         */

        //policy
        if ($this->isAble('update', $ticket)) {
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        }

        return $this->notAuthorized('Current user is not authorized to update this resource');
    }

    /**
     * PUT
     */
    public function replace(ReplaceTicketRequest $request, Ticket $ticket)
    {
        //policy
        if ($this->isAble('replace', $ticket)) {
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        }

        return $this->notAuthorized('Current user is not authorized to update this resource');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //policy
        if ($this->isAble('delete', $ticket)) {
            $ticket->delete();

            return $this->ok('Ticket deleted.');
        }

        return $this->notAuthorized('Current user is not authorized to delete this resource');
    }
}
