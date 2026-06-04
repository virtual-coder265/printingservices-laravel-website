<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'description';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('product_id')
                ->relationship('product', 'title')
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
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product')
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
