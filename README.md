# Laravel8 バリデーション 複数のカラムに跨ったユニーク条件をかける

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
