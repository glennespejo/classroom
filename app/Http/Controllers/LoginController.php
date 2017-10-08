<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function loginApi(Request $request)
    {
        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
        if (empty($user)) {
            return response()->json([
                'error' => 'invalid_credentials',
                'message' => 'The user credentials were incorrect.',
            ], 400);
        }

        if (!\Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'incorrect_password.',
                'message' => 'Incorrect password.',
            ], 400);
        }

        return response()->json($user);
    }
}
