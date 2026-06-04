<?php

namespace App\Filament\Resources\PrintJobResource\Pages;

use App\Filament\Resources\PrintJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrintJobs extends ListRecords
{
    protected static string $resource = PrintJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
