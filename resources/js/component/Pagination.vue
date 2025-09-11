<script setup>
import { computed } from "vue";

const props = defineProps({
    page: Number,
    total: Number,
    perPage: Number,
});
const emit = defineEmits(["update:page", "update:perPage"]);

const totalPages = computed(() => Math.ceil(props.total / props.perPage));

const prev = () => {
    if (props.page > 1) emit("update:page", props.page - 1);
};
const next = () => {
    if (props.page < totalPages.value) emit("update:page", props.page + 1);
};
</script>
<template>
    <div
        v-if="totalPages > 1"
        class="flex flex-col items-center space-y-4 mt-6"
    >
        <div class="flex justify-center items-center space-x-3">
            <button
                @click="prev"
                :disabled="page === 1"
                :class="[
                    'flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    page === 1
                        ? 'text-slate-400 bg-slate-200 cursor-not-allowed'
                        : 'text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800',
                ]"
            >
                <svg
                    class="w-4 h-4 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Previous
            </button>

            <div
                class="flex items-center px-4 py-2 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded-lg"
            >
                Page {{ page }} of {{ totalPages }}
            </div>

            <button
                @click="next"
                :disabled="page === totalPages"
                :class="[
                    'flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200',
                    page === totalPages
                        ? 'text-slate-400 bg-slate-200 cursor-not-allowed'
                        : 'text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800',
                ]"
            >
                Next
                <svg
                    class="w-4 h-4 ml-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-3 text-sm">
            <label for="perPage" class="text-gray-600 font-medium">
                Rows per page:
            </label>
            <select
                id="perPage"
                :value="perPage"
                @change="emit('update:perPage', Number($event.target.value))"
                class="border border-gray-300 rounded-md px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
            >
                <option
                    v-for="n in [3, 6, 10, 12, 24, 50]"
                    :key="n"
                    :value="n"
                    :selected="n === perPage"
                >
                    {{ n }}
                </option>
            </select>
            <span class="text-gray-500 text-xs">
                (Showing {{ perPage }} per page)
            </span>
        </div>
    </div>
</template>
