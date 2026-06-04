<?php

namespace App\Filament\Pages;

use App\Models\ErpSyncLog;
use App\Services\Erp\ErpSyncService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class ErpDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'ERP Integration';

    protected static ?string $navigationLabel = 'ERP Dashboard';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.erp-dashboard';

    public function syncProducts(): void
    {
        $count = app(ErpSyncService::class)->syncProductsFromErp();
        Notification::make()->title("Synced {$count} products")->success()->send();
    }

    public function syncServices(): void
    {
        $count = app(ErpSyncService::class)->syncServicesFromErp();
        Notification::make()->title("Synced {$count} services")->success()->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ErpSyncLog::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('direction')->badge(),
                Tables\Columns\TextColumn::make('entity_type'),
                Tables\Columns\TextColumn::make('entity_id'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state) => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('error_message')->limit(50),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
