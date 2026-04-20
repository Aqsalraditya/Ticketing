<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Mengambil objek ticket dari route parameter
        $ticket = $this->route('ticket');

        // SECURITY: Menggunakan Policy untuk mengecek apakah user boleh update tiket ini
        return $this->user()->can('update', $ticket);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => $this->title ? trim($this->title) : null,
            'description' => $this->description ? trim($this->description) : null,
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'min:20',
            ],
            'priority' => [
                'required',
                'in:low,medium,high',
            ],
            'category' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];

        // SECURITY: Hanya Admin dan Staff yang diizinkan mengirim/mengubah field 'status'
        if ($this->user()->hasAnyRole(['admin', 'staff'])) {
            $rules['status'] = [
                'required',
                'in:open,in_progress,resolved,closed',
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul tiket wajib diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.min' => 'Judul minimal :min karakter.',
            'title.max' => 'Judul maksimal :max karakter.',
            
            'description.required' => 'Deskripsi tiket wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'description.min' => 'Deskripsi minimal :min karakter agar permasalahan jelas.',
            
            'status.required' => 'Status tiket wajib dipilih.',
            'status.in' => 'Status tidak valid. Pilih: Open, In Progress, Resolved, atau Closed.',
            
            'priority.required' => 'Prioritas tiket wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid. Pilih: Low, Medium, atau High.',
            
            'category.string' => 'Kategori harus berupa teks.',
            'category.max' => 'Kategori maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul tiket',
            'description' => 'deskripsi',
            'status' => 'status',
            'priority' => 'prioritas',
            'category' => 'kategori',
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'description' => strip_tags($this->description),
        ]);
    }
}