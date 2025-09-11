<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Add New Partner</h1>
      <router-link 
        to="/partners" 
        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
      >
        Back to Partners
      </router-link>
    </div>

    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
      <form @submit.prevent="submitForm" enctype="multipart/form-data">
        <!-- Partner Name -->
        <div class="mb-6">
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Partner Name
          </label>
          <input 
            type="text" 
            id="name"
            v-model="form.name"
            :class="[
              'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500',
              errors.name ? 'border-red-500' : 'border-gray-300'
            ]"
            required
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
        </div>

        <!-- Partner Logo -->
        <div class="mb-6">
          <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
            Partner Logo
          </label>
          <input 
            type="file" 
            id="logo"
            @change="handleFileUpload"
            accept="image/*"
            :class="[
              'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500',
              errors.logo ? 'border-red-500' : 'border-gray-300'
            ]"
            required
          />
          <p v-if="errors.logo" class="mt-1 text-sm text-red-600">{{ errors.logo[0] }}</p>
          <p class="mt-1 text-sm text-gray-500">
            Upload an image file (jpeg, png, jpg, gif). Max size: 2MB.
          </p>
        </div>

        <!-- Logo Preview -->
        <div v-if="logoPreview" class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
          <img :src="logoPreview" alt="Logo preview" class="w-32 h-32 object-cover rounded-md shadow">
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button 
            type="submit" 
            :disabled="loading"
            :class="[
              'px-6 py-2 rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-blue-500',
              loading 
                ? 'bg-gray-400 cursor-not-allowed' 
                : 'bg-blue-500 hover:bg-blue-700 text-white'
            ]"
          >
            <span v-if="loading" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Creating...
            </span>
            <span v-else>Create Partner</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'PartnersCreate',
  data() {
    return {
      form: {
        name: '',
        logo: null
      },
      logoPreview: null,
      errors: {},
      loading: false
    }
  },
  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0]
      this.form.logo = file
      
      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          this.logoPreview = e.target.result
        }
        reader.readAsDataURL(file)
      } else {
        this.logoPreview = null
      }
    },
    async submitForm() {
      try {
        this.loading = true
        this.errors = {}
        
        const formData = new FormData()
        formData.append('name', this.form.name)
        if (this.form.logo) {
          formData.append('logo', this.form.logo)
        }
        
        await axios.post('/api/partners', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        
        this.$router.push({
          path: '/partners',
          query: { success: 'Partner created successfully.' }
        })
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors
        } else {
          console.error('Error creating partner:', error)
        }
      } finally {
        this.loading = false
      }
    }
  }
}
</script>
