# Laravel8 バリデーション 様々なユニークバリデーションをかける

## 環境

```zsh
// envのDB接続部を書き換えておく
php artisan migrate:fresh
composer require laravel/ui
php artisan ui vue --auth
npm install
npm install resolve-url-loader@^5.0.0 --save-dev --legacy-peer-deps
npm run dev
composer require laravel/pint --dev
php artisan serve
```

## 概要

以下記事の続きとして、バリデーションをかけていきます

https://qiita.com/akitika/items/837aa9a0932756eb542a

本リポジトリの記事↓

https://qiita.com/akitika/items/75f4edd3b2142b126b09