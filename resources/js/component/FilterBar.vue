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
            class="border rounded-lg px-3 py-2 w-full sm:w-1/3"
        />
        <select v-model="department" class="border rounded-lg px-3 py-2">
            <option value="">All Departments</option>
            <option v-for="d in props.departments" :key="d" :value="d">
                {{ d }}
            </option>
        </select>
        <input
            v-model="skill"
            type="text"
            placeholder="Filter by skill..."
            class="border rounded-lg px-3 py-2 w-full sm:w-1/3"
        />
    </div>
</template>
