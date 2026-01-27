<script setup>
import { ref, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import UserTable from "@/Components/Admin/UserTable.vue";

const props = defineProps({
    users: Object,
    filters: Object,
});

/* =========================
   SEARCH
========================= */
const search = ref(props.filters?.search ?? "");

watch(search, (value) => {
    router.get(
        "/admin/users",
        { search: value },
        {
            preserveState: true,
            replace: true,
        }
    );
});

/* =========================
   CREATE & EDIT MODAL
========================= */
const showModal = ref(false);
const isEdit = ref(false);

const form = useForm({
    id: null,
    name: "",
    email: "",
    password: "",
    role: "user",
});

const openCreate = () => {
    isEdit.value = false;
    form.reset();
    showModal.value = true;
};

const openEdit = (user) => {
    isEdit.value = true;
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.role = user.role;
    form.password = "";
    showModal.value = true;
};

const submit = () => {
    isEdit.value
        ? form.put(`/admin/users/${form.id}`, {
              preserveScroll: true,
              onSuccess: () => (showModal.value = false),
          })
        : form.post("/admin/users", {
              preserveScroll: true,
              onSuccess: () => (showModal.value = false),
          });
};

/* =========================
   DELETE MODAL
========================= */
const showDeleteModal = ref(false);
const selectedUser = ref(null);

const openDelete = (user) => {
    selectedUser.value = user;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    router.delete(`/admin/users/${selectedUser.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedUser.value = null;
        },
    });
};
</script>

<template>
    <AdminLayout>
        <!-- PAGE HEADER -->
        <div class="mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">
                Pengguna
            </h1>
        </div>

        <!-- FILTERS -->
        <div class="bg-white rounded-xl p-4 sm:p-6 mb-4 sm:mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <label
                        class="block text-xs sm:text-sm font-medium text-gray-700 mb-2"
                        >Cari</label
                    >
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Nama atau Email"
                        class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <!-- Button -->
                <div class="flex items-end sm:flex-shrink-0">
                    <button
                        @click="openCreate"
                        class="w-full sm:w-auto px-4 py-2 text-sm sm:text-base text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition whitespace-nowrap"
                    >
                        Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <UserTable :users="users.data" @edit="openEdit" @delete="openDelete" />

        <!-- PAGINATION -->
        <div class="bg-white rounded-xl p-4 sm:p-6 mt-4 sm:mt-6 shadow-sm">
            <div class="flex justify-end">
                <div class="flex gap-2">
                    <button
                        v-for="link in users.links"
                        :key="link.label"
                        v-html="link.label"
                        :disabled="!link.url"
                        @click="
                            link.url &&
                                router.get(
                                    link.url,
                                    {},
                                    { preserveState: true }
                                )
                        "
                        class="px-3 py-1 text-sm rounded-lg border"
                        :class="{
                            'bg-blue-600 text-white': link.active,
                            'text-gray-400 cursor-not-allowed': !link.url,
                        }"
                    />
                </div>
            </div>
        </div>

        <!-- CREATE / EDIT MODAL -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4"
        >
            <div
                class="bg-white w-full max-w-md rounded-2xl p-4 sm:p-6 max-h-[90vh] overflow-y-auto"
            >
                <h3 class="text-base sm:text-lg font-semibold mb-4 sm:mb-6">
                    {{ isEdit ? "Edit User" : "Tambah User" }}
                </h3>

                <div class="space-y-3 sm:space-y-4">
                    <input
                        v-model="form.name"
                        placeholder="Username"
                        class="input text-sm sm:text-base"
                    />
                    <input
                        v-model="form.email"
                        placeholder="Email"
                        class="input text-sm sm:text-base"
                    />
                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Password (opsional)"
                        class="input text-sm sm:text-base"
                    />
                    <select
                        v-model="form.role"
                        class="input text-sm sm:text-base"
                    >
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div
                    class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-4 mt-6 sm:mt-8"
                >
                    <button
                        @click="showModal = false"
                        class="text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-100 text-sm sm:text-base"
                    >
                        Batal
                    </button>
                    <button
                        @click="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 text-sm sm:text-base"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <!-- DELETE MODAL -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4"
        >
            <div class="bg-white w-full max-w-sm rounded-2xl p-4 sm:p-6">
                <h3
                    class="text-base sm:text-lg font-semibold text-red-600 mb-3"
                >
                    Hapus User
                </h3>

                <p class="text-sm sm:text-base">
                    Yakin hapus
                    <span class="font-semibold">{{ selectedUser?.name }}</span
                    >?
                </p>

                <div
                    class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-4 mt-6 sm:mt-8"
                >
                    <button
                        @click="showDeleteModal = false"
                        class="px-4 py-2 rounded-lg hover:bg-gray-100 text-sm sm:text-base"
                    >
                        Batal
                    </button>
                    <button
                        @click="confirmDelete"
                        class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 text-sm sm:text-base"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.input {
    @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg
         focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}
</style>
