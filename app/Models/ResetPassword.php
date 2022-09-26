<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    // テーブル名を指定
    protected $table = 'password_resets';
    // プライマリキーを「email」に指定( デフォルトは「id」」)
    protected $primaryKey = 'email';
    // プライマリキーのタイプを指定
    protected $keyType = 'string';
    // タイプがstringの場合はインクリメントを「false」にしないといけない
    public $incrementing = false;

    // モデルが以下のフィールド以外を持たないようにする
    protected $fillable = [
        'email', 'token'
    ];

    // 「created_at」の自動更新を防ぐためタイムスタンプを「false」に設定
    public $timestamps = false;
    // passwor_resetsへ登録するデータ作成時の処理
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // 登録時刻だけを自動管理
            $model->created_at = $model->freshTimestamp();
        });
    }
}