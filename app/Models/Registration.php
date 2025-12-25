<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'registration_code',
        'category',
        'bib_number',
        'participant_info',
        'registration_status',
        'payment_status',
        'payment_verified_at',
        'verified_by',
        'admin_notes',
        'metadata'
    ];

    protected $casts = [
        'participant_info' => 'array',
        'metadata' => 'array',
        'payment_verified_at' => 'datetime',
    ];

    // Boot method to generate registration code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->registration_code)) {
                $registration->registration_code = 'REG-' . strtoupper(Str::random(10));
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('registration_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('registration_status', 'approved');
    }

    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePaymentVerified($query)
    {
        return $query->where('payment_status', 'verified');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->registration_status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->registration_status === 'approved';
    }

    public function isPaymentVerified(): bool
    {
        return $this->payment_status === 'verified';
    }
}
