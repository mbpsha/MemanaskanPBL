<script setup>
import { ref, computed } from 'vue'
import { useForm, Head } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    nik: '',
    address: '',
    phone: '',
    email: '',
    illness: '',
    shirt_size: '',
    ticket_type: '',
    ticket_price: 0,
    transaction_id: '',
    payment_proof: null,
    agreement: false,
});

const selectedTicket = ref(null)

const tickets = [
    { name: "Tiket Basic", price: 100000 },
    { name: "Tiket Fun Run + Support Syiar Ramadan", price: 130000 },
];

const selectTicket = (ticket) => {
    selectedTicket.value = ticket.name
    form.ticket_type = ticket.name
    form.ticket_price = ticket.price
}

const canSubmit = computed(() => {
    return (
        form.name &&
        form.nik &&
        form.address &&
        form.phone &&
        form.email &&
        form.gender &&
        form.shirt_size &&
        form.ticket_type &&
        form.payment_proof &&
        form.agreement
    );
});

const submit = () => {
    if (!canSubmit.value) return

    form.post('/event/register', {
        forceFormData: true,
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Form Pendaftaran Event" />

    <div
        class="min-h-screen bg-gradient-to-b from-[#EAF9FD] to-[#2DB7D2] py-10 px-4"
    >
        <div class="max-w-5xl mx-auto">
            <!-- LOGO -->
            <img src="/images/event run1.png" class="h-20 mx-auto mb-8" />

            <!-- ================= IDENTITAS PESERTA ================= -->
            <h2 class="section-title">Identitas Peserta</h2>

            <div class="card mb-12">
                
                <!-- ⚠️ PERHATIAN -->
                <div class="bg-red-600 text-white rounded-lg p-4 text-sm mb-8">
                    <p class="font-bold text-center mb-1">PERHATIAN!!!</p>
                    <ol class="list-decimal list-inside">
                        <li>Pastikan email yang diisikan benar</li>
                        <li>Ruang penyimpanan email pastikan tidak penuh</li>
                    </ol>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input v-model="form.name" class="input" required />
                    </div>
                    <div>
                        <label class="label">NIK <span class="text-red-600">*</span></label>
                        <input v-model="form.nik" class="input" required />
                    </div>
                    <div>
                        <label class="label">Alamat <span class="text-red-600">*</span></label>
                        <input v-model="form.address" class="input" required />
                    </div>
                    <div>
                        <label class="label">Nomor HP <span class="text-red-600">*</span></label>
                        <input v-model="form.phone" class="input" required />
                    </div>
                    <div>
                        <label class="label">Email</label>
                        <input v-model="form.email" type="email" class="input" />
                    </div>
                    <div>
                        <label class="label">Riwayat Penyakit</label>
                        <input v-model="form.illness" class="input" placeholder="Kosongkan jika tidak ada" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">Ukuran Jersey</label>
                        <select v-model="form.shirt_size" class="input">
                            <option disabled value="">Klik untuk Pilih Ukuran Jersey</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ================= HALAMAN PEMBAYARAN ================= -->
            <h2 class="section-title">Halaman Pembayaran</h2>

            <div class="card mb-12">
                <h3 class="text-center font-bold text-lg mb-6">Jenis Ticket</h3>

                <!-- TIKET -->
                <div class="flex justify-center gap-6 flex-wrap mb-8">
                    <button
                        v-for="ticket in tickets"
                        :key="ticket.name"
                        @click="selectTicket(ticket)"
                        type="button"
                        :class="[
                            'ticket-box',
                            selectedTicket === ticket.name
                                ? 'ticket-active'
                                : 'ticket-inactive',
                        ]"
                    >
                        <div class="font-semibold text-center">
                            {{ ticket.name }}
                        </div>
                        <div class="text-sm text-center">
                            Rp{{ ticket.price.toLocaleString("id-ID") }},-
                        </div>
                    </button>
                </div>

                <!-- SELESAIKAN PEMBAYARAN -->
                <div class="btn-orange mb-10 text-center">
                    Selesaikan Pembayaranmu
                </div>

                <!-- DETAIL TRANSAKSI -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center mb-10"
                >
                    <div class="text-sm space-y-2">
                        <p class="font-semibold mb-2">Detail Transaksi</p>
                        <p>Payment Method : QRIS</p>
                        <p>Tanggal : 2 Juli 2025</p>
                        <p>Harga Tiket : Rp{{ form.ticket_price.toLocaleString('id-ID') }}</p>
                        <div class="border-t pt-2 font-bold">
                            Total Harga : Rp{{
                                form.ticket_price.toLocaleString("id-ID")
                            }}
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <img src="/images/qris.png" class="w-48" />
                    </div>
                </div>

                <!-- ================= KONFIRMASI PEMBAYARANMU ================= -->
                <div class="btn-orange mb-10 text-center">
                    Konfirmasi Pembayaranmu
                </div>

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="label">ID Transaksional (Opsional)</label>
                        <input v-model="form.transaction_id" class="input" placeholder="Nomor referensi pembayaran (opsional)" />
                        <p class="text-xs text-gray-500 mt-1">
                            Nomor referensi membantu verifikasi pembayaran lebih
                            cepat
                        </p>
                    </div>

                    <div>
                        <label class="label">Bukti Pembayaran <span class="text-red-600">*</span></label>
                        <input
                            type="file"
                            class="input"
                            accept="image/png,image/jpeg"
                            @change="e => form.payment_proof = e.target.files[0]"
                        />
                        <p class="text-xs text-gray-500 mt-1">
                            • Format JPG, PNG, JPEG <br />
                            • Maksimal ukuran: 5 Mb <br />
                            • Pastikan bukti pembayaran jelas dan dapat dibaca
                        </p>
                    </div>

                    <label class="flex items-start gap-2 text-sm text-red-600 font-medium">
                        <input type="checkbox" v-model="form.agreement" />
                        <span>
                            Saya menyetujui bahwa informasi yang saya berikan adalah benar dan
                            telah melakukan pembayaran sesuai jumlah yang tertera
                        </span>
                    </label>
                </div>

                <button
                    @click="submit"
                    :disabled="!canSubmit || form.processing"
                    class="btn-submit"
                >
                    Konfirmasi Pembayaran
                </button>

                <Link href="/" class="block mt-4 w-full py-3 rounded-xl font-semibold text-[#1FB5D5] bg-white border-2 border-[#1FB5D5] hover:bg-[#1FB5D5] hover:text-white transition-all duration-300 text-center">
                    Kembali ke Halaman Utama
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.section-title {
    @apply text-xl font-bold mb-4 border-b-4 border-[#1FB5D5] inline-block;
}

.card {
    @apply bg-white rounded-2xl shadow-lg p-8;
}

.label {
    @apply text-sm font-semibold mb-1 block;
}

.input {
    @apply w-full rounded-lg border border-gray-300 px-4 py-3
    focus:outline-none focus:ring-2 focus:ring-[#1FB5D5];
}

/* TIKET */
.ticket-box {
    @apply w-64 h-24 flex flex-col justify-center items-center
    rounded-xl shadow-md transition cursor-pointer;
}

.ticket-active {
    @apply bg-[#1FB5D5] text-white ring-4 ring-[#1FB5D5];
}

.ticket-inactive {
    @apply bg-[#7ED4E6] text-white hover:opacity-90;
}

.btn-orange {
    @apply w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:opacity-90;
}

.btn-submit {
    @apply w-full py-3 rounded-xl font-semibold text-white
    bg-[#1FB5D5] hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed;
}
</style>
