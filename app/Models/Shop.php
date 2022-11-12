<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Shop extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'branch_name', 'prefecture_id', 'city', 'other_address', 'email', 'password',
    ];

    /** JSONに含める属性 */
    protected $visible = [
        'id',
        'name',
        'branch_name',
        'city',
        'other_address',
        'profile',
        'prefecture',
        'type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * リレーションシップ - Productsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
    /**
     * リレーションシップ - Prefecturesテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prefecture()
    {
        return $this->belongsTo('App\Models\Prefecture');
    }

    /**
     * アクセサ - best_time
     * @return object
     */
    public function getBesttimeAttribute()
    {
        // 既に賞味期限が登録されているレコードの場合(GET通信などで情報取得の場合)
        if(!empty($this->attributes['best_before'])) {
            // 取得した文字列データを任意のフォーマットへ変換
            $bestTime = new Carbon($this->attributes['best_before']);
            $bestTime =  $bestTime->format('H時i分');
            return $bestTime;
        }
        
    }
}

