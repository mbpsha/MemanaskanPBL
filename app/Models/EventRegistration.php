<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nik',
        'address',
        'phone',
        'email',
        'gender',
        'illness',
        'shirt_size',
        'payment_method',
        'payment_proof_path',
        'payment_proof_filename',
        'payment_status',
        'payment_verified_at',
        'verified_by',
        'rejection_reason',
        'registration_code',
        'bib_number',
    ];

    protected $casts = [
        'payment_verified_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate registration code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->registration_code)) {
                $registration->registration_code = 'REG-' . strtoupper(Str::random(10));
            }
        });
    }

    /**
     * Relationship to admin who verified payment
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment proof is uploaded
     */
    public function isUploaded(): bool
    {
        return $this->payment_status === 'uploaded';
    }

    /**
     * Check if payment is verified
     */
    public function isVerified(): bool
    {
        return $this->payment_status === 'verified';
    }

    /**
     * Check if payment is rejected
     */
    public function isRejected(): bool
    {
        return $this->payment_status === 'rejected';
    }
}
