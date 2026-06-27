<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 6-1. MemoモデルとAuthのuseを追加
use App\Models\Memo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemoController extends Controller
{
    /**
     * メモ投稿画面の初期表示（一覧）
     *
     * @return View
     */
    public function index()
    {
        return view('memo');
    }

    /**
     * 6-2. メモの追加処理
     *
     * @return RedirectResponse
     */
    public function add()
    {
        // ログイン中のユーザーIDを取得し、memosテーブルにデータを1行作成します
        Memo::create([
            'user_id' => Auth::id(),
            'title'   => '新規メモ',
            'content' => '',
        ]);

        // 追加後はメモ一覧画面（/memo）へリダイレクト（再表示）します
        return redirect()->route('memo.index');
    }
}