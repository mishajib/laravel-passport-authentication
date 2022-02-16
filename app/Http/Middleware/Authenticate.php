<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->json()) {
            return route('login');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated, please try again!'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
