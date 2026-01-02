<script setup>
import { computed } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    status: String,
})

const form = useForm({})

const submit = () => {
    form.post('/email/verification-notification')
}

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent'
)
</script>

<template>
    <GuestLayout>
        <Head title="Verifikasi Email" />

        <!-- Logo Row -->
        <div class="flex justify-center items-center gap-4 mb-6">
            <img src="/images/logo pijar1.png" alt="Pijar" class="h-14" />
            <img src="/images/logo km.png" alt="KM" class="h-14" />
            <img src="/images/event run1.png" alt="Event Run" class="h-14" />
        </div>

        <!-- Title -->
        <h2 class="text-center text-2xl font-bold text-gray-800 mb-2">
            Verifikasi Email Kamu
        </h2>

        <!-- Description -->
        <p class="text-center text-gray-600 text-sm mb-6">
            Kami telah mengirimkan email verifikasi ke alamat email kamu.
            Silakan cek inbox atau folder spam, lalu klik link verifikasi
            untuk mulai menggunakan Event Run.
        </p>

        <!-- Alert -->
        <div
            v-if="verificationLinkSent"
            class="mb-4 rounded-lg bg-orange-100 text-orange-700 px-4 py-3 text-sm text-center"
        >
            Email verifikasi baru berhasil dikirim.
        </div>

        <!-- Actions -->
        <form @submit.prevent="submit" class="space-y-3">
            <!-- Resend -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-[#F58B1D] hover:bg-[#e07c14] text-white py-3 rounded-md font-semibold transition disabled:opacity-50"
            >
                Kirim Ulang Email Verifikasi
            </button>

            <!-- Open Gmail -->
            <a
                href="https://mail.google.com"
                target="_blank"
                class="block w-full text-center bg-[#F58B1D] hover:bg-[#e07c14] text-white py-3 rounded-md font-semibold transition"
            >
                Buka Gmail
            </a>

            <!-- Logout -->
            <Link
                href="/logout"
                method="post"
                as="button"
                class="w-full bg-[#F58B1D] hover:bg-[#e07c14] text-white py-3 rounded-md font-semibold transition"
            >
                Keluar
            </Link>
        </form>
    </GuestLayout>
</template>
