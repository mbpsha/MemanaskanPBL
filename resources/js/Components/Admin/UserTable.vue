<script setup>
import { ref, computed } from "vue";
import {
    PencilSquareIcon,
    TrashIcon,
    PlusIcon,
    MagnifyingGlassIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["create", "edit", "delete"]);

/* =====================
   SEARCH STATE
===================== */
const search = ref("");

const filteredUsers = computed(() => {
    if (!search.value) return props.users;

    const keyword = search.value.toLowerCase();

    return props.users.filter(
        (user) =>
            user.name.toLowerCase().includes(keyword) ||
            user.email.toLowerCase().includes(keyword)
    );
});
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm px-8 py-6">
        <!-- HEADER -->
        <div class="flex justify-end mb-6">
            <div class="flex items-center gap-4">
                <!-- SEARCH (DEKAT BUTTON) -->
                <div class="relative w-72">
                    <MagnifyingGlassIcon
                        class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    />
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari username atau email..."
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    />
                </div>

                <!-- BUTTON -->
                <button
                    @click="emit('create')"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
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
                    <tr class="border-b text-sm text-gray-500">
                        <th class="py-4 text-left font-medium">Username</th>
                        <th class="text-left font-medium">Email</th>
                        <th class="text-left font-medium">Role</th>
                        <th class="text-center font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="user in filteredUsers"
                        :key="user.id"
                        class="border-b last:border-none hover:bg-gray-50 transition"
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
                                    class="text-gray-500 hover:text-blue-600 transition"
                                    title="Edit user"
                                >
                                    <PencilSquareIcon class="w-5 h-5" />
                                </button>

                                <button
                                    @click="emit('delete', user)"
                                    class="text-gray-500 hover:text-red-600 transition"
                                    title="Hapus user"
                                >
                                    <TrashIcon class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- EMPTY STATE -->
                    <tr v-if="filteredUsers.length === 0">
                        <td colspan="4" class="py-10 text-center text-gray-400">
                            Data tidak ditemukan
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
