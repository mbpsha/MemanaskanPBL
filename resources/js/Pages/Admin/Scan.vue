<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { BrowserMultiFormatReader } from "@zxing/library";
import {
    QrCodeIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";

const videoRef = ref(null);
const codeReader = ref(null);
const scanning = ref(false);
const scannedCode = ref("");
const scanResult = ref(null);
const error = ref("");
const loading = ref(false);

// Start camera and scanner
const startScanning = async () => {
    try {
        error.value = "";
        scanning.value = true;

        codeReader.value = new BrowserMultiFormatReader();

        const videoInputDevices =
            await codeReader.value.listVideoInputDevices();

        if (videoInputDevices.length === 0) {
            throw new Error("Tidak ada kamera yang ditemukan");
        }

        // Use back camera if available (usually index 0 on mobile)
        const selectedDeviceId = videoInputDevices[0].deviceId;

        await codeReader.value.decodeFromVideoDevice(
            selectedDeviceId,
            videoRef.value,
            async (result, err) => {
                if (result) {
                    const code = result.getText();
                    console.log("Scanned code:", code);
                    scannedCode.value = code;
                    await verifyCode(code);
                }

                if (err && !(err.name === "NotFoundException")) {
                    console.error("Scan error:", err);
                }
            }
        );
    } catch (err) {
        console.error("Error starting scanner:", err);
        error.value =
            err.message ||
            "Gagal memulai scanner. Pastikan browser memiliki akses kamera.";
        scanning.value = false;
    }
};

// Stop scanning
const stopScanning = () => {
    if (codeReader.value) {
        codeReader.value.reset();
        codeReader.value = null;
    }
    scanning.value = false;
};

// Verify scanned code with backend
const verifyCode = async (code) => {
    if (loading.value) return; // Prevent multiple calls

    loading.value = true;
    error.value = "";

    try {
        const response = await fetch(route("admin.scan.verify"), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({ code }),
        });

        const data = await response.json();

        if (data.ok) {
            scanResult.value = data;
            // Stop scanning to show result
            stopScanning();

            // Play success sound (optional)
            const audio = new Audio(
                "data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLaiTcIGWi77eefTRAMUKfj8LZjHAY4ktfyy3ksBSR3x/DdkEAKFF606OvYVRQKRKDh8r5sIQU"
            );
            audio.play().catch(() => {});
        } else {
            error.value = data.message || "Kode tidak valid";
            scanResult.value = null;
        }
    } catch (err) {
        console.error("Verification error:", err);
        error.value = "Gagal memverifikasi kode. Silakan coba lagi.";
        scanResult.value = null;
    } finally {
        loading.value = false;
        // Auto restart scanning after 3 seconds if error
        if (error.value) {
            setTimeout(() => {
                if (!scanning.value) {
                    startScanning();
                }
            }, 3000);
        }
    }
};

// Reset and start new scan
const resetScan = () => {
    scanResult.value = null;
    scannedCode.value = "";
    error.value = "";
    startScanning();
};

// Manual code entry
const manualVerify = async () => {
    if (!scannedCode.value.trim()) return;
    await verifyCode(scannedCode.value.trim());
};

onMounted(() => {
    // Auto start scanning on mount
    startScanning();
});

onUnmounted(() => {
    stopScanning();
});
</script>

<template>
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Scan Barcode Peserta
                </h1>
                <p class="text-gray-600 mt-2">
                    Scan barcode dari email konfirmasi peserta untuk verifikasi
                    pengambilan racepack
                </p>
            </div>

            <!-- Scanner Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-6">
                    <!-- Video Preview -->
                    <div
                        class="relative bg-black rounded-lg overflow-hidden mb-4"
                        style="aspect-ratio: 16/9"
                    >
                        <video
                            ref="videoRef"
                            class="w-full h-full object-cover"
                            autoplay
                            playsinline
                        ></video>

                        <!-- Scanning Overlay -->
                        <div
                            v-if="scanning && !scanResult"
                            class="absolute inset-0 flex items-center justify-center pointer-events-none"
                        >
                            <div
                                class="border-4 border-green-500 rounded-lg animate-pulse"
                                style="width: 80%; height: 40%"
                            ></div>
                        </div>

                        <!-- Loading Indicator -->
                        <div
                            v-if="loading"
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center"
                        >
                            <div class="text-white text-center">
                                <div
                                    class="animate-spin rounded-full h-16 w-16 border-b-2 border-white mx-auto mb-4"
                                ></div>
                                <p class="text-lg font-semibold">
                                    Memverifikasi...
                                </p>
                            </div>
                        </div>

                        <!-- Error Message in Video -->
                        <div
                            v-if="error && !scanResult"
                            class="absolute inset-0 bg-red-600 bg-opacity-90 flex items-center justify-center p-4"
                        >
                            <div class="text-white text-center max-w-md">
                                <XCircleIcon class="w-20 h-20 mx-auto mb-4" />
                                <p class="text-xl font-bold mb-2">
                                    {{ error }}
                                </p>
                                <p class="text-sm">
                                    Scanner akan dimulai ulang otomatis...
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Entry -->
                    <div class="space-y-4">
                        <div class="flex gap-2">
                            <input
                                v-model="scannedCode"
                                type="text"
                                placeholder="Atau masukkan kode registrasi manual (e.g., REG-XXXXXXXXXX)"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                @keyup.enter="manualVerify"
                            />
                            <button
                                @click="manualVerify"
                                :disabled="loading || !scannedCode.trim()"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed font-semibold transition"
                            >
                                Verifikasi
                            </button>
                        </div>

                        <!-- Controls -->
                        <div class="flex gap-2">
                            <button
                                v-if="!scanning"
                                @click="startScanning"
                                class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center justify-center gap-2"
                            >
                                <QrCodeIcon class="w-5 h-5" />
                                Mulai Scan
                            </button>
                            <button
                                v-else
                                @click="stopScanning"
                                class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition"
                            >
                                Stop Scan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scan Result -->
            <div
                v-if="scanResult"
                class="bg-white rounded-xl shadow-lg overflow-hidden"
            >
                <div
                    class="p-6"
                    :class="
                        scanResult.verified
                            ? 'bg-gradient-to-br from-green-50 to-emerald-50'
                            : 'bg-gradient-to-br from-yellow-50 to-orange-50'
                    "
                >
                    <!-- Success -->
                    <div v-if="scanResult.verified" class="text-center mb-6">
                        <CheckCircleIcon
                            class="w-24 h-24 text-green-600 mx-auto mb-4"
                        />
                        <h2 class="text-3xl font-bold text-green-800 mb-2">
                            ✓ TERVERIFIKASI
                        </h2>
                        <p class="text-green-700 text-lg">
                            Peserta valid dan sudah membayar
                        </p>
                    </div>

                    <!-- Warning (Not Verified) -->
                    <div v-else class="text-center mb-6">
                        <XCircleIcon
                            class="w-24 h-24 text-orange-600 mx-auto mb-4"
                        />
                        <h2 class="text-3xl font-bold text-orange-800 mb-2">
                            ⚠ BELUM TERVERIFIKASI
                        </h2>
                        <p class="text-orange-700 text-lg">
                            Status:
                            {{
                                scanResult.participant.payment_status.toUpperCase()
                            }}
                        </p>
                    </div>

                    <!-- Participant Details -->
                    <div class="bg-white rounded-lg p-6 mb-6 shadow-md">
                        <h3
                            class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"
                        >
                            Data Peserta
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ scanResult.participant.name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">NIK</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ scanResult.participant.nik }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ scanResult.participant.email }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Telepon</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ scanResult.participant.phone }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Ukuran Kaos</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{ scanResult.participant.shirt_size }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">
                                    Kode Registrasi
                                </p>
                                <p
                                    class="text-lg font-semibold text-indigo-600"
                                >
                                    {{
                                        scanResult.participant.registration_code
                                    }}
                                </p>
                            </div>
                            <div
                                v-if="
                                    scanResult.participant.payment_verified_at
                                "
                                class="md:col-span-2"
                            >
                                <p class="text-sm text-gray-600">
                                    Tanggal Verifikasi
                                </p>
                                <p class="text-lg font-semibold text-gray-800">
                                    {{
                                        scanResult.participant
                                            .payment_verified_at
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button
                        @click="resetScan"
                        class="w-full px-6 py-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold text-lg transition"
                    >
                        Scan Peserta Berikutnya
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
video {
    transform: scaleX(-1); /* Mirror the video for better UX */
}
</style>
