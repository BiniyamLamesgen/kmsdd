<script setup lang="ts">
import { ref, computed } from "vue";

interface EmployeeCertification {
    id: number;
    certification_name: string;
    issuing_organization: string;
    issue_date: string;
    expiry_date?: string | null;
    credential_id?: string;
    image_url?: string | null;
    image?: string | null;
}

interface Employee {
    certifications?: EmployeeCertification[];
}

const props = defineProps<{
    employee: Employee;
}>();

const emit = defineEmits<{
    openDetail: [certificate: EmployeeCertification];
}>();

// Certificate navigation logic
const certs = computed(() =>
    Array.isArray(props.employee?.certifications)
        ? props.employee.certifications
        : []
);
const certsWithImages = computed(() =>
    certs.value.filter((c: any) => c.image_url || c.image)
);

const certIndex = ref(0);
const visibleCount = 3;
const maxIndex = computed(() =>
    Math.max(0, certsWithImages.value.length - visibleCount)
);

function prevCert() {
    certIndex.value = Math.max(0, certIndex.value - visibleCount);
}

function nextCert() {
    certIndex.value = Math.min(maxIndex.value, certIndex.value + visibleCount);
}

function openCertificateDetail(certificate: EmployeeCertification) {
    emit("openDetail", certificate);
}
</script>

<template>
    <!-- Certificates Section -->
    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <div
                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center"
            >
                <svg
                    class="w-4 h-4 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                    ></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Certificates</h3>
        </div>

        <div v-if="certsWithImages.length > 0" class="space-y-3 sm:space-y-4">
            <!-- Certificate Navigation -->
            <div
                class="flex flex-col sm:flex-row items-center justify-between bg-gray-50 rounded-lg p-2 sm:p-3 gap-3 sm:gap-0"
            >
                <button
                    @click="prevCert"
                    :disabled="certIndex === 0"
                    class="flex items-center space-x-1 sm:space-x-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg bg-white shadow-sm border border-gray-200 text-gray-600 hover:text-gray-800 hover:shadow-md transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm w-full sm:w-auto justify-center sm:justify-start"
                >
                    <svg
                        class="w-3 h-3 sm:w-4 sm:h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        ></path>
                    </svg>
                    <span class="text-xs sm:text-sm font-medium">Previous</span>
                </button>

                <div
                    class="flex items-center space-x-1 sm:space-x-2 order-first sm:order-none"
                >
                    <span class="text-xs sm:text-sm text-gray-600">
                        {{ certIndex + 1 }} -
                        {{
                            Math.min(
                                certIndex + visibleCount,
                                certsWithImages.length
                            )
                        }}
                    </span>
                    <div
                        class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-gray-300 rounded-full"
                    ></div>
                    <span class="text-xs sm:text-sm text-gray-600"
                        >{{ certsWithImages.length }} total</span
                    >
                </div>

                <button
                    @click="nextCert"
                    :disabled="certIndex >= maxIndex"
                    class="flex items-center space-x-1 sm:space-x-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg bg-white shadow-sm border border-gray-200 text-gray-600 hover:text-gray-800 hover:shadow-md transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm w-full sm:w-auto justify-center sm:justify-start"
                >
                    <span class="text-xs sm:text-sm font-medium">Next</span>
                    <svg
                        class="w-3 h-3 sm:w-4 sm:h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        ></path>
                    </svg>
                </button>
            </div>

            <!-- Certificate Cards -->
            <div class="grid gap-3 sm:gap-4">
                <div
                    v-for="cert in certsWithImages.slice(
                        certIndex,
                        certIndex + visibleCount
                    )"
                    :key="'cert-' + cert.id"
                    @click="openCertificateDetail(cert)"
                    class="bg-gradient-to-br from-white to-gray-50 rounded-lg sm:rounded-xl p-3 sm:p-4 lg:p-6 border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 group cursor-pointer transform hover:scale-[1.02]"
                >
                    <div class="flex items-start space-x-3 sm:space-x-4">
                        <div class="flex-shrink-0">
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden border-2 border-gray-200 bg-white shadow-sm group-hover:shadow-md transition-shadow group-hover:border-green-300"
                            >
                                <img
                                    v-if="cert.image_url || cert.image"
                                    :src="
                                        cert.image_url ||
                                        (cert.image
                                            ? `/storage/${cert.image}`
                                            : '')
                                    "
                                    alt="Certificate"
                                    class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4
                                class="text-sm sm:text-base font-semibold text-gray-800 group-hover:text-green-600 transition-colors line-clamp-2"
                            >
                                {{ cert.certification_name }}
                            </h4>
                            <p
                                class="text-xs sm:text-sm text-gray-600 mt-1 line-clamp-1"
                            >
                                {{ cert.issuing_organization }}
                            </p>
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-2 sm:mt-3 space-y-2 sm:space-y-0"
                            >
                                <div class="flex items-center space-x-1">
                                    <svg
                                        class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                    <span
                                        class="text-xs text-gray-500 truncate"
                                        >{{ cert.issue_date }}</span
                                    >
                                </div>
                                <div
                                    class="flex items-center space-x-1 text-green-600"
                                >
                                    <svg
                                        class="w-3 h-3 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        ></path>
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        ></path>
                                    </svg>
                                    <span class="text-xs font-medium"
                                        >View Details</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-6 sm:py-8">
            <div
                class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4"
            >
                <svg
                    class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                    ></path>
                </svg>
            </div>
            <p class="text-gray-500 text-xs sm:text-sm">
                No certificates with images available
            </p>
        </div>
    </div>
</template>
