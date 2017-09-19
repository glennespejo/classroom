<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }*/

    public function loginApi(Request $request)
    {

        $user = User::where('email', $request->email)->first();
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
        return $user;
    }

    public function registerApi(Request $request)
    {

        if (!$request->all()) {
            return response()->json([
                'error' => 'complete_form',
                'message' => 'Please fill up the form to complete the registration.',
            ], 422);
        }
        if (User::where('email', $request->email)->count()) {
            return response()->json([
                'error' => 'email_taken',
                'message' => 'Email already taken.',
            ], 422);
        }
        $password = \Hash::make($request->password);
        $request->request->set('password', $password);
        $user = new User;
        $user->fill($request->all())->save();
        return $user;
    }
}
