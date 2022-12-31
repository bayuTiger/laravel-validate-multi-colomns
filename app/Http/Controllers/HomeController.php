<?php

namespace App\Http\Controllers;

use App\Http\Requests\Home\StoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function store(StoreRequest $request)
    {
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

        $saved_user = User::firstWhere('email', $request->input(['email']));
        $status = 'ユーザー情報の登録に成功しました！';

        return redirect()->route('home')->with(compact('saved_user', 'status'));
    }
}
