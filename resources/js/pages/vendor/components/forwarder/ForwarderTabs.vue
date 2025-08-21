<template>
  <div class="forwarder-tabs">
    <!-- Header Section -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h4 class="mb-1">
                  <i class="fas fa-ship text-primary me-2"></i>
                  Forwarder Profile
                </h4>
                <p class="text-muted mb-0">Complete your forwarder company information</p>
              </div>
              <div class="text-end">
                <div class="badge bg-primary fs-6 px-3 py-2">
                  {{ completion_percentage }}% Complete
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2 text-muted">Loading forwarder tabs...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="alert alert-danger">
      <i class="fas fa-exclamation-triangle me-2"></i>
      {{ error }}
    </div>

    <!-- Main Content -->
    <div v-else>
      <!-- Tabs Navigation -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
              <ul class="nav nav-tabs nav-fill border-0" id="forwarderTabs" role="tablist">
                <li 
                  v-for="(tab, index) in tabs" 
                  :key="tab.id" 
                  class="nav-item" 
                  role="presentation"
                >
                  <button
                    :class="[
                      'nav-link border-0 py-3 px-2 position-relative',
                      { 'active': activeTab === tab.id }
                    ]"
                    :id="`${tab.id}-tab`"
                    type="button"
                    role="tab"
                    :aria-controls="tab.id"
                    :aria-selected="activeTab === tab.id"
                    @click="setActiveTab(tab.id)"
                  >
                    <div class="d-flex flex-column align-items-center">
                      <i :class="tab.icon + ' mb-1'" style="font-size: 1.2rem;"></i>
                      <small class="text-nowrap">{{ tab.label }}</small>
                    </div>
                    <!-- Completion Indicator -->
                    <div 
                      v-if="isTabCompleted(tab.id)"
                      class="position-absolute top-0 end-0 mt-1 me-1"
                    >
                      <i class="fas fa-check-circle text-success" style="font-size: 0.8rem;"></i>
                    </div>
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Content -->
      <div class="row">
        <div class="col-12">
          <div class="tab-content" id="forwarderTabContent">
            <!-- Tab 1: General -->
            <div 
              v-show="activeTab === 'general'"
              class="tab-pane fade show active"
              id="general"
              role="tabpanel"
              aria-labelledby="general-tab"
            >
              <forwarder-general 
                :forwarder="forwarder"
                :vendor="vendor"
                @updated="handleTabUpdate"
              />
            </div>

            <!-- Tab 2: Other Services -->
            <div 
              v-show="activeTab === 'other_services'"
              class="tab-pane fade"
              id="other_services"
              role="tabpanel"
              aria-labelledby="other_services-tab"
            >
              <forwarder-other-services 
                :forwarder="forwarder"
                :options="options"
                @updated="handleTabUpdate"
              />
            </div>

            <!-- Tab 3: Facilities -->
            <div 
              v-show="activeTab === 'facilities'"
              class="tab-pane fade"
              id="facilities"
              role="tabpanel"
              aria-labelledby="facilities-tab"
            >
              <forwarder-facilities 
                :forwarder="forwarder"
                :options="options"
                @updated="handleTabUpdate"
              />
            </div>

            <!-- Tab 4: Branch Office -->
            <div 
              v-show="activeTab === 'branch_office'"
              class="tab-pane fade"
              id="branch_office"
              role="tabpanel"
              aria-labelledby="branch_office-tab"
            >
              <forwarder-branch-office 
                :forwarder="forwarder"
                @updated="handleTabUpdate"
              />
            </div>

            <!-- Tab 5: Line Service -->
            <div 
              v-show="activeTab === 'line_service'"
              class="tab-pane fade"
              id="line_service"
              role="tabpanel"
              aria-labelledby="line_service-tab"
            >
              <forwarder-line-service 
                :forwarder="forwarder"
                :options="options"
                @updated="handleTabUpdate"
              />
            </div>

            <!-- Tab 6: Insurance -->
            <div 
              v-show="activeTab === 'insurance'"
              class="tab-pane fade"
              id="insurance"
              role="tabpanel"
              aria-labelledby="insurance-tab"
            >
              <forwarder-insurance 
                :forwarder="forwarder"
                @updated="handleTabUpdate"
              />
            </div>

            <!-- Tab 7: Manpower Support -->
            <div 
              v-show="activeTab === 'manpower_support'"
              class="tab-pane fade"
              id="manpower_support"
              role="tabpanel"
              aria-labelledby="manpower_support-tab"
            >
              <forwarder-manpower-support 
                :forwarder="forwarder"
                :options="options"
                @updated="handleTabUpdate"
              />
            </div>

            <!-- Tab 8: Documents -->
            <div 
              v-show="activeTab === 'documents'"
              class="tab-pane fade"
              id="documents"
              role="tabpanel"
              aria-labelledby="documents-tab"
            >
              <forwarder-documents 
                :forwarder="forwarder"
                @updated="handleTabUpdate"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ForwarderGeneral from './ForwarderGeneral.vue'
import ForwarderOtherServices from './ForwarderOtherServices.vue'
import ForwarderFacilities from './ForwarderFacilities.vue'
import ForwarderBranchOffice from './ForwarderBranchOffice.vue'
import ForwarderLineService from './ForwarderLineService.vue'
import ForwarderInsurance from './ForwarderInsurance.vue'
import ForwarderManpowerSupport from './ForwarderManpowerSupport.vue'
import ForwarderDocuments from './ForwarderDocuments.vue'

export default {
  name: 'ForwarderTabs',
  components: {
    ForwarderGeneral,
    ForwarderOtherServices,
    ForwarderFacilities,
    ForwarderBranchOffice,
    ForwarderLineService,
    ForwarderInsurance,
    ForwarderManpowerSupport,
    ForwarderDocuments
  },
  data() {
    return {
      loading: true,
      error: null,
      activeTab: 'general',
      tabs: [],
      vendor: null,
      forwarder: null,
      options: {},
      completion_percentage: 0
    }
  },
  async mounted() {
    await this.fetchTabs()
  },
  methods: {
    async fetchTabs() {
      try {
        this.loading = true
        this.error = null
        
        const res = await this.$axios.get('/api/vendor/forwarder/tabs')
        if (res.data.success) {
          this.tabs = res.data.data.tabs
          this.vendor = res.data.data.vendor
          this.forwarder = res.data.data.forwarder_data
          this.options = res.data.data.options
          this.completion_percentage = res.data.data.completion_percentage || 0
        } else {
          this.error = res.data.message || 'Failed to load forwarder tabs'
        }
      } catch (error) {
        console.error('Error fetching forwarder tabs:', error)
        this.error = error.response?.data?.message || 'Error loading forwarder data'
      } finally {
        this.loading = false
      }
    },
    
    setActiveTab(tabId) {
      this.activeTab = tabId
    },
    
    async handleTabUpdate(data) {
      try {
        const res = await this.$axios.put('/api/vendor/forwarder', data)
        if (res.data.success) {
          this.forwarder = res.data.data.forwarder_data
          this.completion_percentage = res.data.data.completion_percentage || this.completion_percentage
          
          // Refresh tabs untuk update completion status
          await this.fetchTabs()
          
          this.$toast.success(res.data.message || 'Data berhasil diperbarui!')
        } else {
          this.$toast.error(res.data.message || 'Gagal memperbarui data')
        }
      } catch (error) {
        console.error('Error updating forwarder:', error)
        if (error.response?.data?.errors) {
          const errorMessages = Object.values(error.response.data.errors).flat()
          errorMessages.forEach(msg => this.$toast.error(msg))
        } else {
          this.$toast.error(error.response?.data?.message || 'Gagal memperbarui data')
        }
      }
    },
    
    isTabCompleted(tabId) {
      if (!this.forwarder) return false
      
      // Logic untuk menentukan tab completion
      switch (tabId) {
        case 'general':
          return true
        case 'other_services':
          return this.forwarder.other_services_none !== null
        case 'facilities':
          return this.forwarder.monitoring_management !== null
        case 'branch_office':
          return this.forwarder.indonesian_branch_offices?.length > 0
        case 'line_service':
          return this.forwarder.shipping_line_relation !== null
        case 'insurance':
          return this.forwarder.insurance_liability_with_insurance_company !== null
        case 'manpower_support':
          return this.forwarder.manpower_support !== null
        case 'documents':
          return false
        default:
          return false
      }
    }
  }
}
</script>

<style scoped>
.nav-tabs .nav-link {
  color: #6c757d;
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  transition: all 0.3s ease;
  min-height: 80px;
}

.nav-tabs .nav-link:hover {
  background-color: rgba(13, 110, 253, 0.1);
  color: #0d6efd;
  border-bottom-color: rgba(13, 110, 253, 0.3);
}

.nav-tabs .nav-link.active {
  background-color: rgba(13, 110, 253, 0.1);
  color: #0d6efd;
  border-bottom-color: #0d6efd;
  font-weight: 600;
}

.tab-pane {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(10px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}
</style>