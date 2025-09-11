<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Partners</h1>
      <router-link 
        to="/partners/create" 
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
      >
        Add New Partner
      </router-link>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ successMessage }}
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
      <p class="mt-2">Loading partners...</p>
    </div>

    <!-- Partners Table -->
    <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
      <div v-if="partners.length > 0">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="partner in partners" :key="partner.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ partner.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <img 
                  v-if="partner.logo" 
                  :src="partner.logo" 
                  :alt="partner.name"
                  class="h-12 w-12 object-cover rounded"
                />
                <span v-else class="text-gray-400">No logo</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ partner.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(partner.created_at) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <router-link 
                  :to="`/partners/${partner.id}`"
                  class="text-blue-600 hover:text-blue-900"
                >
                  View
                </router-link>
                <router-link 
                  :to="`/partners/${partner.id}/edit`"
                  class="text-yellow-600 hover:text-yellow-900"
                >
                  Edit
                </router-link>
                <button 
                  @click="deletePartner(partner.id)"
                  class="text-red-600 hover:text-red-900"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="text-center py-8">
        <p class="text-gray-500">No partners found.</p>
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="pagination && pagination.last_page > 1" class="mt-6 flex justify-center">
      <nav class="flex space-x-2">
        <button 
          v-for="page in pagination.last_page" 
          :key="page"
          @click="fetchPartners(page)"
          :class="[
            'px-3 py-2 rounded',
            page === pagination.current_page 
              ? 'bg-blue-500 text-white' 
              : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
          ]"
        >
          {{ page }}
        </button>
      </nav>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'PartnersIndex',
  data() {
    return {
      partners: [],
      pagination: null,
      loading: true,
      successMessage: ''
    }
  },
  mounted() {
    this.fetchPartners()
    this.checkForSuccessMessage()
  },
  methods: {
    async fetchPartners(page = 1) {
      try {
        this.loading = true
        const response = await axios.get(`/api/partners?page=${page}`)
        this.partners = response.data.data
        this.pagination = response.data.meta || response.data
      } catch (error) {
        console.error('Error fetching partners:', error)
      } finally {
        this.loading = false
      }
    },
    async deletePartner(id) {
      if (confirm('Are you sure you want to delete this partner?')) {
        try {
          await axios.delete(`/api/partners/${id}`)
          this.successMessage = 'Partner deleted successfully.'
          this.fetchPartners()
          setTimeout(() => this.successMessage = '', 3000)
        } catch (error) {
          console.error('Error deleting partner:', error)
        }
      }
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    },
    checkForSuccessMessage() {
      if (this.$route.query.success) {
        this.successMessage = this.$route.query.success
        setTimeout(() => this.successMessage = '', 3000)
      }
    }
  }
}
</script>
