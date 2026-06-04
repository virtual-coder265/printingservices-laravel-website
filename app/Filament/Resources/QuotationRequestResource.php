<?php

namespace App\Filament\Resources;

use App\Enums\QuotationRequestStatus;
use App\Filament\Resources\QuotationRequestResource\Pages;
use App\Models\QuotationRequest;
use App\Models\User;
use App\Services\QuotationRequests\QuotationRequestWorkflow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuotationRequestResource extends Resource
{
    protected static ?string $model = QuotationRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'Quote requests';

    protected static ?string $modelLabel = 'Quote request';

    protected static ?string $pluralModelLabel = 'Quote requests';

    protected static ?int $navigationSort = 0;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_quotation_request') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Admin')->schema([
                Forms\Components\Select::make('priority')
                    ->options([
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->required(),
                Forms\Components\Select::make('assigned_to')
                    ->label('Assigned to')
                    ->options(fn () => User::query()->where('is_staff', true)->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Forms\Components\Textarea::make('admin_notes')
                    ->rows(4)
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Summary')->schema([
                Infolists\Components\TextEntry::make('external_id')->label('Reference'),
                Infolists\Components\TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn (QuotationRequestStatus $state): string => $state->label())
                    ->color(fn (QuotationRequestStatus $state): string => $state->color()),
                Infolists\Components\TextEntry::make('priority')->badge(),
                Infolists\Components\TextEntry::make('created_at')->dateTime(),
                Infolists\Components\TextEntry::make('contact_name')->label('Client name')->state(fn (QuotationRequest $r) => $r->contactName()),
                Infolists\Components\TextEntry::make('contact_email')->label('Email')->state(fn (QuotationRequest $r) => $r->contactEmail()),
                Infolists\Components\TextEntry::make('contact_phone')->label('Phone')->state(fn (QuotationRequest $r) => $r->payload('contact.phone')),
                Infolists\Components\TextEntry::make('contact_company')->label('Company')->state(fn (QuotationRequest $r) => $r->contactCompany() ?: '—'),
                Infolists\Components\TextEntry::make('job_title')->label('Job title')->state(fn (QuotationRequest $r) => $r->jobTitle()),
                Infolists\Components\TextEntry::make('job_type')->label('Job type')->state(fn (QuotationRequest $r) => $r->jobType()),
                Infolists\Components\TextEntry::make('job_quantity')->label('Quantity')->state(fn (QuotationRequest $r) => $r->jobQuantity()),
                Infolists\Components\TextEntry::make('job_required_by')->label('Required by')->state(fn (QuotationRequest $r) => $r->payload('job.required_by') ?: '—'),
                Infolists\Components\TextEntry::make('job_delivery')->label('Delivery')->state(fn (QuotationRequest $r) => $r->payload('job.delivery') ?: '—'),
            ])->columns(3),
            Infolists\Components\Section::make('Specification')->schema([
                Infolists\Components\TextEntry::make('job_description')
                    ->label('Description')
                    ->state(fn (QuotationRequest $r) => $r->payload('job.description'))
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('spec_size')
                    ->label('Finished size (mm)')
                    ->state(function (QuotationRequest $record): string {
                        $w = $record->payload('spec.finished_size_mm.width');
                        $h = $record->payload('spec.finished_size_mm.height');

                        return ($w && $h) ? "{$w} × {$h}" : '—';
                    }),
                Infolists\Components\TextEntry::make('spec_pages')->label('Pages')->state(fn (QuotationRequest $r) => $r->payload('spec.pages') ?: '—'),
                Infolists\Components\TextEntry::make('spec_colours')->label('Colours')->state(fn (QuotationRequest $r) => $r->payload('spec.colours')),
                Infolists\Components\TextEntry::make('spec_artwork')->label('Artwork status')->state(fn (QuotationRequest $r) => $r->payload('spec.artwork_status')),
                Infolists\Components\TextEntry::make('spec_finishing')
                    ->label('Finishing')
                    ->state(fn (QuotationRequest $r) => implode(', ', $r->payload('spec.finishing', []) ?: []) ?: '—'),
                Infolists\Components\TextEntry::make('spec_finishing_notes')->label('Finishing notes')->state(fn (QuotationRequest $r) => $r->payload('spec.finishing_notes') ?: '—')->columnSpanFull(),
            ])->columns(3),
            Infolists\Components\Section::make('ERP')->schema([
                Infolists\Components\TextEntry::make('erp_estimation_id')->label('ERP estimation ID'),
                Infolists\Components\TextEntry::make('erp_imported_at')->dateTime(),
                Infolists\Components\TextEntry::make('erp_import_error')
                    ->label('Last import error')
                    ->columnSpanFull()
                    ->color('danger'),
            ])->columns(2),
            Infolists\Components\Section::make('Attachments')->schema([
                Infolists\Components\ViewEntry::make('attachments_list')
                    ->view('filament.quotation-requests.attachments')
                    ->columnSpanFull(),
            ]),
            Infolists\Components\Section::make('Audit')->schema([
                Infolists\Components\ViewEntry::make('events_timeline')
                    ->view('filament.quotation-requests.events')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_id')
                    ->label('Reference')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client')
                    ->getStateUsing(fn (QuotationRequest $r) => $r->contactName())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('payload_json->contact->name', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('company')
                    ->label('Company')
                    ->getStateUsing(fn (QuotationRequest $r) => $r->contactCompany())
                    ->toggleable(),
                Tables\Columns\TextColumn::make('job_title')
                    ->label('Job title')
                    ->getStateUsing(fn (QuotationRequest $r) => $r->jobTitle())
                    ->limit(30)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('payload_json->job->title', 'like', "%{$search}%");
                    }),
                Tables\Columns\TextColumn::make('job_type_col')
                    ->label('Job type')
                    ->getStateUsing(fn (QuotationRequest $r) => $r->jobType()),
                Tables\Columns\TextColumn::make('job_qty')
                    ->label('Qty')
                    ->getStateUsing(fn (QuotationRequest $r) => $r->jobQuantity()),
                Tables\Columns\TextColumn::make('required_by')
                    ->getStateUsing(fn (QuotationRequest $r) => $r->payload('job.required_by'))
                    ->label('Required by')
                    ->date()
                    ->color(fn (QuotationRequest $record): ?string => self::deadlineColor($record)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (QuotationRequestStatus $state): string => $state->label())
                    ->color(fn (QuotationRequestStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('priority')->badge(),
                Tables\Columns\TextColumn::make('erp_estimation_id')
                    ->label('ERP')
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(QuotationRequestStatus::cases())->mapWithKeys(
                        fn (QuotationRequestStatus $s) => [$s->value => $s->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'normal' => 'Normal',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]),
                Tables\Filters\SelectFilter::make('job_type')
                    ->label('Job type')
                    ->query(fn (Builder $query, array $data) => $data['value']
                        ? $query->where('payload_json->job->type', $data['value'])
                        : $query)
                    ->options(array_combine(
                        config('quotation_requests.job_types'),
                        config('quotation_requests.job_types')
                    )),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Admin notes'),
            ]);
    }

    protected static function deadlineColor(QuotationRequest $record): ?string
    {
        $date = $record->payload('job.required_by');
        if (! $date) {
            return null;
        }

        $required = \Carbon\Carbon::parse($date);

        if ($required->isPast()) {
            return 'danger';
        }

        if ($required->lte(now()->addDays(7))) {
            return 'warning';
        }

        return null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotationRequests::route('/'),
            'view' => Pages\ViewQuotationRequest::route('/{record}'),
            'edit' => Pages\EditQuotationRequest::route('/{record}/edit'),
        ];
    }
}
