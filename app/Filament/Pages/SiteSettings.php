<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\ProvidesMediaUploadFields;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;
    use ProvidesMediaUploadFields;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('page_site_settings') ?? false;
    }

    public function mount(): void
    {
        $utility = SiteSetting::get('utility', 'contact', config('homepage.utility', []));
        $brand = SiteSetting::get('brand', 'info', config('homepage.brand', []));
        $meta = SiteSetting::get('meta', 'info', config('homepage.meta', []));

        $logo = $brand['logo'] ?? config('homepage.brand.logo');
        $favicon = $brand['favicon'] ?? $logo;

        $this->form->fill([
            'phone' => $utility['phone'] ?? '',
            'phone_href' => $utility['phone_href'] ?? '',
            'email' => $utility['email'] ?? '',
            'email_href' => $utility['email_href'] ?? '',
            'hours' => $utility['hours'] ?? '',
            'whatsapp' => $utility['whatsapp'] ?? '',
            'notice' => $utility['notice'] ?? '',
            'brand_name' => $brand['name'] ?? '',
            'brand_short_name' => $brand['short_name'] ?? '',
            'brand_tagline' => $brand['tagline'] ?? '',
            'logo' => $this->storageUploadPath($logo),
            'logo_existing' => $logo,
            'favicon' => $this->storageUploadPath($favicon),
            'favicon_existing' => $favicon,
            'meta_title' => $meta['title'] ?? '',
            'meta_description' => $meta['description'] ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contact & Utility')->schema([
                    Forms\Components\TextInput::make('phone'),
                    Forms\Components\TextInput::make('phone_href'),
                    Forms\Components\TextInput::make('email')->email(),
                    Forms\Components\TextInput::make('email_href'),
                    Forms\Components\TextInput::make('hours'),
                    Forms\Components\TextInput::make('whatsapp'),
                    Forms\Components\Textarea::make('notice')->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('Brand')->schema([
                    Forms\Components\TextInput::make('brand_name'),
                    Forms\Components\TextInput::make('brand_short_name'),
                    Forms\Components\TextInput::make('brand_tagline'),
                ])->columns(2),
                Forms\Components\Section::make('Brand Identity')
                    ->description('Upload your site logo and favicon. Drag and drop files or click to browse.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Group::make([
                                Forms\Components\Placeholder::make('logo_preview')
                                    ->label('Current logo preview')
                                    ->content(function (Get $get): HtmlString|string {
                                        $path = $this->resolveUploadedPath($get('logo'), $get('logo_existing'));

                                        if (! media_exists($path)) {
                                            return 'No logo uploaded yet.';
                                        }

                                        return new HtmlString(
                                            '<div class="rounded-lg border border-gray-200 bg-gray-50 p-4">'
                                            .'<img src="'.e(media_url($path)).'" alt="Current logo" class="max-h-24 w-auto object-contain" />'
                                            .'</div>'
                                        );
                                    }),
                                Forms\Components\Hidden::make('logo_existing'),
                                self::brandImageUpload('logo', 'Upload logo', 120),
                            ]),
                            Forms\Components\Group::make([
                                Forms\Components\Placeholder::make('favicon_preview')
                                    ->label('Current favicon preview')
                                    ->content(function (Get $get): HtmlString|string {
                                        $path = $this->resolveUploadedPath(
                                            $get('favicon'),
                                            $get('favicon_existing') ?? $get('logo_existing')
                                        );

                                        if (! media_exists($path)) {
                                            return 'No favicon uploaded yet.';
                                        }

                                        return new HtmlString(
                                            '<div class="rounded-lg border border-gray-200 bg-gray-50 p-4 flex items-center gap-4">'
                                            .'<img src="'.e(media_url($path)).'" alt="Current favicon" class="h-8 w-8 object-contain" />'
                                            .'<span class="text-sm text-gray-500">Shown in browser tabs</span>'
                                            .'</div>'
                                        );
                                    }),
                                Forms\Components\Hidden::make('favicon_existing'),
                                self::brandImageUpload('favicon', 'Upload favicon', 48, 512),
                            ]),
                        ]),
                    ]),
                Forms\Components\Section::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title'),
                    Forms\Components\Textarea::make('meta_description')->rows(3),
                ])->columns(1),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::set('utility', 'contact', [
            'phone' => $data['phone'],
            'phone_href' => $data['phone_href'],
            'email' => $data['email'],
            'email_href' => $data['email_href'],
            'hours' => $data['hours'],
            'whatsapp' => $data['whatsapp'],
            'notice' => $data['notice'],
        ]);

        SiteSetting::set('brand', 'info', [
            'name' => $data['brand_name'],
            'short_name' => $data['brand_short_name'],
            'tagline' => $data['brand_tagline'],
            'logo' => $this->resolveUploadedPath($data['logo'] ?? null, $data['logo_existing'] ?? config('homepage.brand.logo')),
            'favicon' => $this->resolveUploadedPath(
                $data['favicon'] ?? null,
                $data['favicon_existing'] ?? $data['logo_existing'] ?? config('homepage.brand.logo')
            ),
        ]);

        SiteSetting::set('meta', 'info', [
            'title' => $data['meta_title'],
            'description' => $data['meta_description'],
        ]);

        Notification::make()->title('Site settings saved')->success()->send();
    }

    protected function storageUploadPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'brand/') || str_starts_with($path, 'homepage/')) {
            return $path;
        }

        return null;
    }

    protected function resolveUploadedPath(mixed $uploaded, ?string $existing): ?string
    {
        if (filled($uploaded)) {
            if (is_array($uploaded)) {
                $path = collect($uploaded)->first(fn ($value) => filled($value));

                return filled($path) ? (string) $path : $existing;
            }

            return (string) $uploaded;
        }

        return $existing;
    }
}
