<script setup>
import { Link } from '@inertiajs/vue3'
import { UsersIcon, CreditCardIcon, ArrowRightOnRectangleIcon } from '@heroicons/vue/24/outline'
</script>

<template>
  <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r px-8 py-10 flex flex-col">
    <h1 class="text-2xl font-bold text-indigo-600 mb-12">Admin</h1>

    <nav class="space-y-6">
      <Link
        href="/admin/users"
        class="flex items-center gap-3"
        :class="$page.url.startsWith('/admin/users')
          ? 'text-indigo-600 font-medium'
          : 'text-gray-500'"
      >
        <UsersIcon class="w-5 h-5" />
        Pengguna
      </Link>

      <Link
        href="/admin/payments"
        class="flex items-center gap-3"
        :class="$page.url.startsWith('/admin/payments')
          ? 'text-indigo-600 font-medium'
          : 'text-gray-400'"
      >
        <CreditCardIcon class="w-5 h-5" />
        Verifikasi Pembayaran
      </Link>
    </nav>

    <Link
      href="/logout"
      method="post"
      as="button"
      class="mt-auto flex items-center gap-2 text-red-500 hover:bg-red-50 px-4 py-2 rounded-lg transition"
    >
      <ArrowRightOnRectangleIcon class="w-5 h-5" />
      Log-out
    </Link>
  </aside>
</template>
