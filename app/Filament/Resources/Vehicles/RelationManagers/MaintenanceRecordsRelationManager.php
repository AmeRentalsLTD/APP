<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use App\Filament\Resources\MaintenanceRecords\Schemas\MaintenanceRecordForm;
use App\Models\MaintenanceRecord;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MaintenanceRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenanceRecords';

    protected static ?string $title = 'Maintenance history';

    public function form(Form $form): Form
    {
        return $form->schema(
            MaintenanceRecordForm::getComponents(includeVehicleField: false)
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('scheduled_at')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'scheduled',
                        'info' => 'in_progress',
                        'success' => 'completed',
                        'gray' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
                TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->date()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('cost')
                    ->money('GBP')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::options(MaintenanceRecord::STATUSES))
                    ->native(false),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline($value)])
            ->all();
    }
}
