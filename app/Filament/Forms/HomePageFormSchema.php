<?php

namespace App\Filament\Forms;

use App\Filament\Concerns\ProvidesMediaUploadFields;
use Filament\Forms;

class HomePageFormSchema
{
    use ProvidesMediaUploadFields;

    public static function schema(): array
    {
        return [
            Forms\Components\Tabs::make('Home Page Sections')
                ->tabs([
                    self::heroTab(),
                    self::highlightsTab(),
                    self::overviewTab(),
                    self::valuesTab(),
                    self::catalogueTab(),
                    self::featuredImagesTab(),
                    self::trustMetricsTab(),
                    self::schoolTab(),
                    self::contactTab(),
                    self::footerTab(),
                ])
                ->columnSpanFull()
                ->persistTabInQueryString(),
        ];
    }

    protected static function heroTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Hero')
            ->icon('heroicon-o-photo')
            ->schema([
                Forms\Components\Repeater::make('hero_slides')
                    ->label('Hero slides')
                    ->schema([
                        Forms\Components\TextInput::make('eyebrow')->maxLength(255),
                        Forms\Components\TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),
                        Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                        self::homepageImageUpload('image', 'Slide image', 180),
                        Forms\Components\TextInput::make('primary_label')->maxLength(255),
                        Forms\Components\TextInput::make('primary_href')->maxLength(255),
                        Forms\Components\TextInput::make('secondary_label')->maxLength(255),
                        Forms\Components\TextInput::make('secondary_href')->maxLength(255),
                        Forms\Components\Repeater::make('points')
                            ->label('Bullet points')
                            ->schema([
                                Forms\Components\TextInput::make('text')->required()->maxLength(255),
                            ])
                            ->defaultItems(0)
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New slide')
                    ->columnSpanFull(),
            ]);
    }

    protected static function highlightsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Highlights')
            ->icon('heroicon-o-sparkles')
            ->schema([
                Forms\Components\Repeater::make('highlights')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Lucide icon name')
                            ->helperText('e.g. award, clock, users')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('title')->required()->maxLength(255),
                        Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]);
    }

    protected static function overviewTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Overview')
            ->icon('heroicon-o-building-library')
            ->schema([
                Forms\Components\Section::make('Overview content')->schema([
                    Forms\Components\TextInput::make('overview.badge')->label('Badge')->maxLength(255),
                    Forms\Components\TextInput::make('overview.eyebrow')->label('Eyebrow')->maxLength(255),
                    Forms\Components\TextInput::make('overview.title')->label('Title')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\Textarea::make('overview.lead')->label('Lead paragraph')->rows(3)->columnSpanFull(),
                    Forms\Components\Repeater::make('overview.body')
                        ->label('Body paragraphs')
                        ->schema([
                            Forms\Components\Textarea::make('text')->required()->rows(2),
                        ])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => str($state['text'] ?? '')->limit(60))
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('overview.mission')->label('Mission statement')->rows(3)->columnSpanFull(),
                    Forms\Components\Repeater::make('overview.points')
                        ->label('Key points')
                        ->schema([
                            Forms\Components\TextInput::make('text')->required()->maxLength(255),
                        ])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('overview.cta_label')->label('CTA label')->maxLength(255),
                    Forms\Components\TextInput::make('overview.cta_href')->label('CTA link')->maxLength(255),
                ])->columns(2),
                Forms\Components\Section::make('Overview images')->schema([
                    self::homepageImageUpload('overview.image_primary', 'Primary image', 160),
                    self::homepageImageUpload('overview.image_secondary', 'Secondary image', 160),
                ])->columns(2),
                Forms\Components\Section::make('Overview cards')->schema([
                    Forms\Components\Repeater::make('overview.cards')
                        ->schema([
                            Forms\Components\TextInput::make('title')->required()->maxLength(255),
                            Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->collapsible()
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                        ->columnSpanFull(),
                ]),
                Forms\Components\Section::make('Heritage stat badge')->schema([
                    Forms\Components\Repeater::make('stats')
                        ->label('Stats (first item shown on overview image)')
                        ->schema([
                            Forms\Components\TextInput::make('value')->required()->maxLength(50),
                            Forms\Components\TextInput::make('label')->required()->maxLength(255),
                        ])
                        ->columns(2)
                        ->maxItems(4)
                        ->collapsible()
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string => ($state['value'] ?? '').' — '.($state['label'] ?? ''))
                        ->columnSpanFull(),
                ]),
            ]);
    }

    protected static function valuesTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Values')
            ->icon('heroicon-o-heart')
            ->schema([
                Forms\Components\Repeater::make('values')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Lucide icon name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('title')->required()->maxLength(255),
                        Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]);
    }

    protected static function catalogueTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Catalogue')
            ->icon('heroicon-o-squares-2x2')
            ->schema([
                Forms\Components\Section::make('Catalogue section text')->schema([
                    Forms\Components\TextInput::make('catalogue.eyebrow')->label('Eyebrow')->maxLength(255),
                    Forms\Components\TextInput::make('catalogue.title')->label('Title')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\Textarea::make('catalogue.lead')->label('Lead paragraph')->rows(3)->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Catalogue images')->schema([
                    self::homepageImageUpload('catalogue.image_primary', 'Primary image', 160),
                    self::homepageImageUpload('catalogue.image_secondary', 'Secondary image', 160),
                ])->columns(2),
            ]);
    }

    protected static function featuredImagesTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Featured Gallery')
            ->icon('heroicon-o-rectangle-group')
            ->schema([
                Forms\Components\Repeater::make('featured_images')
                    ->label('Featured carousel images')
                    ->schema([
                        self::homepageImageUpload('image', 'Image', 140),
                        Forms\Components\TextInput::make('alt')->label('Alt text')->required()->maxLength(255),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => $state['alt'] ?? 'Image')
                    ->columnSpanFull(),
            ]);
    }

    protected static function trustMetricsTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Trust Metrics')
            ->icon('heroicon-o-shield-check')
            ->schema([
                Forms\Components\Repeater::make('trust_metrics')
                    ->label('Service charter metrics')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->label('Lucide icon name')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('title')->required()->maxLength(255),
                        Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->columnSpanFull(),
            ]);
    }

    protected static function schoolTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Training School')
            ->icon('heroicon-o-academic-cap')
            ->schema([
                Forms\Components\Section::make('School content')->schema([
                    Forms\Components\TextInput::make('school.eyebrow')->label('Eyebrow')->maxLength(255),
                    Forms\Components\TextInput::make('school.title')->label('Title')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\Textarea::make('school.lead')->label('Lead paragraph')->rows(3)->columnSpanFull(),
                    Forms\Components\Repeater::make('school.body')
                        ->label('Body paragraphs')
                        ->schema([
                            Forms\Components\Textarea::make('text')->required()->rows(2),
                        ])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => str($state['text'] ?? '')->limit(60))
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('school.focus_areas')
                        ->label('Focus areas')
                        ->schema([
                            Forms\Components\TextInput::make('text')->required()->maxLength(255),
                        ])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('school.cta_label')->label('CTA label')->maxLength(255),
                    Forms\Components\TextInput::make('school.cta_href')->label('CTA link')->maxLength(255),
                ])->columns(2),
                Forms\Components\Section::make('School image')->schema([
                    self::homepageImageUpload('school.image', 'Featured image', 200),
                ]),
            ]);
    }

    protected static function contactTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Contact')
            ->icon('heroicon-o-phone')
            ->schema([
                Forms\Components\Section::make('Contact section')->schema([
                    Forms\Components\TextInput::make('contact.eyebrow')->label('Eyebrow')->maxLength(255),
                    Forms\Components\TextInput::make('contact.title')->label('Title')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\Textarea::make('contact.lead')->label('Lead paragraph')->rows(3)->columnSpanFull(),
                    Forms\Components\TextInput::make('contact.phone')->label('Phone')->maxLength(255),
                    Forms\Components\TextInput::make('contact.phone_href')->label('Phone link')->maxLength(255),
                    Forms\Components\TextInput::make('contact.email')->label('Email')->email()->maxLength(255),
                    Forms\Components\TextInput::make('contact.email_href')->label('Email link')->maxLength(255),
                    Forms\Components\TextInput::make('contact.hours')->label('Office hours')->maxLength(255),
                    Forms\Components\Repeater::make('contact.locations')
                        ->label('Office locations')
                        ->schema([
                            Forms\Components\TextInput::make('text')->required()->maxLength(255),
                        ])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('contact.quote_checklist')
                        ->label('Quotation checklist')
                        ->schema([
                            Forms\Components\TextInput::make('text')->required()->maxLength(255),
                        ])
                        ->defaultItems(0)
                        ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    protected static function footerTab(): Forms\Components\Tabs\Tab
    {
        return Forms\Components\Tabs\Tab::make('Footer')
            ->icon('heroicon-o-bars-3-bottom-left')
            ->schema([
                Forms\Components\Textarea::make('footer.summary')
                    ->label('Footer summary')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('footer.links')
                    ->label('Footer links')
                    ->schema([
                        Forms\Components\TextInput::make('label')->required()->maxLength(255),
                        Forms\Components\TextInput::make('href')->required()->maxLength(255),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                    ->columnSpanFull(),
            ]);
    }
}
