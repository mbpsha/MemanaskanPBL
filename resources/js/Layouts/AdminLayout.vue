<script setup>
import { ref } from "vue";
import SidebarAdmin from "@/Components/Admin/SidebarAdmin.vue";
import { Bars3Icon } from "@heroicons/vue/24/outline";

const sidebarOpen = ref(false);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
    sidebarOpen.value = false;
};
</script>

<template>
    <div class="flex min-h-screen bg-sky-100">
        <!-- Sidebar -->
        <SidebarAdmin :open="sidebarOpen" @close="closeSidebar" />

        <!-- Overlay for mobile -->
        <div
            v-if="sidebarOpen"
            @click="closeSidebar"
            class="fixed inset-0 z-30 bg-black/50 lg:hidden"
        ></div>

        <!-- Main Content -->
        <main class="w-full min-h-screen transition-all duration-300 lg:ml-64">
            <!-- Mobile Header with Hamburger -->
            <div
                class="sticky top-0 z-20 flex items-center justify-between px-4 py-4 bg-white shadow-sm lg:hidden"
            >
                <h1 class="text-xl font-bold text-indigo-600">Admin Panel</h1>
                <button
                    @click="toggleSidebar"
                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition"
                >
                    <Bars3Icon class="w-6 h-6" />
                </button>
            </div>

            <!-- Page Content -->
            <div class="px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
                <slot />
            </div>
        </main>
    </div>
</template>
