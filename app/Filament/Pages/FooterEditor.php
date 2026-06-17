<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class FooterEditor extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3-bottom-left';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Footer';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.footer-editor';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('page_footer_editor') ?? false;
    }

    public function mount(): void
    {
        $defaults = config('homepage', []);

        $footer = SiteSetting::get('homepage', 'footer') ?? $defaults['footer'] ?? [];
        $footerAddresses = SiteSetting::get('homepage', 'footer_addresses') ?? $defaults['footer_addresses'] ?? [];
        $contact = SiteSetting::get('homepage', 'contact') ?? $defaults['contact'] ?? [];
        $socialLinks = SiteSetting::get('utility', 'social_links') ?? $defaults['utility']['social_links'] ?? [];
        $teams = SiteSetting::get('homepage', 'teams') ?? $defaults['teams'] ?? [];

        $this->form->fill([
            'footer' => [
                'summary' => $footer['summary'] ?? '',
                'links' => $this->normalizeFooterLinks($footer['links'] ?? []),
            ],
            'footer_addresses' => $footerAddresses,
            'po_boxes' => $this->stringsToRepeater($contact['po_boxes'] ?? []),
            'social_links' => $socialLinks,
            'teams' => $teams,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Footer content')->schema([
                    Forms\Components\Textarea::make('footer.summary')
                        ->label('Footer summary')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('footer.links')
                        ->label('Quick links')
                        ->schema([
                            Forms\Components\TextInput::make('label')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Select::make('route')
                                ->label('Page')
                                ->options($this->publicRouteOptions())
                                ->searchable()
                                ->nullable(),
                            Forms\Components\TextInput::make('href')
                                ->label('Custom URL')
                                ->helperText('Use for external links or anchors. Overrides the page selection when filled.')
                                ->maxLength(255),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->cloneable()
                        ->addActionLabel('Add new')
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                        ->columnSpanFull(),
                ]),
                Forms\Components\Section::make('Physical addresses')
                    ->description('Office locations shown in the footer, each with an optional Google Maps link.')
                    ->schema([
                        Forms\Components\Repeater::make('footer_addresses')
                            ->label('Offices')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Office name')
                                    ->placeholder('e.g. Lilongwe - Production Office')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('address')
                                    ->label('Physical address')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('map_url')
                                    ->label('Google Maps link')
                                    ->url()
                                    ->maxLength(2048)
                                    ->helperText('Paste a Google Maps share link. Leave empty to show the address without a link.')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->cloneable()
                            ->addActionLabel('Add new office')
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Post office boxes')
                    ->description('Postal addresses shown in the footer contact column.')
                    ->schema([
                        Forms\Components\Repeater::make('po_boxes')
                            ->label('P.O. Boxes')
                            ->schema([
                                Forms\Components\TextInput::make('text')
                                    ->label('P.O. Box')
                                    ->placeholder('e.g. P.O. Box 216, Lilongwe')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->defaultItems(0)
                            ->collapsible()
                            ->cloneable()
                            ->addActionLabel('Add new P.O. box')
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Social links')->schema([
                    Forms\Components\Repeater::make('social_links')
                        ->label('Social media links')
                        ->schema([
                            Forms\Components\TextInput::make('label')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('href')
                                ->label('URL')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Select::make('icon')
                                ->options($this->socialIconOptions())
                                ->required(),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->cloneable()
                        ->addActionLabel('Add new')
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                        ->columnSpanFull(),
                ]),
                Forms\Components\Section::make('Teams page intro')->schema([
                    Forms\Components\TextInput::make('teams.eyebrow')->maxLength(255),
                    Forms\Components\TextInput::make('teams.title')->maxLength(255)->columnSpanFull(),
                    Forms\Components\Textarea::make('teams.lead')->rows(3)->columnSpanFull(),
                ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::set('homepage', 'footer', [
            'summary' => $data['footer']['summary'] ?? '',
            'links' => $this->denormalizeFooterLinks($data['footer']['links'] ?? []),
        ]);
        SiteSetting::set('homepage', 'footer_addresses', $data['footer_addresses'] ?? []);

        $existingContact = SiteSetting::get('homepage', 'contact') ?? config('homepage.contact', []);
        $existingContact['po_boxes'] = $this->repeaterToStrings($data['po_boxes'] ?? []);
        SiteSetting::set('homepage', 'contact', $existingContact);

        SiteSetting::set('utility', 'social_links', $data['social_links'] ?? []);
        SiteSetting::set('homepage', 'teams', $data['teams'] ?? []);

        Notification::make()->title('Footer content saved')->success()->send();
    }

    protected function publicRouteOptions(): array
    {
        return collect(config('homepage.public_menu', []))
            ->pluck('label', 'route')
            ->all();
    }

    protected function socialIconOptions(): array
    {
        return [
            'facebook' => 'Facebook',
            'linkedin' => 'LinkedIn',
            'x-social' => 'X (Twitter)',
            'instagram' => 'Instagram',
        ];
    }

    protected function normalizeFooterLinks(array $links): array
    {
        return array_map(function (array $link): array {
            return [
                'label' => $link['label'] ?? '',
                'route' => $link['route'] ?? null,
                'href' => $link['href'] ?? null,
            ];
        }, $links);
    }

    protected function denormalizeFooterLinks(array $links): array
    {
        return array_values(array_filter(array_map(function (array $link): ?array {
            if (blank($link['label'] ?? null)) {
                return null;
            }

            if (filled($link['href'] ?? null)) {
                return [
                    'label' => $link['label'],
                    'href' => $link['href'],
                ];
            }

            if (filled($link['route'] ?? null)) {
                return [
                    'label' => $link['label'],
                    'route' => $link['route'],
                ];
            }

            return null;
        }, $links)));
    }

    protected function stringsToRepeater(array $items): array
    {
        return array_map(function ($item): array {
            if (is_array($item)) {
                return $item;
            }

            return ['text' => (string) $item];
        }, $items);
    }

    protected function repeaterToStrings(array $items): array
    {
        return array_values(array_filter(array_map(function ($item): ?string {
            if (is_string($item)) {
                return $item;
            }

            return filled($item['text'] ?? null) ? (string) $item['text'] : null;
        }, $items)));
    }
}
