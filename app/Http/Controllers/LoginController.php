<?php

namespace App\Http\Controllers;

use App\Notifications\ChangePassword;
use App\User;
use Illuminate\Http\Request;
use Notification;

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

    public function changePass(Request $request)
    {

        if (empty($request->all()) || !isset($request->email)) {
            return response()->json([
                'error' => 'Oops!',
                'message' => 'Your request is empty.',
            ], 400);
        }
        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
        if (empty($user)) {
            return response()->json([
                'error' => 'invalid_credentials',
                'message' => 'Email does not exist.',
            ], 400);
        }
        $user = User::find($user->id);
        $user->remember_token = $this->generateRandomString(35);
        $user->save();
        Notification::send($user, new ChangePassword($user));

        return response()->json([
            'error' => 'success!',
            'message' => 'Successfully requested for change in password.',
        ], 200);

    }

    public function passwordChange($token)
    {
        if (empty($token)) {
            abort(404);
        }
        $user = User::where('remember_token', $token)->first();
        if (empty($user)) {
            abort(404);
        }
        $email = $user->email;
        return view('auth.passwords.reset', compact('token', 'email'));
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function updatePassword(Request $request)
    {
        $user = User::where('email', $request->email)->where('remember_token', $request->token)->first();
        if (empty($user)) {
            \Session::flash('message', 'Email is not yours.');
            return redirect()->back();
        }
        $this->validate($request, [
            'password' => 'sometimes|required|min:5',
            'password_confirmation' => 'sometimes|required|same:password',
        ]);
        $password = \Hash::make($request->password);

        $user = User::find($user->id);
        $user->remember_token = null;
        $user->password = $password;
        $user->save();
        \Session::flash('message', 'You have successfully changed password');
        return redirect(url('http://turuapp.herokuapp.com/'));
    }

}
