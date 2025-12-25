<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $eventId;

    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Registration::with(['user', 'event'])
            ->orderBy('created_at', 'desc');

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Registration Code',
            'BIB Number',
            'Name',
            'Email',
            'Phone',
            'Event',
            'Category',
            'Registration Status',
            'Payment Status',
            'Registration Date',
        ];
    }

    /**
     * @param Registration $registration
     * @return array
     */
    public function map($registration): array
    {
        return [
            $registration->registration_code,
            $registration->bib_number ?? 'Not Assigned',
            $registration->user->name,
            $registration->user->email,
            $registration->user->phone ?? 'N/A',
            $registration->event->name,
            $registration->category,
            ucfirst($registration->registration_status),
            ucfirst($registration->payment_status),
            $registration->created_at->format('Y-m-d H:i'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
