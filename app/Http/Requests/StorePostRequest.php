<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
     * @return array<string, (Rule | array | string)>
     */
    public function rules(): array
    {
        return [
            'bank' => 'required|mimes:csv,xlsx,xls',
            'order_payment' => 'required|mimes:csv,xlsx,xls',
        ];
    }
}
