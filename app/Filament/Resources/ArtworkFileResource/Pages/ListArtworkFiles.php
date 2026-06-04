<?php

namespace App\Filament\Resources\ArtworkFileResource\Pages;

use App\Filament\Resources\ArtworkFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtworkFiles extends ListRecords
{
    protected static string $resource = ArtworkFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
