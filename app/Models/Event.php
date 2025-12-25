<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'event_date',
        'registration_open_date',
        'registration_close_date',
        'location',
        'registration_fee',
        'max_participants',
        'current_participants',
        'status',
        'categories',
        'additional_fields',
        'is_active',
        'featured_image'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'registration_open_date' => 'datetime',
        'registration_close_date' => 'datetime',
        'categories' => 'array',
        'additional_fields' => 'array',
        'short_description' => 'array',
        'is_active' => 'boolean',
    ];

    // Laravel 12 attribute casting with custom logic
    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn($value) => is_array($value) ? $value : json_decode($value, true),
            set: fn($value) => is_array($value) ? json_encode($value) : $value,
        );
    }

    // Relationships
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRegistrationOpen($query)
    {
        return $query->where('status', 'registration_open')
            ->where('registration_open_date', '<=', now())
            ->where('registration_close_date', '>=', now());
    }

    // Methods
    public function isRegistrationOpen(): bool
    {
        return $this->status === 'registration_open'
            && now()->between($this->registration_open_date, $this->registration_close_date)
            && (!$this->max_participants || $this->current_participants < $this->max_participants);
    }

    public function availableSlots(): ?int
    {
        if (!$this->max_participants) {
            return null;
        }

        return max(0, $this->max_participants - $this->current_participants);
    }
}
