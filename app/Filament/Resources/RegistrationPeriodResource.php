<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationPeriodResource\Pages;
use App\Models\RegistrationPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrationPeriodResource extends Resource
{
    protected static ?string $model = RegistrationPeriod::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Training School';

    protected static ?string $navigationLabel = 'Registration calendar';

    protected static ?string $modelLabel = 'Registration period';

    protected static ?string $pluralModelLabel = 'Registration periods';

    protected static ?int $navigationSort = 0;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_registration_period') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Period details')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. 2026 Intake'),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('opens_at')
                    ->required()
                    ->native(false),
                Forms\Components\DateTimePicker::make('closes_at')
                    ->required()
                    ->native(false)
                    ->after('opens_at'),
                Forms\Components\TextInput::make('max_placements')
                    ->numeric()
                    ->minValue(1)
                    ->nullable()
                    ->helperText('Leave empty for unlimited placements.'),
                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->helperText('Only published periods appear on the public site.')
                    ->default(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('opens_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('closes_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn (RegistrationPeriod $record): string => $record->statusLabel())
                    ->color(fn (RegistrationPeriod $record): string => match ($record->statusLabel()) {
                        'Open' => 'success',
                        'Upcoming' => 'info',
                        'Full' => 'warning',
                        'Closed' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Applications')
                    ->counts('enrollments')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_placements')
                    ->label('Capacity')
                    ->placeholder('Unlimited'),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
            ])
            ->defaultSort('opens_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrationPeriods::route('/'),
            'create' => Pages\CreateRegistrationPeriod::route('/create'),
            'edit' => Pages\EditRegistrationPeriod::route('/{record}/edit'),
        ];
    }
}
