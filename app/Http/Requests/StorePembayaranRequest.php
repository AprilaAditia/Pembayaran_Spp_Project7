<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembayaranRequest extends FormRequest
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
            'tanggal_bayar' => 'required|date',
            'jumlah_dibayar' => 'required|numeric',
            'tagihan_id' => 'required|numeric',

        ];
    }

        /**
     * Prepare the data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'jumlah_dibayar' => str_replace('.', '', $this->jumlah_dibayar),
        ]);
    }
}
