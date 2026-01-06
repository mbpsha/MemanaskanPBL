<template>
    <div class="min-h-screen bg-white">
        <!-- Success/Error Notification Popup -->
        <div
            v-if="$page.props.flash.success"
            class="fixed z-50 max-w-md px-6 py-4 text-white bg-green-500 rounded-lg shadow-lg top-4 right-4 animate-slide-in"
        >
            <div class="flex items-start gap-3">
                <svg class="flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">Berhasil!</p>
                    <p class="mt-1 text-sm">{{ $page.props.flash.success }}</p>
                </div>
                <button @click="closeNotification" class="text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div
            v-if="$page.props.flash.error"
            class="fixed z-50 max-w-md px-6 py-4 text-white bg-red-500 rounded-lg shadow-lg top-4 right-4 animate-slide-in"
        >
            <div class="flex items-start gap-3">
                <svg class="flex-shrink-0 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold">Gagal!</p>
                    <p class="mt-1 text-sm">{{ $page.props.flash.error }}</p>
                </div>
                <button @click="closeNotification" class="text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <Header />
        <HeroSection />
        <EventInfo />
        <HowToRegister />
        <SponsorSection />
        <Footer />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import HeroSection from '@/Components/Landing/HeroSection.vue';
import EventInfo from '@/Components/Landing/EventInfo.vue';
import HowToRegister from '@/Components/Landing/HowToRegister.vue';
// import SponsorSection from '@/Components/Landing/SponsorSection.vue';

const page = usePage();

// Auto-close notification after 8 seconds
onMounted(() => {
    if (page.props.flash.success || page.props.flash.error) {
        setTimeout(() => {
            closeNotification();
        }, 8000);
    }
});

const closeNotification = () => {
    page.props.flash.success = null;
    page.props.flash.error = null;
};
</script>

<style scoped>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}
</style>
