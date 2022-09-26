/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import './bootstrap'

import Vue from 'vue'
// ルーティングの定義をインポート
import router from './router'
// ストアの定義をインポート
import store from './store'
// ルートコンポーネントをインポート
import App from './App.vue'

const createApp = async () => {
    // vueインスタンス生成前にログインユーザー情報を取得
    await store.dispatch('auth/currentUser')
    
    new Vue({
        el: '#app',
        router, // ルーティングの定義を読み込む
        store, // ストアの定義を読み込む
        components: { App }, // ルートコンポーネントの使用を宣言する
        template: '<App />' // ルートコンポーネントを描画する
    })
}

createApp()
