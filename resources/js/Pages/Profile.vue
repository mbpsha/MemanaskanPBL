<template>
    <div class="min-h-screen bg-white">
        <Header />
        
        <div class="container mx-auto px-8 pt-32 pb-16">
            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center mb-8">
                <Link href="/" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2.5 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </Link>
                <Link href="/logout" method="post" as="button" class="bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-lg font-semibold transition">
                    Logout
                </Link>
            </div>

            <!-- Profile Card -->
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
                <!-- Profile Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-32 h-32 rounded-full bg-blue-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                </div>

                <!-- User Info -->
                <div class="space-y-4 mb-8">
                    <div class="bg-gray-100 rounded-lg p-4 text-center">
                        <p class="text-xl font-semibold text-gray-800">{{ user.name }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-4 text-center">
                        <p class="text-lg text-gray-600">{{ user.email }}</p>
                    </div>
                </div>

                <!-- Event Registration Status -->
                <div class="border-t pt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Status Pendaftaran Event</h2>
                    
                    <div v-if="hasRegistrations" class="space-y-3">
                        <div v-for="registration in eventRegistrations" :key="registration.id" class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ registration.event?.name || 'Ramadan Vaganza Fun Run 2026' }}</p>
                                    <p class="text-sm text-gray-600">Terdaftar pada: {{ formatDate(registration.created_at) }}</p>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-green-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-else class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-400 mx-auto mb-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <p class="text-gray-600 font-semibold mb-2">Belum Terdaftar Event</p>
                        <p class="text-sm text-gray-500 mb-4">Anda belum mendaftar event apapun</p>
                        <Link href="/event-registrations" class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-full font-semibold transition">
                            Daftar Sekarang
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Header from '@/Components/Header.vue'
import Footer from '@/Components/Footer.vue'

const props = defineProps({
    user: Object,
    eventRegistrations: Array,
    hasRegistrations: Boolean
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}
</script>
