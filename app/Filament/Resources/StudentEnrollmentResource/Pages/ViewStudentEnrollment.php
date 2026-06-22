<?php

namespace App\Filament\Resources\StudentEnrollmentResource\Pages;

use App\Enums\StudentEnrollmentStatus;
use App\Filament\Resources\StudentEnrollmentResource;
use App\Models\StudentEnrollment;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentEnrollment extends ViewRecord
{
    protected static string $resource = StudentEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Update status'),
            Actions\Action::make('accept')
                ->label('Accept')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (StudentEnrollment $record): bool => auth()->user()?->can('approve_student_enrollment') && ! $record->status->isTerminal())
                ->action(function (StudentEnrollment $record): void {
                    $record->update([
                        'status' => StudentEnrollmentStatus::Accepted,
                        'reviewed_at' => now(),
                        'reviewed_by' => auth()->id(),
                    ]);

                    Notification::make()->success()->title('Application accepted')->send();
                }),
            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection reason')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn (StudentEnrollment $record): bool => auth()->user()?->can('reject_student_enrollment') && ! $record->status->isTerminal())
                ->action(function (StudentEnrollment $record, array $data): void {
                    $record->update([
                        'status' => StudentEnrollmentStatus::Rejected,
                        'rejection_reason' => $data['rejection_reason'],
                        'reviewed_at' => now(),
                        'reviewed_by' => auth()->id(),
                    ]);

                    Notification::make()->success()->title('Application rejected')->send();
                }),
            Actions\Action::make('waitlist')
                ->label('Waitlist')
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->requiresConfirmation()
                ->visible(fn (StudentEnrollment $record): bool => auth()->user()?->can('update_student_enrollment') && ! $record->status->isTerminal())
                ->action(function (StudentEnrollment $record): void {
                    $record->update([
                        'status' => StudentEnrollmentStatus::Waitlisted,
                        'reviewed_at' => now(),
                        'reviewed_by' => auth()->id(),
                    ]);

                    Notification::make()->success()->title('Added to waitlist')->send();
                }),
        ];
    }
}
