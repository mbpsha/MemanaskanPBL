<script setup>
import { ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UserTable from '@/Components/Admin/UserTable.vue'

const props = defineProps({
  users: Object,
  filters: Object
})

/* =========================
   SEARCH
========================= */
const search = ref(props.filters?.search ?? '')

watch(search, (value) => {
  router.get(
    '/admin/users',
    { search: value },
    {
      preserveState: true,
      replace: true
    }
  )
})

/* =========================
   CREATE & EDIT MODAL
========================= */
const showModal = ref(false)
const isEdit = ref(false)

const form = useForm({
  id: null,
  name: '',
  email: '',
  password: '',
  role: 'user'
})

const openCreate = () => {
  isEdit.value = false
  form.reset()
  showModal.value = true
}

const openEdit = (user) => {
  isEdit.value = true
  form.id = user.id
  form.name = user.name
  form.email = user.email
  form.role = user.role
  form.password = ''
  showModal.value = true
}

const submit = () => {
  isEdit.value
    ? form.put(`/admin/users/${form.id}`, {
        preserveScroll: true,
        onSuccess: () => showModal.value = false
      })
    : form.post('/admin/users', {
        preserveScroll: true,
        onSuccess: () => showModal.value = false
      })
}

/* =========================
   DELETE MODAL
========================= */
const showDeleteModal = ref(false)
const selectedUser = ref(null)

const openDelete = (user) => {
  selectedUser.value = user
  showDeleteModal.value = true
}

const confirmDelete = () => {
  router.delete(`/admin/users/${selectedUser.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false
      selectedUser.value = null
    }
  })
}
</script>

<template>
  <AdminLayout>

    <!-- <h1 class="text-xl font-semibold text-gray-800">
        Pengguna
    </h1> -->

    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between mb-6">
      <!-- TITLE -->
      <h1 class="text-xl font-semibold text-gray-800">
        Pengguna
      </h1>
    </div>

    <!-- TABLE -->
    <UserTable
      :users="users.data"
      @create="openCreate"
      @edit="openEdit"
      @delete="openDelete"
    />

    <!-- PAGINATION -->
    <div class="flex justify-end mt-6">
      <div class="flex gap-2">
        <button
          v-for="link in users.links"
          :key="link.label"
          v-html="link.label"
          :disabled="!link.url"
          @click="link.url && router.get(link.url, {}, { preserveState: true })"
          class="px-3 py-1 text-sm rounded-lg border"
          :class="{
            'bg-blue-600 text-white': link.active,
            'text-gray-400 cursor-not-allowed': !link.url
          }"
        />
      </div>
    </div>

    <!-- CREATE / EDIT MODAL -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center"
    >
      <div class="bg-white w-full max-w-md rounded-2xl p-6">
        <h3 class="text-lg font-semibold mb-6">
          {{ isEdit ? 'Edit User' : 'Tambah User' }}
        </h3>

        <div class="space-y-4">
          <input v-model="form.name" placeholder="Username" class="input" />
          <input v-model="form.email" placeholder="Email" class="input" />
          <input v-model="form.password" type="password" placeholder="Password (opsional)" class="input" />
          <select v-model="form.role" class="input">
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>

        <div class="flex justify-end gap-4 mt-8">
          <button @click="showModal=false" class="text-gray-600">Batal</button>
          <button @click="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg">
            Simpan
          </button>
        </div>
      </div>
    </div>

    <!-- DELETE MODAL -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center"
    >
      <div class="bg-white w-full max-w-sm rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-red-600 mb-3">
          Hapus User
        </h3>

        <p>
          Yakin hapus
          <span class="font-semibold">{{ selectedUser?.name }}</span>?
        </p>

        <div class="flex justify-end gap-4 mt-8">
          <button @click="showDeleteModal=false">Batal</button>
          <button @click="confirmDelete" class="bg-red-600 text-white px-5 py-2 rounded-lg">
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
