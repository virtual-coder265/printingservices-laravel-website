<?php

namespace App\Filament\Resources\RegistrationPeriodResource\Pages;

use App\Filament\Resources\RegistrationPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrationPeriod extends EditRecord
{
    protected static string $resource = RegistrationPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
