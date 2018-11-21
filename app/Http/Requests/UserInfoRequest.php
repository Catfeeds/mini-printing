<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserInfoRequest extends FormRequest
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
            'birthday' => 'nullable|date',
            'phone' => 'nullable|regex:/^1[34578]\d{9}$/',
        ];
    }

    public function messages()
    {
        return [
           'birthday.date' => '请输入正确的时间格式',
            'phone.regex' => '手机号格式错误',
        ];
    }
}
