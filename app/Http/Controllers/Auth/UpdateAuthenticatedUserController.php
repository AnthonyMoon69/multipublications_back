<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateAuthenticatedUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateAuthenticatedUserController extends Controller
{
    public function __invoke(UpdateAuthenticatedUserRequest $request): JsonResource
    {
        $user = $request->user();

        $data = collect($request->validated())
            ->only(['email', 'password', 'image'])
            ->toArray();

        if (! empty($data)) {
            $user->fill($data);

            if ($user->isDirty()) {
                $user->save();
            }
        }

        return new UserResource($user->fresh());
    }
}
