<?php

namespace App\Filament\Resources\QuotationRequestResource\Pages;

use App\Filament\Resources\QuotationRequestResource;
use App\Models\QuotationRequest;
use App\Services\QuotationRequests\QuotationRequestWorkflow;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewQuotationRequest extends ViewRecord
{
    protected static string $resource = QuotationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit admin fields'),
            Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (QuotationRequest $record): bool => auth()->user()?->can('approve', $record) ?? false)
                ->action(function (QuotationRequest $record): void {
                    app(QuotationRequestWorkflow::class)->approve($record, auth()->user());

                    Notification::make()->success()->title('Request approved')->send();

                    $this->refreshFormData(['status', 'reviewed_at', 'reviewed_by']);
                }),
            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection reason (sent to client when email is enabled)')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn (QuotationRequest $record): bool => auth()->user()?->can('reject', $record) ?? false)
                ->action(function (QuotationRequest $record, array $data): void {
                    app(QuotationRequestWorkflow::class)->reject($record, auth()->user(), $data['rejection_reason']);

                    Notification::make()->success()->title('Request rejected')->send();
                }),
            Actions\Action::make('sendToErp')
                ->label('Send to ERP')
                ->icon('heroicon-o-arrow-up-tray')
                ->requiresConfirmation()
                ->modalDescription('Imports this request into Press ERP as a draft estimation when the ERP API is configured.')
                ->visible(fn (QuotationRequest $record): bool => auth()->user()?->can('sendToErp', $record) ?? false)
                ->action(function (QuotationRequest $record): void {
                    $success = app(QuotationRequestWorkflow::class)->sendToErp($record, auth()->user());

                    $notification = Notification::make()
                        ->title($success ? 'Sent to ERP' : 'ERP import failed');

                    $success ? $notification->success() : $notification->danger()->send();

                    $this->refreshFormData(['status', 'erp_estimation_id', 'erp_imported_at', 'erp_import_error']);
                }),
            Actions\Action::make('markSpam')
                ->label('Mark spam')
                ->icon('heroicon-o-no-symbol')
                ->color('gray')
                ->requiresConfirmation()
                ->visible(fn (QuotationRequest $record): bool => auth()->user()?->can('markSpam', $record) ?? false)
                ->action(function (QuotationRequest $record): void {
                    app(QuotationRequestWorkflow::class)->markSpam($record, auth()->user());

                    Notification::make()->success()->title('Marked as spam')->send();
                }),
        ];
    }
}
