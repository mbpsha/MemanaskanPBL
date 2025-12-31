<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

// Heroicons
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline'

const showPassword = ref(false)
const showConfirmPassword = ref(false)

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: () => {
            alert(Object.values(form.errors).join('\n'))
        }
    })
}
</script>

<template>
    <Head title="Sign Up" />

    <div class="min-h-screen flex items-center justify-center bg-[#1e2a4a] px-4">
        <div class="w-full max-w-md">

            <!-- Title -->
            <h1 class="text-center text-white text-4xl font-bold mb-8">
                SIGN UP HERE
            </h1>

            <!-- Switch Button -->
            <div class="flex mb-6 rounded-lg overflow-hidden shadow-md">
                <Link
                    href="/login"
                    class="w-1/2 py-3 text-center bg-gray-200 text-black font-bold hover:bg-gray-300 transition"
                >
                    LOGIN
                </Link>
                <div
                    class="w-1/2 py-3 text-center bg-[#3d4f91] text-white font-bold"
                >
                    SIGN UP
                </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-5">

                <!-- Username -->
                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Masukkan Username"
                    class="w-full px-4 py-3 rounded-md bg-gray-200 focus:outline-none
                           transition focus:ring-2 focus:ring-[#3d4f91]"
                    required
                />

                <!-- Email -->
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="Masukkan Email"
                    class="w-full px-4 py-3 rounded-md bg-gray-200 focus:outline-none
                           transition focus:ring-2 focus:ring-[#3d4f91]"
                    required
                />

                <!-- Password -->
                <div class="relative">
                    <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="Masukkan Password"
                        class="w-full px-4 py-3 pr-12 rounded-md bg-gray-200 focus:outline-none
                               transition focus:ring-2 focus:ring-[#3d4f91]"
                        required
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-4 flex items-center
                               text-gray-600 hover:text-gray-800"
                        tabindex="-1"
                    >
                        <EyeSlashIcon v-if="showPassword" class="w-5 h-5" />
                        <EyeIcon v-else class="w-5 h-5" />
                    </button>
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <input
                        v-model="form.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        placeholder="Konfirmasi Password"
                        class="w-full px-4 py-3 pr-12 rounded-md bg-gray-200 focus:outline-none
                               transition focus:ring-2 focus:ring-[#3d4f91]"
                        required
                    />
                    <button
                        type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-4 flex items-center
                               text-gray-600 hover:text-gray-800"
                        tabindex="-1"
                    >
                        <EyeSlashIcon v-if="showConfirmPassword" class="w-5 h-5" />
                        <EyeIcon v-else class="w-5 h-5" />
                    </button>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full bg-[#3d4f91] text-white py-3 rounded-md font-semibold
                           hover:bg-[#34437a] transition active:scale-[0.98]"
                    :disabled="form.processing"
                >
                    SIGN UP
                </button>
            </form>

            <!-- Footer -->
            <p class="text-center text-white mt-6">
                Sudah Punya Akun?
                <Link href="/login" class="text-orange-400 font-semibold hover:underline">
                    Masuk Sekarang
                </Link>
            </p>
        </div>
    </div>
</template>
