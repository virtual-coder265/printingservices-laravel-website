<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrintJobResource\Pages;
use App\Models\CustomerProfile;
use App\Models\PrintJob;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PrintJobResource extends Resource
{
    protected static ?string $model = PrintJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Production';

    protected static ?string $navigationLabel = 'Print Jobs';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Job Details')->schema([
                Forms\Components\TextInput::make('reference')
                    ->disabledOn('edit')
                    ->dehydrated()
                    ->maxLength(255),
                Forms\Components\Select::make('quotation_id')
                    ->label('Quotation')
                    ->relationship('quotation', 'reference')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('customer_profile_id')
                    ->label('Customer')
                    ->relationship('customerProfile', 'id')
                    ->getOptionLabelFromRecordUsing(fn (CustomerProfile $record): string => $record->user?->name ?? "Profile #{$record->id}")
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('assigned_to_user_id')
                    ->label('Assigned To')
                    ->relationship(
                        name: 'assignee',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('is_staff', true),
                    )
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('status')
                    ->options([
                        'queued' => 'Queued',
                        'prepress' => 'Prepress',
                        'printing' => 'Printing',
                        'finishing' => 'Finishing',
                        'quality_check' => 'Quality Check',
                        'ready' => 'Ready',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
            ])->columns(2),
            Forms\Components\Section::make('Delivery')->schema([
                Forms\Components\DatePicker::make('target_delivery_date'),
                Forms\Components\DatePicker::make('actual_delivery_date'),
            ])->columns(2),
            Forms\Components\Section::make('Additional')->schema([
                Forms\Components\TextInput::make('external_erp_id')
                    ->maxLength(255),
                Forms\Components\Textarea::make('internal_notes')
                    ->rows(4)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customerProfile.user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Assignee')
                    ->placeholder('Unassigned')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'queued' => 'gray',
                        'prepress' => 'info',
                        'printing' => 'warning',
                        'finishing' => 'warning',
                        'quality_check' => 'info',
                        'ready' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str($state)->replace('_', ' ')->title()),
                Tables\Columns\TextColumn::make('target_delivery_date')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'queued' => 'Queued',
                        'prepress' => 'Prepress',
                        'printing' => 'Printing',
                        'finishing' => 'Finishing',
                        'quality_check' => 'Quality Check',
                        'ready' => 'Ready',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrintJobs::route('/'),
            'create' => Pages\CreatePrintJob::route('/create'),
            'edit' => Pages\EditPrintJob::route('/{record}/edit'),
        ];
    }
}
