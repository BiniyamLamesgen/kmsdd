<template>
    <section class="mx-auto max-w-4xl px-4 py-12">
        <h1 class="mb-12 text-center text-3xl font-extrabold text-sky-500">Frequently Asked Questions (FAQs)</h1>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div
                v-for="(faq, index) in props.frequentlyAskedQuestions.data"
                :key="faq.id"
                :class="[
                    'rounded-2xl border border-gray-300 bg-white/80 transition-shadow duration-300 hover:bg-sky-50',
                    openIndex === index ? 'ring-2 ring-sky-400' : '',
                ]"
            >
                <button
                    class="group flex w-full cursor-pointer items-center justify-between px-8 py-6 text-left text-lg font-semibold text-sky-800 transition-colors duration-200 focus:outline-none"
                    @click="toggle(index)"
                    :aria-expanded="openIndex === index"
                >
                    <span class="transition-colors duration-200 group-hover:text-sky-600">
                        {{ faq.question }}
                    </span>
                    <span
                        :class="[
                            'ml-4 text-2xl font-bold transition-transform duration-300',
                            openIndex === index ? 'rotate-180 text-sky-500' : 'text-gray-400',
                        ]"
                        aria-hidden="true"
                    >
                        {{ openIndex === index ? '−' : '+' }}
                    </span>
                </button>

                <div
                    class="overflow-hidden transition-all duration-500"
                    :style="{
                        maxHeight: openIndex === index ? '200px' : '0',
                        opacity: openIndex === index ? 1 : 0,
                        padding: openIndex === index ? '1.5rem 2rem' : '0 2rem',
                        background: openIndex === index ? '#f9fafb' : 'transparent',
                        borderTop: openIndex === index ? '1px solid #e5e7eb' : 'none',
                    }"
                >
                    <div class="text-base leading-relaxed text-gray-700">
                        {{ faq.answer }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script lang="ts" setup>
import type { ServerPaginatedResponse } from '@/types/datatable';
import { ref } from 'vue';

type FrequentlyAskedQuestion = {
    id: number;
    question: string;
    answer: string;
};

const props = defineProps<{
    frequentlyAskedQuestions: ServerPaginatedResponse<FrequentlyAskedQuestion>;
}>();

const openIndex = ref<number | null>(null);

const toggle = (index: number) => {
    openIndex.value = openIndex.value === index ? null : index;
};
</script>
