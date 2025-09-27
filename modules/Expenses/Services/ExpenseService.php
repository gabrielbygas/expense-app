<?php

namespace Modules\Expenses\Services;

use Modules\Expenses\Models\Expense;
use Modules\Expenses\Events\ExpenseCreated; 
use Illuminate\Support\Collection;

class ExpenseService
{
    public function list(): Collection
    {
        return Expense::latest()->get();
    }

    public function create(array $data): Expense
    {
        $expense = Expense::create($data);
        event(new ExpenseCreated($expense)); 
        return $expense;
    }

    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);
        return $expense;
    }

    public function delete(Expense $expense): bool
    {
        return $expense->delete();
    }

    // Bonus: Filtre par catégorie et date
    public function filter(array $filters): Collection
    {
        return Expense::when($filters['category'] ?? null, fn($q) => $q->where('category', $filters['category']))
            ->when($filters['from_date'] ?? null, fn($q) => $q->where('expense_date', '>=', $filters['from_date']))
            ->when($filters['to_date'] ?? null, fn($q) => $q->where('expense_date', '<=', $filters['to_date']))
            ->get();
    }
}