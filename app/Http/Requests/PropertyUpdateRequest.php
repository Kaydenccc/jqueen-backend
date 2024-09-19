<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PropertyUpdateRequest extends FormRequest
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
            'nama'=>["nullable"],
            'alamat'=>["nullable"],
            'baru'=>["nullable"],
            'type' => ['nullable', Rule::in(['Rumah', 'Ruko', 'Tanah', 'Apartemen'])],
            'newThumbnail'=>["nullable", "image", "mimes:jpeg,png,bmp,jpg","max:5120"],
            'thumbnail'=>["nullable", "string"],
            "harga"=>["nullable"], 
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
            'lokasi'=> ["nullable", "max:100"],
            'vr'=> ["nullable"],
        ];
    }

    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
