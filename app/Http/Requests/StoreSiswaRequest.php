<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'wali_id' => 'nullable',
            'nama' => 'required',
            'nisn' => 'required|unique:siswas',
            'tingkatan' => 'required',
            'kelas' => 'required',
            'angkatan' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ];
    }
}
