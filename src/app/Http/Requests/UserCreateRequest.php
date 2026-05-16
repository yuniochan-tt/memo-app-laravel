<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるかどうかを判断します。
     *
     * @return bool
     */
    public function authorize()
    {
        // 常にアクセスを許可するために true に変更
        return true; 
    }

    /**
     * リクエストに適用するバリデーションルールを定義します。
     *
     * @return array
     */
    public function rules()
    {
        // バリデーション実行前に言語設定を日本語に強制する
        app()->setLocale('ja');

        return [
            'name'     => 'required|max:255|regex:/^[a-zA-Z0-9]+$/',
            'email'    => 'required|max:255|email|unique:users',
            'password' => 'required|max:255|min:8|regex:/^[a-zA-Z0-9]+$/',
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージをカスタマイズします。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.regex'     => ':attributeは半角英数字で入力してください。',
            'password.regex' => ':attributeは半角英数字で入力してください。',
        ];
    }
}