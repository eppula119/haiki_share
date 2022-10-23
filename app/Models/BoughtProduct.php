<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoughtProduct extends Model
{
    // テーブル名を指定
    protected $table = 'bought_products';

    // 購入した買い手ユーザーリレーション
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // 購入した商品リレーション
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
