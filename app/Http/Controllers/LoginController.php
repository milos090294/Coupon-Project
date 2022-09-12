<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
        /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    
    {
        $credentials = $request->only('email', 'password');
 
        if (Auth::attempt($credentials)) {
          
           
            return redirect('/home')->with('message', 'Log in');

        }else {

            return redirect('/')->with('message', 'Error Credentials');

        }

    }


    public function login() { 

        return view('login');
    }


    public function logout(Request $request) {

        auth()->logout();

        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You are logged out!');
    }
}
