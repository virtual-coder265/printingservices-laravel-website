<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegistrationPeriod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'opens_at',
        'closes_at',
        'max_placements',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'opens_at' => 'datetime',
            'closes_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    public function enrollmentCount(): int
    {
        return $this->enrollments()
            ->where('type', 'enrollment')
            ->whereNotIn('status', ['rejected', 'withdrawn'])
            ->count();
    }

    public function isOpen(): bool
    {
        if (! $this->is_published) {
            return false;
        }

        $now = now();

        return $now->gte($this->opens_at) && $now->lte($this->closes_at);
    }

    public function hasCapacity(): bool
    {
        if ($this->max_placements === null) {
            return true;
        }

        return $this->enrollmentCount() < $this->max_placements;
    }

    public function isAcceptingEnrollments(): bool
    {
        return $this->isOpen() && $this->hasCapacity();
    }

    public function statusLabel(): string
    {
        if (! $this->is_published) {
            return 'Draft';
        }

        $now = now();

        if ($now->lt($this->opens_at)) {
            return 'Upcoming';
        }

        if ($now->gt($this->closes_at)) {
            return 'Closed';
        }

        if (! $this->hasCapacity()) {
            return 'Full';
        }

        return 'Open';
    }

    public static function currentOpen(): ?self
    {
        return static::query()
            ->where('is_published', true)
            ->where('opens_at', '<=', now())
            ->where('closes_at', '>=', now())
            ->orderBy('opens_at')
            ->get()
            ->first(fn (self $period) => $period->hasCapacity());
    }
}
