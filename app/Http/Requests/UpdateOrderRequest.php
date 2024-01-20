<?php

namespace App\Http\Requests;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $createdAt = DateTime::createFromFormat('d/m/Y H:i:s', $this->created_at);
        $createdAtFormatted = $createdAt ? $createdAt->format('Y-m-d H:i:s') : null;

        $updatedAt = DateTime::createFromFormat('d/m/Y H:i:s', $this->updated_at);
        $updatedAtFormatted = $updatedAt ? $updatedAt->format('Y-m-d H:i:s') : null;

        $expireAt = DateTime::createFromFormat('d/m/Y H:i:s', $this->expire_at);
        $expireAtFormatted = $expireAt ? $expireAt->format('Y-m-d H:i:s') : null;

        $approvedAt = DateTime::createFromFormat('d/m/Y H:i:s', $this->approved_at);
        $approvedAtFormatted = $approvedAt ? $approvedAt->format('Y-m-d H:i:s') : null;

        $this->merge([
            'value' => str_replace(['.', ','], ['', '.'], $this->value),
            'created_at' => $createdAtFormatted,
            'updated_at' => $updatedAtFormatted,
            'expire_at' => $expireAtFormatted,
            'approved_at' => $approvedAtFormatted,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'pack_id' => ['required', 'exists:packs,id'],
            'value' => ['required', 'numeric'],
            'payment_method' => ['nullable', 'string'],
            'image' => ['nullable', 'file'],
            'parcels' => ['nullable', 'integer'],
            'parcels_data' => ['nullable', 'string'],
            'status' => ['nullable'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'expire_at' => ['nullable'],
            'approved_at' => ['nullable'],
        ];
    }
}
