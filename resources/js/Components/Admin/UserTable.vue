<script setup>
import { ref, computed } from 'vue'
import {
  PencilSquareIcon,
  TrashIcon,
  PlusIcon,
  MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  users: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['create', 'edit', 'delete'])

/* =====================
   SEARCH STATE
===================== */
const search = ref('')

const filteredUsers = computed(() => {
  if (!search.value) return props.users

  const keyword = search.value.toLowerCase()

  return props.users.filter(user =>
    user.name.toLowerCase().includes(keyword) ||
    user.email.toLowerCase().includes(keyword)
  )
})
</script>

<template>
  <div class="px-8 py-6 bg-white shadow-sm rounded-2xl">

    <!-- HEADER -->
    <div class="flex justify-end mb-6">
      <div class="flex items-center gap-4">

        <!-- SEARCH (DEKAT BUTTON) -->
        <div class="relative w-72">
          <MagnifyingGlassIcon
            class="absolute w-5 h-5 text-gray-400 -translate-y-1/2 left-3 top-1/2"
          />
          <input
            v-model="search"
            type="text"
            placeholder="Cari username atau email..."
            class="w-full py-2 pl-10 pr-4 text-sm border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <!-- BUTTON -->
        <button
          @click="emit('create')"
          class="flex items-center gap-2 px-4 py-2 text-white transition bg-blue-600 rounded-lg hover:bg-blue-700"
        >
          <PlusIcon class="w-5 h-5" />
          Tambahkan Pengguna
        </button>
      </div>
    </div>


    <!-- TABLE -->
    <div class="overflow-x-auto">
      <table class="w-full border-collapse min-h-[150px]">
        <thead>
          <tr class="text-sm text-gray-500 border-b">
            <th class="py-4 font-medium text-left">Username</th>
            <th class="font-medium text-left">Email</th>
            <th class="font-medium text-left">Role</th>
            <th class="font-medium text-center">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="user in filteredUsers"
            :key="user.id"
            class="transition border-b last:border-none hover:bg-gray-50"
          >
            <td class="py-5 font-medium text-gray-800">
              {{ user.name }}
            </td>

            <td class="text-gray-500">
              {{ user.email }}
            </td>

            <td>
              <span
                v-if="user.role === 'admin'"
                class="inline-block px-4 py-1 text-sm text-red-600 bg-red-100 rounded-lg"
              >
                Admin
              </span>

              <span
                v-else
                class="inline-block px-4 py-1 text-sm text-blue-600 bg-blue-100 rounded-lg"
              >
                User
              </span>
            </td>

            <td class="text-center">
              <div class="flex justify-center gap-4">
                <button
                  @click="emit('edit', user)"
                  class="text-gray-500 transition hover:text-blue-600"
                  title="Edit user"
                >
                  <PencilSquareIcon class="w-5 h-5" />
                </button>

                <button
                  @click="emit('delete', user)"
                  class="text-gray-500 transition hover:text-red-600"
                  title="Hapus user"
                >
                  <TrashIcon class="w-5 h-5" />
                </button>
              </div>
            </td>
          </tr>

          <!-- EMPTY STATE -->
          <tr v-if="filteredUsers.length === 0">
            <td
              colspan="4"
              class="py-10 text-center text-gray-400"
            >
              Data tidak ditemukan
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>
