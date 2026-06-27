<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * 受信リクエストを処理します。
     * ログイン済みのユーザーが「guest」向けの画面（ログイン・新規登録）にアクセスした場合、/memo へリダイレクトします。
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // ユーザーが既にログインしているかどうかをチェック
            if (Auth::guard($guard)->check()) {
                // ログイン済みであれば、デフォルトのHOMEではなく、メモ投稿画面（/memo）へ強制リダイレクト
                return redirect('/memo');
            }
        }

        return $next($request);
    }
}