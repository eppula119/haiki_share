<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MegaBytes; // 画像ファイルサイズルール

class StoreProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 商品の画像タイプのルールを設定
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image_1' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_2' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_3' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_4' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'image_5' => ['file','mimes:jpg,jpeg,png', new MegaBytes(1)],
            'product_name' => ['required','string','max:30'],
            'price' => ['required','numeric'],
            'day' => ['required','date_format:Y-m-d'],
            'time' => ['required','date_format:H:i'],
        ];
    }
}
