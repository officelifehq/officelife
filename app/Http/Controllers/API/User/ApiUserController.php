<?php

namespace App\Http\Controllers\API\User;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\User\User as UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiUserController extends ApiController
{
    /**
     * Get the detail of the logged in user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        try {
            $user = User::where('id', Auth::user()->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return new UserResource($user);
    }
}
