<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Http\Requests\UserCreateRequest; // ▼ 追加：カスタムフォームリクエストのインポート
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
     * ユーザー新規登録処理を行い、投稿画面へ遷移する
     *
     * @param UserCreateRequest $request // ▼ 変更：通常の Request から UserCreateRequest に書き換え
     * @return RedirectResponse
     */
    public function register(UserCreateRequest $request)
    {
        // ※ $request->validate() の処理ブロックは FormRequest に移行したため削除しました。

        // ユーザー情報をデータベースに保存
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // メモ一覧画面へリダイレクト
        return redirect()->route('memo.index');
    }
}