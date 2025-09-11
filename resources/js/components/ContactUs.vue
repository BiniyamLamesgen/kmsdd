<script setup lang="ts">
import type { ServerPaginatedResponse } from '@/types/datatable';
import { useForm } from '@inertiajs/vue3';
import { Mail, MapPin, Phone } from 'lucide-vue-next';
import { reactive, ref } from 'vue';
import { route } from 'ziggy-js';

interface FormState {
    name: string;
    email: string;
    message: string;
}

interface ContentManagement {
    id: number;
    key: string;
    value: string;
    type: 'image' | 'text';
    is_image: boolean;
    value_url: string | null;
    created_at: string | null;
}

const props = defineProps<{
    contents: ServerPaginatedResponse<ContentManagement>;
}>();

// Helper function to get content by key
const getContent = (key: string): string => {
    const content = props.contents.data.find((item) => item.key === key);
    return content?.value || '';
};

// Inertia form
const form = useForm({
    name: '',
    email: '',
    message: '',
});

const submitted = ref(false);
const validationErrors = reactive<Partial<FormState>>({});

const validateEmail = (email: string) => /^\S+@\S+\.\S+$/.test(email);

const validateForm = () => {
    validationErrors.name = !form.name.trim() ? 'Name is required' : form.name.length < 2 ? 'Name must be at least 2 characters' : undefined;

    validationErrors.email = !form.email.trim() ? 'Email is required' : !validateEmail(form.email) ? 'Email is invalid' : undefined;

    validationErrors.message = !form.message.trim()
        ? 'Message is required'
        : form.message.length < 10
          ? 'Message must be at least 10 characters'
          : undefined;

    return !validationErrors.name && !validationErrors.email && !validationErrors.message;
};

const handleSubmit = () => {
    // Clear previous validation errors
    Object.keys(validationErrors).forEach((key) => {
        delete validationErrors[key as keyof FormState];
    });

    if (!validateForm()) return;

    form.post(route('contact.public.store'), {
        onSuccess: () => {
            submitted.value = true;
            form.reset();
            // Clear the submitted state after a few seconds
            setTimeout(() => {
                submitted.value = false;
            }, 3000);
        },
        onError: (errors) => {
            Object.assign(validationErrors, errors);
        },
    });
};

const inputClass = (error?: string) =>
    `w-full p-3 border rounded-lg focus:outline-none focus:ring-2 text-black ${
        error ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-300'
    }`;
</script>
<template>
    <div class="mx-auto mt-16 max-w-7xl space-y-12 px-4 py-12">
        <h1 class="mb-8 text-center text-4xl font-extrabold text-sky-500">Contact Us</h1>

        <div class="grid grid-cols-1 items-start gap-12 md:grid-cols-2">
            <!-- Contact Info -->
            <div class="ml-6 space-y-8">
                <h2 class="text-2xl font-semibold text-gray-800">We’d love to hear from you</h2>
                <p class="text-gray-600">Feel free to reach out for any questions, feedback, or inquiries.</p>

                <div class="space-y-4 text-gray-700">
                    <div class="flex items-start gap-4">
                        <Phone class="mt-1 text-sky-500" />
                        <div>
                            <p>{{ getContent('phone') }}</p>
                            <p>{{ getContent('phone_2') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <Mail class="mt-1 text-sky-500" />
                        <p>{{ getContent('email') }}</p>
                    </div>

                    <div class="flex items-start gap-4">
                        <MapPin class="mt-1 text-sky-500" />
                        <p>{{ getContent('address') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <form @submit.prevent="handleSubmit" class="space-y-5 rounded-xl border border-gray-100 bg-white p-8 shadow-lg">
                <h2 class="text-xl font-semibold text-gray-800">Send a Message</h2>

                <input v-model="form.name" type="text" name="name" placeholder="Your Name" :class="inputClass(validationErrors.name)" />
                <p v-if="validationErrors.name" class="text-sm text-red-600">{{ validationErrors.name }}</p>

                <input v-model="form.email" type="email" name="email" placeholder="Your Email" :class="inputClass(validationErrors.email)" />
                <p v-if="validationErrors.email" class="text-sm text-red-600">{{ validationErrors.email }}</p>

                <textarea
                    v-model="form.message"
                    name="message"
                    placeholder="Your Message"
                    rows="5"
                    :class="inputClass(validationErrors.message)"
                ></textarea>
                <p v-if="validationErrors.message" class="text-sm text-red-600">{{ validationErrors.message }}</p>

                <p v-if="submitted" class="text-sm text-green-600">Thank you! Your message has been sent.</p>
                <p v-if="form.hasErrors" class="text-sm text-red-600">Please fix the errors above.</p>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-sky-500 py-3 font-medium text-white transition hover:bg-sky-600"
                    :class="{ 'cursor-not-allowed opacity-50': form.processing }"
                >
                    {{ form.processing ? 'Sending...' : submitted ? 'Message Sent!' : 'Send Message' }}
                </button>
            </form>
        </div>
    </div>
</template>
