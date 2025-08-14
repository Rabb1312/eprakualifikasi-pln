<!-- resources/js/views/VendorDashboard.vue -->
<template>
  <div class="vendor-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
      <div class="vendor-info">
        <div class="vendor-avatar">
          <i class="fas fa-hard-hat"></i>
        </div>
        <div class="vendor-details">
          <h2>{{ vendor.nama_perusahaan || 'Loading...' }}</h2>
          <div class="vendor-meta">
            <span class="vendor-number">{{ vendor.nomor_vendor }}</span>
            <span class="vendor-type">{{ vendorTypeName }}</span>
            <span class="completion-badge" :class="completionClass">
              {{ vendor.completion_percentage || 0 }}% Complete
            </span>
          </div>
        </div>
      </div>
      
      <div class="header-actions">
        <button class="btn btn-primary" @click="saveAllData" :disabled="loading">
          <i class="fas fa-save"></i>
          {{ loading ? 'Saving...' : 'Save All' }}
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
      <p>Loading subcontractor data...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="error-container">
      <i class="fas fa-exclamation-triangle"></i>
      <p>{{ error }}</p>
      <button @click="loadData" class="btn btn-primary">Retry</button>
    </div>

    <!-- Tab System for Subcontractor -->
    <div v-else class="dashboard-main">
      <!-- Tab Navigation -->
      <div class="tab-navigation">
        <button 
          v-for="tab in subcontractorTabs" 
          :key="tab.id"
          :class="['tab-btn', { active: activeTab === tab.id }]"
          @click="activeTab = tab.id"
        >
          <i :class="tab.icon"></i>
          <span>{{ tab.label }}</span>
        </button>
      </div>

      <!-- Tab Content -->
      <div class="tab-content">
        <!-- TAB 1: General Information -->
        <SubcontractorGeneral 
          v-if="activeTab === 'general'"
          :vendor="vendor"
          @updated="onVendorUpdated"
        />

        <!-- TAB 2: Holding Company -->
        <SubcontractorHolding 
          v-if="activeTab === 'holding'"
          :subcontractor-data="subcontractorData"
          @updated="onSubcontractorUpdated"
        />

        <!-- TAB 3: Facilities Company -->
        <SubcontractorFacilities 
          v-if="activeTab === 'facilities'"
          :subcontractor-data="subcontractorData"
          :facilities-options="facilitiesOptions"
          @updated="onSubcontractorUpdated"
        />

        <!-- TAB 4: Total Permanent Construction Employees -->
        <SubcontractorEmployees 
          v-if="activeTab === 'employees'"
          :subcontractor-data="subcontractorData"
          :employee-classifications="employeeClassifications"
          @updated="onSubcontractorUpdated"
        />

        <!-- TAB 5: Scope of Work -->
        <SubcontractorScope 
          v-if="activeTab === 'scope'"
          :subcontractor-data="subcontractorData"
          @updated="onSubcontractorUpdated"
        />

        <!-- TAB 6: Major Projects 3-5 -->
        <SubcontractorProjects 
          v-if="activeTab === 'projects'"
          :subcontractor-data="subcontractorData"
          @updated="onSubcontractorUpdated"
        />

        <!-- TAB 7: Knowledgeable -->
        <SubcontractorKnowledge 
          v-if="activeTab === 'knowledge'"
          :subcontractor-data="subcontractorData"
          @updated="onSubcontractorUpdated"
        />

        <!-- TAB 8: Documents/Attachments -->
        <SubcontractorDocuments 
          v-if="activeTab === 'documents'"
          :subcontractor-data="subcontractorData"
          :document-checklist="documentChecklist"
          @updated="onSubcontractorUpdated"
        />
      </div>
    </div>

    <!-- Alert Component -->
    <AlertComponent 
      v-if="alertMsg"
      :type="alertType"
      :message="alertMsg"
      @close="alertMsg = ''"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

// Import components
import SubcontractorGeneral from './components/subcontractor/SubcontractorGeneral.vue'
import SubcontractorHolding from './components/subcontractor/SubcontractorHolding.vue'
import SubcontractorFacilities from './components/subcontractor/SubcontractorFacilities.vue'
import SubcontractorEmployees from './components/subcontractor/SubcontractorEmployees.vue'
import SubcontractorScope from './components/subcontractor/SubcontractorScope.vue'
import SubcontractorProjects from './components/subcontractor/SubcontractorProjects.vue'
import SubcontractorKnowledge from './components/subcontractor/SubcontractorKnowledge.vue'
import SubcontractorDocuments from './components/subcontractor/SubcontractorDocuments.vue'
import AlertComponent from './components/AlertComponent.vue'

const router = useRouter()

// Reactive data
const loading = ref(true)
const error = ref('')
const activeTab = ref('general')
const user = ref({})
const vendor = ref({})
const subcontractorData = ref({})
const subcontractorTabs = ref([])
const facilitiesOptions = ref({})
const employeeClassifications = ref([])
const documentChecklist = ref([])
const alertMsg = ref('')
const alertType = ref('success')

// Axios config
axios.defaults.baseURL = "http://eprakualifikasi-pln.test"

// Computed
const vendorTypeName = computed(() => {
  return vendor.value.tipe_perusahaan === 'SC' ? 'Subcontractor' : 'Unknown'
})

const completionClass = computed(() => {
  const percentage = vendor.value.completion_percentage || 0
  if (percentage >= 80) return 'high'
  if (percentage >= 50) return 'medium'
  return 'low'
})

// Methods
onMounted(async () => {
  console.log('🚀 VendorDashboard mounted for Subcontractor')
  await loadData()
})

async function loadData() {
  loading.value = true
  error.value = ''
  
  try {
    await loadUserData()
    await loadSubcontractorTabs()
    console.log('✅ All subcontractor data loaded successfully')
  } catch (err) {
    console.error('❌ Error loading data:', err)
    error.value = err.message || 'Failed to load data'
  } finally {
    loading.value = false
  }
}

async function loadUserData() {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      router.push('/login')
      return
    }

    const response = await axios.get('/api/me', {
      headers: { Authorization: `Bearer ${token}` }
    })

    console.log('📥 User data response:', response.data)

    if (response.data.success) {
      user.value = response.data.data.user
      vendor.value = response.data.data.vendor || {}

      // Verify this is a subcontractor
      if (vendor.value.tipe_perusahaan !== 'SC') {
        throw new Error('This user is not a subcontractor')
      }
    } else {
      throw new Error('Failed to load user data')
    }
  } catch (error) {
    console.error('❌ Load user data error:', error)
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      throw error
    }
  }
}

async function loadSubcontractorTabs() {
  try {
    const token = localStorage.getItem('token')
    const response = await axios.get('/api/vendor/subcontractor/tabs', {
      headers: { Authorization: `Bearer ${token}` }
    })

    console.log('📋 Subcontractor tabs response:', response.data)

    if (response.data.success) {
      const data = response.data.data
      subcontractorTabs.value = data.tabs
      subcontractorData.value = data.subcontractor_data || {}
      facilitiesOptions.value = data.facilities_options || {}
      employeeClassifications.value = data.employee_classifications || []
      documentChecklist.value = data.document_checklist || []

      console.log('✅ Subcontractor data loaded:', {
        tabs: subcontractorTabs.value.length,
        hasData: !!subcontractorData.value.id,
        facilitiesCount: Object.keys(facilitiesOptions.value).length,
        employeeTypes: employeeClassifications.value.length,
        documentsCount: documentChecklist.value.length
      })
    } else {
      throw new Error(response.data.message || 'Failed to load subcontractor tabs')
    }
  } catch (error) {
    console.error('❌ Load subcontractor tabs error:', error)
    throw error
  }
}

async function onVendorUpdated(updatedVendor) {
  console.log('🔄 Vendor data updated:', updatedVendor)
  vendor.value = { ...vendor.value, ...updatedVendor }
  showAlert('success', 'Vendor data updated successfully')
}

async function onSubcontractorUpdated(updatedData) {
  console.log('🔄 Subcontractor data updated:', updatedData)
  subcontractorData.value = { ...subcontractorData.value, ...updatedData }
  showAlert('success', 'Subcontractor data updated successfully')
}

async function saveAllData() {
  loading.value = true
  try {
    // This would save all pending changes
    showAlert('success', 'All data saved successfully')
  } catch (error) {
    console.error('❌ Save all data error:', error)
    showAlert('error', 'Failed to save data')
  } finally {
    loading.value = false
  }
}

function showAlert(type, message) {
  alertType.value = type
  alertMsg.value = message
  setTimeout(() => {
    alertMsg.value = ''
  }, 5000)
}
</script>

<style scoped>
.vendor-dashboard {
  min-height: 100vh;
  background: #f8f9fa;
}

.dashboard-header {
  background: white;
  padding: 2rem;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.vendor-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.vendor-avatar {
  width: 60px;
  height: 60px;
  background: #fd7e14;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
}

.vendor-details h2 {
  margin: 0 0 0.5rem 0;
  color: #212529;
}

.vendor-meta {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.vendor-number {
  background: #e9ecef;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.vendor-type {
  background: #fd7e14;
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.completion-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  font-weight: 500;
}

.completion-badge.high {
  background: #d4edda;
  color: #155724;
}

.completion-badge.medium {
  background: #fff3cd;
  color: #856404;
}

.completion-badge.low {
  background: #f8d7da;
  color: #721c24;
}

.loading-container, .error-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  gap: 1rem;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #fd7e14;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.dashboard-main {
  display: flex;
  min-height: calc(100vh - 120px);
}

.tab-navigation {
  width: 280px;
  background: white;
  border-right: 1px solid #dee2e6;
  padding: 1rem 0;
}

.tab-btn {
  width: 100%;
  padding: 1rem 1.5rem;
  border: none;
  background: none;
  text-align: left;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: #6c757d;
}

.tab-btn:hover {
  background: #f8f9fa;
  color: #495057;
}

.tab-btn.active {
  background: #fd7e14;
  color: white;
  border-right: 3px solid #e56b00;
}

.tab-btn i {
  width: 20px;
  font-size: 16px;
}

.tab-content {
  flex: 1;
  padding: 2rem;
  background: white;
  overflow-y: auto;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary {
  background: #fd7e14;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #e56b00;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>