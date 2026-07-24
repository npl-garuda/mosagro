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
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'inn' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'contact_preference' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:5000'],
            'consent' => ['accepted'],
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'ФИО',
            'phone' => 'телефон',
            'email' => 'email',
            'inn' => 'ИНН',
            'city' => 'город',
            'contact_preference' => 'как удобнее связаться',
            'comment' => 'комментарий',
            'consent' => 'согласие на обработку ПДн',
        ];
    }
}
