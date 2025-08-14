<!-- resources/js/views/components/subcontractor/SubcontractorFacilities.vue -->
<template>
  <div class="subcontractor-facilities">
    <div class="tab-header">
      <h3>
        <i class="fas fa-tools"></i>
        Facilities Company
      </h3>
      <p>What facilities which available in your company? / Fasilitas apa yang merupakan kapabilitas Perusahaan Anda?</p>
    </div>

    <div class="facilities-section">
      <h4>Available Facilities</h4>
      <p class="section-description">Please select all facilities that your company provides (you can select multiple options):</p>
      
      <div class="facilities-grid">
        <div 
          v-for="(label, key) in facilitiesOptions" 
          :key="key"
          class="facility-item"
        >
          <label class="facility-checkbox">
            <input 
              type="checkbox" 
              :value="key"
              v-model="selectedFacilities"
            />
            <span class="checkmark"></span>
            <span class="facility-label">{{ label }}</span>
          </label>
        </div>
      </div>

      <div class="other-services-section">
        <div class="form-group">
          <label>Other services, please describe / jelaskan</label>
          <textarea 
            v-model="formData.other_services"
            rows="4"
            placeholder="Describe any other services not listed above..."
          ></textarea>
        </div>
      </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section" v-if="selectedFacilities.length > 0">
      <h4>Selected Facilities Summary</h4>
      <div class="selected-facilities">
        <span 
          v-for="facility in selectedFacilities" 
          :key="facility"
          class="facility-tag"
        >
          {{ facilitiesOptions[facility] }}
        </span>
      </div>
    </div>

    <div class="form-actions">
      <button @click="saveFacilities" :disabled="loading" class="btn btn-primary">
        <i class="fas fa-save"></i>
        {{ loading ? 'Saving...' : 'Save Facilities Information' }}
      </button>
    </div>

    <!-- Alert -->
    <AlertComponent 
      v-if="alertMsg"
      :type="alertType"
      :message="alertMsg"
      @close="alertMsg = ''"
    />
  </div>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue'
import axios from 'axios'
import AlertComponent from '../AlertComponent.vue'

const props = defineProps({
  subcontractorData: Object,
  facilitiesOptions: Object
})

const emit = defineEmits(['updated'])

const loading = ref(false)
const alertMsg = ref('')
const alertType = ref('success')

const formData = reactive({
  other_services: ''
})

const selectedFacilities = ref([])

watch(() => props.subcontractorData, (newData) => {
  if (newData) {
    initializeFormData()
  }
}, { immediate: true, deep: true })

function initializeFormData() {
  if (!props.subcontractorData) return
  
  const data = props.subcontractorData
  
  formData.other_services = data.other_services || ''
  
  // Initialize selected facilities
  if (data.facilities && Array.isArray(data.facilities)) {
    selectedFacilities.value = [...data.facilities]
  } else {
    selectedFacilities.value = []
  }
}

async function saveFacilities() {
  loading.value = true
  
  try {
    const dataToSave = {
      facilities: selectedFacilities.value,
      other_services: formData.other_services
    }

    const token = localStorage.getItem('token')
    const response = await axios.put('/api/vendor/subcontractor', dataToSave, {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      showAlert('success', 'Facilities information saved successfully!')
      emit('updated', response.data.data.subcontractor_data)
    } else {
      showAlert('error', response.data.message || 'Failed to save facilities information')
    }
  } catch (error) {
    console.error('Save facilities error:', error)
    showAlert('error', error.response?.data?.message || 'Failed to save facilities information')
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
.subcontractor-facilities {
  max-width: 900px;
}

.tab-header {
  margin-bottom: 2rem;
}

.tab-header h3 {
  color: #212529;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.tab-header p {
  color: #6c757d;
  margin: 0;
  font-style: italic;
}

.facilities-section {
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}

.facilities-section h4 {
  color: #495057;
  margin-bottom: 1rem;
}

.section-description {
  color: #6c757d;
  margin-bottom: 1.5rem;
}

.facilities-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.facility-item {
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  padding: 1rem;
  transition: all 0.2s;
}

.facility-item:hover {
  border-color: #fd7e14;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.facility-checkbox {
  display: flex;
  align-items: center;
  cursor: pointer;
  position: relative;
  gap: 1rem;
  width: 100%;
}

.facility-checkbox input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.checkmark {
  width: 20px;
  height: 20px;
  background-color: #f8f9fa;
  border: 2px solid #ced4da;
  border-radius: 4px;
  position: relative;
  transition: all 0.2s;
  flex-shrink: 0;
}

.facility-checkbox input[type="checkbox"]:checked ~ .checkmark {
  background-color: #fd7e14;
  border-color: #fd7e14;
}

.facility-checkbox input[type="checkbox"]:checked ~ .checkmark:after {
  content: '';
  position: absolute;
  left: 6px;
  top: 2px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.facility-label {
  font-weight: 500;
  color: #495057;
  line-height: 1.4;
}

.other-services-section {
  border-top: 1px solid #dee2e6;
  padding-top: 2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

.form-group textarea {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 0.875rem;
  transition: border-color 0.2s;
  resize: vertical;
}

.form-group textarea:focus {
  outline: none;
  border-color: #fd7e14;
  box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.summary-section {
  background: #e7f3ff;
  padding: 1.5rem;
  border-radius: 8px;
  border-left: 4px solid #007bff;
  margin-bottom: 2rem;
}

.summary-section h4 {
  color: #0056b3;
  margin-bottom: 1rem;
}

.selected-facilities {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.facility-tag {
  background: #fd7e14;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
}

.form-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #dee2e6;
}

.btn {
  padding: 0.75rem 1.5rem;
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

@media (max-width: 768px) {
  .facilities-grid {
    grid-template-columns: 1fr;
  }
}
</style>