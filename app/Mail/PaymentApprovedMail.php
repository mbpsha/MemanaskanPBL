<?php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PaymentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $barcodeImage;

    /**
     * Create a new message instance.
     */
    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;

        // Generate barcode image (Code 128 format)
        try {
            $generator = new BarcodeGeneratorPNG();
            $this->barcodeImage = base64_encode(
                $generator->getBarcode($registration->registration_code, $generator::TYPE_CODE_128)
            );
        } catch (\Exception $e) {
            \Log::error('Failed to generate barcode: ' . $e->getMessage());
            $this->barcodeImage = null;
        }
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
                'barcodeImage' => $this->barcodeImage,
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
