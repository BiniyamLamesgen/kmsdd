<script setup lang="ts">
import { ref, watch } from "vue";
const emit = defineEmits(["filter"]);

const search = ref("");
const department = ref("");
const skill = ref("");
const props = defineProps<{
    departments: string[];
}>();

watch([search, department, skill], () => {
    emit("filter", {
        search: search.value,
        department: department.value,
        skill: skill.value,
    });
});
</script>
<template>
    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-xl shadow"
    >
        <input
            v-model="search"
            type="text"
            placeholder="Search by name..."
            class="border rounded-lg px-3 py-2 w-full sm:w-1/3 placeholder-black text-black"
        />
        <select
            v-model="department"
            class="border rounded-lg px-3 py-2 bg-white text-black w-full sm:w-1/3 placeholder-black pr-8 appearance-none"
            style="
                background-image: url('data:image/svg+xml;utf8,<svg fill=\'none\' stroke=\'%23000000\' stroke-width=\'2\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19 9l-7 7-7-7\'/></svg>');
                background-repeat: no-repeat;
                background-position: right 0.75rem center;
                background-size: 1.25em 1.25em;
            "
        >
            <option value="">All Departments</option>
            <option v-for="d in props.departments" :key="d" :value="d">
                {{ d }}
            </option>
        </select>
        <input
            v-model="skill"
            type="text"
            placeholder="Filter by skill..."
            class="border rounded-lg px-3 py-2 w-full sm:w-1/3 placeholder-black text-black"
        />
    </div>
</template>
