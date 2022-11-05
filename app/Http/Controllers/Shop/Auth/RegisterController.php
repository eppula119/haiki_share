<?php

namespace App\Http\Controllers\Shop\Auth;

use App\Models\Shop;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; //ログ取得

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Log::debug('売り手ユーザー登録バリデーション実行');
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'branch_name' => ['required', 'string', 'max:255'],
            'prefecture' => ['required', 'integer'],
            'city' => ['required', 'string', 'max:255'],
            'other_address' => ['string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:shops'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Shop
     */
    protected function create(array $data)
    {
        Log::debug('売り手ユーザー登録,db保存実行');
        return Shop::create([
            'name' => $data['name'],
            'branch_name' => $data['branch_name'],
            'prefecture_id' => $data['prefecture'],
            'city' => $data['city'],
            'other_address' => $data['other_address'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard('shop'); // 売り手ユーザー認証のguardを指定
    }

    // 売り手ユーザー情報を返す
    protected function registered(Request $request, $shop)
    {
        Log::debug('売り手ユーザー登録完了し、登録売り手ユーザー情報を返す');
        Log::debug($shop);
        $shop->type = 'shop';
        // 都道府県情報一緒に返す
        $shop["prefecture"] = $shop->prefecture;
        return $shop;
    }
}
