<script setup>
import { Link } from "@inertiajs/vue3";
import {
    UsersIcon,
    CreditCardIcon,
    QrCodeIcon,
    ArrowRightOnRectangleIcon,
} from "@heroicons/vue/24/outline";
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 flex flex-col w-64 px-8 py-10 bg-white border-r"
    >
        <h1 class="mb-12 text-2xl font-bold text-indigo-600">Admin</h1>

        <nav class="space-y-6">
            <Link
                href="/admin/users"
                class="flex items-center gap-3"
                :class="
                    $page.url.startsWith('/admin/users')
                        ? 'text-indigo-600 font-medium'
                        : 'text-gray-500'
                "
            >
                <UsersIcon class="w-5 h-5" />
                Pengguna
            </Link>

            <Link
                href="/admin/payments"
                class="flex items-center gap-3"
                :class="
                    $page.url.startsWith('/admin/payments')
                        ? 'text-indigo-600 font-medium'
                        : 'text-gray-400'
                "
            >
                <CreditCardIcon class="w-5 h-5" />
                Verifikasi Pembayaran
            </Link>

            <Link
                href="/admin/scan"
                class="flex items-center gap-3"
                :class="
                    $page.url.startsWith('/admin/scan')
                        ? 'text-indigo-600 font-medium'
                        : 'text-gray-500'
                "
            >
                <QrCodeIcon class="w-5 h-5" />
                Scan Barcode
            </Link>
        </nav>

        <Link
            href="/logout"
            method="post"
            as="button"
            class="flex items-center gap-2 px-4 py-2 mt-auto text-red-500 transition rounded-lg hover:bg-red-50"
        >
            <ArrowRightOnRectangleIcon class="w-5 h-5" />
            Log-out
        </Link>
    </aside>
</template>
