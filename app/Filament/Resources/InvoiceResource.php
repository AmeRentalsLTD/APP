<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Mail\SendInvoiceEmail;
use App\Models\Invoice;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationGroup = 'Finance';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Forms\Components\Select::make('rental_id')
                    ->relationship('rental', 'id')
                    ->label('Rental')
                    ->searchable(),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->default(fn () => app(\App\Services\InvoiceNumberGenerator::class)->nextNumber()),
                Forms\Components\DatePicker::make('issue_date')->required(),
                Forms\Components\DatePicker::make('due_date')->required(),
                Forms\Components\Select::make('status')->options([
                    'draft' => 'Draft',
                    'sent' => 'Sent',
                    'part_paid' => 'Part paid',
                    'paid' => 'Paid',
                    'overdue' => 'Overdue',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('Number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('issue_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('due_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('total_gross')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => '£' . number_format((float) $state, 2)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'secondary' => 'draft',
                        'primary' => 'sent',
                        'warning' => 'part_paid',
                        'success' => 'paid',
                        'danger' => 'overdue',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sendPdf')
                    ->label('Send PDF')
                    ->requiresConfirmation()
                    ->action(function (Invoice $record) {
                        Mail::to($record->customer->email)->queue(new SendInvoiceEmail($record));
                    }),
                Tables\Actions\Action::make('addPayment')
                    ->label('Add payment')
                    ->form([
                        Forms\Components\Select::make('method')->options([
                            'bank' => 'Bank transfer',
                            'stripe' => 'Stripe',
                            'gocardless' => 'GoCardless',
                            'cash' => 'Cash',
                            'other' => 'Other',
                        ])->default('bank')->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount (gross)')
                            ->numeric()
                            ->required(),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('reference'),
                        Forms\Components\Textarea::make('notes')->rows(2),
                    ])
                    ->action(function (Invoice $record, array $data) {
                        $record->payments()->create([
                            'method' => $data['method'],
                            'amount_gross' => $data['amount'],
                            'paid_at' => Carbon::parse($data['paid_at']),
                            'reference' => $data['reference'] ?? null,
                            'notes' => $data['notes'] ?? null,
                        ]);

                        $record->refresh();
                    }),
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark as paid')
                    ->requiresConfirmation()
                    ->visible(fn (Invoice $record) => $record->status !== 'paid')
                    ->action(function (Invoice $record) {
                        $record->status = 'paid';
                        $record->save();
                    }),
                Tables\Actions\Action::make('downloadPdf')
                    ->label('Download PDF')
                    ->url(fn (Invoice $record) => route('invoices.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'view' => Pages\ViewInvoice::route('/{record}'),
        ];
    }
}
