<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PropertiesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama'=>["required"],
            'alamat'=>["nullable"],
            'baru'=>["required"],
            'type' => ['required', Rule::in(['Rumah', 'Ruko', 'Tanah', 'Apartemen'])],
            'thumbnail'=>["nullable","image"],
            "images" => ['required', 'array'], // Make sure it's an array
            "images.*" => ['image'],
            "harga"=>["required"], 
            "sertifikat"=>["nullable", "max:100"], 
            'jumlah_kamar_tidur'=> ["nullable", "max:100"], 
            "jumlah_kamar_mandi"=> ["nullable", "max:100"], 
            'jumlah_dapur'=> ["nullable", "max:100"],
            "kolam_renang"=> ["nullable", "max:100"], 
            "gudang"=> ["nullable", "max:100"],
            'garasi'=> ["nullable", "max:100"],
            'tingkat'=> ["nullable", "max:100"],
            'listrik'=> ["nullable", "max:100"],
            'luas_tanah'=> ["nullable", "max:100"],
            'luas_bangunan'=> ["nullable", "max:100"],
            'deskripsi'=> ["nullable"],
            'lokasi'=> ["required"],
            'vr'=> ["required"],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}