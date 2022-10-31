<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    /** JSONに含める属性 */
    protected $visible = [
        'id', 'name'
    ];

    // 売り手ユーザーリレーション
    public function shops() {
        return $this->hasMany('App\Models\Shop');
    }
}
