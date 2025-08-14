<!-- resources/js/views/components/subcontractor/SubcontractorProjects.vue -->
<template>
  <div class="subcontractor-projects">
    <div class="tab-header">
      <h3>
        <i class="fas fa-project-diagram"></i>
        Major Projects 3-5 Previous Years
      </h3>
      <p>Please complete information of your major project 3-5 previous years below. / Harap melengkapi informasi terkait pengalaman terbesar Perusahaan Anda 3-5 tahun terakhir di bawah ini.</p>
    </div>

    <div class="projects-section">
      <div class="projects-table-container">
        <table class="projects-table">
          <thead>
            <tr>
              <th class="field-label">Field</th>
              <th class="project-col">Project 1</th>
              <th class="project-col">Project 2</th>
              <th class="project-col">Project 3</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="field-label">
                <strong>Year of Execution</strong><br>
                <small>Tahun Pelaksanaan</small>
              </td>
              <td>
                <input 
                  v-model="projects[0].year_of_execution"
                  type="text"
                  placeholder="e.g. 2022"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[1].year_of_execution"
                  type="text"
                  placeholder="e.g. 2021"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[2].year_of_execution"
                  type="text"
                  placeholder="e.g. 2020"
                  class="project-input"
                />
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Project Name</strong><br>
                <small>Nama Proyek</small>
              </td>
              <td>
                <input 
                  v-model="projects[0].project_name"
                  type="text"
                  placeholder="Project name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[1].project_name"
                  type="text"
                  placeholder="Project name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[2].project_name"
                  type="text"
                  placeholder="Project name"
                  class="project-input"
                />
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Project Owner</strong><br>
                <small>Pemilik Proyek</small>
              </td>
              <td>
                <input 
                  v-model="projects[0].project_owner"
                  type="text"
                  placeholder="Owner name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[1].project_owner"
                  type="text"
                  placeholder="Owner name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[2].project_owner"
                  type="text"
                  placeholder="Owner name"
                  class="project-input"
                />
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Main Contractor</strong><br>
                <small>Kontraktor</small>
              </td>
              <td>
                <input 
                  v-model="projects[0].main_contractor"
                  type="text"
                  placeholder="Contractor name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[1].main_contractor"
                  type="text"
                  placeholder="Contractor name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[2].main_contractor"
                  type="text"
                  placeholder="Contractor name"
                  class="project-input"
                />
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Value</strong><br>
                <small>Nilai PO/Kontrak</small>
              </td>
              <td>
                <div class="currency-input">
                  <span class="currency-prefix">Rp</span>
                  <input 
                    v-model="projects[0].value"
                    type="text"
                    placeholder="0"
                    class="project-input currency"
                    @input="formatCurrency(0, $event)"
                  />
                </div>
              </td>
              <td>
                <div class="currency-input">
                  <span class="currency-prefix">Rp</span>
                  <input 
                    v-model="projects[1].value"
                    type="text"
                    placeholder="0"
                    class="project-input currency"
                    @input="formatCurrency(1, $event)"
                  />
                </div>
              </td>
              <td>
                <div class="currency-input">
                  <span class="currency-prefix">Rp</span>
                  <input 
                    v-model="projects[2].value"
                    type="text"
                    placeholder="0"
                    class="project-input currency"
                    @input="formatCurrency(2, $event)"
                  />
                </div>
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Work Package</strong><br>
                <small>Paket Pekerjaan</small>
              </td>
              <td>
                <textarea 
                  v-model="projects[0].work_package"
                  rows="3"
                  placeholder="Work package description"
                  class="project-textarea"
                ></textarea>
              </td>
              <td>
                <textarea 
                  v-model="projects[1].work_package"
                  rows="3"
                  placeholder="Work package description"
                  class="project-textarea"
                ></textarea>
              </td>
              <td>
                <textarea 
                  v-model="projects[2].work_package"
                  rows="3"
                  placeholder="Work package description"
                  class="project-textarea"
                ></textarea>
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Scope of Work</strong><br>
                <small>Sub Bidang Pekerjaan</small>
              </td>
              <td>
                <textarea 
                  v-model="projects[0].scope_of_work"
                  rows="3"
                  placeholder="Detailed scope description"
                  class="project-textarea"
                ></textarea>
              </td>
              <td>
                <textarea 
                  v-model="projects[1].scope_of_work"
                  rows="3"
                  placeholder="Detailed scope description"
                  class="project-textarea"
                ></textarea>
              </td>
              <td>
                <textarea 
                  v-model="projects[2].scope_of_work"
                  rows="3"
                  placeholder="Detailed scope description"
                  class="project-textarea"
                ></textarea>
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Work Duration</strong><br>
                <small>Durasi Pekerjaan</small>
              </td>
              <td>
                <input 
                  v-model="projects[0].work_duration"
                  type="text"
                  placeholder="e.g. 12 months"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[1].work_duration"
                  type="text"
                  placeholder="e.g. 18 months"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[2].work_duration"
                  type="text"
                  placeholder="e.g. 24 months"
                  class="project-input"
                />
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Representative of Client/Contractor</strong><br>
                <small>Perwakilan Klien/Kontraktor</small>
              </td>
              <td>
                <input 
                  v-model="projects[0].client_representative"
                  type="text"
                  placeholder="Contact person name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[1].client_representative"
                  type="text"
                  placeholder="Contact person name"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[2].client_representative"
                  type="text"
                  placeholder="Contact person name"
                  class="project-input"
                />
              </td>
            </tr>
            
            <tr>
              <td class="field-label">
                <strong>Phone</strong><br>
                <small>Telepon</small>
              </td>
              <td>
                <input 
                  v-model="projects[0].phone"
                  type="tel"
                  placeholder="+62 xxx xxxx xxxx"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[1].phone"
                  type="tel"
                  placeholder="+62 xxx xxxx xxxx"
                  class="project-input"
                />
              </td>
              <td>
                <input 
                  v-model="projects[2].phone"
                  type="tel"
                  placeholder="+62 xxx xxxx xxxx"
                  class="project-input"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Project Summary Cards -->
      <div class="project-summary">
        <h4>Projects Summary</h4>
        <div class="summary-cards">
          <div 
            v-for="(project, index) in projects" 
            :key="index"
            class="summary-card"
            :class="{ 'filled': isProjectFilled(project) }"
          >
            <div class="card-header">
              <h5>Project {{ index + 1 }}</h5>
              <span class="completion-badge" :class="getCompletionClass(project)">
                {{ getCompletionPercentage(project) }}%
              </span>
            </div>
            <div class="card-content">
              <div class="project-info" v-if="project.project_name">
                <strong>{{ project.project_name }}</strong>
              </div>
              <div class="project-info" v-if="project.year_of_execution">
                <i class="fas fa-calendar"></i> {{ project.year_of_execution }}
              </div>
              <div class="project-info" v-if="project.value">
                <i class="fas fa-money-bill"></i> {{ project.value }}
              </div>
              <div class="project-info" v-if="project.work_duration">
                <i class="fas fa-clock"></i> {{ project.work_duration }}
              </div>
              <div v-if="!isProjectFilled(project)" class="empty-state">
                <i class="fas fa-plus-circle"></i>
                <span>Click fields above to add project information</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button @click="saveProjects" :disabled="loading" class="btn btn-primary">
        <i class="fas fa-save"></i>
        {{ loading ? 'Saving...' : 'Save Major Projects' }}
      </button>
      
      <button @click="clearAllProjects" class="btn btn-outline">
        <i class="fas fa-trash"></i>
        Clear All Projects
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
import { ref, reactive, watch } from 'vue'
import axios from 'axios'
import AlertComponent from '../AlertComponent.vue'

const props = defineProps({
  subcontractorData: Object
})

const emit = defineEmits(['updated'])

const loading = ref(false)
const alertMsg = ref('')
const alertType = ref('success')

const projects = ref([
  {
    year_of_execution: '',
    project_name: '',
    project_owner: '',
    main_contractor: '',
    value: '',
    work_package: '',
    scope_of_work: '',
    work_duration: '',
    client_representative: '',
    phone: ''
  },
  {
    year_of_execution: '',
    project_name: '',
    project_owner: '',
    main_contractor: '',
    value: '',
    work_package: '',
    scope_of_work: '',
    work_duration: '',
    client_representative: '',
    phone: ''
  },
  {
    year_of_execution: '',
    project_name: '',
    project_owner: '',
    main_contractor: '',
    value: '',
    work_package: '',
    scope_of_work: '',
    work_duration: '',
    client_representative: '',
    phone: ''
  }
])

watch(() => props.subcontractorData, (newData) => {
  if (newData) {
    initializeProjectData()
  }
}, { immediate: true, deep: true })

function initializeProjectData() {
  if (!props.subcontractorData) return
  
  const data = props.subcontractorData
  
  if (data.major_projects && Array.isArray(data.major_projects)) {
    data.major_projects.forEach((project, index) => {
      if (index < 3 && projects.value[index]) {
        projects.value[index] = { ...projects.value[index], ...project }
      }
    })
  }
}

function formatCurrency(index, event) {
  let value = event.target.value.replace(/[^\d]/g, '')
  if (value) {
    value = parseInt(value).toLocaleString('id-ID')
  }
  projects.value[index].value = value
}

function isProjectFilled(project) {
  const requiredFields = ['project_name', 'year_of_execution', 'project_owner']
  return requiredFields.some(field => project[field] && project[field].trim())
}

function getCompletionPercentage(project) {
  const totalFields = 10
  const filledFields = Object.values(project).filter(value => value && value.toString().trim()).length
  return Math.round((filledFields / totalFields) * 100)
}

function getCompletionClass(project) {
  const percentage = getCompletionPercentage(project)
  if (percentage >= 80) return 'high'
  if (percentage >= 50) return 'medium'
  if (percentage > 0) return 'low'
  return 'empty'
}

function clearAllProjects() {
  if (confirm('Are you sure you want to clear all project data? This action cannot be undone.')) {
    projects.value.forEach(project => {
      Object.keys(project).forEach(key => {
        project[key] = ''
      })
    })
    showAlert('info', 'All project data has been cleared')
  }
}

async function saveProjects() {
  loading.value = true
  
  try {
    const dataToSave = {
      major_projects: projects.value
    }

    const token = localStorage.getItem('token')
    const response = await axios.put('/api/vendor/subcontractor', dataToSave, {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      showAlert('success', 'Major projects saved successfully!')
      emit('updated', response.data.data.subcontractor_data)
    } else {
      showAlert('error', response.data.message || 'Failed to save major projects')
    }
  } catch (error) {
    console.error('Save projects error:', error)
    showAlert('error', error.response?.data?.message || 'Failed to save major projects')
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
.subcontractor-projects {
  max-width: 1200px;
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

.projects-section {
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}

.projects-table-container {
  overflow-x: auto;
  margin-bottom: 2rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.projects-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 800px;
}

.projects-table th,
.projects-table td {
  padding: 1rem;
  border-bottom: 1px solid #dee2e6;
  vertical-align: top;
}

.projects-table th {
  background: #495057;
  color: white;
  font-weight: 600;
  position: sticky;
  top: 0;
  z-index: 10;
}

.field-label {
  background: #f8f9fa !important;
  font-weight: 500;
  width: 200px;
  min-width: 200px;
}

.field-label strong {
  color: #495057;
}

.field-label small {
  color: #6c757d;
  font-style: italic;
}

.project-col {
  width: 250px;
  text-align: center;
}

.project-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 0.875rem;
}

.project-input:focus {
  outline: none;
  border-color: #fd7e14;
  box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.project-textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 0.875rem;
  resize: vertical;
  font-family: inherit;
}

.project-textarea:focus {
  outline: none;
  border-color: #fd7e14;
  box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.currency-input {
  position: relative;
  display: flex;
  align-items: center;
}

.currency-prefix {
  position: absolute;
  left: 0.5rem;
  color: #6c757d;
  font-weight: 500;
  z-index: 1;
  font-size: 0.875rem;
}

.currency-input .project-input {
  padding-left: 2rem;
}

.project-summary {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #dee2e6;
}

.project-summary h4 {
  color: #495057;
  margin-bottom: 1rem;
}

.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1rem;
}

.summary-card {
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 1.5rem;
  transition: all 0.2s;
}

.summary-card.filled {
  border-color: #fd7e14;
  box-shadow: 0 2px 8px rgba(253, 126, 20, 0.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.card-header h5 {
  margin: 0;
  color: #495057;
}

.completion-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
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

.completion-badge.empty {
  background: #e9ecef;
  color: #6c757d;
}

.project-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  color: #495057;
}

.project-info i {
  color: #6c757d;
  width: 16px;
}

.empty-state {
  text-align: center;
  color: #6c757d;
  font-style: italic;
  padding: 2rem 0;
}

.empty-state i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  display: block;
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
  .summary-cards {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
  }
}
</style>