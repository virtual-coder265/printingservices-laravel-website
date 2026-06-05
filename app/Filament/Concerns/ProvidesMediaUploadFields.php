<?php

namespace App\Filament\Concerns;

use Filament\Forms;

trait ProvidesMediaUploadFields
{
    protected static function homepageImageUpload(string $name, string $label, int $previewHeight = 150): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory('homepage')
            ->visibility('public')
            ->imagePreviewHeight((string) $previewHeight)
            ->maxSize(5120)
            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
            ->downloadable()
            ->openable()
            ->columnSpanFull();
    }

    protected static function brandImageUpload(string $name, string $label, int $previewHeight = 120, int $maxSize = 2048): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory('brand')
            ->visibility('public')
            ->imagePreviewHeight((string) $previewHeight)
            ->maxSize($maxSize)
            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'])
            ->downloadable()
            ->openable()
            ->columnSpanFull();
    }
}
