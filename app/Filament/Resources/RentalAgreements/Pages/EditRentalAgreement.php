<?php

namespace App\Filament\Resources\RentalAgreements\Pages;

use App\Filament\Resources\RentalAgreements\RentalAgreementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRentalAgreement extends EditRecord
{
    protected static string $resource = RentalAgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
