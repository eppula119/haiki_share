<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MegaBytes implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $param;

    public function __construct($param)
    {
        $this->param = $param; // 「1」などメガバイト単位の数字のパラメーターが入る
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 画像サイズがメガバイト単位の指定パラメーター以下かどうかバリデーションルール設定
        $megaBytes = $value->getSize() / 1024 / 1024;
        return $megaBytes <= $this->param;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.max_mb', ['max_mb' => $this->param]);
    }
}
