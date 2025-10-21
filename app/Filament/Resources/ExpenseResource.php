<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static UnitEnum|string|null $navigationGroup = 'Finance';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-receipt-refund';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'registration')
                    ->searchable()
                    ->label('Vehicle'),
                Forms\Components\Select::make('category')
                    ->options([
                        'fuel' => 'Fuel',
                        'insurance' => 'Insurance',
                        'service' => 'Service',
                        'road_tax' => 'Road tax',
                        'mot' => 'MOT',
                        'repairs' => 'Repairs',
                        'other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('vendor')->maxLength(255),
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\TextInput::make('net')->numeric()->required(),
                Forms\Components\TextInput::make('vat_rate')->numeric()->default(config('finance.default_vat_rate')),
                Forms\Components\TextInput::make('tax')->numeric()->required(),
                Forms\Components\TextInput::make('gross')->numeric()->required(),
                Forms\Components\TextInput::make('reference')->maxLength(255),
                Forms\Components\FileUpload::make('attachment_path')->directory('expenses')->label('Attachment'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('vehicle.registration')->label('Vehicle'),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('vendor'),
                Tables\Columns\TextColumn::make('gross')->label('Gross')->formatStateUsing(fn ($state) => '£' . number_format((float) $state, 2)),
                Tables\Columns\TextColumn::make('reference'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship('vehicle', 'registration'),
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'fuel' => 'Fuel',
                        'insurance' => 'Insurance',
                        'service' => 'Service',
                        'road_tax' => 'Road tax',
                        'mot' => 'MOT',
                        'repairs' => 'Repairs',
                        'other' => 'Other',
                    ]),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('date', '>=', Carbon::parse($date)))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('date', '<=', Carbon::parse($date)));
                    }),
            ])
            ->actions([
              EditAction::make(),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
