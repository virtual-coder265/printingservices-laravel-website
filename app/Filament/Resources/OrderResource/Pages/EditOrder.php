<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Services\Erp\ErpSyncService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pushToErp')
                ->label('Push to ERP')
                ->icon('heroicon-o-arrow-up-tray')
                ->action(function (): void {
                    $success = app(ErpSyncService::class)->pushOrder($this->record);

                    $notification = Notification::make()
                        ->title($success ? 'Order synced to ERP' : 'ERP sync failed');

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
