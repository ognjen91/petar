<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole('Booker');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ime je obavezno polje',
            'email.required' => 'Email je obavezno polje',
            'password.required' => 'Molimo upišite svoju staru lozinku',
            'new_password.required' => 'Molimo upišite svoju novu lozinku',
            'new_password.confirmed' => 'Nova lozinka i potvrda nove lozinke moraju da se poklapaju!',
            'new_password.min' => 'Nova lozinka mora da ima 8 karaktera!',
        ];
    }
}
