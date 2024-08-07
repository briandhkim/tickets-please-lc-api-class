<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Policies\V1\UserPolicy;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{
    protected $policyClass = UserPolicy::class;

    /**
     * Display a listing of the resource.
     * normally should have created UserFilter instead of using AuthorFilter
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::filter($filters)->paginate()
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
    public function store(StoreUserRequest $request)
    {
        //policy
        if ($this->isAble('store', User::class)) {
            return new UserResource(User::create($request->mappedAttributes()));
        }

        return $this->notAuthorized('Current user is not authorized to create this resource');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            if ($this->include('tickets')) {
                return new UserResource($user->load('tickets'));
            }

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found', 404);
        }
    }

    public function replace(ReplaceUserRequest $request, User $user)
    {
        //policy
        if ($this->isAble('replace', $user)) {
            $user->update($request->mappedAttributes());

            return new UserResource($user);
        }

        return $this->notAuthorized('Current user is not authorized to update this resource.');
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(User $user)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        /**
         * PATCH - partial update - e.g. title or description or both
         * PUT - replacement - fetch a ticket and replace all of the data
         */
        //policy
        if ($this->isAble('update', $user)) {
            $user->update($request->mappedAttributes());

            return new UserResource($user);
        }

        return $this->notAuthorized('Current user is not authorized to update this resource.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //policy
        if ($this->isAble('delete', $user)) {
            $user->delete();

            return $this->ok('User deleted.');
        }

        $this->notAuthorized('Current user is not authorized to delete this resource.');
    }
}
