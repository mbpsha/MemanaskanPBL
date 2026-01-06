<script setup>
import { ref, computed } from 'vue'
import { useForm, Head, Link } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    nik: '',
    address: '',
    phone: '',
    email: '',
    gender: '',
    illness: '',
    shirt_size: '',
    ticket_type: '',
    ticket_price: 0,
    payment_method: 'Transfer Bank',
    transaction_id: '',
    payment_proof: null,
    agreement: false,
})

const selectedTicket = ref(null)

const currentDate = computed(() => {
    const today = new Date()
    return today.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
})

const tickets = [
    { name: 'Tiket Basic', price: 100000 },
    { name: 'Tiket Fun Run + Support Syiar Ramadan', price: 130000 },
]

const selectTicket = (ticket) => {
    selectedTicket.value = ticket.name
    form.ticket_type = ticket.name
    form.ticket_price = ticket.price
}

const showError = ref(false)

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
    )
})

const submit = () => {
    if (!canSubmit.value) {
        showError.value = true
        alert('Harap lengkapi semua field yang wajib diisi (ditandai dengan *)')
        return
    }

    showError.value = false
    form.post('/event-registrations', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            alert('Pendaftaran berhasil! Silakan cek email untuk konfirmasi.')
        },
        onError: (errors) => {
            console.error('Submission errors:', errors)
            alert('Terjadi kesalahan. Silakan cek kembali data Anda.')
        }
    })
}
</script>

<template>
    <Head title="Form Pendaftaran Event" />

    <div class="min-h-screen bg-gradient-to-b from-[#EAF9FD] to-[#2DB7D2] py-10 px-4">
        <div class="max-w-5xl mx-auto">

            <!-- LOGO -->
            <img src="/images/event run1.png" class="h-20 mx-auto mb-8" />

            <!-- ================= IDENTITAS PESERTA ================= -->
            <h2 class="section-title">Identitas Peserta</h2>

            <div class="mb-12 card">

                <!-- ⚠️ PERHATIAN -->
                <div class="p-4 mb-8 text-sm text-white bg-red-600 rounded-lg">
                    <p class="mb-1 font-bold text-center">PERHATIAN!!!</p>
                    <ol class="list-decimal list-inside">
                        <li>Pastikan email yang diisikan benar</li>
                        <li>Ruang penyimpanan email pastikan tidak penuh</li>
                    </ol>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
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
                        <label class="label">Email <span class="text-red-600">*</span></label>
                        <input v-model="form.email" type="email" class="input" required />
                    </div>
                    <!-- Gender -->
                    <div>
                        <label class="label">Gender <span class="text-red-600">*</span></label>
                        <select v-model="form.gender" class="input" required>
                            <option disabled value="">Pilih Gender</option>
                            <option value="M">Laki-Laki</option>
                            <option value="F">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Riwayat Penyakit</label>
                        <input v-model="form.illness" class="input" placeholder="Kosongkan jika tidak ada" />
                    </div>
                    <div>
                        <label class="label">Ukuran Jersey <span class="text-red-600">*</span></label>
                        <select v-model="form.shirt_size" class="input" required>
                            <option disabled value="">Klik untuk Pilih Ukuran Jersey</option>
                            <option>XS</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                            <option>XXL</option>
                        </select>
                    </div>
                </div>

                <!-- Panduan Ukuran Jersey -->
                <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border border-[#1FB5D5]">
                    <h4 class="text-sm font-semibold mb-3 text-center text-[#1FB5D5]">Panduan Ukuran Jersey</h4>
                    <img src="/images/sizeKaos.jpeg" alt="Panduan Ukuran Jersey" class="w-full max-w-sm mx-auto rounded-lg shadow-md" />
                    <p class="mt-3 text-xs text-center text-gray-600">
                        Silakan lihat panduan ukuran di atas untuk memilih ukuran jersey yang sesuai
                    </p>
                </div>
            </div>

            <!-- ================= HALAMAN PEMBAYARAN ================= -->
            <h2 class="section-title">Halaman Pembayaran</h2>

            <div class="mb-12 card">
                <h3 class="mb-6 text-lg font-bold text-center">Jenis Tiket</h3>

                <!-- TIKET -->
                <div class="flex flex-wrap justify-center gap-6 mb-8">
                    <button
                        v-for="ticket in tickets"
                        :key="ticket.name"
                        @click="selectTicket(ticket)"
                        type="button"
                        :class="[
                            'ticket-box',
                            selectedTicket === ticket.name
                                ? 'ticket-active'
                                : 'ticket-inactive'
                        ]"
                    >
                        <div class="font-semibold text-center">{{ ticket.name }}</div>
                        <div class="text-sm text-center">
                            Rp{{ ticket.price.toLocaleString('id-ID') }},-
                        </div>
                    </button>
                </div>

                <!-- SELESAIKAN PEMBAYARAN -->
                <div class="mb-10 text-center btn-orange">
                    Selesaikan Pembayaranmu
                </div>

                <!-- DETAIL TRANSAKSI -->
                <div class="grid items-center grid-cols-1 gap-8 mb-10 md:grid-cols-2">
                    <div class="space-y-2 text-sm">
                        <p class="mb-2 font-semibold">Detail Transaksi</p>
                        <p>Payment Method : Transfer Bank</p>
                        <p>Tanggal : {{ currentDate }}</p>
                        <p>Harga Tiket : Rp{{ form.ticket_price.toLocaleString('id-ID') }}</p>
                        <div class="pt-2 font-bold border-t">
                            Total Harga : Rp{{ form.ticket_price.toLocaleString('id-ID') }}
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <p class="mb-2 font-semibold">Informasi Rekening</p>
                        <div class="p-4 rounded-lg bg-gray-50">
                            <p class="font-medium">Nama Penerima:</p>
                            <p class="mb-3 text-lg font-bold text-[#1FB5D5]"> a.n Lisak Yiha Rodliyah </p>

                            <p class="font-medium">Bank:</p>
                            <p class="mb-3 text-lg font-bold text-[#1FB5D5]"> 🏦 BRI </p>
                            <p class="font-medium">Nomor Rekening:</p>
                            <p class="text-xl font-bold text-[#1FB5D5]"> 7672 01 005378 53 1 </p>
                        </div>
                        <p class="text-xs italic text-gray-500">
                            * Mohon transfer sesuai dengan total harga yang tertera agar proses verifikasi berjalan lancar
                        </p>
                    </div>
                </div>

                <div class="mb-10 text-center btn-orange">
                    Konfirmasi Pembayaranmu
                </div>

                <div class="mb-6 space-y-4">
                    <div>
                        <label class="label">ID Transaksional (Opsional)</label>
                        <input v-model="form.transaction_id" class="input" placeholder="Nomor referensi pembayaran (opsional)" />
                        <p class="mt-1 text-xs text-gray-500">
                            Nomor referensi membantu verifikasi pembayaran lebih cepat
                        </p>
                    </div>

                    <div>
                        <label class="label">Bukti Pembayaran <span class="text-red-600">*</span></label>
                        <input
                            type="file"
                            class="input"
                            accept="image/png,image/jpeg,image/jpg"
                            required
                            @change="e => form.payment_proof = e.target.files[0]"
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            • Format JPG, PNG, JPEG <br>
                            • Maksimal ukuran: 5 Mb <br>
                            • Pastikan bukti pembayaran jelas dan dapat dibaca
                        </p>
                    </div>

                    <label class="flex items-start gap-2 text-sm font-medium text-red-600">
                        <input type="checkbox" v-model="form.agreement" required class="mt-1" />
                        <span>
                            <span class="text-red-600">*</span> Saya menyetujui bahwa informasi yang saya berikan adalah benar dan
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
