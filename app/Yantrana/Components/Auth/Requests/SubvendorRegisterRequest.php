<?php

namespace App\Yantrana\Components\Auth\Requests;

use App\Yantrana\Base\BaseRequest;

class SubvendorRegisterRequest extends BaseRequest
{
    /**
     * Secure form
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

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
        $rules = [
            'brand_title' => 'required|string|min:2|max:100',
            'brand_category' => 'required|string',
            'username' => 'required|string|unique:users|alpha_dash|min:2|max:45|unique:users,username',
            'phone_number' => 'required|string|digits:10', 
            'wa_number' => 'required|string|digits:10', 
            'email' => 'required|string|email|unique:users,email' . (getAppSettings('disallow_disposable_emails') ? '|indisposable' : ''),
            'address' => 'required|string|min:2|max:100',
            'city' => 'required|string|min:2|max:100',
            'district' => 'required|string|min:2|max:100',
            'state' => 'required|string|min:2|max:100',
            'password' => 'required|string|confirmed|min:8',
        ];

        if (getAppSettings('user_terms') or getAppSettings('vendor_terms') or getAppSettings('privacy_policy')) {
            $rules['terms_and_conditions'] = 'accepted';
        }

        return $rules;
    }
}
