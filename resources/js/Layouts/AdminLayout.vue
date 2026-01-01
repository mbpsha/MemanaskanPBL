<script setup>
import SidebarAdmin from '@/Components/Admin/SidebarAdmin.vue'
</script>

<template>
  <div class="flex min-h-screen bg-sky-100">
    <SidebarAdmin />
    <main class="ml-64 w-full px-10 py-8">
      <slot />
    </main>
  </div>
</template>
