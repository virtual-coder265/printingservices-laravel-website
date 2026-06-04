<?php

namespace App\Filament\Resources\ArtworkFileResource\Pages;

use App\Filament\Resources\ArtworkFileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArtworkFile extends EditRecord
{
    protected static string $resource = ArtworkFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
