<?php

namespace Modules\Expenses\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Modules\Expenses\Enums\ExpenseCategory;
use Illuminate\Support\Str;

class Expense extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'amount',
        'category',
        'expense_date',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'category' => ExpenseCategory::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}