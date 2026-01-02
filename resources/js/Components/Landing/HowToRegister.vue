<template>
    <section id="cara-daftar" class="py-12 md:py-16 bg-cyan-400">
        <div class="container mx-auto px-4 md:px-8 max-w-6xl">
            <h2 class="text-3xl md:text-5xl font-bold text-white text-center mb-8 md:mb-12 hover:scale-105 transition-transform duration-300">Cara Daftar Event</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                <!-- Data Peserta -->
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 cursor-pointer active:scale-95">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-cyan-100 rounded-full flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg md:text-xl mb-2 md:mb-3 text-gray-900">Data Peserta</h3>
                    <p class="text-gray-600 text-sm">
                        Isi data peserta dengan lengkap dan
                    </p>
                </div>
                
                <!-- Pilih Jenis Tiket -->
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 cursor-pointer active:scale-95">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-cyan-100 rounded-full flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg md:text-xl mb-2 md:mb-3 text-gray-900">Pilih Jenis Tiket</h3>
                    <p class="text-gray-600 text-sm">
                        Pilih jenis tiket Anda: Tiket Basic atau Tiket Syiar Ramadan yang sudah termasuk donasi
                    </p>
                </div>
                
                <!-- Pembayaran -->
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 cursor-pointer active:scale-95">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-cyan-100 rounded-full flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 md:w-8 md:h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg md:text-xl mb-2 md:mb-3 text-gray-900">Pembayaran</h3>
                    <p class="text-gray-600 text-sm">
                        Selesaikan pembayaran sesuai nominal. E-ticket akan dikirimkan ke email Anda segera setelah proses verifikasi berhasil
                    </p>
                </div>
            </div>
            
            <div class="text-center mt-8 md:mt-12">
                <Link href="/event-registrations" class="inline-block bg-yellow-400 hover:bg-yellow-500 active:scale-95 text-gray-900 px-8 md:px-16 py-4 md:py-5 rounded-2xl text-xl md:text-2xl font-black transition-all duration-300 shadow-xl hover:shadow-2xl">
                    DAFTAR SEKARANG
                </Link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
