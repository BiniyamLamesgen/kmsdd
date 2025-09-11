<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Star } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { z } from 'zod';
import { Button } from '../../components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../../components/ui/card';
import { Input } from '../../components/ui/input';
import { Label } from '../../components/ui/label';
import AppLayout from '../../layouts/AppLayout.vue';

interface EmployeeOption { id: number; name: string; }
interface Props {
	employees?: EmployeeOption[]; // added
	breadcrumbs?: Array<{ title: string; href: string }>;

}

withDefaults(defineProps<Props>(), {
	employees: () => [], 
	breadcrumbs: () => [
		{ title: 'Experiences', href: route('experiences.index') },
		{ title: 'Create', href: '' },
	],
});

const form = useForm({
	employee_id: '' as string | number,
	company_name: '',
	position: '',
	start_date: '',
	end_date: '',
	responsibilities: '',
});

const experienceSchema = z.object({
	employee_id: z.union([z.string().min(1), z.number()]).transform((v) => (typeof v === 'string' ? v.trim() : v)),
	company_name: z.string().min(1, 'Company name is required').max(255),
	position: z.string().min(1, 'Position is required').max(255),
	start_date: z.string().optional().or(z.literal('')),
	end_date: z.string().optional().or(z.literal('')),
	responsibilities: z.string().max(5000).optional().or(z.literal('')),
}).refine((data) => {
	if (data.start_date && data.end_date) {
		return new Date(data.end_date) >= new Date(data.start_date);
	}
	return true;
}, { message: 'End date must be after or equal to start date', path: ['end_date'] });

const handleSubmit = () => {
	const result = experienceSchema.safeParse(form.data());
	if (!result.success) {
		form.errors = {};
		result.error.issues.forEach((err) => {
			if (err.path && err.path[0]) {
				form.errors[err.path[0] as keyof typeof form.errors] = err.message;
			}
		});
		return;
	}

	form.post(route('experiences.store'), {
		onSuccess: () => router.visit(route('experiences.index')),
	});
};

const handleCancel = () => {
	router.visit(route('experiences.index'));
};
</script>

<template>
	<Head title="Create Experience" />
	<AppLayout :breadcrumbs="breadcrumbs">
		<div class="mx-auto max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
			<!-- Header -->
			<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
				<div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
					<Button variant="outline" size="sm" @click="handleCancel" class="w-fit cursor-pointer">
						<ArrowLeft class="mr-2 h-4 w-4" />
						Back
					</Button>
					<div>
						<h1 class="text-2xl font-semibold text-gray-900 sm:text-3xl dark:text-gray-100">Create Experience</h1>
						<p class="text-sm text-gray-600 sm:text-base dark:text-gray-400">Add a new experience for an employee.</p>
					</div>
				</div>
			</div>

			<form @submit.prevent="handleSubmit" class="space-y-6">
				<div class="grid grid-cols-1 gap-6 lg:gap-8 xl:grid-cols-2">
					<!-- Experience Information -->
					<Card class="xl:col-span-2">
						<CardHeader>
							<CardTitle class="flex items-center space-x-2">
								<Star class="h-5 w-5" />
								<span>Experience Information</span>
							</CardTitle>
							<CardDescription> Enter the details for the new experience. </CardDescription>
						</CardHeader>
						<CardContent>
							<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
								<!-- employee select (replaces free input) -->
								<div class="space-y-2">
									<Label for="employee_id">Employee *</Label>
									<select
										id="employee_id"
										v-model="form.employee_id"
										class="w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
									>
										<option value="">Select employee</option>
										<option v-for="e in employees" :key="e.id" :value="e.id">{{ e.name }}</option>
									</select>
									<p v-if="form.errors.employee_id" class="text-sm text-red-600">{{ form.errors.employee_id }}</p>
								</div>

								<div class="space-y-2">
									<Label for="company_name">Company *</Label>
									<Input
										id="company_name"
										v-model="form.company_name"
										placeholder="Company name"
										:class="{ 'border-red-500': form.errors.company_name }"
										class="w-full"
									/>
									<p v-if="form.errors.company_name" class="text-sm text-red-600">{{ form.errors.company_name }}</p>
								</div>

								<div class="space-y-2">
									<Label for="position">Position *</Label>
									<Input
										id="position"
										v-model="form.position"
										placeholder="Position"
										:class="{ 'border-red-500': form.errors.position }"
										class="w-full"
									/>
									<p v-if="form.errors.position" class="text-sm text-red-600">{{ form.errors.position }}</p>
								</div>

								<div class="space-y-2">
									<Label for="start_date">Start Date</Label>
									<Input id="start_date" type="date" v-model="form.start_date" :class="{ 'border-red-500': form.errors.start_date }" class="w-full" />
									<p v-if="form.errors.start_date" class="text-sm text-red-600">{{ form.errors.start_date }}</p>
								</div>

								<div class="space-y-2">
									<Label for="end_date">End Date</Label>
									<Input id="end_date" type="date" v-model="form.end_date" :class="{ 'border-red-500': form.errors.end_date }" class="w-full" />
									<p v-if="form.errors.end_date" class="text-sm text-red-600">{{ form.errors.end_date }}</p>
								</div>

								<div class="space-y-2 md:col-span-2 lg:col-span-1">
									<Label for="responsibilities">Responsibilities</Label>
									<textarea
										id="responsibilities"
										v-model="form.responsibilities"
										class="w-full rounded border px-3 py-2 text-sm"
										rows="4"
										placeholder="Describe responsibilities..."
									></textarea>
									<p v-if="form.errors.responsibilities" class="text-sm text-red-600">{{ form.errors.responsibilities }}</p>
									<p class="text-xs text-gray-500 dark:text-gray-400">Optional: describe the role responsibilities</p>
								</div>

								
							</div>
						</CardContent>
					</Card>
				</div>

				<!-- Form Actions -->
				<div class="flex flex-col items-center justify-end space-y-3 border-t pt-6 sm:flex-row sm:space-y-0 sm:space-x-4">
					<Button type="button" variant="outline" @click="handleCancel" class="order-2 w-full cursor-pointer sm:order-1 sm:w-auto">
						Cancel
					</Button>
					<Button
						type="submit"
						:loading="form.processing"
						class="order-1 w-full cursor-pointer sm:order-2 sm:w-auto"
						:disabled="form.errors.employee_id || form.errors.company_name || form.errors.position"
					>
						Create Experience
					</Button>
				</div>
			</form>
		</div>
	</AppLayout>
</template>
