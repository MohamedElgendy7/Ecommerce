<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'abbr' => 'required|string|max:10',
            //'active' => 'required|in:1,0',
            'direction' => 'required|in:rtl,ltr',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'in' => 'القيمة المدخلة غير صحيحة',
            'string' => 'احرف فقط',

            'name.max' => 'هذا الاسم طويل , يجب ان لا يتخطي ال 100 حرف',
            'abbr.max' => 'هذا الاختصار طويل , يجب ان لا يتخطي ال 10 حرف',
        ];
    }
}
