<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage; // 画像ファイルの操作

class Product extends Model
{
    // プライマリキーの自動採番を使用しない
    public $incrementing = false;
    // プライマリキーの型
    protected $keyType = 'string';
    // IDの桁数
    const ID_LENGTH = 20;

    /** JSONに含めない属性 */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /** JSONに含める属性 */
    protected $appends = [
        'url',
    ];

    public function __construct(array $attributes = [])
    {
        // モデルで初期値を入れる場合に必要な記述
        parent::__construct($attributes);
        // IDの値を取得できなかった場合
        if (!Arr::get($this->attributes, 'id')) {
            // IDに値を代入する
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成
     * @return string
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['image_1']);
    }
}
