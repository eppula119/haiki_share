const mix = require('laravel-mix');

mix.webpackConfig({
    module: {
        rules: [
            { // scssファイルのimport時、ワイルドカードを使えるようにする設定
                test: /\.scss/,
                loader: 'import-glob-loader'
            }
        ]
    }
})

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({ processCssUrls: false }) // cssのbackground-imageのurlパスを分かり易くするため
    .sourceMaps() //ソースマップの出力(コンパイル前のファイルを出力してデバッグし易くするため)
    .browserSync({ //ブラウザの自動リロード
        host: 'local.haiki-share.jp',
        https: false, // https通信をproxyする場合ばtrueを設定
        files: [
            'resources/**/*.*',
            'config/**/*.*',
            'routes/**/*.*',
            'app/**/*.*',
            'public/**/*.*',
        ],
        proxy: {
            target: 'http://local.haiki-share.jp',
        },
        open: false, //起動時にブラウザを開かない
        reloadOnRestart: true //起動時にブラウザにリロード命令おくる
    });
