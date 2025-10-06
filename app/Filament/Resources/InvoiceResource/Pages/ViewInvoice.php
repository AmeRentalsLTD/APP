<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Invoice details')
                    ->schema([
                        TextEntry::make('number')->label('Number'),
                        TextEntry::make('customer.name')->label('Customer'),
                        TextEntry::make('issue_date')->date()->label('Issued on'),
                        TextEntry::make('due_date')->date()->label('Due on'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('total_gross')->label('Total gross')->formatStateUsing(fn ($state) => '£' . number_format((float) $state, 2)),
                    ])->columns(3),
                Section::make('Line items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('description'),
                                TextEntry::make('qty')->label('Qty'),
                                TextEntry::make('unit_price_net')->label('Unit net'),
                                TextEntry::make('vat_rate')->label('VAT %'),
                                TextEntry::make('line_total_gross')->label('Total gross'),
                            ])
                            ->columns(5),
                    ]),
                Section::make('Payments')
                    ->schema([
                        RepeatableEntry::make('payments')
                            ->schema([
                                TextEntry::make('paid_at')->dateTime()->label('Paid at'),
                                TextEntry::make('method')->label('Method'),
                                TextEntry::make('amount_gross')->label('Amount')->formatStateUsing(fn ($state) => '£' . number_format((float) $state, 2)),
                                TextEntry::make('reference')->label('Reference'),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
}
