<?php

namespace App\Filament\Resources;

use App\Enums\StudentEnrollmentStatus;
use App\Enums\StudentEnrollmentType;
use App\Filament\Resources\StudentEnrollmentResource\Pages;
use App\Models\StudentEnrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentEnrollmentResource extends Resource
{
    protected static ?string $model = StudentEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Training School';

    protected static ?string $navigationLabel = 'Enrollments & interests';

    protected static ?string $modelLabel = 'Student record';

    protected static ?string $pluralModelLabel = 'Student records';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_student_enrollment') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Admin')->schema([
                Forms\Components\Select::make('status')
                    ->options(collect(StudentEnrollmentStatus::cases())->mapWithKeys(
                        fn (StudentEnrollmentStatus $s) => [$s->value => $s->label()]
                    )->all())
                    ->required(),
                Forms\Components\Textarea::make('admin_notes')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('rejection_reason')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Summary')->schema([
                Infolists\Components\TextEntry::make('external_id')->label('Reference'),
                Infolists\Components\TextEntry::make('type')
                    ->badge()
                    ->formatStateUsing(fn (StudentEnrollmentType $state): string => $state->label())
                    ->color(fn (StudentEnrollmentType $state): string => $state->color()),
                Infolists\Components\TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn (StudentEnrollmentStatus $state): string => $state->label())
                    ->color(fn (StudentEnrollmentStatus $state): string => $state->color()),
                Infolists\Components\TextEntry::make('registrationPeriod.name')
                    ->label('Registration period')
                    ->placeholder('—'),
                Infolists\Components\TextEntry::make('created_at')->dateTime(),
            ])->columns(3),
            Infolists\Components\Section::make('Personal details')->schema([
                Infolists\Components\TextEntry::make('full_name')
                    ->label('Full name')
                    ->state(fn (StudentEnrollment $r) => $r->fullName()),
                Infolists\Components\TextEntry::make('dob')
                    ->label('Date of birth')
                    ->state(fn (StudentEnrollment $r) => $r->payload('personal.date_of_birth') ?: '—'),
                Infolists\Components\TextEntry::make('gender')
                    ->state(fn (StudentEnrollment $r) => config('student_enrollments.gender_options.'.$r->payload('personal.gender'), $r->payload('personal.gender')) ?: '—'),
                Infolists\Components\TextEntry::make('national_id')
                    ->state(fn (StudentEnrollment $r) => $r->payload('personal.national_id') ?: '—'),
                Infolists\Components\TextEntry::make('district')
                    ->state(fn (StudentEnrollment $r) => $r->payload('personal.district') ?: '—'),
                Infolists\Components\TextEntry::make('address')
                    ->state(fn (StudentEnrollment $r) => $r->payload('personal.address') ?: '—')
                    ->columnSpanFull(),
            ])->columns(3),
            Infolists\Components\Section::make('Contact')->schema([
                Infolists\Components\TextEntry::make('phone')
                    ->label('Phone')
                    ->state(fn (StudentEnrollment $r) => $r->phone()),
                Infolists\Components\TextEntry::make('emergency_name')
                    ->state(fn (StudentEnrollment $r) => $r->payload('contact.emergency_name') ?: '—'),
                Infolists\Components\TextEntry::make('emergency_phone')
                    ->state(fn (StudentEnrollment $r) => $r->payload('contact.emergency_phone') ?: '—'),
            ])->columns(3),
            Infolists\Components\Section::make('Education')->schema([
                Infolists\Components\TextEntry::make('qualification')
                    ->label('Highest qualification')
                    ->state(fn (StudentEnrollment $r) => $r->highestQualification()),
                Infolists\Components\TextEntry::make('institution')
                    ->state(fn (StudentEnrollment $r) => $r->payload('education.institution') ?: '—'),
                Infolists\Components\TextEntry::make('year_obtained')
                    ->state(fn (StudentEnrollment $r) => $r->payload('education.year_obtained') ?: '—'),
                Infolists\Components\TextEntry::make('education_details')
                    ->label('Details')
                    ->state(fn (StudentEnrollment $r) => $r->payload('education.details') ?: '—')
                    ->columnSpanFull(),
            ])->columns(3),
            Infolists\Components\Section::make('Experience')
                ->schema([
                    Infolists\Components\TextEntry::make('prior_experience')
                        ->label('Prior printing experience')
                        ->state(fn (StudentEnrollment $r) => $r->payload('experience.prior_printing_experience') ?: '—')
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('motivation')
                        ->state(fn (StudentEnrollment $r) => $r->payload('experience.motivation') ?: '—')
                        ->columnSpanFull(),
                ])
                ->visible(fn (StudentEnrollment $record): bool => $record->type === StudentEnrollmentType::Enrollment)
                ->columns(1),
            Infolists\Components\Section::make('Submission')->schema([
                Infolists\Components\TextEntry::make('client_ip')->label('IP address'),
                Infolists\Components\TextEntry::make('reviewed_at')->dateTime()->placeholder('—'),
                Infolists\Components\TextEntry::make('reviewer.name')->label('Reviewed by')->placeholder('—'),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_id')
                    ->label('Reference')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (StudentEnrollmentType $state): string => $state->label())
                    ->color(fn (StudentEnrollmentType $state): string => $state->color()),
                Tables\Columns\TextColumn::make('applicant_name')
                    ->label('Name')
                    ->getStateUsing(fn (StudentEnrollment $r) => $r->fullName())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search) {
                            $q->where('payload_json->personal->first_name', 'like', "%{$search}%")
                                ->orWhere('payload_json->personal->last_name', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->getStateUsing(fn (StudentEnrollment $r) => $r->phone()),
                Tables\Columns\TextColumn::make('qualification')
                    ->getStateUsing(fn (StudentEnrollment $r) => $r->highestQualification())
                    ->toggleable(),
                Tables\Columns\TextColumn::make('registrationPeriod.name')
                    ->label('Period')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (StudentEnrollmentStatus $state): string => $state->label())
                    ->color(fn (StudentEnrollmentStatus $state): string => $state->color()),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(collect(StudentEnrollmentType::cases())->mapWithKeys(
                        fn (StudentEnrollmentType $t) => [$t->value => $t->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(StudentEnrollmentStatus::cases())->mapWithKeys(
                        fn (StudentEnrollmentStatus $s) => [$s->value => $s->label()]
                    )->all()),
                Tables\Filters\SelectFilter::make('registration_period_id')
                    ->label('Registration period')
                    ->relationship('registrationPeriod', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->label('Update status'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentEnrollments::route('/'),
            'view' => Pages\ViewStudentEnrollment::route('/{record}'),
            'edit' => Pages\EditStudentEnrollment::route('/{record}/edit'),
        ];
    }
}
