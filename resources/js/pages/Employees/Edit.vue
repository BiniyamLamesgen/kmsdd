<script setup lang="ts">
import { Head, router, useForm } from "@inertiajs/vue3";
import { ArrowLeft, Star, ImagePlus, Upload } from "lucide-vue-next";
import { z } from "zod";
import { Button } from "../../components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "../../components/ui/card";
import { Input } from "../../components/ui/input";
import { Label } from "../../components/ui/label";
import AppLayout from "../../layouts/AppLayout.vue";
import { route } from "ziggy-js";
import { ref } from "vue";

interface Department {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
}

const props = defineProps<{
    employee: {
        id: number;
        first_name: string;
        last_name: string;
        position: string;
        department: string | null;
        department_id?: number | null;
        email: string;
        phone: string;
        internal_extension: string;
        profile_picture: string | null;
        date_joined: string | null;
        linkedin: string | null;
        facebook: string | null;
        twitter: string | null;
        github: string | null;
        personal_website: string | null;
        languages: string | null;
        mentoring_interest: string | null;
        availability_for_sharing: boolean;
    };
    departments: Department[];
}>();

const breadcrumbs = [
    { title: "Employees", href: route("employees.index") },
    { title: "Edit", href: "" },
];

const imagePreview = ref<string | null>(
    props.employee.profile_picture
        ? `/storage/${props.employee.profile_picture}`
        : null
);
const imageInputRef = ref<HTMLInputElement | null>(null);
const imageError = ref<string | null>(null);

const form = useForm<{
    first_name: string;
    last_name: string;
    position: string;
    department_id: string | number;
    email: string;
    phone: string;
    internal_extension: string;
    profile_picture: File | null;
    date_joined: string;
    linkedin: string;
    facebook: string;
    twitter: string;
    github: string;
    personal_website: string;
    languages: string;
    mentoring_interest: string;
    availability_for_sharing: boolean;
}>({
    first_name: props.employee.first_name ?? "",
    last_name: props.employee.last_name ?? "",
    position: props.employee.position ?? "",
    department_id: props.employee.department_id ?? "",
    email: props.employee.email ?? "",
    phone: props.employee.phone ?? "",
    internal_extension: props.employee.internal_extension ?? "",
    profile_picture: null,
    date_joined: props.employee.date_joined ?? "",
    linkedin: props.employee.linkedin ?? "",
    facebook: props.employee.facebook ?? "",
    twitter: props.employee.twitter ?? "",
    github: props.employee.github ?? "",
    personal_website: props.employee.personal_website ?? "",
    languages: props.employee.languages ?? "",
    mentoring_interest: props.employee.mentoring_interest ?? "",
    availability_for_sharing: props.employee.availability_for_sharing ?? false,
});

const employeeSchema = z.object({
    first_name: z.string().min(1, "First name is required").max(255),
    last_name: z.string().min(1, "Last name is required").max(255),
    position: z.string().min(1, "Position is required").max(255),
    department_id: z.union([z.string(), z.number()]).optional(),
    email: z.string().email("Invalid email address").max(255),
    phone: z.string().max(20).optional(),
    internal_extension: z.string().max(10).optional(),
    profile_picture: z.instanceof(File).optional(),
    date_joined: z.string().optional(),
    linkedin: z.string().max(500).optional(),
    facebook: z.string().max(500).optional(),
    twitter: z.string().max(500).optional(),
    github: z.string().max(500).optional(),
    personal_website: z.string().max(500).optional(),
    languages: z.string().max(500).optional(),
    mentoring_interest: z.string().optional(),
    availability_for_sharing: z.boolean(),
});

function handleImageChange(e: Event) {
    const target = e.target as HTMLInputElement;
    imageError.value = null;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        if (!file.type.startsWith("image/")) {
            imageError.value = "Only image files are allowed";
            form.profile_picture = null;
            imagePreview.value = null;
            return;
        }
        form.profile_picture = file;
        const reader = new FileReader();
        reader.onload = (ev) => {
            imagePreview.value = ev.target?.result as string;
        };
        reader.readAsDataURL(file);
    } else {
        form.profile_picture = null;
        imagePreview.value = props.employee.profile_picture
            ? `/storage/${props.employee.profile_picture}`
            : null;
    }
}

const handleSubmit = () => {
    imageError.value = null;
    const result = employeeSchema.safeParse(form.data());
    form.errors = {};
    if (!result.success) {
        result.error.issues.forEach((err) => {
            if (err.path[0]) {
                form.errors[err.path[0] as keyof typeof form.errors] =
                    err.message;
            }
        });
        return;
    }
    const data = new FormData();
    Object.entries(form.data()).forEach(([key, value]) => {
        if (key === "profile_picture" && value instanceof File) {
            data.append("profile_picture", value);
        } else if (
            key !== "profile_picture" &&
            value !== undefined &&
            value !== null
        ) {
            data.append(key, value as any);
        }
    });
    form.post(route("employees.update", props.employee.id), data);
};

const handleCancel = () => {
    router.visit(route("employees.index"));
};
</script>

<template>
    <Head title="Edit Employee" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-6xl space-y-6 p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div
                    class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4"
                >
                    <Button
                        variant="outline"
                        size="sm"
                        @click="handleCancel"
                        class="w-fit cursor-pointer"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h1
                            class="text-2xl font-semibold text-gray-900 sm:text-3xl dark:text-gray-100"
                        >
                            Edit Employee
                        </h1>
                        <p
                            class="text-sm text-gray-600 sm:text-base dark:text-gray-400"
                        >
                            Update employee details.
                        </p>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="handleSubmit"
                class="space-y-6"
                enctype="multipart/form-data"
            >
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Star class="h-5 w-5" />
                            <span>Employee Information</span>
                        </CardTitle>
                        <CardDescription>
                            Update the fields below and click save.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6"
                        >
                            <div class="space-y-2">
                                <Label for="first_name">First Name *</Label>
                                <Input
                                    id="first_name"
                                    v-model="form.first_name"
                                    placeholder="First name"
                                    :class="{
                                        'border-red-500':
                                            form.errors.first_name,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.first_name"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.first_name }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="last_name">Last Name *</Label>
                                <Input
                                    id="last_name"
                                    v-model="form.last_name"
                                    placeholder="Last name"
                                    :class="{
                                        'border-red-500': form.errors.last_name,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.last_name"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.last_name }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="position">Position *</Label>
                                <Input
                                    id="position"
                                    v-model="form.position"
                                    placeholder="Position"
                                    :class="{
                                        'border-red-500': form.errors.position,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.position"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.position }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="department_id">Department</Label>
                                <select
                                    id="department_id"
                                    v-model="form.department_id"
                                    :class="[
                                        'w-full rounded border px-3 py-2',
                                        form.errors.department_id
                                            ? 'border-red-500'
                                            : 'border-gray-300',
                                    ]"
                                >
                                    <option value="">
                                        Select a department
                                    </option>
                                    <option
                                        v-for="department in props.departments"
                                        :key="department.id"
                                        :value="department.id"
                                    >
                                        {{ department.name }}
                                    </option>
                                </select>
                                <p
                                    v-if="form.errors.department_id"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.department_id }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="email">Email *</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    placeholder="Email"
                                    :class="{
                                        'border-red-500': form.errors.email,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="phone">Phone</Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    placeholder="Phone"
                                    :class="{
                                        'border-red-500': form.errors.phone,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.phone"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.phone }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="internal_extension"
                                    >Internal Extension</Label
                                >
                                <Input
                                    id="internal_extension"
                                    v-model="form.internal_extension"
                                    placeholder="Internal extension"
                                    :class="{
                                        'border-red-500':
                                            form.errors.internal_extension,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.internal_extension"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.internal_extension }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="date_joined">Date Joined</Label>
                                <Input
                                    id="date_joined"
                                    type="date"
                                    v-model="form.date_joined"
                                    :class="{
                                        'border-red-500':
                                            form.errors.date_joined,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.date_joined"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.date_joined }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="linkedin">LinkedIn</Label>
                                <Input
                                    id="linkedin"
                                    v-model="form.linkedin"
                                    placeholder="LinkedIn URL"
                                    :class="{
                                        'border-red-500': form.errors.linkedin,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.linkedin"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.linkedin }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="facebook">Facebook</Label>
                                <Input
                                    id="facebook"
                                    v-model="form.facebook"
                                    placeholder="Facebook URL"
                                    :class="{
                                        'border-red-500': form.errors.facebook,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.facebook"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.facebook }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="twitter">Twitter</Label>
                                <Input
                                    id="twitter"
                                    v-model="form.twitter"
                                    placeholder="Twitter URL"
                                    :class="{
                                        'border-red-500': form.errors.twitter,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.twitter"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.twitter }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="github">GitHub</Label>
                                <Input
                                    id="github"
                                    v-model="form.github"
                                    placeholder="GitHub URL"
                                    :class="{
                                        'border-red-500': form.errors.github,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.github"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.github }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="personal_website"
                                    >Personal Website</Label
                                >
                                <Input
                                    id="personal_website"
                                    v-model="form.personal_website"
                                    placeholder="Personal website URL"
                                    :class="{
                                        'border-red-500':
                                            form.errors.personal_website,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.personal_website"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.personal_website }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="languages">Languages</Label>
                                <Input
                                    id="languages"
                                    v-model="form.languages"
                                    placeholder="Languages"
                                    :class="{
                                        'border-red-500': form.errors.languages,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.languages"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.languages }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="mentoring_interest"
                                    >Mentoring Interest</Label
                                >
                                <Input
                                    id="mentoring_interest"
                                    v-model="form.mentoring_interest"
                                    placeholder="Mentoring interest"
                                    :class="{
                                        'border-red-500':
                                            form.errors.mentoring_interest,
                                    }"
                                    class="w-full"
                                />
                                <p
                                    v-if="form.errors.mentoring_interest"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.mentoring_interest }}
                                </p>
                            </div>
                            <div class="space-y-2">
                                <Label for="availability_for_sharing"
                                    >Available for Sharing</Label
                                >
                                <select
                                    id="availability_for_sharing"
                                    v-model="form.availability_for_sharing"
                                    :class="[
                                        'w-full rounded border px-3 py-2',
                                        form.errors.availability_for_sharing
                                            ? 'border-red-500'
                                            : 'border-gray-300',
                                    ]"
                                >
                                    <option :value="true">Yes</option>
                                    <option :value="false">No</option>
                                </select>
                                <p
                                    v-if="form.errors.availability_for_sharing"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.availability_for_sharing }}
                                </p>
                            </div>
                        </div>

                        <!-- Profile Picture Upload Section - Moved to end with improved UI -->
                        <div class="mt-8 border-t pt-6">
                            <div class="space-y-4">
                                <Label
                                    for="profile_picture"
                                    class="text-lg font-medium"
                                    >Profile Picture</Label
                                >

                                <!-- Drag and Drop Area -->
                                <div
                                    @click="imageInputRef?.click()"
                                    @dragover.prevent
                                    @drop.prevent="handleImageChange"
                                    class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 transition-colors duration-200 bg-gray-50 hover:bg-blue-50 min-h-[200px] flex flex-col items-center justify-center"
                                    :class="{
                                        'border-red-300 bg-red-50':
                                            imageError ||
                                            form.errors.profile_picture,
                                    }"
                                >
                                    <input
                                        ref="imageInputRef"
                                        id="profile_picture"
                                        type="file"
                                        accept="image/*"
                                        @change="handleImageChange"
                                        class="hidden"
                                    />

                                    <!-- Current Image Preview -->
                                    <div v-if="imagePreview" class="mb-4">
                                        <img
                                            :src="imagePreview"
                                            alt="Profile Preview"
                                            class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg"
                                        />
                                    </div>

                                    <!-- Upload Icon and Text -->
                                    <div class="space-y-2">
                                        <Upload
                                            class="w-12 h-12 text-gray-400 mx-auto"
                                        />
                                        <div class="text-center">
                                            <p
                                                class="text-lg font-medium text-gray-700"
                                            >
                                                {{
                                                    imagePreview
                                                        ? "Change Profile Picture"
                                                        : "Upload Profile Picture"
                                                }}
                                            </p>
                                            <p
                                                class="text-sm text-gray-500 mt-1"
                                            >
                                                Drag and drop an image here, or
                                                click to select
                                            </p>
                                            <p
                                                class="text-xs text-gray-400 mt-1"
                                            >
                                                Supports: JPG, PNG, GIF, SVG
                                                (Max: 2MB)
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Messages -->
                                <p
                                    v-if="
                                        imageError ||
                                        form.errors.profile_picture
                                    "
                                    class="text-sm text-red-600"
                                >
                                    {{
                                        imageError ||
                                        form.errors.profile_picture
                                    }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Form Actions -->
                <div
                    class="flex flex-col items-center justify-end space-y-3 border-t pt-6 sm:flex-row sm:space-y-0 sm:space-x-4"
                >
                    <Button
                        type="button"
                        variant="outline"
                        @click="handleCancel"
                        class="order-2 w-full cursor-pointer sm:order-1 sm:w-auto"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :loading="form.processing"
                        class="order-1 w-full cursor-pointer sm:order-2 sm:w-auto"
                    >
                        Update Employee
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
