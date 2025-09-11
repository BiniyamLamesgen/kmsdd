<template>
    <div class="mx-auto mt-16 mb-8 max-w-4xl rounded-2xl bg-white p-8 shadow-lg">
        <!-- Lucide toast will be used instead of vue-sonner -->

        <h2 class="mb-6 text-center text-3xl font-bold text-sky-500">Registration Form</h2>

        <div v-if="success" class="mb-6 rounded-lg bg-green-100 p-4 text-center font-semibold text-green-800">Registration successful!</div>

        <!-- Form Steps Indicator -->
        <div class="mb-8 flex justify-center">
            <div class="flex items-center">
                <div v-for="(step, index) in steps" :key="index" class="flex items-center">
                    <button
                        @click="currentStep = index"
                        :class="[
                            'flex h-10 w-10 items-center justify-center rounded-full border-2 font-medium',
                            currentStep >= index ? 'border-sky-500 bg-sky-500 text-white' : 'border-gray-300 text-gray-500',
                        ]"
                    >
                        {{ index + 1 }}
                    </button>
                    <div v-if="index < steps.length - 1" :class="['h-1 w-16', currentStep > index ? 'bg-sky-500' : 'bg-gray-300']"></div>
                </div>
            </div>
        </div>

        <!-- Personal Details Form -->
        <form v-if="currentStep === 0" @submit.prevent="nextStep" class="grid grid-cols-1 gap-4 md:grid-cols-2" novalidate>
            <h3 class="col-span-full text-lg font-semibold text-gray-700">Personal Details</h3>

            <div>
                <input
                    type="text"
                    name="fullName"
                    placeholder="Full Name"
                    v-model="form.fullName"
                    @input="clearError('fullName')"
                    :class="[inputBase, errors.fullName ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.fullName" class="mt-1 text-sm text-red-500">{{ errors.fullName }}</p>
            </div>

            <div>
                <input
                    type="date"
                    name="dateOfBirth"
                    v-model="form.dateOfBirth"
                    @input="clearError('dateOfBirth')"
                    :class="[inputBase, errors.dateOfBirth ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.dateOfBirth" class="mt-1 text-sm text-red-500">{{ errors.dateOfBirth }}</p>
            </div>

            <div>
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    v-model="form.email"
                    @input="clearError('email')"
                    :class="[inputBase, errors.email ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.email" class="mt-1 text-sm text-red-500">{{ errors.email }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="mobileNumber"
                    placeholder="Mobile Number"
                    v-model="form.mobileNumber"
                    @input="clearError('mobileNumber')"
                    :class="[inputBase, errors.mobileNumber ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.mobileNumber" class="mt-1 text-sm text-red-500">{{ errors.mobileNumber }}</p>
            </div>

            <div>
                <select
                    name="gender"
                    v-model="form.gender"
                    @change="clearError('gender')"
                    :class="[inputBase, errors.gender ? 'border-red-500' : 'border-gray-300']"
                    required
                >
                    <option value="">Select Gender</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
                <p v-if="errors.gender" class="mt-1 text-sm text-red-500">{{ errors.gender }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="occupation"
                    placeholder="Occupation"
                    v-model="form.occupation"
                    @input="clearError('occupation')"
                    :class="[inputBase, errors.occupation ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.occupation" class="mt-1 text-sm text-red-500">{{ errors.occupation }}</p>
            </div>

            <div class="col-span-full mt-4 flex justify-end">
                <button type="submit" class="cursor-pointer rounded-lg bg-sky-500 px-6 py-3 text-white hover:bg-sky-600">Next</button>
            </div>
        </form>

        <!-- Identity Details Form -->
        <form v-if="currentStep === 1" @submit.prevent="nextStep" class="grid grid-cols-1 gap-4 md:grid-cols-2" novalidate>
            <h3 class="col-span-full text-lg font-semibold text-gray-700">Identity Details</h3>

            <div>
                <select
                    name="idType"
                    v-model="form.idType"
                    @change="clearError('idType')"
                    :class="[inputBase, errors.idType ? 'border-red-500' : 'border-gray-300']"
                    required
                >
                    <option value="">Select ID Type</option>
                    <option>Passport</option>
                    <option>National ID</option>
                    <option>Driver's License</option>
                </select>
                <p v-if="errors.idType" class="mt-1 text-sm text-red-500">{{ errors.idType }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="idNumber"
                    placeholder="ID Number"
                    v-model="form.idNumber"
                    @input="clearError('idNumber')"
                    :class="[inputBase, errors.idNumber ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.idNumber" class="mt-1 text-sm text-red-500">{{ errors.idNumber }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="issuedAuthority"
                    placeholder="Issued Authority"
                    v-model="form.issuedAuthority"
                    @input="clearError('issuedAuthority')"
                    :class="[inputBase, errors.issuedAuthority ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.issuedAuthority" class="mt-1 text-sm text-red-500">
                    {{ errors.issuedAuthority }}
                </p>
            </div>

            <div>
                <input
                    type="text"
                    name="issuedPlace"
                    placeholder="Issued Place"
                    v-model="form.issuedPlace"
                    @input="clearError('issuedPlace')"
                    :class="[inputBase, errors.issuedPlace ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.issuedPlace" class="mt-1 text-sm text-red-500">{{ errors.issuedPlace }}</p>
            </div>

            <div>
                <input
                    type="date"
                    name="issuedDate"
                    v-model="form.issuedDate"
                    @input="clearError('issuedDate')"
                    :class="[inputBase, errors.issuedDate ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.issuedDate" class="mt-1 text-sm text-red-500">{{ errors.issuedDate }}</p>
            </div>

            <div>
                <input
                    type="date"
                    name="expiryDate"
                    v-model="form.expiryDate"
                    @input="clearError('expiryDate')"
                    :class="[inputBase, errors.expiryDate ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.expiryDate" class="mt-1 text-sm text-red-500">{{ errors.expiryDate }}</p>
            </div>

            <div class="col-span-full mt-4 flex justify-between">
                <button type="button" @click="prevStep" class="cursor-pointer rounded-lg bg-gray-300 px-6 py-3 text-gray-700 hover:bg-gray-400">
                    Back
                </button>
                <button type="submit" class="cursor-pointer rounded-lg bg-sky-500 px-6 py-3 text-white hover:bg-sky-600">Next</button>
            </div>
        </form>

        <!-- Address Details Form -->
        <form v-if="currentStep === 2" @submit.prevent="nextStep" class="grid grid-cols-1 gap-4 md:grid-cols-2" novalidate>
            <h3 class="col-span-full text-lg font-semibold text-gray-700">Address Details</h3>

            <div>
                <select
                    name="addressType"
                    v-model="form.addressType"
                    @change="clearError('addressType')"
                    :class="[inputBase, errors.addressType ? 'border-red-500' : 'border-gray-300']"
                    required
                >
                    <option value="" disabled>Select Address Type</option>
                    <option value="Permanent">Permanent</option>
                    <option value="Temporary">Temporary</option>
                </select>
                <p v-if="errors.addressType" class="mt-1 text-sm text-red-500">{{ errors.addressType }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="nationality"
                    placeholder="Nationality"
                    v-model="form.nationality"
                    @input="clearError('nationality')"
                    :class="[inputBase, errors.nationality ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.nationality" class="mt-1 text-sm text-red-500">{{ errors.nationality }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="city"
                    placeholder="City"
                    v-model="form.city"
                    @input="clearError('city')"
                    :class="[inputBase, errors.city ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.city" class="mt-1 text-sm text-red-500">{{ errors.city }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="district"
                    placeholder="District/Wereda"
                    v-model="form.district"
                    @input="clearError('district')"
                    :class="[inputBase, errors.district ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.district" class="mt-1 text-sm text-red-500">{{ errors.district }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="districtNumber"
                    placeholder="District/Wereda Number"
                    v-model="form.districtNumber"
                    @input="clearError('districtNumber')"
                    :class="[inputBase, errors.districtNumber ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.districtNumber" class="mt-1 text-sm text-red-500">{{ errors.districtNumber }}</p>
            </div>

            <div class="col-span-full mt-4 flex justify-between">
                <button type="button" @click="prevStep" class="cursor-pointer rounded-lg bg-gray-300 px-6 py-3 text-gray-700 hover:bg-gray-400">
                    Back
                </button>
                <button type="submit" class="cursor-pointer rounded-lg bg-sky-500 px-6 py-3 text-white hover:bg-sky-600">Next</button>
            </div>
        </form>

        <!-- Family Details Form -->
        <form v-if="currentStep === 3" @submit.prevent="handleSubmit" class="grid grid-cols-1 gap-4 md:grid-cols-2" novalidate>
            <h3 class="col-span-full text-lg font-semibold text-gray-700">Family Details</h3>

            <div>
                <input
                    type="text"
                    name="fatherName"
                    placeholder="Father Name"
                    v-model="form.fatherName"
                    @input="clearError('fatherName')"
                    :class="[inputBase, errors.fatherName ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.fatherName" class="mt-1 text-sm text-red-500">{{ errors.fatherName }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="motherName"
                    placeholder="Mother Name"
                    v-model="form.motherName"
                    @input="clearError('motherName')"
                    :class="[inputBase, errors.motherName ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.motherName" class="mt-1 text-sm text-red-500">{{ errors.motherName }}</p>
            </div>

            <div>
                <input
                    type="text"
                    name="grandfatherName"
                    placeholder="Grandfather Name"
                    v-model="form.grandfatherName"
                    @input="clearError('grandfatherName')"
                    :class="[inputBase, errors.grandfatherName ? 'border-red-500' : 'border-gray-300']"
                    required
                />
                <p v-if="errors.grandfatherName" class="mt-1 text-sm text-red-500">{{ errors.grandfatherName }}</p>
            </div>

            <div class="col-span-full mt-4 flex justify-between">
                <button type="button" @click="prevStep" class="cursor-pointer rounded-lg bg-gray-300 px-6 py-3 text-gray-700 hover:bg-gray-400">
                    Back
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    :class="[
                        'cursor-pointer rounded-lg bg-sky-500 px-6 py-3 text-white hover:bg-sky-600',
                        form.processing ? 'cursor-not-allowed opacity-50' : '',
                    ]"
                >
                    {{ form.processing ? 'Submitting...' : 'Submit' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const steps = ['Personal', 'Identity', 'Address', 'Family'];
const currentStep = ref<number>(0);
const success = ref<boolean>(false);
const inputBase = 'w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-800';

interface RegistrationForm {
    fullName: string;
    dateOfBirth: string;
    email: string;
    mobileNumber: string;
    gender: string;
    occupation: string;
    idType: string;
    idNumber: string;
    issuedAuthority: string;
    issuedPlace: string;
    issuedDate: string;
    expiryDate: string;
    addressType: string;
    nationality: string;
    city: string;
    district: string;
    districtNumber: string;
    fatherName: string;
    motherName: string;
    grandfatherName: string;
    [key: string]: any;
}

const form = useForm<RegistrationForm>({
    // Personal Details
    fullName: '',
    dateOfBirth: '',
    email: '',
    mobileNumber: '',
    gender: '',
    occupation: '',

    // Identity Details
    idType: '',
    idNumber: '',
    issuedAuthority: '',
    issuedPlace: '',
    issuedDate: '',
    expiryDate: '',

    // Address Details
    addressType: '',
    nationality: '',
    city: '',
    district: '',
    districtNumber: '',

    // Family Details
    fatherName: '',
    motherName: '',
    grandfatherName: '',
});

type ErrorFields = Partial<Record<keyof RegistrationForm, string>>;
const errors = ref<ErrorFields>({});
const validateStep = (step: number): boolean => {
    const newErrors: Partial<Record<keyof RegistrationForm, string>> = {};

    if (step === 0) {
        // Personal Details validation
        if (!form.fullName.trim()) newErrors.fullName = 'Full Name is required';
        if (!form.dateOfBirth) newErrors.dateOfBirth = 'Date of Birth is required';
        if (!form.email) newErrors.email = 'Email is required';
        else if (!/^\S+@\S+\.\S+$/.test(form.email)) newErrors.email = 'Email format is invalid';
        if (!form.mobileNumber.trim()) newErrors.mobileNumber = 'Mobile Number is required';
        if (!form.gender) newErrors.gender = 'Please select gender';
        if (!form.occupation.trim()) newErrors.occupation = 'Occupation is required';
    } else if (step === 1) {
        // Identity Details validation
        if (!form.idType) newErrors.idType = 'Please select ID Type';
        if (!form.idNumber.trim()) newErrors.idNumber = 'ID Number is required';
        if (!form.issuedAuthority.trim()) newErrors.issuedAuthority = 'Issued Authority is required';
        if (!form.issuedPlace.trim()) newErrors.issuedPlace = 'Issued Place is required';
        if (!form.issuedDate) newErrors.issuedDate = 'Issued Date is required';
        if (!form.expiryDate) newErrors.expiryDate = 'Expiry Date is required';

        if (form.issuedDate && form.expiryDate) {
            if (new Date(form.expiryDate) <= new Date(form.issuedDate)) {
                newErrors.expiryDate = 'Expiry Date must be after Issued Date';
            }
        }
    } else if (step === 2) {
        // Address Details validation
        if (!form.addressType) newErrors.addressType = 'Please select Address Type';
        if (!form.nationality.trim()) newErrors.nationality = 'Nationality is required';
        if (!form.city.trim()) newErrors.city = 'City is required';
        if (!form.district.trim()) newErrors.district = 'District/Wereda is required';
        if (!form.districtNumber.trim()) newErrors.districtNumber = 'District/Wereda Number is required';
    } else if (step === 3) {
        // Family Details validation
        if (!form.fatherName.trim()) newErrors.fatherName = 'Father Name is required';
        if (!form.motherName.trim()) newErrors.motherName = 'Mother Name is required';
        if (!form.grandfatherName.trim()) newErrors.grandfatherName = 'Grandfather Name is required';
    }

    errors.value = newErrors;
    return Object.keys(newErrors).length === 0;
};

const clearError = (field: keyof RegistrationForm): void => {
    if (errors.value[field]) {
        errors.value = { ...errors.value, [field]: '' };
    }
};
const nextStep = (): void => {
    if (!validateStep(currentStep.value)) {
        return;
    }
    currentStep.value++;
};
const prevStep = (): void => {
    currentStep.value--;
};

const handleSubmit = (): void => {
    if (!validateStep(currentStep.value)) {
        return;
    }

    form.post(route('membership.public-register'), {
        onSuccess: () => {
            success.value = true;
            // Reset form if needed
            form.reset();
            currentStep.value = 0;
        },
        onError: (errors) => {
            console.log('Form submission errors:', errors);
        },
    });
};
</script>
