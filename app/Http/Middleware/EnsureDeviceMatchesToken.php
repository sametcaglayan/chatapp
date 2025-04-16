<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TokenCheck;
use Illuminate\Support\Facades\Hash;

class EnsureDeviceMatchesToken
{
    public function handle(Request $request, Closure $next)
    {
        $authDevice = auth()->user();
        // Check if the user is authenticated
        if (!$authDevice) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.'
            ], 401);
        }
        if ($request->bearerToken()) {
            $bearerToken = $request->bearerToken();
            $id = explode('|', $bearerToken)[0];
            $token = explode('|', $bearerToken)[1];

            $hash = TokenCheck::where('id', $id)->value('token');

            if ($hash != hash('sha256', $token)) {
                return response()->json(['status' => 'success', 'message' => 'Token is invalid.'], 401);
            }
        }
        return $next($request);
    }
}
