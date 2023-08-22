<?php

namespace App\Http\Requests;

use App\Rules\CustomFileRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:txt,pdf,csv,xlx,xls,xlsx,doc,docx,html,htm,css,js,jpg,jpeg,png,gif,mp4,avi,3gp,webm,wav,ogg,mp3', 'max:5120'],
            'aeskey' => ['required', 'string'],
            'rsa_pri_key' => ['require', new CustomFileRule(['pem']), 'max:1024'],
        ];
    }
}
