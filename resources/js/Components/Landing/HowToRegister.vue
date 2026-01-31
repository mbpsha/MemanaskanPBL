<template>
    <section id="cara-daftar" class="py-12 md:py-16 bg-cyan-400">
        <div class="container max-w-6xl px-4 mx-auto md:px-8">
            <h2
                class="mb-8 text-3xl font-bold text-center text-white transition-transform duration-300 md:text-5xl md:mb-12 hover:scale-105"
            >
                Cara Daftar Event
            </h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-8">
                <!-- Data Peserta -->
                <div
                    class="p-6 transition-all duration-300 transform bg-white shadow-lg cursor-pointer rounded-2xl md:p-8 hover:shadow-2xl hover:-translate-y-2 active:scale-95"
                >
                    <div
                        class="flex items-center justify-center mb-4 transition-transform duration-300 rounded-full w-14 h-14 md:w-16 md:h-16 bg-cyan-100 md:mb-6 group-hover:scale-110"
                    >
                        <svg
                            class="text-orange-500 w-7 h-7 md:w-8 md:h-8"
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
                    <h3
                        class="mb-2 text-lg font-bold text-gray-900 md:text-xl md:mb-3"
                    >
                        Data Peserta
                    </h3>
                    <p class="text-sm text-gray-600">
                        Isi data peserta dengan lengkap dan
                    </p>
                </div>

                <!-- Pilih Jenis Tiket -->
                <div
                    class="p-6 transition-all duration-300 transform bg-white shadow-lg cursor-pointer rounded-2xl md:p-8 hover:shadow-2xl hover:-translate-y-2 active:scale-95"
                >
                    <div
                        class="flex items-center justify-center mb-4 transition-transform duration-300 rounded-full w-14 h-14 md:w-16 md:h-16 bg-cyan-100 md:mb-6 group-hover:scale-110"
                    >
                        <svg
                            class="text-orange-500 w-7 h-7 md:w-8 md:h-8"
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
                    <h3
                        class="mb-2 text-lg font-bold text-gray-900 md:text-xl md:mb-3"
                    >
                        Pilih Jenis Tiket
                    </h3>
                    <p class="text-sm text-gray-600">
                        Pilih jenis tiket Anda: Tiket Basic atau Tiket Syiar
                        Ramadan yang sudah termasuk donasi
                    </p>
                </div>

                <!-- Pembayaran -->
                <div
                    class="p-6 transition-all duration-300 transform bg-white shadow-lg cursor-pointer rounded-2xl md:p-8 hover:shadow-2xl hover:-translate-y-2 active:scale-95"
                >
                    <div
                        class="flex items-center justify-center mb-4 transition-transform duration-300 rounded-full w-14 h-14 md:w-16 md:h-16 bg-cyan-100 md:mb-6 group-hover:scale-110"
                    >
                        <svg
                            class="text-orange-500 w-7 h-7 md:w-8 md:h-8"
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
                    <h3
                        class="mb-2 text-lg font-bold text-gray-900 md:text-xl md:mb-3"
                    >
                        Pembayaran
                    </h3>
                    <p class="text-sm text-gray-600">
                        Selesaikan pembayaran sesuai nominal. E-ticket akan
                        dikirimkan ke email Anda segera setelah proses
                        verifikasi berhasil
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center md:mt-12">
                <button
                    @click="showRegistrationClosedAlert"
                    class="inline-block px-8 py-4 text-xl font-black text-gray-900 transition-all duration-300 bg-yellow-400 shadow-xl cursor-pointer hover:bg-yellow-500 active:scale-95 md:px-16 md:py-5 rounded-2xl md:text-2xl hover:shadow-2xl"
                >
                    DAFTAR SEKARANG
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
const showRegistrationClosedAlert = () => {
    alert("Pendaftaran Event Sudah Berakhir!");
};
</script>
