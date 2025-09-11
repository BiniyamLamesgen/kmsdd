<script lang="ts" setup>
// Import Lucide Icons manually (Vue version)
import type { ServerPaginatedResponse } from '@/types/datatable';
import { useForm } from '@inertiajs/vue3';
import {
    Facebook as FacebookIcon,
    Instagram as InstagramIcon,
    Linkedin as LinkedinIcon,
    Mail as MailIcon,
    MapPin as MapPinIcon,
    Phone as PhoneIcon,
    X as XIcon,
} from 'lucide-vue-next';

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

// Newsletter subscription form
const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('newsletter.store'), {
        onSuccess: () => {
            // Clear the form on successful submission
            form.reset();
        },
        onFinish: () => {
            // Form will be reset automatically by Inertia on success
        },
    });
};

// Helper function to get content by key
const getContent = (key: string): string => {
    const content = props.contents.data.find((item) => item.key === key);
    return content?.value || '';
};

// Helper function to get social media URLs with fallback to existing keys
const getSocialUrl = (footerKey: string, fallbackKey: string): string => {
    return getContent(footerKey) || getContent(fallbackKey);
};
</script>

<template>
    <footer class="bg-cyan-950 py-12 text-white">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 md:grid-cols-4 lg:px-20">
            <!-- About -->
            <div>
                <h3 class="mb-4 text-2xl font-extrabold text-sky-500">
                    {{ getContent('footer_organization_name') || 'Ethiopian Kidney Association' }}
                </h3>
                <p class="max-w-md text-white/90">
                    {{ getContent('footer_description') || 'Dedicated to improving Nephrology in Ethiopia.' }}
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="mb-4 text-2xl font-extrabold text-sky-500">Quick Links</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="/#home" class="transition hover:text-sky-500">Home</a>
                    </li>
                    <li>
                        <a href="/#about" class="transition hover:text-sky-500">About Us</a>
                    </li>
                    <li>
                        <a href="/#services" class="transition hover:text-sky-500">Services</a>
                    </li>
                    <li>
                        <a href="/contactUs" class="transition hover:text-sky-500">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Newsletter Subscription -->
            <div>
                <h3 class="mb-4 text-2xl font-extrabold text-sky-500">Subscribe to Our Newsletter</h3>

                <!-- Flash Messages -->
                <div v-if="($page.props.flash as any)?.newsletter_success" class="mb-4 rounded-md border border-green-200 bg-green-50 p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-2">
                            <p class="text-xs font-medium text-green-800">
                                {{ ($page.props.flash as any).newsletter_success }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="($page.props.flash as any)?.newsletter_error" class="mb-4 rounded-md border border-red-200 bg-red-50 p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-2">
                            <p class="text-xs font-medium text-red-800">
                                {{ ($page.props.flash as any).newsletter_error }}
                            </p>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-3">
                    <div>
                        <input
                            id="email"
                            v-model="form.email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 placeholder-gray-400 shadow-sm focus:border-sky-500 focus:ring-sky-500 focus:outline-none sm:text-sm"
                            :class="{ 'border-red-500': form.errors.email }"
                            placeholder="Enter your email"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-400">
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full cursor-pointer rounded-md bg-sky-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-600 focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <span v-if="form.processing" class="inline-flex items-center">
                            <svg
                                class="mr-2 -ml-1 h-4 w-4 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            Subscribing...
                        </span>
                        <span v-else>Subscribe</span>
                    </button>
                </form>
            </div>

            <!-- Contact Info & Social -->
            <div>
                <h3 class="mb-4 text-2xl font-extrabold text-sky-500">Contact Us</h3>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <MailIcon class="text-white" />
                        <a :href="`mailto:${getContent('email')}`" class="transition hover:text-sky-500">
                            {{ getContent('email') }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <PhoneIcon class="text-white" />
                        <a :href="`tel:${getContent('phone')?.replace(/\s/g, '')}`" class="transition hover:text-sky-500">
                            {{ getContent('phone') }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <MapPinIcon class="text-white" />
                        {{ getContent('address') }}
                    </li>
                </ul>

                <div class="mt-8 flex space-x-6">
                    <a
                        v-if="getSocialUrl('footer_facebook_url', 'facebook')"
                        :href="getSocialUrl('footer_facebook_url', 'facebook')"
                        target="_blank"
                        rel="noreferrer"
                        aria-label="Facebook"
                        class="transition hover:text-sky-500"
                    >
                        <FacebookIcon class="h-7 w-7" />
                    </a>
                    <a
                        v-if="getSocialUrl('footer_twitter_url', 'twitter')"
                        :href="getSocialUrl('footer_twitter_url', 'twitter')"
                        target="_blank"
                        rel="noreferrer"
                        aria-label="X"
                        class="transition hover:text-sky-500"
                    >
                        <XIcon class="h-7 w-7" />
                    </a>
                    <a
                        v-if="getSocialUrl('footer_instagram_url', 'instagram')"
                        :href="getSocialUrl('footer_instagram_url', 'instagram')"
                        target="_blank"
                        rel="noreferrer"
                        aria-label="Instagram"
                        class="transition hover:text-sky-500"
                    >
                        <InstagramIcon class="h-7 w-7" />
                    </a>
                    <a
                        v-if="getSocialUrl('footer_linkedin_url', 'linkedin')"
                        :href="getSocialUrl('footer_linkedin_url', 'linkedin')"
                        target="_blank"
                        rel="noreferrer"
                        aria-label="LinkedIn"
                        class="transition hover:text-sky-500"
                    >
                        <LinkedinIcon class="h-7 w-7" />
                    </a>
                    <a
                        v-if="getSocialUrl('footer_telegram_url', 'telegram')"
                        :href="getSocialUrl('footer_telegram_url', 'telegram')"
                        target="_blank"
                        rel="noreferrer"
                        aria-label="Telegram"
                        class="transition hover:text-sky-500"
                    >
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121L9.864 13.96l-2.91-.918c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.954z"
                            />
                        </svg>
                    </a>
                    <a
                        v-if="getSocialUrl('footer_tiktok_url', 'tiktok')"
                        :href="getSocialUrl('footer_tiktok_url', 'tiktok')"
                        target="_blank"
                        rel="noreferrer"
                        aria-label="TikTok"
                        class="transition hover:text-sky-500"
                    >
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"
                            />
                        </svg>
                    </a>
                    <a
                        v-if="getSocialUrl('footer_whatsapp_url', 'whatsapp')"
                        :href="getSocialUrl('footer_whatsapp_url', 'whatsapp')"
                        target="_blank"
                        rel="noreferrer"
                        aria-label="WhatsApp"
                        class="transition hover:text-sky-500"
                    >
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.488"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <hr class="mx-auto my-8 max-w-7xl border-white/30" />

        <div class="text-center text-sm text-white/80 select-none">
            &copy; {{ new Date().getFullYear() }} {{ getContent('footer_organization_name') || 'Ethiopian Kidney Association' }}. All rights reserved.
        </div>

        <div class="mt-2 text-center text-xs font-medium tracking-wide text-white/70 select-none">
            <span>{{ getContent('footer_developed_text') || 'Designed & Developed by' }} </span>
            <a
                :href="getContent('footer_developer_url') || 'https://degantechnologies.com'"
                target="_blank"
                rel="noreferrer"
                class="ml-1 text-sky-500 underline hover:text-sky-600"
            >
                {{ getContent('footer_developer_name') || 'Degan Technologies' }}
            </a>
        </div>
    </footer>
</template>
