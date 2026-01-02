<template>
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
        <Header />
        
        <div class="container mx-auto px-4 md:px-8 pt-24 md:pt-32 pb-12 md:pb-16">
            <!-- Navigation Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mb-6 md:mb-8">
                <Link href="/" class="bg-orange-500 hover:bg-orange-600 active:scale-95 text-white px-6 md:px-8 py-2.5 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </Link>
                <Link href="/logout" method="post" as="button" class="bg-red-600 hover:bg-red-700 active:scale-95 text-white px-6 md:px-8 py-2.5 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Logout
                </Link>
            </div>

            <!-- Profile Card -->
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
                    <!-- Header Background -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-32 md:h-40 relative">
                        <div class="absolute inset-0 bg-black/10"></div>
                    </div>

                    <!-- Profile Content -->
                    <div class="relative px-4 md:px-8 pb-8">
                        <!-- Profile Icon -->
                        <div class="flex justify-center -mt-16 md:-mt-20 mb-6">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-2xl border-4 border-white transform hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-16 md:h-16 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="text-center mb-8">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">{{ user.name }}</h1>
                            <p class="text-base md:text-lg text-gray-600 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                {{ user.email }}
                            </p>
                        </div>

                        <!-- Event Registration Status -->
                        <div class="border-t border-gray-200 pt-6 md:pt-8">
                            <div class="flex items-center gap-2 mb-4 md:mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-orange-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800">Status Pendaftaran Event</h2>
                            </div>
                            
                            <div v-if="hasRegistrations" class="space-y-3">
                                <div v-for="registration in eventRegistrations" :key="registration.id" class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4 md:p-5 transform hover:scale-[1.02] hover:shadow-lg transition-all duration-300">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-900 text-base md:text-lg mb-1">{{ registration.event?.name || 'Ramadan Vaganza Fun Run 2026' }}</p>
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                                </svg>
                                                Terdaftar pada: {{ formatDate(registration.created_at) }}
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-center sm:justify-end">
                                            <div class="bg-green-600 rounded-full p-2 shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-6 md:p-8 text-center transform hover:scale-[1.02] transition-all duration-300">
                                <div class="inline-block p-4 bg-white rounded-full shadow-md mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 md:w-16 md:h-16 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </div>
                                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Belum Terdaftar Event</h3>
                                <p class="text-sm md:text-base text-gray-600 mb-6">Anda belum mendaftar event apapun. Daftar sekarang dan ikuti keseruan eventnya!</p>
                                <Link href="/event-registrations" class="inline-block bg-orange-500 hover:bg-orange-600 active:scale-95 text-white px-8 py-3 rounded-full font-bold transition-all duration-300 shadow-lg hover:shadow-xl">
                                    Daftar Sekarang
                                </Link>
                            </div>
                        </div>
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
