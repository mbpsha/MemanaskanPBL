<template>
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm">
        <nav class="container mx-auto px-8 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <Link href="/">
                    <img src="/images/homepage/logoRun.png" alt="Ramadan Vaganza" class="h-14 cursor-pointer" />
                </Link>
            </div>
            
            <div class="flex gap-3">
                <Link href="/login" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-2.5 rounded-full font-semibold transition inline-block">
                    Masuk
                </Link>
                <button class="bg-white hover:bg-orange-50 text-orange-500 border-2 border-orange-500 px-8 py-2.5 rounded-full font-semibold transition">
                    Daftar
                </button>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
