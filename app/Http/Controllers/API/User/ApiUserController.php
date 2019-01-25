<?php

namespace App\Http\Controllers\API\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\User\User as UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiUserController extends ApiController
{
    /**
     * Get the list of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $users = auth()->user()->account->users()->get();
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

        return UserResource::collection($users);
    }

    /**
     * Get the detail of a given user.
     *
     * @param  Request $request
     * @param int $UserId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $userId)
    {
        try {
            $user = User::where('account_id', auth()->user()->account_id)
                ->where('id', $userId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return new UserResource($user);
    }
}
