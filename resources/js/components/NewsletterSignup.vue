<template>
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Stay Updated
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Subscribe to our newsletter to get the latest news and updates delivered to your inbox.
                </p>
                
                <!-- Flash Messages -->
                <div v-if="showSuccess" class="mt-6 p-4 rounded-md bg-green-50 border border-green-200 max-w-md mx-auto">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ successMessage }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="showError" class="mt-6 p-4 rounded-md bg-red-50 border border-red-200 max-w-md mx-auto">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ errorMessage }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <form @submit.prevent="submit" class="mt-8 max-w-md mx-auto">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{ 'border-red-500': form.errors.email }"
                                placeholder="Enter your email address"
                            >
                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-600 text-left">
                                {{ form.errors.email }}
                            </p>
                        </div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
                        >
                            <span v-if="form.processing" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Subscribing...
                            </span>
                            <span v-else>Subscribe</span>
                        </button>
                    </div>
                </form>
                
                <p class="mt-4 text-xs text-gray-500">
                    By subscribing, you agree to receive our newsletter emails. You can unsubscribe at any time.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const page = usePage()
const showSuccess = ref(false)
const showError = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const form = useForm({
    email: ''
})

// Watch for flash messages
watch(() => (page.props.flash as any)?.success, (newVal) => {
    if (newVal) {
        successMessage.value = newVal
        showSuccess.value = true
        showError.value = false
        setTimeout(() => {
            showSuccess.value = false
        }, 5000)
    }
})

watch(() => (page.props.flash as any)?.error, (newVal) => {
    if (newVal) {
        errorMessage.value = newVal
        showError.value = true
        showSuccess.value = false
        setTimeout(() => {
            showError.value = false
        }, 5000)
    }
})

const submit = () => {
    form.post(route('newsletter.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('email')
        },
        onError: () => {
            // Keep the email in the form for easy correction
        }
    })
}
</script>
