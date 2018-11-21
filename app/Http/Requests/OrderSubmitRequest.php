<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderSubmitRequest extends FormRequest
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
            'user_name' => 'required',
            'national_code' => 'required',
            'postal_code' => 'required',
            'tel' => 'required',
            'province' => 'required',
            'city' => 'required',
            'county' => 'required',
            'detail' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'user_name.required' => '缺少联系人参数',
            'national_code.required' => '缺少国际码参数',
            'postal_code.required' => '缺少邮政编码参数',
            'tel.required' => '缺少联系电话参数',
            'province.required' => '缺少省份参数',
            'city.required' => '缺少城市参数',
            'county.required' => '缺少县参数',
            'detail.required' => '缺少详细地址参数',
        ];
    }
}
