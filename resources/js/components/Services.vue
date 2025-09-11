<template>
    <section id="services" class="bg-blue-50 py-20">
        <div class="mx-auto max-w-7xl px-6 text-center lg:px-20">
            <h2 class="mb-12 text-3xl font-extrabold text-sky-500">Our Services</h2>

            <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="service in currentServices"
                    :key="service.title"
                    :id="`service-${formatServiceId(service.title)}`"
                    class="cursor-pointer rounded-xl bg-white p-6 shadow-md transition-all duration-300 hover:shadow-lg"
                    :class="highlightedService === service.title ? 'bg-sky-50 font-bold ring-4 ring-sky-400' : ''"
                >
                    <component :is="service.icon" class="mx-auto mb-4 text-sky-500" size="48" />
                    <h3 class="mb-2 text-xl font-semibold text-sky-900" :class="highlightedService === service.title ? 'font-bold' : ''">
                        {{ service.title }}
                    </h3>
                    <p class="text-sm leading-relaxed text-sky-800" :class="highlightedService === service.title ? 'font-medium' : ''">
                        {{ service.description }}
                    </p>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div class="mt-10 flex justify-center space-x-3">
                <button
                    v-for="number in pageNumbers"
                    :key="number"
                    @click="setCurrentPage(number)"
                    :class="[
                        'cursor-pointer rounded px-4 py-2 transition hover:bg-sky-500 hover:text-white',
                        number === currentPage ? 'bg-sky-600 text-white' : 'border border-sky-600 bg-white text-sky-600',
                    ]"
                    :aria-current="number === currentPage ? 'page' : undefined"
                >
                    {{ number }}
                </button>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { BookOpenCheck, FileBarChart2, Landmark, Megaphone, Microscope, Stethoscope, Syringe, Users } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

type Service = {
    icon: any; // Vue component
    title: string;
    description: string;
};

const services: Service[] = [
    {
        icon: Users,
        title: 'Community-Based CKD Screening',
        description: 'Offering kidney disease screening services directly within local communities for early detection and intervention.',
    },
    {
        icon: BookOpenCheck,
        title: 'CME & Capacity-Building',
        description: 'Organizing Continuing Medical Education (CME) programs and training for healthcare workers to improve kidney care.',
    },
    {
        icon: Megaphone,
        title: 'Public Awareness & Education',
        description: 'Running campaigns to educate the public about kidney health, prevention, and treatment options.',
    },
    {
        icon: Stethoscope,
        title: 'Nephrology Workshop Facilitation',
        description: 'Hosting specialized workshops to enhance skills and knowledge in nephrology for medical professionals.',
    },
    {
        icon: Landmark,
        title: 'Advocacy and Policy Engagement',
        description: 'Working with government and stakeholders to influence health policy and improve kidney care systems.',
    },
    {
        icon: Microscope,
        title: 'Research and Data Collection',
        description: 'Conducting studies and collecting data to inform decision-making and improve kidney disease outcomes.',
    },
    {
        icon: Syringe,
        title: 'Hemodialysis Access Advocacy',
        description: 'Advocating for better access to affordable and quality hemodialysis services across Ethiopia.',
    },
    {
        icon: FileBarChart2,
        title: 'Health Program Evaluation',
        description: 'Assessing the effectiveness of kidney health initiatives to inform future planning and strategy.',
    },
];

const currentPage = ref(1);
const itemsPerPage = 4;

const indexOfLast = () => currentPage.value * itemsPerPage;
const indexOfFirst = () => indexOfLast() - itemsPerPage;

const currentServices = ref(services.slice(indexOfFirst(), indexOfLast()));

const totalPages = Math.ceil(services.length / itemsPerPage);
const pageNumbers = Array.from({ length: totalPages }, (_, i) => i + 1);

// Highlight state for services
const highlightedService = ref<string | null>(null);

// Helper function to format service titles consistently
const formatServiceId = (title: string): string => {
    return title.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-');
};

onMounted(() => {
    function handleScrollToService(event: CustomEvent) {
        const { title } = event.detail;

        // Clear any existing highlights
        highlightedService.value = null;

        if (title === 'section') {
            // Just scroll to the services section
            setTimeout(() => {
                const section = document.getElementById('services');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 200);
        } else {
            // Find specific service and navigate to it
            const serviceIndex = services.findIndex((s) => s.title === title);
            if (serviceIndex !== -1) {
                const targetPage = Math.floor(serviceIndex / itemsPerPage) + 1;
                const servicesSection = document.getElementById('services');

                if (!servicesSection) return;

                // Check if we're already in services area (more lenient threshold)
                const servicesRect = servicesSection.getBoundingClientRect();
                const isInServicesArea = servicesRect.top <= window.innerHeight * 0.5 && servicesRect.bottom >= 0;

                if (currentPage.value !== targetPage) {
                    // Store current viewport position relative to services section

                    // Change page to show the target service
                    currentPage.value = targetPage;
                    currentServices.value = services.slice(indexOfFirst(), indexOfLast());

                    requestAnimationFrame(() => {
                        setTimeout(() => {
                            const serviceId = `service-${formatServiceId(title)}`;
                            const serviceElement = document.getElementById(serviceId);

                            if (serviceElement) {
                                if (isInServicesArea) {
                                    // We're in services area, just scroll to the specific service smoothly
                                    serviceElement.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center',
                                        inline: 'nearest',
                                    });
                                } else {
                                    // Not in services area, calculate optimal position
                                    const serviceRect = serviceElement.getBoundingClientRect();
                                    const absoluteServiceTop = serviceRect.top + window.pageYOffset;
                                    const viewportHeight = window.innerHeight;
                                    const serviceHeight = serviceRect.height;
                                    const centerOffset = (viewportHeight - serviceHeight) / 2;
                                    const targetScrollPosition = absoluteServiceTop - centerOffset;
                                    const minScrollPosition = servicesSection.offsetTop - 80;
                                    const finalScrollPosition = Math.max(targetScrollPosition, minScrollPosition);

                                    window.scrollTo({
                                        top: finalScrollPosition,
                                        behavior: 'smooth',
                                    });
                                }
                            }

                            // Highlight the service
                            highlightedService.value = title;
                            setTimeout(() => {
                                highlightedService.value = null;
                            }, 3000);
                        }, 100);
                    });
                } else {
                    // Service is on current page
                    const serviceId = `service-${formatServiceId(title)}`;
                    const serviceElement = document.getElementById(serviceId);

                    if (serviceElement) {
                        if (isInServicesArea) {
                            // We're in services area, just scroll to the specific service
                            serviceElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center',
                                inline: 'nearest',
                            });
                        } else {
                            // Not in services area, calculate optimal position
                            const serviceRect = serviceElement.getBoundingClientRect();
                            const absoluteServiceTop = serviceRect.top + window.pageYOffset;
                            const viewportHeight = window.innerHeight;
                            const serviceHeight = serviceRect.height;
                            const centerOffset = (viewportHeight - serviceHeight) / 2;
                            const targetScrollPosition = absoluteServiceTop - centerOffset;
                            const minScrollPosition = servicesSection.offsetTop - 80;
                            const finalScrollPosition = Math.max(targetScrollPosition, minScrollPosition);

                            window.scrollTo({
                                top: finalScrollPosition,
                                behavior: 'smooth',
                            });
                        }
                    }

                    // Highlight the service immediately
                    highlightedService.value = title;
                    setTimeout(() => {
                        highlightedService.value = null;
                    }, 3000);
                }
            }
        }
    }

    window.addEventListener('scroll-to-service', handleScrollToService as EventListener);

    onUnmounted(() => {
        window.removeEventListener('scroll-to-service', handleScrollToService as EventListener);
    });
});

// When paginating, update the current services while preserving scroll context
function setCurrentPage(page: number) {
    const servicesSection = document.getElementById('services');
    if (!servicesSection) {
        currentPage.value = page;
        currentServices.value = services.slice(indexOfFirst(), indexOfLast());
        return;
    }

    // Check if we're currently viewing the services section
    const servicesRect = servicesSection.getBoundingClientRect();
    const isViewingServices = servicesRect.top <= window.innerHeight * 0.5 && servicesRect.bottom >= 0;

    if (isViewingServices) {
        // Store the current scroll position relative to the services section
        const relativeScrollPosition = window.pageYOffset - servicesSection.offsetTop;

        currentPage.value = page;
        currentServices.value = services.slice(indexOfFirst(), indexOfLast());

        // Maintain the scroll position within the services section
        requestAnimationFrame(() => {
            const newScrollPosition = servicesSection.offsetTop + relativeScrollPosition;
            window.scrollTo({
                top: newScrollPosition,
                behavior: 'auto',
            });
        });
    } else {
        // Normal pagination when not viewing services
        currentPage.value = page;
        currentServices.value = services.slice(indexOfFirst(), indexOfLast());
    }
}
</script>
