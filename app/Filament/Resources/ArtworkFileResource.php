<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtworkFileResource\Pages;
use App\Models\ArtworkFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ArtworkFileResource extends Resource
{
    protected static ?string $model = ArtworkFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Production';

    protected static ?string $navigationLabel = 'Artwork Files';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('File Details')->schema([
                Forms\Components\Select::make('print_job_id')
                    ->label('Print Job')
                    ->relationship('printJob', 'reference')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('quotation_id')
                    ->label('Quotation')
                    ->relationship('quotation', 'reference')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('uploaded_by_user_id')
                    ->label('Uploaded By')
                    ->relationship('uploadedBy', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('original_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file_path')
                    ->directory('artwork')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('file_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('file_size')
                    ->numeric()
                    ->suffix('bytes'),
            ])->columns(2),
            Forms\Components\Section::make('Review')->schema([
                Forms\Components\Toggle::make('is_approved')
                    ->default(false),
                Forms\Components\Textarea::make('review_notes')
                    ->rows(4)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('original_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('printJob.reference')
                    ->label('Print Job')
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quotation.reference')
                    ->label('Quotation')
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('uploadedBy.name')
                    ->label('Uploaded By')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('file_type')
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Approved'),
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
            'index' => Pages\ListArtworkFiles::route('/'),
            'create' => Pages\CreateArtworkFile::route('/create'),
            'edit' => Pages\EditArtworkFile::route('/{record}/edit'),
        ];
    }
}
