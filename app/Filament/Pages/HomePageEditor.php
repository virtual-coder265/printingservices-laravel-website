<?php

namespace App\Filament\Pages;

use App\Filament\Forms\HomePageFormSchema;
use App\Models\SiteSetting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class HomePageEditor extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationLabel = 'Home Page';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.home-page-editor';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('page_homepage_editor') ?? false;
    }

    public function mount(): void
    {
        $defaults = config('homepage', []);

        $this->form->fill([
            'hero_slides' => $this->normalizeHeroSlides(
                SiteSetting::get('homepage', 'hero_slides') ?? $defaults['hero_slides'] ?? []
            ),
            'highlights' => SiteSetting::get('homepage', 'highlights') ?? $defaults['highlights'] ?? [],
            'overview' => $this->normalizeOverview(
                SiteSetting::get('homepage', 'overview') ?? $defaults['overview'] ?? []
            ),
            'stats' => SiteSetting::get('homepage', 'stats') ?? $defaults['stats'] ?? [],
            'values' => SiteSetting::get('homepage', 'values') ?? $defaults['values'] ?? [],
            'catalogue' => $this->normalizeCatalogue(
                SiteSetting::get('homepage', 'catalogue') ?? $defaults['catalogue'] ?? []
            ),
            'featured_images' => SiteSetting::get('homepage', 'featured_images') ?? $defaults['featured_images'] ?? [],
            'trust_metrics' => SiteSetting::get('homepage', 'trust_metrics') ?? $defaults['trust_metrics'] ?? [],
            'school' => $this->normalizeSchool(
                SiteSetting::get('homepage', 'school') ?? $defaults['school'] ?? []
            ),
            'contact' => $this->normalizeContact(
                SiteSetting::get('homepage', 'contact') ?? $defaults['contact'] ?? []
            ),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(HomePageFormSchema::schema())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::set('homepage', 'hero_slides', $this->denormalizeHeroSlides($data['hero_slides'] ?? []));
        SiteSetting::set('homepage', 'highlights', $data['highlights'] ?? []);
        SiteSetting::set('homepage', 'overview', $this->denormalizeOverview($data['overview'] ?? []));
        SiteSetting::set('homepage', 'stats', $data['stats'] ?? []);
        SiteSetting::set('homepage', 'values', $data['values'] ?? []);
        SiteSetting::set('homepage', 'catalogue', $this->denormalizeCatalogue($data['catalogue'] ?? []));
        SiteSetting::set('homepage', 'featured_images', $data['featured_images'] ?? []);
        SiteSetting::set('homepage', 'trust_metrics', $data['trust_metrics'] ?? []);
        SiteSetting::set('homepage', 'school', $this->denormalizeSchool($data['school'] ?? []));
        SiteSetting::set('homepage', 'contact', $this->denormalizeContact($data['contact'] ?? []));

        Notification::make()->title('Home page content saved')->success()->send();
    }

    protected function normalizeHeroSlides(array $slides): array
    {
        return array_map(function (array $slide): array {
            $slide['points'] = $this->stringsToRepeater($slide['points'] ?? []);

            return $slide;
        }, $slides);
    }

    protected function denormalizeHeroSlides(array $slides): array
    {
        return array_map(function (array $slide): array {
            $slide['points'] = $this->repeaterToStrings($slide['points'] ?? []);

            return $slide;
        }, $slides);
    }

    protected function normalizeOverview(array $overview): array
    {
        $overview['body'] = $this->stringsToRepeater($overview['body'] ?? []);
        $overview['points'] = $this->stringsToRepeater($overview['points'] ?? []);

        return $overview;
    }

    protected function denormalizeOverview(array $overview): array
    {
        $overview['body'] = $this->repeaterToStrings($overview['body'] ?? []);
        $overview['points'] = $this->repeaterToStrings($overview['points'] ?? []);

        return $overview;
    }

    protected function normalizeCatalogue(array $catalogue): array
    {
        return array_intersect_key($catalogue, array_flip([
            'eyebrow', 'title', 'lead', 'image_primary', 'image_secondary',
        ]));
    }

    protected function denormalizeCatalogue(array $catalogue): array
    {
        return $this->normalizeCatalogue($catalogue);
    }

    protected function normalizeSchool(array $school): array
    {
        $school['body'] = $this->stringsToRepeater($school['body'] ?? []);
        $school['focus_areas'] = $this->stringsToRepeater($school['focus_areas'] ?? []);

        return $school;
    }

    protected function denormalizeSchool(array $school): array
    {
        $school['body'] = $this->repeaterToStrings($school['body'] ?? []);
        $school['focus_areas'] = $this->repeaterToStrings($school['focus_areas'] ?? []);

        return $school;
    }

    protected function normalizeContact(array $contact): array
    {
        $contact['po_boxes'] = $this->stringsToRepeater($contact['po_boxes'] ?? config('homepage.contact.po_boxes', []));
        $contact['locations'] = $this->stringsToRepeater($contact['locations'] ?? []);
        $contact['quote_checklist'] = $this->stringsToRepeater($contact['quote_checklist'] ?? []);

        return $contact;
    }

    protected function denormalizeContact(array $contact): array
    {
        $contact['po_boxes'] = $this->repeaterToStrings($contact['po_boxes'] ?? []);
        $contact['locations'] = $this->repeaterToStrings($contact['locations'] ?? []);
        $contact['quote_checklist'] = $this->repeaterToStrings($contact['quote_checklist'] ?? []);

        return $contact;
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
