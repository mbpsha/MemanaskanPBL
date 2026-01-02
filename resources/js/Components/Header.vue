<template>
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm transition-shadow duration-300 hover:shadow-md">
        <nav class="container mx-auto px-4 md:px-8 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <Link href="/">
                    <img src="/images/logoRun.png" alt="Ramadan Vaganza" class="h-10 md:h-14 cursor-pointer transform hover:scale-105 transition-transform duration-300" />
                </Link>
            </div>
            
            <!-- Mobile Menu Button -->
            <button @click="showMobileMenu = !showMobileMenu" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                <svg v-if="!showMobileMenu" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg v-else class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Guest Buttons - Desktop -->
            <div v-if="!$page.props.auth.user" class="hidden md:flex gap-3">
                <Link href="/login" class="bg-orange-500 hover:bg-orange-600 active:scale-95 text-white px-6 md:px-8 py-2.5 rounded-full font-semibold transition-all duration-300 inline-block shadow-md hover:shadow-lg">
                    Masuk
                </Link>
                <Link href="/register" class="bg-white hover:bg-orange-50 active:scale-95 text-orange-500 border-2 border-orange-500 px-6 md:px-8 py-2.5 rounded-full font-semibold transition-all duration-300 inline-block">
                    Daftar
                </Link>
            </div>

            <!-- Authenticated User Profile - Desktop -->
            <div v-else class="hidden md:block relative">
                <button @click="showDropdown = !showDropdown" class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white hover:bg-blue-700 active:scale-95 transition-all duration-300 shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div v-if="showDropdown" class="absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-2xl py-2 border border-gray-100 animate-fade-in overflow-hidden">
                    <!-- User Info Header -->
                    <div class="px-5 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="py-2">
                        <Link href="/profile" class="flex items-center gap-3 px-5 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">Profile Saya</span>
                        </Link>
                        <Link href="/logout" method="post" as="button" class="w-full flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200 group">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500 group-hover:text-red-700 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            <span class="font-medium">Keluar</span>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Mobile Menu -->
        <div v-if="showMobileMenu" class="md:hidden bg-white border-t border-gray-200 animate-slide-down">
            <div class="container mx-auto px-4 py-4">
                <div v-if="!$page.props.auth.user" class="flex flex-col gap-3">
                    <Link href="/login" class="bg-orange-500 hover:bg-orange-600 active:scale-95 text-white px-8 py-2.5 rounded-full font-semibold transition-all duration-300 text-center shadow-md">
                        Masuk
                    </Link>
                    <Link href="/register" class="bg-white hover:bg-orange-50 active:scale-95 text-orange-500 border-2 border-orange-500 px-8 py-2.5 rounded-full font-semibold transition-all duration-300 text-center">
                        Daftar
                    </Link>
                </div>
                <div v-else class="space-y-2">
                    <!-- Mobile User Info Header -->
                    <div class="px-5 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-gray-200 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Items -->
                    <Link href="/profile" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-all duration-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Profile Saya</span>
                    </Link>
                    <Link href="/logout" method="post" as="button" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-lg transition-all duration-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500 group-hover:text-red-700 transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span class="font-medium">Keluar</span>
                    </Link>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const showDropdown = ref(false)
const showMobileMenu = ref(false)
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-down {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
    }
}

.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}

.animate-slide-down {
    animation: slide-down 0.3s ease-out;
}
</style>
