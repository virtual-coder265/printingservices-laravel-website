<?php

namespace App\Filament\Resources\QuotationResource\Pages;

use App\Filament\Resources\QuotationResource;
use App\Models\PrintJob;
use App\Models\Quotation;
use App\Services\Erp\ErpSyncService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Quotation $record): bool => in_array($record->status, ['pending_review', 'reviewing'], true))
                ->action(function (Quotation $record): void {
                    $record->update(['status' => 'approved']);

                    Notification::make()
                        ->success()
                        ->title('Quotation approved')
                        ->send();
                }),
            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn (Quotation $record): bool => in_array($record->status, ['pending_review', 'reviewing', 'approved'], true))
                ->action(function (Quotation $record, array $data): void {
                    $record->update([
                        'status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Quotation rejected')
                        ->send();
                }),
            Actions\Action::make('convertToJob')
                ->label('Convert to Job')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(fn (Quotation $record): bool => $record->status === 'approved' && ! $record->printJob()->exists())
                ->action(function (Quotation $record): void {
                    PrintJob::create([
                        'reference' => PrintJob::generateReference(),
                        'quotation_id' => $record->id,
                        'customer_profile_id' => $record->customer_profile_id,
                        'status' => 'queued',
                    ]);

                    $record->update(['status' => 'converted_to_job']);

                    Notification::make()
                        ->success()
                        ->title('Print job created')
                        ->send();
                }),
            Actions\Action::make('pushToErp')
                ->label('Push to ERP')
                ->icon('heroicon-o-arrow-up-tray')
                ->action(function (Quotation $record): void {
                    $success = app(ErpSyncService::class)->pushQuotation($record);

                    $notification = Notification::make()
                        ->title($success ? 'Quotation synced to ERP' : 'ERP sync failed');

                    if ($success) {
                        $notification->success()->send();
                    } else {
                        $notification->danger()->send();
                    }
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
