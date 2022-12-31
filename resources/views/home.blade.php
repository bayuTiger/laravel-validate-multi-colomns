@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">登録・更新</div>

                    <div class="card-body">
                        {{-- 登録完了メッセージ --}}
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('store') }}">
                            @csrf
                            {{-- ログインID --}}
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

                            {{-- 名前 --}}
                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('名前') }}</label>

                                <div class="col-md-6">
                                    <input v-model="name" id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name" required>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- メールアドレス --}}
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('メールアドレス') }}</label>

                                <div class="col-md-6">
                                    <input v-model="email" id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email" required>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- パスワード --}}
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('パスワード') }}</label>

                                <div class="col-md-6">
                                    <input v-model="password" id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        @if (session('saved_user')) placeholder="更新が不要な場合は未入力にしてください" @endif>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" @click="confirm">登録</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        const app = new Vue({
            el: '#app',
            data: () => {
                return {
                    login_id: '{{ old('login_id') ?? (session('saved_user')->login_id ?? '') }}',
                    name: '{{ old('name') ?? (session('saved_user')->name ?? '') }}',
                    email: '{{ old('email') ?? (session('saved_user')->email ?? '') }}',
                    password: '{{ old('password') ?? '' }}',
                }
            },
            methods: {
                confirm: function(e) {
                    if (confirm('登録しますか？')) {
                        return true;
                    } else {
                        console.log(this.login_id);
                        console.log(this.name);
                        console.log(this.email);
                        console.log(this.password);
                        e.preventDefault();
                        return false;
                    }
                }
            }
        });
    </script>
@endsection
