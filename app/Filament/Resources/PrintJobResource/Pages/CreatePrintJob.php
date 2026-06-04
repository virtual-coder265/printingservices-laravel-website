<?php

namespace App\Filament\Resources\PrintJobResource\Pages;

use App\Filament\Resources\PrintJobResource;
use App\Models\PrintJob;
use Filament\Resources\Pages\CreateRecord;

class CreatePrintJob extends CreateRecord
{
    protected static string $resource = PrintJobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['reference'] = PrintJob::generateReference();

        return $data;
    }
}
