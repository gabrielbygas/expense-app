<?php

namespace Modules\Expenses\Tests\Feature;

use Tests\TestCase;
use Modules\Expenses\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_expense(): void
    {
        $data = [
            'title' => 'Diner',
            'amount' => 50.99,
            'category' => 'food',
            'expense_date' => '2025-09-26',
        ];

        $response = $this->postJson('/api/expenses', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('expenses', $data);
    }

    public function test_can_list_expenses(): void
    {
        Expense::factory()->create(); // On créera la factory plus tard
        $response = $this->getJson('/api/expenses');
        $response->assertStatus(200);
    }
}