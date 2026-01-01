<template>
    <section id="cara-daftar" class="py-16 bg-cyan-400">
        <div class="container mx-auto px-8 max-w-6xl">
            <h2 class="text-5xl font-bold text-white text-center mb-12">
                Cara Daftar Event
            </h2>

            <div class="grid grid-cols-3 gap-8">
                <!-- Data Peserta -->
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div
                        class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mb-6"
                    >
                        <svg
                            class="w-8 h-8 text-orange-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">
                        Data Peserta
                    </h3>
                    <p class="text-gray-600 text-sm">
                        Isi data peserta dengan lengkap dan
                    </p>
                </div>

                <!-- Pilih Jenis Tiket -->
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div
                        class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mb-6"
                    >
                        <svg
                            class="w-8 h-8 text-orange-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                            />
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">
                        Pilih Jenis Tiket
                    </h3>
                    <p class="text-gray-600 text-sm">
                        Pilih jenis tiket Anda: Tiket Basic atau Tiket Syiar
                        Ramadan yang sudah termasuk donasi
                    </p>
                </div>

                <!-- Pembayaran -->
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div
                        class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mb-6"
                    >
                        <svg
                            class="w-8 h-8 text-orange-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                            />
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">
                        Pembayaran
                    </h3>
                    <p class="text-gray-600 text-sm">
                        Selesaikan pembayaran sesuai nominal. E-ticket akan
                        dikirimkan ke email Anda segera setelah proses
                        verifikasi berhasil
                    </p>
                </div>
            </div>

            <div class="text-center mt-12">
                <Link
                    href="/event-registrations"
                    class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-16 py-5 rounded-2xl text-2xl font-black transition shadow-xl inline-block"
                >
                    DAFTAR SEKARANG
                </Link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
