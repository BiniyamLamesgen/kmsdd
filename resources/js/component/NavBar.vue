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
    <nav class="glass-navbar sticky top-0 sm:top-2 z-50 max-w-7xl mx-auto">
        <div class="mx-auto max-w-7xl px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <Link
                    href="/"
                    class="flex items-center gap-3 min-w-0"
                    @click="closeMenus"
                >
                    <img
                        src="/images/img.jpg"
                        alt="Logo"
                        class="h-10 w-10 md:h-12 md:w-12 shadow-md flex-shrink-0 rounded-full border border-gray-300"
                    />
                    <span class="flex flex-col leading-tight min-w-0">
                        <span
                            class="text-white font-bold text-lg md:text-xl truncate"
                        >
                            Lemi Kura Sub City
                        </span>
                        <span
                            class="text-sky-400 text-xs md:text-sm font-medium truncate"
                        >
                            Knowledge Management
                        </span>
                    </span>
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <template v-for="link in navLinks" :key="link.href">
                        <Link
                            :href="link.href"
                            @click="
                                (event) => handleAnchorClick(link.href, event)
                            "
                            class="nav-link text-white hover:text-sky-400 px-2 py-1 transition font-semibold relative"
                        >
                            {{ link.name }}
                        </Link>
                    </template>
                </div>

                <!-- Mobile Button -->
                <button
                    @click="toggleMenu"
                    class="md:hidden text-white hover:text-sky-300 focus:outline-none"
                >
                    <svg
                        v-if="!isOpen"
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7"
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
                        class="h-7 w-7"
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
            <div v-if="isOpen" class="md:hidden glass-navbar-mobile">
                <div class="mx-auto max-w-6xl px-4 py-3 space-y-2">
                    <template v-for="link in navLinks" :key="link.href">
                        <Link
                            :href="link.href"
                            @click="
                                (event) => handleAnchorClick(link.href, event)
                            "
                            class="block px-4 py-2 text-white hover:text-sky-400 hover:bg-sky-900/20 rounded transition font-semibold"
                        >
                            {{ link.name }}
                        </Link>
                    </template>
                </div>
            </div>
        </Transition>
    </nav>
</template>

<style scoped>
.glass-navbar {
    background: rgba(15, 23, 42, 0.85); /* blue-black glass */
    backdrop-filter: blur(14px) saturate(160%);
    -webkit-backdrop-filter: blur(14px) saturate(160%);
    border-radius: 10px 10px 10px 10px; /* curved bottom */
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.glass-navbar-mobile {
    background: rgba(30, 41, 59, 0.9);
    backdrop-filter: blur(12px) saturate(160%);
    -webkit-backdrop-filter: blur(12px) saturate(160%);
    border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
}
.nav-link::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 0;
    height: 2px;
    background: #38bdf8; /* sky-400 */
    transition: width 0.3s;
}
.nav-link:hover::after {
    width: 100%;
}
</style>
