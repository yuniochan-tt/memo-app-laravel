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
     * 認証成功後のリダイレクト先遷移パス
     *
     * @var string
     */
    protected $redirectTo = '/memo'; 

    /**
     * ログイン時のバリデーション（トレイトのメソッドをオーバーライド）
     * * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        // ログインバリデーション実行前に言語設定を日本語に強制する
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
     * 新しいコントローラーインスタンスの生成
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
