<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Partner Details</h1>
      <div class="space-x-2">
        <router-link 
          :to="`/partners/${partner.id}/edit`"
          class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
        >
          Edit
        </router-link>
        <router-link 
          to="/partners" 
          class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
        >
          Back to Partners
        </router-link>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
      <p class="mt-2">Loading partner...</p>
    </div>

    <!-- Partner Details -->
    <div v-else class="max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
      <div class="md:flex">
        <!-- Logo Section -->
        <div class="md:w-1/3 p-6 bg-gray-50">
          <div v-if="partner.logo" class="text-center">
            <img 
              :src="partner.logo" 
              :alt="partner.name" 
              class="max-w-full h-auto rounded-lg shadow-md mx-auto"
            />
          </div>
          <div v-else class="bg-gray-200 p-8 rounded-lg text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="mt-2 text-gray-500">No logo available</p>
          </div>
        </div>

        <!-- Details Section -->
        <div class="md:w-2/3 p-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ partner.name }}</h2>
          
          <div class="border-t border-gray-200 pt-4">
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ partner.id }}</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ partner.name }}</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Created</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(partner.created_at) }}</dd>
              </div>
              
              <div>
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ formatDateTime(partner.updated_at) }}</dd>
              </div>
            </dl>
          </div>

          <!-- Actions -->
          <div class="mt-6 pt-4 border-t border-gray-200">
            <button 
              @click="deletePartner"
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
            >
              Delete Partner
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'PartnersShow',
  data() {
    return {
      partner: {},
      loading: true
    }
  },
  mounted() {
    this.fetchPartner()
  },
  methods: {
    async fetchPartner() {
      try {
        const response = await axios.get(`/api/partners/${this.$route.params.id}`)
        this.partner = response.data.data || response.data
      } catch (error) {
        console.error('Error fetching partner:', error)
        this.$router.push('/partners')
      } finally {
        this.loading = false
      }
    },
    async deletePartner() {
      if (confirm('Are you sure you want to delete this partner?')) {
        try {
          await axios.delete(`/api/partners/${this.partner.id}`)
          this.$router.push({
            path: '/partners',
            query: { success: 'Partner deleted successfully.' }
          })
        } catch (error) {
          console.error('Error deleting partner:', error)
        }
      }
    },
    formatDateTime(dateString) {
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }
  }
}
</script>
