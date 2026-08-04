<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKerjasamaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'ks_jenis' => 'required|exists:ks_jenis,id',
            'ks_tingkat' => 'required|exists:ks_tingkat,id',
            'nama_kl' => 'required|string|max:200',
            'narahubung_adm' => 'nullable|string|max:200',
            'nomor_cp_adm' => 'nullable|string|max:50',
            'narahubung_teknis' => 'nullable|string|max:200',
            'nomor_cp_teknis' => 'nullable|string|max:50',
        ];

        if ($this->input('ks_jenis') == 3) {
            $rules['surat_permohonan'] = 'required|file|mimes:pdf|max:20480';
            $rules['draft_nk'] = 'required|file|mimes:pdf|max:20480';
        }

        return $rules;
    }
}
