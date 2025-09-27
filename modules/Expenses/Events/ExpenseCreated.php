<?php

namespace Modules\Expenses\Events;

use Modules\Expenses\Models\Expense;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpenseCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Expense $expense) {}
}