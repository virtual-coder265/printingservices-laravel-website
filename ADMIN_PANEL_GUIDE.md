# Admin Panel Guide

Design rules, conventions, and boilerplate instructions for the Government Press Filament admin panel.

This document reflects the **current** admin implementation. Use it as the single source of truth when adding resources, pages, widgets, or custom views.

---

## Stack & Theme

| Item | Value |
|------|-------|
| Framework | Laravel + [Filament 3.3](https://filamentphp.com/docs/3.x/panels/installation) |
| Panel path | `/admin` |
| Panel ID | `admin` |
| Primary color | `Color::Blue` (stock Filament blue) |
| Custom theme/CSS | **None** — use published Filament assets only |
| Custom font | **None** |
| Brand name | `config('homepage.brand.short_name', 'Government Press')` |

**Design rule:** Do not introduce custom admin CSS or override Filament layouts unless there is a strong product reason. Consistency with the stock Filament blue theme is required.

Published assets live in `public/css/filament/` and `public/js/filament/`. Regenerate after Filament upgrades:

```bash
php artisan filament:assets
```

---

## Directory Structure

```
app/
  Providers/Filament/
    AdminPanelProvider.php          # Panel config (colors, discovery, hooks)
  Filament/
    Concerns/                       # Reusable traits for forms
    Forms/                          # Large form schemas (extracted from pages)
    Pages/                          # Custom standalone admin pages
    Resources/                      # CRUD resources + Pages/ + RelationManagers/
    Widgets/                        # Dashboard widgets

resources/views/filament/
  pages/                            # Custom page Blade wrappers
  {feature}/                        # Feature-specific partials (e.g. quotation-requests/)

admin-boilerplate-temp/             # Copy-paste templates (see folder README)
```

Filament auto-discovers Resources, Pages, and Widgets from `app/Filament/`. No manual registration is needed beyond `AdminPanelProvider`.

---

## Panel Configuration Rules

`AdminPanelProvider` must:

1. Set `->default()`, `->id('admin')`, `->path('admin')`, `->login()`
2. Use `->colors(['primary' => Color::Blue])`
3. Auto-discover Resources, Pages, and Widgets
4. Register `DashboardStatsWidget` and `AccountWidget` on the dashboard
5. Keep the mobile sidebar `renderHook` on `PanelsRenderHook::SCRIPTS_AFTER` (closes sidebar below 1024px)

Do **not** add `config/filament.php` — all panel config stays in the provider.

---

## Navigation Conventions

### Groups (use exactly these names for consistency)

| Group | Purpose | Sort examples |
|-------|---------|---------------|
| **CMS** | Pages, documents, vacancies, homepage, site settings | 1–10 |
| **Catalog** | Service categories, services, products | 1–3 |
| **Sales** | Quote requests, quotations, orders | 0–2 |
| **Production** | Print jobs, artwork files | 1–2 |
| **Customers** | Organizations, customer profiles | 1–2 |
| **Administration** | Users | 1 |
| **ERP Integration** | ERP dashboard, sync tools | 1 |

### Navigation properties (every Resource/Page)

```php
protected static ?string $navigationIcon = 'heroicon-o-...';  // Heroicons outline
protected static ?string $navigationGroup = 'Catalog';       // One of the groups above
protected static ?int $navigationSort = 1;                     // Lower = higher in group
```

Optional:

```php
protected static ?string $navigationLabel = 'Human label';
protected static ?string $modelLabel = 'singular';
protected static ?string $pluralModelLabel = 'plural';
```

**Icon rule:** Use `heroicon-o-*` (outline) icons everywhere. Do not mix icon sets.

---

## Access Control

### Panel gate

Users must have `is_staff === true` (`User::canAccessPanel()`).

### Page-level permissions

Custom CMS pages enforce Spatie permissions in `canAccess()`:

```php
public static function canAccess(): bool
{
    return auth()->user()?->can('page_site_settings') ?? false;
}
```

Register new page permissions in `RoleAndPermissionSeeder`.

### Resource-level permissions

- Prefer `canViewAny()`, `canCreate()`, etc. on the Resource class
- Use Policies for workflow actions (approve, reject, send to ERP)
- `QuotationRequestResource` is the reference for policy-driven header actions

**Rule:** Every new admin feature that exposes sensitive data must define a permission and check it explicitly. Do not rely on “staff only” as sufficient authorization.

---

## Resource Patterns

### Standard CRUD resource

Reference: `ServiceResource`, `PageResource`

1. **Form** — group fields into `Section::make()` blocks
2. **Content section** — title, slug, descriptions, media
3. **Settings / Publishing section** — toggles, dates, meta, sort order
4. **Table** — searchable text columns, `IconColumn` for booleans, `ImageColumn` for thumbnails
5. **Filters** — `TernaryFilter` for booleans, `SelectFilter` for relationships
6. **Actions** — `EditAction` on rows; `CreateAction` on list; `DeleteAction` on edit
7. **Bulk actions** — `DeleteBulkAction` inside `BulkActionGroup`
8. **Pages** — `ListRecords`, `CreateRecord`, `EditRecord` in `Resources/{Name}/Pages/`

### Slug auto-generation

```php
Forms\Components\TextInput::make('title')
    ->required()
    ->live(onBlur: true)
    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
Forms\Components\TextInput::make('slug')
    ->required()
    ->unique(ignoreRecord: true),
```

### Publishing block (CMS content)

```php
Forms\Components\Section::make('Publishing')->schema([
    Forms\Components\Toggle::make('is_published')->default(false),
    Forms\Components\DateTimePicker::make('published_at'),
    Forms\Components\TextInput::make('meta_title')->maxLength(255),
    Forms\Components\Textarea::make('meta_description')->rows(3),
])->columns(2),
```

### Relationship selects

```php
Forms\Components\Select::make('category_id')
    ->relationship('category', 'name')
    ->searchable()
    ->preload(),
```

### Money fields

Use `MWK` prefix and `->money('MWK')` on table columns.

---

## Custom Page Patterns

### Form-based settings page

Reference: `SiteSettings`, `HomePageEditor`

| Requirement | Pattern |
|-------------|---------|
| Base class | `Page` + `HasForms` |
| Trait | `InteractsWithForms` |
| State | `public ?array $data = []` with `->statePath('data')` |
| Load | `mount()` → `$this->form->fill([...])` |
| Save | `save()` → persist data → `Notification::make()->success()` |
| View | Thin Blade: `wire:submit="save"` + `{{ $this->form }}` + submit button |

### Action dashboard page (form + table)

Reference: `ErpDashboard`

| Requirement | Pattern |
|-------------|---------|
| Base class | `Page` + `HasTable` |
| Trait | `InteractsWithTable` |
| Actions | Public methods called via `wire:click` on `<x-filament::button>` |
| Table | `table(Table $table): Table` method |
| View | Custom layout with action buttons + card wrapper + `{{ $this->table }}` |

### Large multi-section forms

Extract schema to `app/Filament/Forms/{Name}FormSchema.php`:

```php
Forms\Components\Tabs::make('Sections')
    ->tabs([...])
    ->columnSpanFull()
    ->persistTabInQueryString(),
```

Use `Repeater` for lists. Set `->collapsible()`, `->cloneable()`, and `->itemLabel()` where helpful.

---

## File Upload Rules

### Reusable trait

Use `ProvidesMediaUploadFields` for CMS images:

| Method | Directory | Max size | Types |
|--------|-----------|----------|-------|
| `homepageImageUpload()` | `homepage/` | 5120 KB | png, jpeg, webp |
| `brandImageUpload()` | `brand/` | 2048 KB (default) | png, jpeg, webp, svg, ico |

Both use `disk('public')`, `visibility('public')`, `downloadable()`, `openable()`.

### Per-resource uploads

| Resource type | Directory | Field |
|---------------|-----------|-------|
| Pages | `pages/` | `hero_image` |
| Products | `products/` | `thumbnail` |
| Services | `services/` | `thumbnail` |
| Documents | `documents/` | `file_path` (any file) |
| Artwork | `artwork/` | `file_path` (any file) |

**Rule:** Never upload to the project root or arbitrary paths. One directory per domain entity.

### Preserving existing uploads on save

For settings pages that replace files:

1. Add hidden `*_existing` field with the stored path
2. On `mount()`, fill upload field only if path starts with allowed prefix (`brand/`, `homepage/`)
3. On save, use `resolveUploadedPath($uploaded, $existing)` — uploaded value wins, else keep existing

### Media helpers

Use `media_url($path)` and `media_exists($path)` from `app/helpers.php` in previews and public views. Do not call `Storage::` directly in Blade.

---

## Blade View Rules

### Minimal form page (preferred default)

```blade
<div>
    <form wire:submit="save">
        {{ $this->form }}
        <div class="mt-6">
            <x-filament::button type="submit">Save</x-filament::button>
        </div>
    </form>
</div>
```

### Custom dashboard card

Match Filament surface tokens:

```blade
<div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    ...
</div>
```

### Buttons

Always use `<x-filament::button>` with `color="primary"` or `color="gray"`. Do not use raw `<button class="btn ...">`.

### Custom infolist entries

Use `ViewEntry::make('field')->view('filament.{feature}.{partial}')` and keep partials focused (attachments list, event timeline, etc.).

**Rule:** No `resources/views/vendor/filament/` overrides unless upgrading Filament core behavior.

---

## Widget Rules

Dashboard widgets extend `StatsOverviewWidget`:

```php
Stat::make('Label', $count)
    ->description('Short context')
    ->color('warning'),  // warning | success | info | danger | gray
```

Keep stats actionable — describe what the number means in `->description()`.

---

## Notifications

Use Filament notifications for all user feedback:

```php
Notification::make()
    ->title('Saved successfully')
    ->success()
    ->send();
```

Do not use `session()->flash()` in admin pages.

---

## Relation Managers

Reference: `OrderItemsRelationManager`

- Live on the parent Resource: `public static function getRelations(): array`
- Set `$relationship` to the Eloquent relation name
- Mirror Resource conventions for form sections and table columns
- Use `->placeholder('—')` for optional relationship columns

---

## CMS Data Flow (Site Settings / Homepage)

```
config/homepage.php  →  defaults
        ↓
site_settings table  →  SiteSetting::get() / ::set()  (1hr cache)
        ↓
Filament CMS pages   →  edit content
        ↓
SiteContentService   →  merged output for public site
```

When adding new site-wide settings:

1. Add defaults to `config/homepage.php`
2. Seed via `SiteSettingsSeeder` if needed
3. Add group/key constants in documentation
4. Read on public site through `SiteContentService`, not raw `SiteSetting` in views

---

## Checklist: Adding a New Admin Feature

- [ ] Choose type: Resource, custom Page, Widget, or RelationManager
- [ ] Copy the matching template from `admin-boilerplate-temp/`
- [ ] Set navigation group, icon, and sort order
- [ ] Add Spatie permission + seeder entry if access must be restricted
- [ ] Group form fields into `Section` blocks (2 columns default)
- [ ] Add table search/sort/filters for Resources
- [ ] Use standard FileUpload directories and the media trait where applicable
- [ ] Use `Notification::make()` for save/action feedback
- [ ] Keep Blade views thin; extract large schemas to `Forms/`
- [ ] Run `php artisan filament:assets` after Filament package changes
- [ ] Verify mobile sidebar behavior at < 1024px viewport

---

## Anti-Patterns (Do Not Do)

| Avoid | Do instead |
|-------|------------|
| Custom admin CSS files | Filament components + Tailwind utility classes in custom views |
| Inline 200-line `form()` methods | Extract to `Forms/{Name}FormSchema.php` |
| Hard-coded brand name in panel | `config('homepage.brand.short_name')` |
| `Storage::url()` in Blade | `media_url()` helper |
| Skipping `canAccess()` on sensitive pages | Spatie permission check |
| Random upload directories | Entity-based directories from the table above |
| Raw HTML buttons in admin views | `<x-filament::button>` |

---

## Boilerplate Templates

Copy-ready stubs live in **`admin-boilerplate-temp/`**. See that folder’s `README.md` for file-by-file usage and rename instructions.

Quick start:

```bash
# Example: new catalog resource
cp admin-boilerplate-temp/app/Filament/Resources/ExampleResource.php.stub \
   app/Filament/Resources/WidgetResource.php
# ...then rename class, model, and navigation properties
```

Or use Filament generators and align output with this guide:

```bash
php artisan make:filament-resource Widget --generate
php artisan make:filament-page WidgetSettings --type=custom
php artisan make:filament-widget WidgetStats --stats-overview
```

After generating, adjust navigation group, sections, permissions, and upload paths to match this document.
