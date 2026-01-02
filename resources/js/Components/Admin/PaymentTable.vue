<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  payments: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['updateStatus', 'viewImage'])

/* STATUS HELPERS*/
const getStatusDisplay = (status) => {
  const map = {
    'uploaded': 'pending',
    'verified': 'success',
    'rejected': 'failed',
    'pending': 'pending'
  }
  return map[status] || status
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

const formatTime = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  }) + ' WIB'
}
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Id Transaksi</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Bukti Bayar</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Daftar</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Jam</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="payment in payments.data" :key="payment.id" class="hover:bg-gray-50">
            <!-- Name -->
            <td class="px-6 py-4">
              <div class="text-sm font-medium text-gray-900">{{ payment.name }}</div>
              <div class="text-sm text-gray-500">{{ payment.email }}</div>
            </td>

            <!-- Transaction ID -->
            <td class="px-6 py-4 text-sm text-gray-700">
              {{ payment.registration_code }}
            </td>

            <!-- Payment Proof -->
            <td class="px-6 py-4">
              <button 
                v-if="payment.payment_proof_path"
                @click="emit('viewImage', payment)"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium underline"
              >
                Bukti Bayar
              </button>
              <span v-else class="text-gray-400 text-sm">-</span>
            </td>

            <!-- Date -->
            <td class="px-6 py-4 text-sm text-gray-700">
              {{ formatDate(payment.created_at) }}
            </td>

            <!-- Time -->
            <td class="px-6 py-4 text-sm text-gray-700">
              {{ formatTime(payment.created_at) }}
            </td>

            <!-- Status Dropdown -->
            <td class="px-6 py-4">
              <div class="space-y-2">
                <!-- Status Display + Dropdown (Side by Side) -->
                <div class="flex items-center gap-2">
                  <!-- Current Status Badge -->
                  <div class="px-3 py-2 rounded-lg font-bold text-sm border-2 whitespace-nowrap"
                    :class="{
                      'bg-yellow-100 text-yellow-800 border-yellow-300': getStatusDisplay(payment.payment_status) === 'pending',
                      'bg-green-100 text-green-800 border-green-300': getStatusDisplay(payment.payment_status) === 'success',
                      'bg-red-100 text-red-800 border-red-300': getStatusDisplay(payment.payment_status) === 'failed'
                    }"
                  >
                    <span v-if="getStatusDisplay(payment.payment_status) === 'pending'">Pending</span>
                    <span v-else-if="getStatusDisplay(payment.payment_status) === 'success'">Verified</span>
                    <span v-else>Rejected</span>
                  </div>

                  <!-- Action Dropdown -->
                  <select
                    @change="(e) => { emit('updateStatus', payment, e.target.value); e.target.value = '' }"
                    class="flex-1 px-3 py-2 rounded-lg font-semibold text-sm cursor-pointer focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition border-2 border-gray-300 bg-white text-gray-700"
                  >
                    <option value="" disabled selected>Ubah...</option>
                    <option value="verified" class="bg-white text-gray-900 font-semibold">Verified</option>
                    <option value="rejected" class="bg-white text-gray-900 font-semibold">Rejected</option>
                  </select>
                </div>
                
                <!-- Rejection Reason (only shown if rejected) -->
                <div v-if="getStatusDisplay(payment.payment_status) === 'failed' && payment.rejection_reason" class="bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                  <p class="text-xs font-semibold text-red-800 mb-1">Alasan Penolakan:</p>
                  <p class="text-xs text-red-700">{{ payment.rejection_reason }}</p>
                </div>
              </div>
            </td>
          </tr>

          <!-- Empty State -->
          <tr v-if="payments.data.length === 0">
            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
              Tidak ada data pembayaran
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div v-if="payments.links.length > 3" class="px-6 py-4 border-t bg-gray-50">
      <div class="flex justify-end gap-2">
        <button
          v-for="link in payments.links"
          :key="link.label"
          v-html="link.label"
          :disabled="!link.url"
          @click="link.url && router.get(link.url, {}, { preserveState: true })"
          class="px-4 py-2 text-sm rounded-lg border transition"
          :class="{
            'bg-indigo-600 text-white border-indigo-600': link.active,
            'bg-white text-gray-700 border-gray-300 hover:bg-gray-50': !link.active && link.url,
            'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed': !link.url
          }"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>
select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
}
</style>
