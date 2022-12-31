<?php

namespace App\Http\Requests\Home;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'login_id' => ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('name', $request->name);
            })],
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('login_id', $request->login_id);
            })],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required']
        ];

        // 更新時の処理
        if (empty($request->email) === false) {
            $user = User::firstWhere('email', $request->email);
            if (is_null($user) === false) {
                $rules['login_id'] = ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name);
                })->ignore($user),
                ];

                $rules['name'] = ['required', 'string', 'max:255', Rule::unique('users')->where(function ($query) use ($request) {
                        return $query->where('login_id', $request->login_id);
                    })->ignore($user),
                ];

                $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user),
                ];

                $rules['password'] = [''];
            }
        }

        return $rules;
    }
}
