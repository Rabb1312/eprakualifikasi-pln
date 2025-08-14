<!-- resources/js/views/components/subcontractor/SubcontractorScope.vue -->
<template>
  <div class="subcontractor-scope">
    <div class="tab-header">
      <h3>
        <i class="fas fa-tasks"></i>
        Scope of Work
      </h3>
      <p>What kind of scope of work does your company usually sub-contracted to? Please mention, if any. / Lingkup pekerjaan seperti apa yang biasanya di sub-kontrakkan oleh perusahaan Anda? Mohon sebutkan, jika ada.</p>
    </div>

    <div class="scope-section">
      <div class="form-group">
        <label>Scope of Work Description</label>
        <textarea 
          v-model="formData.scope_of_work"
          rows="12"
          placeholder="Please describe in detail the scope of work that your company usually undertakes as a subcontractor. Include:

• Types of construction projects
• Specific services provided
• Areas of expertise
• Technical capabilities
• Project scale and complexity
• Geographic coverage
• Any specialized equipment or methodologies used

Example:
- Electrical installation for power transmission projects
- Civil construction for substation foundations
- Mechanical assembly for power plant equipment
- Instrumentation and control system installation
- Testing and commissioning services"
          class="scope-textarea"
        ></textarea>
        <div class="character-count">
          {{ characterCount }}/{{ maxCharacters }} characters
        </div>
      </div>

      <!-- Predefined Categories -->
      <!-- <div class="categories-section">
        <h4>Common Scope Categories</h4>
        <p class="section-description">Check the categories that apply to your company's scope of work:</p>
        
        <div class="categories-grid">
          <div 
            v-for="category in scopeCategories" 
            :key="category.id"
            class="category-item"
          >
            <label class="category-checkbox">
              <input 
                type="checkbox" 
                :value="category.id"
                v-model="selectedCategories"
              />
              <span class="checkmark"></span>
              <div class="category-content">
                <span class="category-title">{{ category.title }}</span>
                <span class="category-description">{{ category.description }}</span>
              </div>
            </label>
          </div>
        </div>
      </div> -->

      <!-- Additional Notes -->
      <!-- <div class="notes-section">
        <div class="form-group">
          <label>Additional Notes / Catatan Tambahan</label>
          <textarea 
            v-model="formData.additional_notes"
            rows="4"
            placeholder="Any additional information about your scope of work, special certifications, or unique capabilities..."
          ></textarea>
        </div>
      </div> -->
    </div>

    <div class="form-actions">
      <button @click="saveScope" :disabled="loading" class="btn btn-primary">
        <i class="fas fa-save"></i>
        {{ loading ? 'Saving...' : 'Save Scope of Work' }}
      </button>
      
      <!-- <button @click="clearForm" class="btn btn-outline">
        <i class="fas fa-trash"></i>
        Clear Form
      </button> -->
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
  subcontractorData: Object
})

const emit = defineEmits(['updated'])

const loading = ref(false)
const alertMsg = ref('')
const alertType = ref('success')
const maxCharacters = 2000

const formData = reactive({
  scope_of_work: '',
  additional_notes: ''
})

const selectedCategories = ref([])

const scopeCategories = [
  {
    id: 'electrical_installation',
    title: 'Electrical Installation',
    description: 'Power distribution, transmission lines, substation electrical work'
  },
  {
    id: 'civil_construction',
    title: 'Civil Construction',
    description: 'Foundations, structures, roads, drainage systems'
  },
  {
    id: 'mechanical_installation',
    title: 'Mechanical Installation',
    description: 'Equipment installation, piping, HVAC systems'
  },
  {
    id: 'instrumentation',
    title: 'Instrumentation & Control',
    description: 'Control systems, automation, monitoring equipment'
  },
  {
    id: 'testing_commissioning',
    title: 'Testing & Commissioning',
    description: 'System testing, performance verification, startup services'
  },
  {
    id: 'maintenance',
    title: 'Maintenance Services',
    description: 'Preventive maintenance, repairs, overhauls'
  },
  {
    id: 'fabrication',
    title: 'Shop Fabrication',
    description: 'Steel fabrication, equipment manufacturing'
  },
  {
    id: 'erection',
    title: 'Erection Services',
    description: 'Equipment erection, tower installation, structural assembly'
  }
]

const characterCount = computed(() => {
  return formData.scope_of_work.length
})

watch(() => props.subcontractorData, (newData) => {
  if (newData) {
    initializeFormData()
  }
}, { immediate: true, deep: true })

function initializeFormData() {
  if (!props.subcontractorData) return
  
  const data = props.subcontractorData
  
  formData.scope_of_work = data.scope_of_work || ''
  formData.additional_notes = data.additional_notes || ''
  
  // Initialize selected categories (if stored in future)
  if (data.scope_categories && Array.isArray(data.scope_categories)) {
    selectedCategories.value = [...data.scope_categories]
  } else {
    selectedCategories.value = []
  }
}

function clearForm() {
  if (confirm('Are you sure you want to clear all scope of work data?')) {
    formData.scope_of_work = ''
    formData.additional_notes = ''
    selectedCategories.value = []
    showAlert('info', 'Form has been cleared')
  }
}

async function saveScope() {
  if (!formData.scope_of_work.trim()) {
    showAlert('warning', 'Please provide a description of your scope of work')
    return
  }

  if (formData.scope_of_work.length > maxCharacters) {
    showAlert('warning', `Scope of work description cannot exceed ${maxCharacters} characters`)
    return
  }

  loading.value = true
  
  try {
    const dataToSave = {
      scope_of_work: formData.scope_of_work.trim(),
      additional_notes: formData.additional_notes.trim(),
      scope_categories: selectedCategories.value
    }

    const token = localStorage.getItem('token')
    const response = await axios.put('/api/vendor/subcontractor', dataToSave, {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      showAlert('success', 'Scope of work saved successfully!')
      emit('updated', response.data.data.subcontractor_data)
    } else {
      showAlert('error', response.data.message || 'Failed to save scope of work')
    }
  } catch (error) {
    console.error('Save scope error:', error)
    showAlert('error', error.response?.data?.message || 'Failed to save scope of work')
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
.subcontractor-scope {
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
  line-height: 1.5;
}

.scope-section {
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 2rem;
}

.form-group label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
}

.scope-textarea {
  padding: 1rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 0.875rem;
  line-height: 1.5;
  resize: vertical;
  font-family: inherit;
}

.scope-textarea:focus {
  outline: none;
  border-color: #fd7e14;
  box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.character-count {
  text-align: right;
  font-size: 0.75rem;
  color: #6c757d;
  margin-top: 0.25rem;
}

.categories-section {
  border-top: 1px solid #dee2e6;
  padding-top: 2rem;
  margin-bottom: 2rem;
}

.categories-section h4 {
  color: #495057;
  margin-bottom: 1rem;
}

.section-description {
  color: #6c757d;
  margin-bottom: 1.5rem;
}

.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1rem;
}

.category-item {
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  padding: 1rem;
  transition: all 0.2s;
}

.category-item:hover {
  border-color: #fd7e14;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.category-checkbox {
  display: flex;
  align-items: flex-start;
  cursor: pointer;
  gap: 1rem;
  width: 100%;
}

.category-checkbox input[type="checkbox"] {
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
  margin-top: 2px;
}

.category-checkbox input[type="checkbox"]:checked ~ .checkmark {
  background-color: #fd7e14;
  border-color: #fd7e14;
}

.category-checkbox input[type="checkbox"]:checked ~ .checkmark:after {
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

.category-content {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.category-title {
  font-weight: 600;
  color: #495057;
}

.category-description {
  font-size: 0.875rem;
  color: #6c757d;
  line-height: 1.4;
}

.notes-section {
  border-top: 1px solid #dee2e6;
  padding-top: 2rem;
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

.form-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #dee2e6;
  display: flex;
  gap: 1rem;
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

.btn-outline {
  background: white;
  color: #6c757d;
  border: 1px solid #ced4da;
}

.btn-outline:hover {
  background: #f8f9fa;
  border-color: #adb5bd;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .categories-grid {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
  }
}
</style>