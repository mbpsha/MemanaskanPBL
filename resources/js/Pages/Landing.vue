<template>
    <div class="min-h-screen bg-white">
        <Header />
        <HeroSection />
        <EventInfo />
        <HowToRegister />
        <SponsorSection />
        <Footer />
    </div>
</template>

<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import HeroSection from '@/Components/Landing/HeroSection.vue';
import EventInfo from '@/Components/Landing/EventInfo.vue';
import HowToRegister from '@/Components/Landing/HowToRegister.vue';
import SponsorSection from '@/Components/Landing/SponsorSection.vue';
</script>
