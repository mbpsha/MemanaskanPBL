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
            // Try local generation first if GD is available
            if (extension_loaded('gd') && function_exists('imagecreate')) {
                $generator = new BarcodeGeneratorPNG();
                $this->barcodeImage = base64_encode(
                    $generator->getBarcode($registration->registration_code, $generator::TYPE_CODE_128)
                );
            } else {
                // Fallback: Use external barcode API
                \Log::info('Using external barcode API as fallback');
                $barcodeUrl = 'https://barcode.tec-it.com/barcode.ashx?data=' . urlencode($registration->registration_code) . '&code=Code128&translate-esc=on&dpi=96&imagetype=png';

                // Fetch barcode from external API
                $barcodeData = @file_get_contents($barcodeUrl);

                if ($barcodeData !== false) {
                    $this->barcodeImage = base64_encode($barcodeData);
                    \Log::info('Barcode generated successfully using external API');
                } else {
                    throw new \Exception('Failed to fetch barcode from external API');
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate barcode: ' . $e->getMessage());
            \Log::error('GD loaded: ' . (extension_loaded('gd') ? 'yes' : 'no'));
            \Log::error('GD functions: ' . (function_exists('imagecreate') ? 'yes' : 'no'));
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
