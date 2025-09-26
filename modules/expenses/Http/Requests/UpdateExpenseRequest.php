<?php

namespace Modules\Expenses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'category' => 'sometimes|required|in:office,travel,meals,supplies,others',
            'expense_date' => 'sometimes|required|date',
            'notes' => 'nullable|string',
        ];
    }
}