<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\PrintJob;
use App\Models\Quotation;
use App\Models\QuotationRequest;
use App\Enums\QuotationRequestStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Website quote requests', QuotationRequest::where('status', QuotationRequestStatus::Pending)->count())
                ->description('Public form — awaiting review')
                ->color('warning'),
            Stat::make('Portal quotations', Quotation::whereIn('status', ['pending_review', 'reviewing'])->count())
                ->description('Logged-in customer quotes')
                ->color('gray'),
            Stat::make('Active print jobs', PrintJob::whereNotIn('status', ['delivered', 'cancelled'])->count())
                ->description('In production pipeline')
                ->color('info'),
            Stat::make('Recent orders', Order::where('created_at', '>=', now()->subDays(7))->count())
                ->description('Last 7 days')
                ->color('success'),
        ];
    }
}
