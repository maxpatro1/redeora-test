<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'birthdate' => 'required|date|before:today',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Имя обязательно для заполнения.',
            'second_name.required' => 'Фамилия обязательна для заполнения.',
            'birthdate.required' => 'Дата рождения обязательна.',
            'birthdate.date' => 'Дата рождения должна быть корректной датой.',
            'birthdate.before' => 'Дата рождения должна быть раньше сегодняшнего дня.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'first_name' => trim($this->first_name),
            'second_name' => trim($this->second_name),
        ]);
    }
}
