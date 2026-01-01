<template>
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm">
        <nav class="container mx-auto px-8 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <Link href="/">
                    <img src="/images/logoRun.png" alt="Ramadan Vaganza" class="h-14 cursor-pointer" />
                </Link>
            </div>
            
            <!-- Guest Buttons -->
            <div v-if="!$page.props.auth.user" class="flex gap-3">
                <Link href="/login" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2.5 rounded-full font-semibold transition inline-block">
                    Masuk
                </Link>
                <Link href="/register" class="bg-white hover:bg-orange-50 text-orange-500 border-2 border-orange-500 px-8 py-2.5 rounded-full font-semibold transition inline-block">
                    Daftar
                </Link>
            </div>

            <!-- Authenticated User Profile -->
            <div v-else class="relative">
                <button @click="showDropdown = !showDropdown" class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div v-if="showDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200">
                    <div class="px-4 py-2 border-b border-gray-200">
                        <p class="text-sm font-semibold text-gray-800">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                    </div>
                    <Link href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                        Profile
                    </Link>
                    <Link href="/logout" method="post" as="button" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition">
                        Logout
                    </Link>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const showDropdown = ref(false)
</script>
