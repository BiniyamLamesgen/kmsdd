<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronDown, Menu, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface NavLink {
    name: string;
    href: string;
    dropdown?: boolean;
}

const open = ref(false);
const servicesDropdown = ref(false);

const navLinks: NavLink[] = [
    { name: 'Home', href: '/' },
    { name: 'About', href: '#about' },
    { name: 'News', href: '#news' },
    { name: 'Services', href: '#services', dropdown: true },
    { name: 'Our Kidney', href: '/ourkidney' },
    { name: 'Contact', href: '/contactUs' },
];

const serviceTitles = [
    'Community-Based CKD Screening',
    'CME & Capacity-Building',
    'Public Awareness & Education',
    'Nephrology Workshop Facilitation',
    'Advocacy and Policy Engagement',
    'Research and Data Collection',
    'Hemodialysis Access Advocacy',
    'Health Program Evaluation',
];

const toggleMenu = () => {
    open.value = !open.value;
};

const toggleServicesDropdown = () => {
    servicesDropdown.value = !servicesDropdown.value;
};

const closeMenus = () => {
    open.value = false;
    servicesDropdown.value = false;
};

const handleAnchorClick = (href: string, event: Event) => {
    console.log('Navigation clicked:', href, 'Current path:', window.location.pathname);
    closeMenus();

    if (href.startsWith('#')) {
        // Handle anchor links - prevent default and scroll
        event.preventDefault();

        if (window.location.pathname === '/') {
            const id = href.substring(1);
            const el = document.getElementById(id);
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        } else {
            window.location.href = `/${href}`;
        }
    }
    // For all other links (including /ourkidney, /contact, /registration), let Inertia handle them naturally
    // Don't prevent default, don't do anything special
};

const handleServiceDropdownClick = (title: string) => {
    closeMenus();

    // Check if we're already in the services section area
    const servicesSection = document.getElementById('services');
    const isInServicesArea = servicesSection && window.pageYOffset >= servicesSection.offsetTop - 200;

    if (window.location.pathname === '/') {
        if (isInServicesArea) {
            // Already in services area, just trigger service highlight without scrolling to top
            window.dispatchEvent(new CustomEvent('scroll-to-service', { detail: { title } }));
        } else {
            // Not in services area, scroll to services section first
            if (servicesSection) {
                servicesSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Wait for services section scroll to complete, then trigger service highlight
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('scroll-to-service', { detail: { title } }));
            }, 800);
        }
    } else {
        // Navigate to home page with services anchor
        window.location.href = `/#services`;

        // Wait for page load and then scroll to specific service
        setTimeout(() => {
            const servicesSection = document.getElementById('services');
            if (servicesSection) {
                servicesSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Additional delay for service highlighting after page navigation
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('scroll-to-service', { detail: { title } }));
            }, 500);
        }, 1000);
    }
};
</script>

<template>
    <nav class="fixed top-0 right-0 left-0 z-50 border-b border-blue-400 bg-blue-300 shadow-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-3" @click="closeMenus">
                    <div class="relative h-14 w-14">
                        <img src="/images/img.png" alt="Kidney Association Logo" class="h-full w-full rounded-full object-cover" />
                    </div>
                    <span class="hidden text-sm leading-tight font-semibold tracking-tight text-white capitalize sm:block lg:text-base xl:text-lg">
                        Ethiopian Kidney<br />
                        Association
                    </span>
                </Link>

                <!-- Desktop Nav -->
                <div class="hidden items-center space-x-6 md:flex">
                    <template v-for="link in navLinks" :key="link.href">
                        <div v-if="link.dropdown" class="group relative" @mouseenter="servicesDropdown = true" @mouseleave="servicesDropdown = false">
                            <button
                                @click.prevent="toggleServicesDropdown"
                                class="flex cursor-pointer items-center gap-1 text-sm font-medium text-white transition hover:text-sky-200"
                            >
                                {{ link.name }}
                                <ChevronDown
                                    :class="['transition-transform duration-200', servicesDropdown ? 'rotate-180' : 'rotate-0']"
                                    color="white"
                                />
                            </button>
                            <div
                                class="absolute left-1/2 z-50 mt-6 w-72 -translate-x-1/2 rounded-xl border border-blue-200 bg-blue-300 py-2 shadow-2xl transition-all duration-200"
                                :class="servicesDropdown ? 'visible scale-100 opacity-100' : 'invisible scale-95 opacity-0'"
                            >
                                <button
                                    v-for="title in serviceTitles"
                                    :key="title"
                                    @click="handleServiceDropdownClick(title)"
                                    class="block w-full cursor-pointer rounded px-5 py-2 text-left text-sm font-medium text-sky-700 transition hover:bg-sky-100 hover:text-sky-900"
                                >
                                    {{ title }}
                                </button>
                            </div>
                        </div>
                        <Link
                            v-else
                            :href="link.href"
                            @click="(event) => handleAnchorClick(link.href, event)"
                            class="text-sm font-medium text-white transition hover:text-sky-200"
                        >
                            {{ link.name }}
                        </Link>
                    </template>

                    <Link
                        href="/eka/donation-accounts"
                        class="rounded-full bg-sky-500 px-4 py-1.5 text-sm font-semibold text-white shadow transition hover:bg-sky-600"
                        >Donate</Link
                    >
                    <Link
                        href="/become-member/registration"
                        class="rounded-full bg-sky-500 px-4 py-1.5 text-sm font-semibold text-white shadow transition hover:bg-sky-600"
                        >Become a Member</Link
                    >
                </div>

                <!-- Mobile Hamburger -->
                <button @click="toggleMenu" aria-label="Toggle Mobile Menu" class="p-2 text-sky-700 focus:outline-none md:hidden">
                    <component :is="open ? X : Menu" :size="28" color="white" />
                </button>
            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div
            class="overflow-hidden border-t border-blue-400 bg-blue-300 transition-all duration-300 ease-in-out md:hidden"
            :class="open ? 'max-h-[1000px] p-4' : 'max-h-0 p-0'"
        >
            <div class="flex flex-col space-y-4 text-center">
                <template v-for="link in navLinks" :key="link.href">
                    <div v-if="link.dropdown">
                        <button class="flex w-full items-center justify-center gap-2 font-medium text-white" @click="toggleServicesDropdown">
                            {{ link.name }}
                            <ChevronDown
                                :class="['transition-transform duration-200', servicesDropdown ? 'rotate-180' : 'rotate-0']"
                                :size="18"
                                color="white"
                            />
                        </button>
                        <div v-show="servicesDropdown" class="mt-2 rounded-xl border border-blue-400 bg-blue-100 p-2 shadow-md">
                            <button
                                v-for="title in serviceTitles"
                                :key="title"
                                @click="handleServiceDropdownClick(title)"
                                class="block w-full rounded bg-blue-300 px-5 py-2 text-left text-sm font-medium text-white transition hover:text-white"
                            >
                                {{ title }}
                            </button>
                        </div>
                    </div>
                    <Link
                        v-else
                        :href="link.href"
                        @click="(event) => handleAnchorClick(link.href, event)"
                        class="text-base font-medium text-white transition hover:text-sky-200"
                    >
                        {{ link.name }}
                    </Link>
                </template>

                <div class="flex flex-col items-center gap-2 pt-2">
                    <Link
                        href="/eka/donation-accounts"
                        class="rounded-full bg-sky-500 px-4 py-1.5 text-sm font-semibold text-white shadow transition hover:bg-sky-600"
                        >Donate</Link
                    >
                    <Link
                        href="/become-member/registration"
                        class="rounded-full bg-sky-500 px-4 py-1.5 text-sm font-semibold text-white shadow transition hover:bg-sky-600"
                        >Become a Member</Link
                    >
                </div>
            </div>
        </div>
    </nav>
</template>
