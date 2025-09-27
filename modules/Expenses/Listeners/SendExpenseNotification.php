<?php

namespace Modules\Expenses\Listeners;

use Modules\Expenses\Events\ExpenseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendExpenseNotification implements ShouldQueue
{
    public function handle(ExpenseCreated $event): void
    {
        Log::info('Expense created', ['id' => $event->expense->id, 'amount' => $event->expense->amount]);
    }
}