<script setup>
import { ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PaymentTable from '@/Components/Admin/PaymentTable.vue'

const props = defineProps({
  payments: Object,
  filters: Object
})

const search = ref(props.filters?.search ?? '')
const statusFilter = ref(props.filters?.status ?? '')
const sortOrder = ref(props.filters?.sort ?? 'desc')

watch([search, statusFilter, sortOrder], ([searchVal, statusVal, sortVal]) => {
  router.get(
    '/admin/payments',
    { 
      search: searchVal, 
      status: statusVal,
      sort: sortVal 
    },
    {
      preserveState: true,
      replace: true
    }
  )
})

/* STATUS UPDATE*/
const updateStatus = (payment, newStatus) => {
  if (newStatus === 'rejected') {
    const reason = prompt('Alasan penolakan:')
    if (!reason) return

    router.post(`/admin/payments/${payment.id}/status`, {
      status: newStatus,
      rejection_reason: reason
    }, {
      preserveScroll: true,
      onSuccess: () => {
        alert('Status berhasil diupdate')
      }
    })
  } else {
    router.post(`/admin/payments/${payment.id}/status`, {
      status: newStatus
    }, {
      preserveScroll: true,
      onSuccess: () => {
        alert('Status berhasil diupdate')
      }
    })
  }
}

/*VIEW IMAGE MODAL*/
const showImageModal = ref(false)
const selectedImage = ref(null)

const viewImage = (payment) => {
  if (payment.payment_proof_path) {
    selectedImage.value = `/storage/${payment.payment_proof_path}`
    showImageModal.value = true
  }
}

const closeImageModal = () => {
  showImageModal.value = false
  selectedImage.value = null
}
</script>

<template>
  <AdminLayout>

    <!-- PAGE HEADER -->
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-800">
        Verifikasi Pembayaran
      </h1>
    </div>

    <!-- FILTERS -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
          <input 
            v-model="search"
            type="text"
            placeholder="Nama, Email, atau ID Transaksi"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
          <select 
            v-model="statusFilter"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="verified">Verified</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <!-- Sort Order -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
          <select 
            v-model="sortOrder"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="asc">A-Z</option>
            <option value="desc">Z-A</option>
          </select>
        </div>
      </div>
    </div>

    <!-- TABLE -->
    <PaymentTable 
      :payments="payments"
      @update-status="updateStatus"
      @view-image="viewImage"
    />

    <!-- IMAGE MODAL -->
    <div
      v-if="showImageModal"
      @click="closeImageModal"
      class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4"
    >
      <div @click.stop class="relative bg-white rounded-2xl max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-800">Bukti Pembayaran</h3>
          <button 
            @click="closeImageModal"
            class="text-gray-500 hover:text-gray-700 text-2xl font-bold"
          >
            ×
          </button>
        </div>
        <div class="p-6 overflow-auto max-h-[calc(90vh-80px)]">
          <img 
            :src="selectedImage" 
            alt="Bukti Pembayaran"
            class="w-full h-auto rounded-lg"
          />
        </div>
      </div>
    </div>

  </AdminLayout>
</template>
