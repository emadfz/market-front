<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SendBuyerMessageRequest extends Request
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
        $return = [
            "to"           =>  "required",
            "subject"      =>  "required",
            "ckeditor"     =>  "required",
        ];

        return $return;
    }

    public function messages()
    {
        return [
            'subject.required'    => 'The subject field is required.',
            'ckeditor.required'  => 'The body field is required.',
        ];
    }
}