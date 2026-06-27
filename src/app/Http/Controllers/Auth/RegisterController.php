<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Http\Requests\UserCreateRequest; // カスタムフォームリクエストのインポート
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * 登録後のリダイレクト先遷移パス
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * 新しいコントローラーインスタンスの生成
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * ユーザー新規登録処理を行い、自動ログインした上で投稿画面へ遷移する
     *
     * @param UserCreateRequest $request
     * @return RedirectResponse
     */
    public function register(UserCreateRequest $request)
    {
        // ユーザー情報をデータベースに保存し、作成されたインスタンスを $user に代入
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ▼ 追加：登録したユーザー情報を使ってその場でログイン状態にする
        $this->guard()->login($user);

        // メモ一覧画面へリダイレクト（ログイン済みのため auth ミドルウェアを通過できます）
        return redirect()->route('memo.index');
    }

    /**
     * ▼ 追加：Laravel Coreのトレイトによる強制リダイレクトを上書き（オーバーライド）します。
     * ユーザー登録完了後、確実にメモ一覧画面（/memo）へ遷移させるための設定です。
     *
     * @return string
     */
    public function redirectPath()
    {
        return '/memo';
    }
}