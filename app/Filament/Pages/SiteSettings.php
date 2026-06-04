<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $utility = SiteSetting::get('utility', 'contact', config('homepage.utility', []));
        $brand = SiteSetting::get('brand', 'info', config('homepage.brand', []));
        $meta = SiteSetting::get('meta', 'info', config('homepage.meta', []));

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
            'logo' => config('homepage.brand.logo'),
        ]);

        SiteSetting::set('meta', 'info', [
            'title' => $data['meta_title'],
            'description' => $data['meta_description'],
        ]);

        Notification::make()->title('Site settings saved')->success()->send();
    }
}
