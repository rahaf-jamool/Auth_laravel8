<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
class UserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'fullName' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'alpha_num|bail|string|confirmed|required|min:8',
            'roles' => 'required',
            // 'permissions' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'required'=>'this field is required',
            'password.min' => 'Your User\'s Password Is Too Short',
            'confirmed' => 'your User\'s Password Is Confirmed',
        ];
    }
}
