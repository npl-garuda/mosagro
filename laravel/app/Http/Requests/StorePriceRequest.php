<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StorePriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'organization_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'inn' => ['nullable', 'string', 'max:20'],
            'work_format' => ['nullable', 'string', 'in:опт,розница,сеть,маркетплейс'],
            'comment' => ['nullable', 'string', 'max:5000'],
        ];
    }
    public function attributes(): array
    {
        return [
            'organization_name' => 'название организации',
            'phone' => 'телефон',
            'email' => 'email',
            'inn' => 'ИНН',
            'work_format' => 'формат работы',
            'comment' => 'комментарий',
        ];
    }
}
