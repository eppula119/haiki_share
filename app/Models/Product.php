<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth; //認証に関わる物を使う
use Illuminate\Support\Facades\Storage; // 画像ファイルの操作
use Illuminate\Support\Carbon; // 日付関連に使用
use Illuminate\Support\Facades\Log; //ログ取得

class Product extends Model
{
    // プライマリキーの自動採番を使用しない
    public $incrementing = false;
    // プライマリキーの型
    protected $keyType = 'string';
    // IDの桁数
    const ID_LENGTH = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'best_before',
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'image_5',
    ];

    /** JSONに含める属性 */
    protected $visible = [
        'id',
        'name',
        'price',
        'shop',
        'users',
        'best_day', // アクセサ
        'best_time', // アクセサ
        'images', // アクセサ
        'buy_flg' // アクセサ
    ];

    /** JSONに追加する属性 */
    protected $appends = [
        'images',
        'best_day',
        'best_time',
        
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

    // 売り手ユーザーリレーション
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    /**
     * アクセサ - images
     * @return object
     */
    public function getImagesAttribute()
    {
        // クラウドのストレージ情報を取得
        $storage = Storage::cloud();
        $images = array();
        // 商品テーブルに登録可能な画像枚数分ループ
        for ($num = 0; $num < 5; $num++){
            // 画像ナンバー
            $imgNumber = $num + 1;
            // 既に商品画像が登録されているレコードの場合(GET通信などで情報取得の場合)
            if(!empty($this->attributes["image_{$imgNumber}"])) {
                // 取得した商品の画像情報
                $productImg = $this->attributes["image_{$imgNumber}"];
                // 画像情報がある場合
                if(!empty($productImg)) {
                    // クラウドデータから取得した画像URLを配列に追加
                    $image = array( "image_{$imgNumber}" => $storage->url('product_images/' . $this->attributes["image_{$imgNumber}"]) );
                    $images = array_merge($images, $image);
                }
            }
        }

       return $images;
    }

    /**
     * アクセサ - best_day
     * @return object
     */
    public function getBestdayAttribute()
    {
        // 既に賞味期限が登録されているレコードの場合(GET通信などで情報取得の場合)
        if(!empty($this->attributes['best_before'])) {
            // 取得した文字列データを任意のフォーマットへ変換
            $bestDay = new Carbon($this->attributes['best_before']);
            $bestDay =  $bestDay->format('Y年m月d日');
            return $bestDay;
        }
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

    // /**
    //  * アクセサ - buy_flg
    //  * @return object
    //  */
    // public function getBuyFlgAttribute()
    // {
    //     $buyFlg = array('buy_flg' => false, 'my_buy_flg' => false);
    //     // 購入テーブルの「updated_at」カラムの値を格納する配列
    //     $updatedAtArray = array();
    //     // 購入した(購入キャンセル含む)商品IDに紐づくユーザー情報取得
    //     $users = $this->users;
        
    //     Log::debug($users);
    //     // 購入した(購入キャンセル含む)商品IDに紐づくユーザー情報をループ処理
    //     foreach ($users as $user) {
    //         // 購入テーブルの「updated_at」カラムの値を追加
    //         array_push($updatedAtArray, array($user->pivot->updated_at));
    //     }
    //     // 購入履歴がある場合
    //     if(!empty($updatedAtArray)) {
    //         // 最新の購入日時のみ取得
    //         $latestUpdatedAt = max($updatedAtArray);
    //         Log::debug($latestUpdatedAt);
    //     }

    //     foreach ($users as $user) {
    //         // 最新購入日時のレコードにて、購入キャンセルされてない(購入済み)場合
    //         if($latestUpdatedAt === $user->pivot->updated_at && $user->pivot->deleted_at === null) {
    //             // 購入済みフラグをtrue
    //             $buyFlg['buy_flg'] = true;
    //             // ログインユーザーが購入している場合
    //             if($user->id === $authUserId) {
    //                 // 自分の購入フラグをtrue
    //                 $buyFlg['my_buy_flg'] = true;
    //             }
    //             return $buyFlg;
    //         }
            
    //     }
    //     /* 購入履歴が無い、もしくは最新購入日時のレコードにて、
    //     購入キャンセルされてる場合は購入済みフラグをfalse */
    //     return $buyFlg;
        
    // }

    // 買い手ユーザーリレーション
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'bought_products')->withPivot('deleted_at', 'updated_at');
    }

    
}
