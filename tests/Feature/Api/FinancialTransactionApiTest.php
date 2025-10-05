<?php

namespace Tests\Feature\Api;

use App\Models\FinancialTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialTransactionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_transactions_can_be_listed(): void
    {
        $first = FinancialTransaction::factory()->create([
            'type' => 'income',
            'category' => 'rental_income',
            'amount' => 750,
            'transaction_date' => '2024-04-10',
        ]);

        $second = FinancialTransaction::factory()->create([
            'type' => 'expense',
            'category' => 'maintenance',
            'amount' => 120,
            'transaction_date' => '2024-02-05',
        ]);

        $response = $this->getJson('/api/v1/financial-transactions?per_page=10');

        $response->assertOk()
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('data.0.id', $first->id)
            ->assertJsonPath('data.1.id', $second->id);
    }

    public function test_transaction_can_be_created(): void
    {
        $payload = [
            'type' => 'income',
            'category' => 'rental_income',
            'amount' => 1500,
            'transaction_date' => '2024-03-01',
            'reference' => 'INV-2024-001',
            'notes' => 'March rental payment',
        ];

        $response = $this->postJson('/api/v1/financial-transactions', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.type', 'income')
            ->assertJsonPath('data.reference', 'INV-2024-001')
            ->assertJsonPath('data.amount', 1500);

        $this->assertDatabaseHas('financial_transactions', [
            'type' => 'income',
            'category' => 'rental_income',
            'amount' => 1500,
            'transaction_date' => '2024-03-01 00:00:00',
        ]);
    }

    public function test_transaction_can_be_updated(): void
    {
        $transaction = FinancialTransaction::factory()->create([
            'type' => 'income',
            'category' => 'rental_income',
            'amount' => 500,
            'transaction_date' => '2024-01-15',
        ]);

        $response = $this->putJson("/api/v1/financial-transactions/{$transaction->id}", [
            'type' => 'expense',
            'category' => 'maintenance',
            'amount' => 320.45,
            'transaction_date' => '2024-01-20',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.type', 'expense')
            ->assertJsonPath('data.category', 'maintenance')
            ->assertJsonPath('data.amount', 320.45)
            ->assertJsonPath('data.transaction_date', '2024-01-20');

        $this->assertDatabaseHas('financial_transactions', [
            'id' => $transaction->id,
            'type' => 'expense',
            'category' => 'maintenance',
            'amount' => 320.45,
        ]);
    }

    public function test_transaction_can_be_deleted(): void
    {
        $transaction = FinancialTransaction::factory()->create();

        $this->deleteJson("/api/v1/financial-transactions/{$transaction->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('financial_transactions', [
            'id' => $transaction->id,
        ]);
    }

    public function test_validation_errors_are_reported(): void
    {
        $response = $this->postJson('/api/v1/financial-transactions', [
            'type' => 'invalid',
            'category' => 'unknown',
            'amount' => -5,
            'transaction_date' => 'not-a-date',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['type', 'category', 'amount', 'transaction_date']);
    }
}
