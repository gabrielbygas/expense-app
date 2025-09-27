<?php

namespace Modules\Expenses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:food,transport,utilities,entertainment,other', 
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}