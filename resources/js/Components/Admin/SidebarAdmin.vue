<script setup>
import { Link } from "@inertiajs/vue3";
import {
    UsersIcon,
    CreditCardIcon,
    QrCodeIcon,
    ArrowRightOnRectangleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close"]);
</script>

<template>
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-40 flex flex-col w-64 px-6 py-6 sm:px-8 sm:py-10 bg-white border-r transition-transform duration-300 ease-in-out',
            open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        ]"
    >
        <!-- Header with close button for mobile -->
        <div class="flex items-center justify-between mb-8 lg:mb-12">
            <h1 class="text-2xl font-bold text-indigo-600">Admin</h1>
            <button
                @click="emit('close')"
                class="p-1 text-gray-500 hover:bg-gray-100 rounded-lg transition lg:hidden"
            >
                <XMarkIcon class="w-6 h-6" />
            </button>
        </div>

        <nav class="space-y-4 sm:space-y-6">
            <Link
                href="/admin/users"
                @click="emit('close')"
                class="flex items-center gap-3 p-2 rounded-lg transition hover:bg-indigo-50"
                :class="
                    $page.url.startsWith('/admin/users')
                        ? 'text-indigo-600 font-medium bg-indigo-50'
                        : 'text-gray-400'
                "
            >
                <UsersIcon class="w-5 h-5" />
                <span class="text-sm sm:text-base">Pengguna</span>
            </Link>

            <Link
                href="/admin/payments"
                @click="emit('close')"
                class="flex items-center gap-3 p-2 rounded-lg transition hover:bg-indigo-50"
                :class="
                    $page.url.startsWith('/admin/payments')
                        ? 'text-indigo-600 font-medium bg-indigo-50'
                        : 'text-gray-400'
                "
            >
                <CreditCardIcon class="w-5 h-5" />
                <span class="text-sm sm:text-base">Verifikasi Pembayaran</span>
            </Link>

            <Link
                href="/admin/scan"
                @click="emit('close')"
                class="flex items-center gap-3 p-2 rounded-lg transition hover:bg-indigo-50"
                :class="
                    $page.url.startsWith('/admin/scan')
                        ? 'text-indigo-600 font-medium bg-indigo-50'
                        : 'text-gray-400'
                "
            >
                <QrCodeIcon class="w-5 h-5" />
                <span class="text-sm sm:text-base">Scan Barcode</span>
            </Link>
        </nav>

        <Link
            href="/logout"
            method="post"
            as="button"
            @click="emit('close')"
            class="flex items-center gap-2 px-4 py-2 mt-auto text-red-500 transition rounded-lg hover:bg-red-50 w-full text-left text-sm sm:text-base"
        >
            <ArrowRightOnRectangleIcon class="w-5 h-5" />
            Log-out
        </Link>
    </aside>
</template>
