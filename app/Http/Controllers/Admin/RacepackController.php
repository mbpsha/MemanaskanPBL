<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class RacepackController extends Controller
{
    /**
     * Update status racepack collection
     * Flow: pending -> verified -> done
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'registration_code' => 'required|string',
        ]);

        $registrationCode = $request->registration_code;

        // Cari data berdasarkan registration code
        $registration = EventRegistration::where('registration_code', $registrationCode)->first();

        if (!$registration) {
            return Redirect::back()->with('error', 'Registration code tidak ditemukan.');
        }

        // Cek Syarat 1: Jika payment_status masih 'pending'
        if ($registration->payment_status === 'pending') {
            return Redirect::back()->with('error', 'Peserta belum bayar/verifikasi. Status: Pending');
        }

        // Cek Syarat 2: Jika payment_status sudah 'done'
        if ($registration->payment_status === 'done') {
            return Redirect::back()->with('error', 'Racepack sudah pernah diambil sebelumnya.');
        }

        // Eksekusi: Jika payment_status adalah 'verified', ubah menjadi 'done'
        if ($registration->payment_status === 'verified') {
            $registration->payment_status = 'done';

            // Audit trail: Update verified_by dengan user yang melakukan scan
            $registration->verified_by = Auth::id();

            $registration->save();

            // Success data untuk ditampilkan
            $successData = [
                'name' => $registration->name,
                'bib_number' => $registration->bib_number,
                'shirt_size' => $registration->shirt_size,
            ];

            return Redirect::back()->with([
                'success' => 'Racepack berhasil diserahkan!',
                'success_data' => $successData,
            ]);
        }

        // Jika status tidak sesuai dengan flow yang diharapkan
        return Redirect::back()->with('error', 'Status pembayaran tidak valid: ' . $registration->payment_status);
    }
}
