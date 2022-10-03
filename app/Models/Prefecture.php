<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    /** JSONに含める属性 */
    protected $visible = [
        'name'
    ];
}
