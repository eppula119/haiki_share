<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * クッキーの中の暗号化しないプロパティリスト
     *
     * @var array
     */
    protected $except = [
        'MESSAGE',
        'TYPE'
    ];
}
