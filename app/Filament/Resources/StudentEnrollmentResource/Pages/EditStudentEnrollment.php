<?php

namespace App\Filament\Resources\StudentEnrollmentResource\Pages;

use App\Filament\Resources\StudentEnrollmentResource;
use Filament\Resources\Pages\EditRecord;

class EditStudentEnrollment extends EditRecord
{
    protected static string $resource = StudentEnrollmentResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['status']) && $data['status'] !== $this->record->status->value) {
            $data['reviewed_at'] = now();
            $data['reviewed_by'] = auth()->id();
        }

        return $data;
    }
}
