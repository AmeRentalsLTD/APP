<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepositResource\Pages;
use App\Models\Deposit;
use App\Services\InvoiceNumberGenerator;
use App\Services\VatCalculator;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    protected static ?string $navigationGroup = 'Finance';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rental_id')
                    ->relationship('rental', 'id')
                    ->required(),
                Forms\Components\TextInput::make('amount_net')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('vat_rate')
                    ->numeric()
                    ->default(0),
                Forms\Components\DatePicker::make('held_at')->required(),
                Forms\Components\DatePicker::make('released_at'),
                Forms\Components\Select::make('status')->options([
                    'held' => 'Held',
                    'partially_released' => 'Partially released',
                    'released' => 'Released',
                    'withheld' => 'Withheld',
                ])->required(),
                Forms\Components\Textarea::make('note')->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rental.customer.name')->label('Customer'),
                Tables\Columns\TextColumn::make('rental.vehicle.registration')->label('Vehicle'),
                Tables\Columns\TextColumn::make('amount_net')->label('Amount')->formatStateUsing(fn ($state) => '£' . number_format((float) $state, 2)),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('held_at')->date(),
                Tables\Columns\TextColumn::make('released_at')->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('release')
                    ->label('Release')
                    ->visible(fn (Deposit $record) => $record->status !== 'released')
                    ->requiresConfirmation()
                    ->action(function (Deposit $record) {
                        $record->update([
                            'status' => 'released',
                            'released_at' => Carbon::today(),
                        ]);
                    }),
                Tables\Actions\Action::make('withhold')
                    ->label('Withhold')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount to withhold (net)')
                            ->numeric()
                            ->required(),
                        Forms\Components\Textarea::make('reason')->rows(3)->required(),
                    ])
                    ->action(function (Deposit $record, array $data) {
                        $today = Carbon::today();
                        $generator = app(InvoiceNumberGenerator::class);
                        $vatCalculator = app(VatCalculator::class);
                        $invoice = $record->rental->invoices()->create([
                            'customer_id' => $record->rental->customer_id,
                            'number' => $generator->nextNumber($today),
                            'issue_date' => $today,
                            'due_date' => $today->copy()->addDays(3),
                            'status' => 'sent',
                            'currency' => config('finance.currency', 'GBP'),
                        ]);

                        $net = (float) $data['amount'];
                        $tax = $vatCalculator->taxAmount($net, 0);

                        $invoice->items()->create([
                            'type' => 'other',
                            'description' => 'Deposit withhold – ' . $data['reason'],
                            'qty' => 1,
                            'unit_price_net' => $net,
                            'vat_rate' => 0,
                            'line_total_net' => $net,
                            'line_tax' => $tax,
                            'line_total_gross' => $net + $tax,
                        ]);

                        $record->update([
                            'status' => 'withheld',
                            'note' => trim(($record->note ? $record->note . PHP_EOL : '') . 'Withheld: ' . $data['reason']),
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'create' => Pages\CreateDeposit::route('/create'),
            'edit' => Pages\EditDeposit::route('/{record}/edit'),
        ];
    }
}
