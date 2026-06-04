<?php

namespace App\Filament\Resources\QuotationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuotationItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'description';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('service_id')
                ->relationship('service', 'title')
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('description')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->default(1)
                ->required()
                ->minValue(1),
            Forms\Components\TextInput::make('unit_price')
                ->numeric()
                ->required()
                ->prefix('MWK'),
            Forms\Components\TextInput::make('vat_rate')
                ->numeric()
                ->default(17.50)
                ->required()
                ->suffix('%'),
            Forms\Components\TextInput::make('vat_amount')
                ->numeric()
                ->default(0)
                ->prefix('MWK'),
            Forms\Components\TextInput::make('line_total')
                ->numeric()
                ->default(0)
                ->prefix('MWK'),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('service.title')
                    ->label('Service')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->money('MWK'),
                Tables\Columns\TextColumn::make('vat_rate')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('vat_amount')
                    ->money('MWK'),
                Tables\Columns\TextColumn::make('line_total')
                    ->money('MWK'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(fn () => $this->getOwnerRecord()->fresh()->recalculateTotals()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(fn () => $this->getOwnerRecord()->fresh()->recalculateTotals()),
                Tables\Actions\DeleteAction::make()
                    ->after(fn () => $this->getOwnerRecord()->fresh()->recalculateTotals()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(fn () => $this->getOwnerRecord()->fresh()->recalculateTotals()),
                ]),
            ]);
    }
}
