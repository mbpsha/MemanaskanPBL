<template>
    <div class="min-h-screen bg-[#1F2944] flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <h1 class="mb-8 text-3xl font-bold text-center text-white">LOGIN HERE</h1>

            <!-- Tab Navigation -->
            <div class="grid grid-cols-2 gap-0 mb-6">
                <button class="bg-[#3d4f91] text-white py-3 font-semibold">
                    LOGIN
                </button>
                <Link href="/register" class="py-3 font-semibold text-center text-gray-800 transition bg-gray-200 hover:bg-gray-300">
                    SIGN UP
                </Link>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <input
                        type="text"
                        v-model="form.email"
                        placeholder="Masukkan Email atau Username"
                        class="w-full px-4 py-3 rounded-lg bg-gray-200 focus:outline-none focus:ring-2 focus:ring-[#3d4f91]"
                        :class="{ 'ring-2 ring-red-500': form.errors.email }"
                        required
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-400">{{ form.errors.email }}</p>
                </div>

                <div class="relative">
                    <input
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        placeholder="Masukkan Password"
                        class="w-full px-4 py-3 pr-12 rounded-lg bg-gray-200 focus:outline-none focus:ring-2 focus:ring-[#3d4f91]"
                        :class="{ 'ring-2 ring-red-500': form.errors.password }"
                        required
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute text-gray-600 -translate-y-1/2 right-3 top-1/2 hover:text-gray-800 focus:outline-none"
                    >
                        <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-400">{{ form.errors.password }}</p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-[#3d4f91] hover:bg-[#34437a] text-white py-3 rounded-lg font-semibold transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Loading...' : 'LOGIN' }}
                </button>
            </form>

            <p class="mt-6 text-center text-white">
                Belum Punya Akun?
                <Link href="/register" class="font-semibold text-orange-400 hover:underline">
                    Daftar Sekarang
                </Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const showPassword = ref(false)

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const handleLogin = () => {
    form.post('/login', {
        onSuccess: () => {
            form.reset('password')
        },
        onError: () => {
            form.reset('password')
        }
    })
}
</script>
