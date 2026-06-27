<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemosTable extends Migration
{
    /**
     * マイグレーションを実行します（テーブルの作成）
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();                                    // メモID（主キー / bigint unsigned）
            $table->bigInteger('user_id');                  // メモを作成したユーザーのID（外部キー用）
            $table->string('title')->nullable();            // メモのタイトル（varchar 255 / 空を許可）
            $table->text('content')->nullable();            // メモの本文（text型 / 空を許可）
            $table->timestamps();                            // created_at と updated_at の自動生成
        });
    }

    /**
     * マイグレーションを巻き戻します（テーブルの削除）
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memos');
    }
}