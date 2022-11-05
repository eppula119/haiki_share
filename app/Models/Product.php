<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // クエリビルダー
use Illuminate\Database\Eloquent\SoftDeletes; // 倫理削除
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
    // 倫理削除機能を利用
    use SoftDeletes;

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
        'buy_flg',
        'best_day', // アクセサ
        'best_time', // アクセサ
        'images', // アクセサ
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

   

    // 買い手ユーザーリレーション
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'bought_products', 'product_id', 'user_id')->withPivot('deleted_at', 'updated_at');
    }

    /**
     * 絞り込み・キーワード検索
     * @param \Illuminate\Database\Eloquent\Builder
     * @param array
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, array $params)
    {
        Log::debug('絞り込み開始');
        // 価格の絞り込み(最小値または最大値の絞り込みがある場合)
        if(isset($params['min']) || isset($params['max'])) {
            Log::debug('価格での絞り込み開始');
            // 最小値入力がある場合、数値型に変換。無い場合は0を保持
            $min = isset($params['min']) ? intval($params['min']) : 0;
            // 最大値入力がある場合、数値型に変換。無い場合はnullを保持
            $max = isset($params['max']) ? intval($params['max']) : null;
            // 最大値入力ある場合
            if(isset($max)) {
                Log::debug('最小値、最大値あり');
                // 最小値から最大値の間で商品を絞り込み
                $query->whereBetween('price', [$min, $max]);
            } else {
                Log::debug('最大値なし');
                // 最大値入力ない場合、最小値以上の商品を絞り込み
                $query->where('price', '>=', $min);
            }
        }

        // 賞味期限の絞り込み
        if (!empty($params['bestBefore'])) {
            // 賞味期限内の場合
            if($params['bestBefore'] === "true") {
                Log::debug('賞味期限内(現在の日時以上の賞味期限)');
                // 賞味期限が現在の日時以後の商品で絞り込み
                $query->where('best_before', '>=', date('Y-m-d H:i:s'));
            // 賞味期限切れの場合
            } else if($params['bestBefore'] === "false") {
                Log::debug('賞味期限切れ(現在の日時未満の賞味期限)');
                // 賞味期限が現在の日時以前の商品で絞り込み
                $query->where('best_before', '<', date('Y-m-d H:i:s'));
            }
        }

        // 都道府県の絞り込み
        if (!empty($params['prefecture'])) {
            // 都道府県パラメーター保持
            $prefectures = $params['prefecture'];
            // 取得した商品に紐づく売り手ユーザー情報を取得
            $query->whereHas('shop.prefecture', function ($q) use ($prefectures) {
                // 売り手ユーザーの都道府県名がパラメーターの名前に合致する商品のみ取得
                $q->whereIn('prefectures.name', $prefectures);
            });
        }

        return $query;
    }
    
}
