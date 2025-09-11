<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

interface NavLink {
    name: string;
    href: string;
}

const isOpen = ref(false);

const navLinks: NavLink[] = [
    { name: "Home", href: "/" },
    { name: "About", href: "#about" },
    { name: "Gallery", href: "#gallery" },
    { name: "Knowledge Management", href: "/frontpage" },
];

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

const closeMenus = () => {
    isOpen.value = false;
};

const handleAnchorClick = (href: string, event: Event) => {
    closeMenus();
    if (href.startsWith("#")) {
        event.preventDefault();
        if (window.location.pathname === "/") {
            const id = href.substring(1);
            const el = document.getElementById(id);
            if (el) el.scrollIntoView({ behavior: "smooth" });
        } else {
            window.location.href = `/${href}`;
        }
    }
    // For /frontpage, let Inertia handle navigation
};
</script>

<template>
    <nav
        class="bg-blue-400 shadow-xl fixed w-full z-50 border-b border-slate-700"
    >
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            <div class="flex justify-between h-12 sm:h-14 lg:h-16 items-center">
                <!-- Logo -->
                <Link
                    href="/"
                    class="flex items-center gap-2 sm:gap-3 hover:opacity-80 transition-opacity duration-200 min-w-0 flex-1 md:flex-initial"
                    @click="closeMenus"
                >
                    <img
                        src="/images/img.jpg"
                        alt="KMS Logo"
                        class="h-8 w-12 sm:h-10 sm:w-15 shadow-sm flex-shrink-0"
                    />
                    <span
                        class="text-sm sm:text-lg lg:text-2xl font-extrabold text-white tracking-tight truncate"
                        >Lemi Kura SubCity Administration</span
                    >
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-1 lg:space-x-8">
                    <template v-for="link in navLinks" :key="link.href">
                        <Link
                            :href="link.href"
                            @click="
                                (event) => handleAnchorClick(link.href, event)
                            "
                            class="nav-link text-white hover:text-white px-2 lg:px-4 py-2 rounded-lg transition-all duration-200 font-medium hover:scale-105 relative group text-sm lg:text-base"
                        >
                            {{ link.name }}
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-white group-hover:w-full transition-all duration-300"
                            ></span>
                        </Link>
                    </template>
                </div>

                <!-- Mobile Button -->
                <button
                    @click="toggleMenu"
                    class="md:hidden text-white hover:text-white focus:outline-none p-1.5 sm:p-2 rounded-lg hover:bg-slate-800 transition-all duration-200 flex-shrink-0"
                >
                    <svg
                        v-if="!isOpen"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 sm:h-6 sm:w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                    <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 sm:h-6 sm:w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-if="isOpen"
                class="md:hidden bg-slate-800 shadow-2xl border-t border-slate-700"
            >
                <div class="px-2 pt-2 pb-3 space-y-1 max-h-64 overflow-y-auto">
                    <template v-for="link in navLinks" :key="link.href">
                        <Link
                            :href="link.href"
                            @click="
                                (event) => handleAnchorClick(link.href, event)
                            "
                            class="mobile-link block px-3 sm:px-4 py-2.5 sm:py-3 text-white hover:text-blue-300 hover:bg-slate-700 rounded-lg transition-all duration-200 font-medium border-l-4 border-transparent text-sm sm:text-base"
                        >
                            {{ link.name }}
                        </Link>
                    </template>
                </div>
            </div>
        </Transition>
    </nav>
</template>

<style scoped></style>
