<script setup>
import { ref, computed } from "vue";
import { useForm, Head, Link } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    nik: "",
    address: "",
    phone: "",
    email: "",
    illness: "",
    gender: "",
    shirt_size: "",
    ticket_type: "",
    ticket_price: 0,
    transaction_id: "",
    payment_proof: null,
    agreement: false,
});

const selectedTicket = ref(null);

const tickets = [
    { name: "Tiket Basic", price: 100000 },
    { name: "Tiket Fun Run + Support Syiar Ramadan", price: 130000 },
];

const selectTicket = (ticket) => {
    selectedTicket.value = ticket.name;
    form.ticket_type = ticket.name;
    form.ticket_price = ticket.price;
};

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
    if (!canSubmit.value) return;

    form.post("/event/register", {
        forceFormData: true,
        preserveScroll: true,
    });
};
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

            <div class="mb-12 card">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="label">Nama Lengkap</label>
                        <input v-model="form.name" class="input" />
                    </div>
                    <div>
                        <label class="label">NIK</label>
                        <input v-model="form.nik" class="input" />
                    </div>
                    <div>
                        <label class="label">Alamat</label>
                        <input v-model="form.address" class="input" />
                    </div>
                    <div>
                        <label class="label">Nomor HP</label>
                        <input v-model="form.phone" class="input" />
                    </div>
                    <div>
                        <label class="label">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="input"
                        />
                    </div>
                    <div>
                        <label class="label">Riwayat Penyakit</label>
                        <input v-model="form.illness" class="input" />
                    </div>
                    <!-- Gender -->
                    <div>
                        <label class="label">Gender</label>
                        <select v-model="form.gender" class="input">
                            <option disabled value="">Pilih Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <!-- Ukuran Jersey -->
                    <div>
                        <label class="label">Ukuran Jersey</label>
                        <select v-model="form.shirt_size" class="input">
                            <option disabled value="">
                                Pilih Ukuran Jersey
                            </option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ================= HALAMAN PEMBAYARAN ================= -->
            <h2 class="section-title">Halaman Pembayaran</h2>

            <div class="mb-12 card">
                <h3 class="mb-6 text-lg font-bold text-center">Jenis Ticket</h3>

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
                <div class="mb-10 text-center btn-orange">
                    Selesaikan Pembayaranmu
                </div>

                <!-- DETAIL TRANSAKSI -->
                <div
                    class="grid items-center grid-cols-1 gap-8 mb-10 md:grid-cols-2"
                >
                    <div class="space-y-2 text-sm">
                        <p class="mb-2 font-semibold">Detail Transaksi</p>
                        <p>Payment Method : QRIS</p>
                        <p>Tanggal : 2 Juli 2025</p>
                        <p>
                            Harga Tiket : Rp{{
                                form.ticket_price.toLocaleString("id-ID")
                            }}
                        </p>
                        <div class="pt-2 font-bold border-t">
                            Total Harga : Rp{{
                                form.ticket_price.toLocaleString("id-ID")
                            }}
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <img src="/images/qris.png" class="w-48" />
                    </div>
                </div>

                <!-- ⚠️ PERHATIAN -->
                <div class="p-4 mb-8 text-sm text-white bg-red-600 rounded-lg">
                    <p class="mb-1 font-bold text-center">PERHATIAN!!!</p>
                    <ol class="list-decimal list-inside">
                        <li>Pastikan email yang diisikan benar</li>
                        <li>Ruang penyimpanan email pastikan tidak penuh</li>
                    </ol>
                </div>

                <!-- ================= KONFIRMASI PEMBAYARANMU ================= -->
                <div class="mb-10 text-center btn-orange">
                    Konfirmasi Pembayaranmu
                </div>

                <div class="mb-6 space-y-4">
                    <div>
                        <label class="label">ID Transaksional (Opsional)</label>
                        <input v-model="form.transaction_id" class="input" />
                        <p class="mt-1 text-xs text-gray-500">
                            Nomor referensi membantu verifikasi pembayaran lebih
                            cepat
                        </p>
                    </div>

                    <div>
                        <label class="label">Bukti Pembayaran</label>
                        <input
                            type="file"
                            class="input"
                            accept="image/png,image/jpeg"
                            @change="
                                (e) => (form.payment_proof = e.target.files[0])
                            "
                        />
                        <p class="mt-1 text-xs text-gray-500">
                            • Format JPG, PNG, JPEG <br />
                            • Maksimal ukuran: 5 Mb <br />
                            • Pastikan bukti pembayaran jelas dan dapat dibaca
                        </p>
                    </div>

                    <label
                        class="flex items-start gap-2 text-sm font-medium text-red-600"
                    >
                        <input type="checkbox" v-model="form.agreement" />
                        <span>
                            Saya menyetujui bahwa informasi yang saya berikan
                            adalah benar dan telah melakukan pembayaran sesuai
                            jumlah yang tertera
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
                <Link href="/" class="btn-back">
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

.btn-back {
    @apply w-full py-3 rounded-xl font-semibold text-center
    bg-orange-400 text-white
    hover:bg-orange-600
    transition
    mt-4;
}
</style>
