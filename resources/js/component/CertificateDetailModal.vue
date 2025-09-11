<script setup lang="ts">
interface EmployeeCertification {
    id: number;
    employee_id: number;
    certification_name: string;
    issuing_organization: string;
    issue_date: string;
    expiry_date: string | null;
    credential_id: string;
    created_at: string;
    updated_at: string;
    image_url?: string | null;
    image?: string | null;
}

const props = defineProps<{
    isOpen: boolean;
    certificate: EmployeeCertification | null;
}>();

const emit = defineEmits<{
    close: [];
}>();

function closeModal() {
    emit("close");
}
</script>

<template>
    <Teleport to="body">
        <!-- Certificate Detail Modal -->
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="props.isOpen && props.certificate"
                class="fixed inset-0 z-[60] flex items-center justify-center p-2 sm:p-4"
            >
                <!-- Modal Overlay -->
                <div
                    class="absolute inset-0 bg-black/80 backdrop-blur-sm"
                    @click="closeModal"
                ></div>

                <!-- Modal Content -->
                <Transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="scale-95 opacity-0"
                    enter-to-class="scale-100 opacity-100"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="scale-100 opacity-100"
                    leave-to-class="scale-95 opacity-0"
                >
                    <div
                        v-if="props.isOpen"
                        class="relative bg-white rounded-lg sm:rounded-2xl shadow-2xl w-full max-w-xs sm:max-w-lg md:max-w-2xl lg:max-w-4xl max-h-[95vh] sm:max-h-[90vh] overflow-hidden flex flex-col"
                    >
                        <!-- Modal Header -->
                        <div
                            class="bg-gradient-to-r from-blue-600 to-emerald-600 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 text-white flex-shrink-0"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h2
                                        class="text-lg sm:text-xl lg:text-2xl font-bold mb-1 sm:mb-2 line-clamp-2"
                                    >
                                        {{
                                            props.certificate.certification_name
                                        }}
                                    </h2>
                                    <p
                                        class="text-sm sm:text-base lg:text-lg text-green-100 line-clamp-2"
                                    >
                                        {{
                                            props.certificate
                                                .issuing_organization
                                        }}
                                    </p>
                                </div>
                                <button
                                    @click="closeModal"
                                    class="flex-shrink-0 p-1.5 sm:p-2 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-200"
                                >
                                    <svg
                                        class="w-5 h-5 sm:w-6 sm:h-6 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div
                            class="p-4 sm:p-6 lg:p-8 overflow-y-auto flex-1 min-h-0"
                        >
                            <div
                                class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8"
                            >
                                <!-- Certificate Image -->
                                <div class="space-y-3 sm:space-y-4">
                                    <h3
                                        class="text-base sm:text-lg font-semibold text-gray-800 flex items-center"
                                    >
                                        <svg
                                            class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                        Certificate Image
                                    </h3>
                                    <div
                                        class="bg-gray-50 rounded-lg sm:rounded-xl p-3 sm:p-4 lg:p-6 border border-gray-200 sm:border-2"
                                    >
                                        <img
                                            v-if="
                                                props.certificate.image_url ||
                                                props.certificate.image
                                            "
                                            :src="
                                                props.certificate.image_url ||
                                                (props.certificate.image
                                                    ? `/storage/${props.certificate.image}`
                                                    : '')
                                            "
                                            alt="Certificate"
                                            class="w-full h-auto rounded-md sm:rounded-lg shadow-lg border border-gray-200 hover:shadow-xl transition-shadow duration-300"
                                        />
                                        <div
                                            v-else
                                            class="text-center py-8 sm:py-12 lg:py-16 text-gray-400"
                                        >
                                            <svg
                                                class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                ></path>
                                            </svg>
                                            <p class="text-sm sm:text-base">
                                                No image available
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certificate Details -->
                                <div class="space-y-4 sm:space-y-6">
                                    <h3
                                        class="text-base sm:text-lg font-semibold text-gray-800 flex items-center"
                                    >
                                        <svg
                                            class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            ></path>
                                        </svg>
                                        Certificate Details
                                    </h3>

                                    <div class="space-y-3 sm:space-y-4">
                                        <!-- Issue Date -->
                                        <div
                                            class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 border border-gray-200 hover:border-green-300 transition-colors"
                                        >
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <div
                                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                                >
                                                    <svg
                                                        class="w-4 h-4 sm:w-5 sm:h-5 text-green-600"
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
                                                </div>
                                                <div class="min-w-0">
                                                    <p
                                                        class="text-xs sm:text-sm font-medium text-gray-600"
                                                    >
                                                        Issue Date
                                                    </p>
                                                    <p
                                                        class="text-sm sm:text-base lg:text-lg font-semibold text-gray-800"
                                                    >
                                                        {{
                                                            props.certificate
                                                                .issue_date
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Expiry Date -->
                                        <div
                                            v-if="props.certificate.expiry_date"
                                            class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 border border-gray-200 hover:border-orange-300 transition-colors"
                                        >
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <div
                                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                                >
                                                    <svg
                                                        class="w-4 h-4 sm:w-5 sm:h-5 text-orange-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p
                                                        class="text-xs sm:text-sm font-medium text-gray-600"
                                                    >
                                                        Expiry Date
                                                    </p>
                                                    <p
                                                        class="text-sm sm:text-base lg:text-lg font-semibold text-gray-800"
                                                    >
                                                        {{
                                                            props.certificate
                                                                .expiry_date
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Credential ID -->
                                        <div
                                            v-if="
                                                props.certificate.credential_id
                                            "
                                            class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 border border-gray-200 hover:border-blue-300 transition-colors"
                                        >
                                            <div
                                                class="flex items-start space-x-3"
                                            >
                                                <div
                                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"
                                                >
                                                    <svg
                                                        class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p
                                                        class="text-xs sm:text-sm font-medium text-gray-600"
                                                    >
                                                        Credential ID
                                                    </p>
                                                    <p
                                                        class="text-sm sm:text-base lg:text-lg font-mono text-gray-800 break-all"
                                                    >
                                                        {{
                                                            props.certificate
                                                                .credential_id
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        <div class="pt-2 sm:pt-4">
                                            <div
                                                :class="[
                                                    'inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-medium',
                                                    props.certificate
                                                        .expiry_date &&
                                                    new Date(
                                                        props.certificate.expiry_date
                                                    ) < new Date()
                                                        ? 'bg-red-100 text-red-800'
                                                        : 'bg-green-100 text-green-800',
                                                ]"
                                            >
                                                <svg
                                                    :class="[
                                                        'w-3 h-3 sm:w-4 sm:h-4 mr-1.5 sm:mr-2',
                                                        props.certificate
                                                            .expiry_date &&
                                                        new Date(
                                                            props.certificate.expiry_date
                                                        ) < new Date()
                                                            ? 'text-red-600'
                                                            : 'text-green-600',
                                                    ]"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"
                                                    ></path>
                                                </svg>
                                                {{
                                                    props.certificate
                                                        .expiry_date &&
                                                    new Date(
                                                        props.certificate.expiry_date
                                                    ) < new Date()
                                                        ? "Expired"
                                                        : "Valid"
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div
                            class="bg-gray-50 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border-t border-gray-200 flex-shrink-0"
                        >
                            <div class="flex justify-end">
                                <button
                                    @click="closeModal"
                                    class="px-4 sm:px-6 py-2 bg-gray-600 text-white rounded-md sm:rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium text-sm sm:text-base"
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
