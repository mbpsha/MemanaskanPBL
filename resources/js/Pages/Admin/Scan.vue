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
                    Scan QR Code Peserta
                </h1>
                <p class="mt-2 text-gray-600">
                    Scan QR code dari email konfirmasi peserta untuk verifikasi
                    pengambilan racepack
                </p>
            </div>

            <!-- Scanner Card -->
            <div class="mb-6 overflow-hidden bg-white shadow-lg rounded-xl">
                <div class="p-6">
                    <!-- Video Preview -->
                    <div
                        class="relative mb-4 overflow-hidden bg-black rounded-lg"
                        style="aspect-ratio: 16/9"
                    >
                        <video
                            ref="videoRef"
                            class="object-cover w-full h-full"
                            autoplay
                            playsinline
                        ></video>

                        <!-- Scanning Overlay -->
                        <div
                            v-if="scanning && !scanResult"
                            class="absolute inset-0 flex items-center justify-center pointer-events-none"
                        >
                            <!-- Dark overlay outside scan area -->
                            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                            
                            <!-- Scan frame container -->
                            <div class="relative" style="width: 70%; aspect-ratio: 1/1;">
                                <!-- Transparent center (cut-out effect) -->
                                <div class="absolute inset-0 border-4 border-green-500 rounded-lg bg-transparent" style="box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.6);"></div>
                                
                                <!-- Corner markers -->
                                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-green-400"></div>
                                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-green-400"></div>
                                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-green-400"></div>
                                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-green-400"></div>
                                
                                <!-- Scanning animation line -->
                                <div class="absolute inset-x-0 top-0 h-1 bg-green-400 animate-scan"></div>
                            </div>
                        </div>

                        <!-- Loading Indicator -->
                        <div
                            v-if="loading"
                            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50"
                        >
                            <div class="text-center text-white">
                                <div
                                    class="w-16 h-16 mx-auto mb-4 border-b-2 border-white rounded-full animate-spin"
                                ></div>
                                <p class="text-lg font-semibold">
                                    Memverifikasi...
                                </p>
                            </div>
                        </div>

                        <!-- Error Message in Video -->
                        <div
                            v-if="error && !scanResult"
                            class="absolute inset-0 flex items-center justify-center p-4 bg-red-600 bg-opacity-90"
                        >
                            <div class="max-w-md text-center text-white">
                                <XCircleIcon class="w-20 h-20 mx-auto mb-4" />
                                <p class="mb-2 text-xl font-bold">
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
                                class="px-6 py-3 font-semibold text-white transition bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Verifikasi
                            </button>
                        </div>

                        <!-- Controls -->
                        <div class="flex gap-2">
                            <button
                                v-if="!scanning"
                                @click="startScanning"
                                class="flex items-center justify-center flex-1 gap-2 px-4 py-3 font-semibold text-white transition bg-green-600 rounded-lg hover:bg-green-700"
                            >
                                <QrCodeIcon class="w-5 h-5" />
                                Mulai Scan
                            </button>
                            <button
                                v-else
                                @click="stopScanning"
                                class="flex-1 px-4 py-3 font-semibold text-white transition bg-red-600 rounded-lg hover:bg-red-700"
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
                class="overflow-hidden bg-white shadow-lg rounded-xl"
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
                    <div v-if="scanResult.verified" class="mb-6 text-center">
                        <CheckCircleIcon
                            class="w-24 h-24 mx-auto mb-4 text-green-600"
                        />
                        <h2 class="mb-2 text-3xl font-bold text-green-800">
                            ✓ TERVERIFIKASI
                        </h2>
                        <p class="text-lg text-green-700">
                            Peserta valid dan sudah membayar
                        </p>
                    </div>

                    <!-- Warning (Not Verified) -->
                    <div v-else class="mb-6 text-center">
                        <XCircleIcon
                            class="w-24 h-24 mx-auto mb-4 text-orange-600"
                        />
                        <h2 class="mb-2 text-3xl font-bold text-orange-800">
                            ⚠ BELUM TERVERIFIKASI
                        </h2>
                        <p class="text-lg text-orange-700">
                            Status:
                            {{
                                scanResult.participant.payment_status.toUpperCase()
                            }}
                        </p>
                    </div>

                    <!-- Participant Details -->
                    <div class="p-6 mb-6 bg-white rounded-lg shadow-md">
                        <h3
                            class="pb-2 mb-4 text-xl font-bold text-gray-800 border-b"
                        >
                            Data Peserta
                        </h3>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                        class="w-full px-6 py-4 text-lg font-bold text-white transition bg-indigo-600 rounded-lg hover:bg-indigo-700"
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

@keyframes scan {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(calc(100% * 20));
    }
    100% {
        transform: translateY(0);
    }
}

.animate-scan {
    animation: scan 2s ease-in-out infinite;
}
</style>
