<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class classifiedProductRequest extends Request
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
            "name"                  =>  "required",
            "category_id.*"         =>  "required",
            "product_condition_id"  =>  "required",
            "product_origin"        =>  "required",
            "meta_tag"              =>  "required",
            "meta_keyword"          =>  "required",
            "meta_description"      =>  "required",
            "terms_conditions"      =>  "required",
            "ckeditor"              =>  "required",
            "available_date.*"      =>  "required",
            "from_time.*"           =>  "required",
            "to_time.*"             =>  "required",
        ];

        if(! is_null(Request::file('uploadvideo') ))
        {
            $return += [
                'uploadvideo'  => 'required|mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv| max:25000',
            ];
        }

        if(! is_null(Request::get('video_link') ))
        {
            $return += [
                'video_link'  => "url",
            ];
        }

        if(empty(Request::file()))
        {
            $return += [
                "product_files.*"   =>  "required",
            ];
        }

        if(Request::get('product_origin') == 'No')
        {
            $return += [
                'billing_address_1'     => 'max:100|required',
                'billing_address_2'     => 'max:100|required',
                'billing_country'       => 'required',
                'billing_postal_code'   => 'max:10|required',
                'billing_state'         => 'required',
                'billing_city'          => 'required'
            ];
        }

        return $return;
    }

    public function messages()
    {
        return [
            'ckeditor.required'         => 'The description filed is required.',
            'category_id.*.required'    => 'The category field is required.',
            'product_files.*.required'  => 'Please upload product image.',
        ];
    }
}