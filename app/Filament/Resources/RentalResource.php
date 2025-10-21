<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalResource\Pages;
use App\Models\Rental;
use App\Services\InvoiceNumberGenerator;
use App\Services\VatCalculator;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationGroup = 'Finance';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->label('Customer')
                    ->required(),
                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'registration')
                    ->searchable()
                    ->label('Vehicle')
                    ->required(),
                Forms\Components\TextInput::make('price_net')
                    ->numeric()
                    ->step('0.01')
                    ->label('Rent (net)')
                    ->required(),
                Forms\Components\TextInput::make('vat_rate')
                    ->numeric()
                    ->label('VAT rate')
                    ->default(config('finance.default_vat_rate')),
                Forms\Components\Select::make('frequency')
                    ->options([
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('deposit_net')
                    ->numeric()
                    ->step('0.01')
                    ->label('Deposit (net)')
                    ->default(0),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\DatePicker::make('end_date'),
                Forms\Components\TextInput::make('notice_days')
                    ->numeric()
                    ->label('Notice (days)')
                    ->default(14),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                        'ended' => 'Ended',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Customer')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('vehicle.registration')->label('Vehicle')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price_net')
                    ->label('Rent net')
                    ->formatStateUsing(fn ($state) => '£' . number_format((float) $state, 2)),
                Tables\Columns\TextColumn::make('frequency')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('start_date')->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('pause')
                    ->label('Pause')
                    ->visible(fn (Rental $record) => $record->status === 'active')
                    ->action(fn (Rental $record) => $record->update(['status' => 'paused'])),
                Tables\Actions\Action::make('resume')
                    ->label('Resume')
                    ->visible(fn (Rental $record) => $record->status === 'paused')
                    ->action(fn (Rental $record) => $record->update(['status' => 'active'])),
                Tables\Actions\Action::make('end')
                    ->label('End rental')
                    ->requiresConfirmation()
                    ->action(function (Rental $record) {
                        $record->update([
                            'status' => 'ended',
                            'end_date' => $record->end_date ?? Carbon::today(),
                        ]);
                    }),
                Tables\Actions\Action::make('addCharge')
                    ->label('Add charge')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options([
                                'fuel' => 'Fuel recharge',
                                'damage' => 'Damage',
                                'fee' => 'Admin fee',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->label('Net amount')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('vat_rate')
                            ->label('VAT rate')
                            ->numeric()
                            ->default(config('finance.default_vat_rate')),
                    ])
                    ->action(function (Rental $record, array $data) {
                        $today = Carbon::today();
                        $invoice = $record->invoices()
                            ->whereYear('issue_date', $today->year)
                            ->whereMonth('issue_date', $today->month)
                            ->latest('issue_date')
                            ->first();

                        $generator = app(InvoiceNumberGenerator::class);

                        if (! $invoice) {
                            $invoice = $record->invoices()->create([
                                'customer_id' => $record->customer_id,
                                'number' => $generator->nextNumber($today),
                                'issue_date' => $today,
                                'due_date' => $today->copy()->addDays(3),
                                'status' => 'sent',
                                'currency' => config('finance.currency', 'GBP'),
                            ]);
                        }

                        $vatCalculator = app(VatCalculator::class);
                        $net = (float) $data['amount'];
                        $tax = $vatCalculator->taxAmount($net, (int) $data['vat_rate']);

                        $invoice->items()->create([
                            'type' => $data['type'],
                            'description' => $data['description'] ?: ucfirst($data['type']) . ' charge',
                            'qty' => 1,
                            'unit_price_net' => $net,
                            'vat_rate' => (int) $data['vat_rate'],
                            'line_total_net' => $net,
                            'line_tax' => $tax,
                            'line_total_gross' => $net + $tax,
                        ]);

                        $invoice->refresh();
                    })
                    ->modalHeading('Add manual charge'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRental::route('/create'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }
}
