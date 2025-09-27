<?php

namespace Modules\Expenses\Database\Seeders;


use Illuminate\Database\Seeder;
use Modules\Expenses\Models\Expense;

class ExpensesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['food', 'transport', 'utilities', 'entertainment', 'other'];
        
        foreach (range(1,25) as $i) {
            Expense::factory()->create([
                'category' => $categories[array_rand($categories)],
            ]);
        }
    }
}