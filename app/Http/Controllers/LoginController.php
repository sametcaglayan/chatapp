<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $username = $request->input('username');
        $password = $request->input('password');

        if ($username === 'admin' && $password === '123') {
            return response()->json([
                'status' => 'success',
                'message' => 'Giriş başarılı.',
            ]);
        }

        return response()->json(['message' => 'Giriş başarısız'], 401);
    }
}
