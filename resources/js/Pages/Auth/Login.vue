<template>
    <div class="min-h-screen bg-[#1F2944] flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold text-white text-center mb-8">LOGIN HERE</h1>
            
            <!-- Tab Navigation -->
            <div class="grid grid-cols-2 gap-0 mb-6">
                <button class="bg-[#3d4f91] text-white py-3 font-semibold">
                    LOGIN
                </button>
                <Link href="/register" class="bg-gray-200 text-gray-800 py-3 font-semibold hover:bg-gray-300 transition text-center">
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
                    <p v-if="form.errors.email" class="text-red-400 text-sm mt-1">{{ form.errors.email }}</p>
                </div>
                
                <div>
                    <input 
                        type="password" 
                        v-model="form.password"
                        placeholder="Masukkan Password" 
                        class="w-full px-4 py-3 rounded-lg bg-gray-200 focus:outline-none focus:ring-2 focus:ring-[#3d4f91]"
                        :class="{ 'ring-2 ring-red-500': form.errors.password }"
                        required
                    />
                    <p v-if="form.errors.password" class="text-red-400 text-sm mt-1">{{ form.errors.password }}</p>
                </div>
                
                <button 
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-[#3d4f91] hover:bg-[#34437a] text-white py-3 rounded-lg font-semibold transition disabled:opacity-50"
                >
                    {{ form.processing ? 'Loading...' : 'LOGIN' }}
                </button>
            </form>
            
            <p class="text-center text-white mt-6">
                Belum Punya Akun? 
                <Link href="/register" class="text-orange-400 font-semibold hover:underline">
                    Daftar Sekarang
                </Link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

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
