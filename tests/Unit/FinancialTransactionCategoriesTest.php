<?php

namespace Tests\Unit;

use App\Models\FinancialTransaction;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FinancialTransactionCategoriesTest extends TestCase
{
    #[Test]
    public function it_returns_categories_for_each_type(): void
    {
        $this->assertSame(
            FinancialTransaction::CATEGORY_GROUPS['income'],
            FinancialTransaction::categories('income')
        );

        $this->assertSame(
            FinancialTransaction::CATEGORY_GROUPS['expense'],
            FinancialTransaction::categories('expense')
        );
    }

    #[Test]
    public function it_returns_a_merged_list_when_type_not_provided(): void
    {
        $merged = FinancialTransaction::categories();
        $expected = array_values(array_unique(array_merge(
            FinancialTransaction::CATEGORY_GROUPS['income'],
            FinancialTransaction::CATEGORY_GROUPS['expense'],
        )));

        $this->assertSame($expected, $merged);
    }

    #[Test]
    public function it_exposes_default_categories_for_each_type(): void
    {
        $this->assertSame('rental_income', FinancialTransaction::defaultCategory());
        $this->assertSame('maintenance', FinancialTransaction::defaultCategory('expense'));
    }
}
