<template>
    <section class="relative pt-32 pb-12 px-8">
        <div class="relative w-full overflow-hidden transition-all duration-500 transform hover:scale-[1.02] group">
            <img 
                src="/images/homepage/herosection.png" 
                alt="Hero Background"
                class="w-full h-auto rounded-2xl shadow-2xl hover:shadow-3xl transition-all duration-700 group-hover:scale-105"
            />
            
            <!-- Overlay gradient for better interactivity -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
        </div>
    </section>
</template>

<script setup>
// Hero section component
</script>

<style scoped>
/* Enhanced shadow effect */
.shadow-3xl {
    box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.4);
}
</style>
