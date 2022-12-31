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

## 1. テーブルとmodelの定義を変更する

- nameのunique属性を外して、新しくlogin_idカラムを追加します
- そして、nameとlogin_id両方合わせたものに、unique属性を付与します

以下2つのmigrationファイルを追加・編集します

```zsh:プロジェクトルート
php artisan make:migration edit_name_of_users_table --table=users
php artisan make:migration add_login_id_to_users_table --table=users
```

まずnameカラムの変更から

```php:edit_name_of_users_table
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditNameOfUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique(['name']);
        });
    }
}
```

編集後に`php artisan migrate`
そしてlogin_idカラムとuniqueキーの追加をします

```php:add_login_id_to_users_table
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoginIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login_id');

            $table->unique(['name', 'login_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name', 'login_id']);
        });
    }
}
```

編集後に`php artisan migrate`
最後にmodelを編集します

- ホワイトリストにlogin_idを追加します

```php:User.php
// ...
    protected $fillable = [
        'login_id',
        'name',
        'email',
        'password',
    ];
// ...
```

## 2. 表示画面と登録・更新ロジックの修正

まずlogin_idの入力欄を追加し、passwordのrequiredを外します

```php:home.blade.php

// ... login_idの入力欄を追加
<div class="row mb-3">
  <label for="name"
      class="col-md-4 col-form-label text-md-end">{{ __('ログインID') }}</label>

  <div class="col-md-6">
      <input v-model="login_id" id="login_id" type="text"
          class="form-control @error('login_id') is-invalid @enderror" name="login_id"
          required>

        @error('login_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

// ... passwordのrequiredを外す
<input v-model="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" @if (session('saved_user')) placeholder="更新が不要な場合は未入力にしてください" @endif>

// ... dataに追記
data: () => {
    return {
        login_id: '{{ old('login_id') ?? (session('saved_user')->login_id ?? '') }}',

// ... confirmメソッドに追記
  } else {
      console.log(this.login_id);
      console.log(this.name);
```

次にロジックの修正をします

- login_idのデータを登録処理に追加します
- 単一のunique属性がemailについているので、登録処理のキーとなるカラムをemailに変更します

```php:HomeController
$params = [
    'login_id' => $request->login_id,
    'name' => $request->name,
    'email' => $request->email,
];
if ($request->password) {
    $params['password'] = Hash::make($request->input(['password']));
}

DB::transaction(function () use ($request, $params) {
    User::updateOrCreate([
        'email' => $request->email,
    ], $params);
});
```

## 3. Requestの作成

まずリクエストファイルを作成します

- app/Http直下にRequestsフォルダが作成されます

```zsh:プロジェクトルート
php artisan make:request Home/Store
```

次にControllerでuseします

- 既存のRequestは削除します

```php:HomeController
namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\Home\StoreRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller

// ...

public function store(StoreRequest $request)
{
```

最後にStoreRequestクラスのauthorizeメソッドの返り値をtrueに変更します

- これをtrueにすることでバリデーションが機能するようになります

```php:StoreRequest.php
// ...
    public function authorize()
    {
        return true;
    }
// ...
```

## 4. ユニークバリデーション