<?php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $qrcodeImage;

    /**
     * Create a new message instance.
     */
    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;

        // Generate QR code URL using external API
        // QR code redirects to admin scan page with code parameter
        $verifyUrl = config('app.url') . '/admin/scan?code=' . $registration->registration_code;
        $qrData = urlencode($verifyUrl);
        $this->qrcodeImage = "https://quickchart.io/qr?text={$qrData}&size=300";

        \Log::info('QR code URL generated for: ' . $registration->registration_code);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Approved - WebRunning 5K Event',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-approved',
            with: [
                'name' => $this->registration->name,
                'registrationCode' => $this->registration->registration_code,
                'bibNumber' => $this->registration->bib_number,
                'shirtSize' => $this->registration->shirt_size,
                'verifiedAt' => $this->registration->payment_verified_at->format('d F Y, H:i'),
                'qrcodeImage' => $this->qrcodeImage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
