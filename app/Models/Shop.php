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
        'name',
        'branch_name',
        'city',
        'other_address',
        'profile',
        'prefecture'
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
}

