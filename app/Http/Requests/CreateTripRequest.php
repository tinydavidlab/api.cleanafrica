<?php

namespace App\Http\Requests;


use Anik\Form\FormRequest;

class CreateTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer_name'                 => 'required',
            'customer_primary_phone_number' => 'required',
            'customer_apartment_number'     => 'required',
            'customer_country'              => 'required',
            'customer_division'             => 'required',
            'customer_subdivision'          => 'required',
            'customer_snoocode'             => 'required',
            'customer_latitude'             => 'required',
            'customer_longitude'            => 'required',
            'company_id'                    => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            //
        ];
    }
}
