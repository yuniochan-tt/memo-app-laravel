<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; 

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/memo'; 

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        // Force Japanese locale before executing validation
        app()->setLocale('ja');

        $request->validate(
            [
                $this->username() => 'required|max:255|email',
                'password'        => 'required|min:8|max:255|regex:/^[a-zA-Z0-9]+$/',
            ],
            [
                'password.regex' => ':attributeは半角英数字で入力してください。'
            ]
        );
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle the user response after logging out.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loggedOut(Request $request)
    {
        // Invalidate session and regenerate CSRF token to prevent redirect loops
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect directly to the login URL
        return redirect('/');
    }
}