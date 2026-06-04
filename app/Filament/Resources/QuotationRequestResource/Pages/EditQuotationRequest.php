<?php

namespace App\Filament\Resources\QuotationRequestResource\Pages;

use App\Filament\Resources\QuotationRequestResource;
use App\Services\QuotationRequests\QuotationRequestEventLogger;
use Filament\Resources\Pages\EditRecord;

class EditQuotationRequest extends EditRecord
{
    protected static string $resource = QuotationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ViewAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        app(QuotationRequestEventLogger::class)->log(
            $this->record->fresh(),
            'note_updated',
            auth()->user(),
            ['priority' => $this->record->priority]
        );
    }
}
