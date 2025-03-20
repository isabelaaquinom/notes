<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        //formvalidation
        $request->validate(
            //rules
            [
                'text_username' => 'required|email',
                'text_password' => 'required|min:6|max:16'
            ],
            //messages
            [
                'text_username.required' => "The username is required",
                'text_username.email' => "The username must be a valid email",
                'text_password.required' => "The password is required",
                'text_password.min' => "The password must be at least :min characters long",
                'text_password.max' => "The password must be at most :max characters long",


            ]
        );

        //get user input
        $username = $request->input('text_username');
        $password = $request->input('text_password');

        // check if user exists
        $user = User::where('username', $username)
                        ->where('deleted_at', NULL)
                        ->first();

        if(!$user){
            return redirect()
                ->back()
                ->withInput()
                ->with('loginError', 'Incorrect username or password');
        }

        //check if password is correct
        if(!password_verify($password, $user->password)){
            return redirect()
            ->back()
            ->withInput()
            ->with('loginError', 'Incorrect username or password');
        }

        // uptade last login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        //login user
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
            ]
            ]);

        // redirect to home
        return redirect()->to("/");
    }

    public function logout() 
    {
        //logout from the application
        session()->forget('user');
        return redirect()->to('/login');
    }
}
