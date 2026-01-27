<script setup>
import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["edit", "delete"]);
</script>

<template>
    <div
        class="px-4 py-4 sm:px-6 sm:py-6 lg:px-8 bg-white shadow-sm rounded-2xl"
    >
        <!-- TABLE -->
        <div class="overflow-x-auto -mx-4 sm:mx-0">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr
                                class="text-xs sm:text-sm text-gray-500 border-b"
                            >
                                <th
                                    class="py-3 sm:py-4 px-3 sm:px-4 font-medium text-left whitespace-nowrap"
                                >
                                    Username
                                </th>
                                <th
                                    class="py-3 sm:py-4 px-3 sm:px-4 font-medium text-left whitespace-nowrap"
                                >
                                    Email
                                </th>
                                <th
                                    class="py-3 sm:py-4 px-3 sm:px-4 font-medium text-left whitespace-nowrap"
                                >
                                    Role
                                </th>
                                <th
                                    class="py-3 sm:py-4 px-3 sm:px-4 font-medium text-center whitespace-nowrap"
                                >
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="transition border-b last:border-none hover:bg-gray-50"
                            >
                                <td
                                    class="py-4 sm:py-5 px-3 sm:px-4 font-medium text-gray-800 text-xs sm:text-sm"
                                >
                                    {{ user.name }}
                                </td>

                                <td
                                    class="py-4 sm:py-5 px-3 sm:px-4 text-gray-500 text-xs sm:text-sm"
                                >
                                    {{ user.email }}
                                </td>

                                <td class="py-4 sm:py-5 px-3 sm:px-4">
                                    <span
                                        v-if="user.role === 'admin'"
                                        class="inline-block px-2 sm:px-4 py-1 text-xs sm:text-sm text-red-600 bg-red-100 rounded-lg whitespace-nowrap"
                                    >
                                        Admin
                                    </span>
                                    <span
                                        v-else
                                        class="inline-block px-2 sm:px-4 py-1 text-xs sm:text-sm text-blue-600 bg-blue-100 rounded-lg whitespace-nowrap"
                                    >
                                        User
                                    </span>
                                </td>

                                <td
                                    class="py-4 sm:py-5 px-3 sm:px-4 text-center"
                                >
                                    <div
                                        class="flex justify-center gap-2 sm:gap-4"
                                    >
                                        <button
                                            @click="emit('edit', user)"
                                            class="text-gray-500 transition hover:text-blue-600"
                                            title="Edit user"
                                        >
                                            <PencilSquareIcon
                                                class="w-4 h-4 sm:w-5 sm:h-5"
                                            />
                                        </button>

                                        <button
                                            @click="emit('delete', user)"
                                            class="text-gray-500 transition hover:text-red-600"
                                            title="Hapus user"
                                        >
                                            <TrashIcon
                                                class="w-4 h-4 sm:w-5 sm:h-5"
                                            />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- EMPTY STATE -->
                            <tr v-if="users.length === 0">
                                <td
                                    colspan="4"
                                    class="py-8 sm:py-10 text-center text-gray-400 text-xs sm:text-sm"
                                >
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
