<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name' => ['required'],
            'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
            'email' => ['required', 'email'],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'postalCode' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => ':attribute không được bỏ trống',
            'type.required' => ':attribute không được bỏ trống',
            'type.in' => ':attribute không hợp lệ',
            'email.required' => ':attribute không được bỏ trống',
            'email.email' => ':attribute sai định dạng',
            'type.required' => ':attribute không được bỏ trống',
            'address.required' => ':attribute không được bỏ trống',
            'city.required' => ':attribute không được bỏ trống',
            'state.required' => ':attribute không được bỏ trống',
            'postalCode' => ':attribute không được bỏ trống',
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Tên',
            'type' => 'Loại',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'city' => 'Thành phố',
            'state' => 'Bang',
            'postalCode' => 'Mã vùng',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'postal_code' => $this->postalCode,
        ]);
    }
}