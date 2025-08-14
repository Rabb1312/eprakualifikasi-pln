<!-- resources/js/views/components/subcontractor/SubcontractorEmployees.vue -->
<template>
  <div class="subcontractor-employees">
    <div class="tab-header">
      <h3>
        <i class="fas fa-users"></i>
        Total Permanent Construction Employees
      </h3>
      <p>Give below total Number of permanent Construction employees at present on your payroll / Berikan dibawah ini Jumlah total karyawan Konstruksi tetap saat ini dalam daftar gaji Anda</p>
    </div>

    <div class="employees-table-section">
      <div class="table-container">
        <table class="employees-table">
          <thead>
            <tr>
              <th class="no-col">No</th>
              <th class="classification-col">Classification</th>
              <th class="count-col">Craft Labor</th>
              <th class="count-col">Foreman</th>
              <th class="count-col">Supervisor</th>
              <th class="count-col">Manager</th>
              <th class="total-col">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="(employee, index) in employees" 
              :key="index"
              :class="{ 'highlight-row': employee.classification === 'Others' }"
            >
              <td class="text-center">{{ index + 1 }}</td>
              <td class="classification-cell">
                <strong>{{ employee.classification }}</strong>
              </td>
              <td>
                <input 
                  v-model.number="employee.craft_labor"
                  type="number"
                  min="0"
                  class="count-input"
                  @input="calculateTotal(index)"
                />
              </td>
              <td>
                <input 
                  v-model.number="employee.foreman"
                  type="number"
                  min="0"
                  class="count-input"
                  @input="calculateTotal(index)"
                />
              </td>
              <td>
                <input 
                  v-model.number="employee.supervisor"
                  type="number"
                  min="0"
                  class="count-input"
                  @input="calculateTotal(index)"
                />
              </td>
              <td>
                <input 
                  v-model.number="employee.manager"
                  type="number"
                  min="0"
                  class="count-input"
                  @input="calculateTotal(index)"
                />
              </td>
              <td class="total-cell">
                <span class="total-badge">{{ getEmployeeTotal(employee) }}</span>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr class="total-row">
              <td colspan="2"><strong>TOTAL</strong></td>
              <td><strong>{{ getTotalByType('craft_labor') }}</strong></td>
              <td><strong>{{ getTotalByType('foreman') }}</strong></td>
              <td><strong>{{ getTotalByType('supervisor') }}</strong></td>
              <td><strong>{{ getTotalByType('manager') }}</strong></td>
              <td><strong class="grand-total">{{ getGrandTotal() }}</strong></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Summary Cards -->
      <div class="summary-cards">
        <div class="summary-card">
          <div class="summary-icon">
            <i class="fas fa-hard-hat"></i>
          </div>
          <div class="summary-content">
            <h4>{{ getTotalByType('craft_labor') }}</h4>
            <p>Craft Labor</p>
          </div>
        </div>
        
        <div class="summary-card">
          <div class="summary-icon">
            <i class="fas fa-user-tie"></i>
          </div>
          <div class="summary-content">
            <h4>{{ getTotalByType('foreman') }}</h4>
            <p>Foreman</p>
          </div>
        </div>
        
        <div class="summary-card">
          <div class="summary-icon">
            <i class="fas fa-user-cog"></i>
          </div>
          <div class="summary-content">
            <h4>{{ getTotalByType('supervisor') }}</h4>
            <p>Supervisor</p>
          </div>
        </div>
        
        <div class="summary-card">
          <div class="summary-icon">
            <i class="fas fa-user-crown"></i>
          </div>
          <div class="summary-content">
            <h4>{{ getTotalByType('manager') }}</h4>
            <p>Manager</p>
          </div>
        </div>
        
        <div class="summary-card grand-total-card">
          <div class="summary-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="summary-content">
            <h4>{{ getGrandTotal() }}</h4>
            <p>Total Employees</p>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button @click="saveEmployees" :disabled="loading" class="btn btn-primary">
        <i class="fas fa-save"></i>
        {{ loading ? 'Saving...' : 'Save Employee Data' }}
      </button>
      
      <button @click="resetAllData" class="btn btn-outline">
        <i class="fas fa-undo"></i>
        Reset All Data
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
  subcontractorData: Object,
  employeeClassifications: Array
})

const emit = defineEmits(['updated'])

const loading = ref(false)
const alertMsg = ref('')
const alertType = ref('success')

const employees = ref([])

watch(() => props.subcontractorData, (newData) => {
  if (newData) {
    initializeEmployeeData()
  }
}, { immediate: true, deep: true })

watch(() => props.employeeClassifications, (newClassifications) => {
  if (newClassifications && newClassifications.length > 0) {
    initializeEmployeeData()
  }
}, { immediate: true })

function initializeEmployeeData() {
  if (!props.employeeClassifications || props.employeeClassifications.length === 0) return
  
  if (props.subcontractorData && props.subcontractorData.employees && Array.isArray(props.subcontractorData.employees)) {
    // Use existing data
    employees.value = props.subcontractorData.employees.map(emp => ({
      classification: emp.classification,
      craft_labor: Number(emp.craft_labor) || 0,
      foreman: Number(emp.foreman) || 0,
      supervisor: Number(emp.supervisor) || 0,
      manager: Number(emp.manager) || 0
    }))
  } else {
    // Initialize with default data
    employees.value = props.employeeClassifications.map(classification => ({
      classification,
      craft_labor: 0,
      foreman: 0,
      supervisor: 0,
      manager: 0
    }))
  }
}

function calculateTotal(index) {
  // This is called when input changes to trigger reactivity
  const employee = employees.value[index]
  if (employee) {
    // Ensure values are numbers
    employee.craft_labor = Number(employee.craft_labor) || 0
    employee.foreman = Number(employee.foreman) || 0
    employee.supervisor = Number(employee.supervisor) || 0
    employee.manager = Number(employee.manager) || 0
  }
}

function getEmployeeTotal(employee) {
  return (Number(employee.craft_labor) || 0) + 
         (Number(employee.foreman) || 0) + 
         (Number(employee.supervisor) || 0) + 
         (Number(employee.manager) || 0)
}

function getTotalByType(type) {
  return employees.value.reduce((total, employee) => {
    return total + (Number(employee[type]) || 0)
  }, 0)
}

function getGrandTotal() {
  return employees.value.reduce((total, employee) => {
    return total + getEmployeeTotal(employee)
  }, 0)
}

function resetAllData() {
  if (confirm('Are you sure you want to reset all employee data? This action cannot be undone.')) {
    employees.value.forEach(employee => {
      employee.craft_labor = 0
      employee.foreman = 0
      employee.supervisor = 0
      employee.manager = 0
    })
    showAlert('info', 'Employee data has been reset')
  }
}

async function saveEmployees() {
  loading.value = true
  
  try {
    const dataToSave = {
      employees: employees.value
    }

    const token = localStorage.getItem('token')
    const response = await axios.put('/api/vendor/subcontractor', dataToSave, {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      showAlert('success', 'Employee data saved successfully!')
      emit('updated', response.data.data.subcontractor_data)
    } else {
      showAlert('error', response.data.message || 'Failed to save employee data')
    }
  } catch (error) {
    console.error('Save employees error:', error)
    showAlert('error', error.response?.data?.message || 'Failed to save employee data')
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
.subcontractor-employees {
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
}

.employees-table-section {
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}

.table-container {
  overflow-x: auto;
  margin-bottom: 2rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.employees-table {
  width: 100%;
  border-collapse: collapse;
}

.employees-table th,
.employees-table td {
  padding: 1rem;
  text-align: center;
  border-bottom: 1px solid #dee2e6;
}

.employees-table th {
  background: #495057;
  color: white;
  font-weight: 600;
  position: sticky;
  top: 0;
  z-index: 10;
}

.no-col {
  width: 60px;
}

.classification-col {
  width: 250px;
  text-align: left !important;
}

.count-col {
  width: 120px;
}

.total-col {
  width: 100px;
}

.classification-cell {
  text-align: left !important;
  font-weight: 500;
  color: #495057;
}

.count-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ced4da;
  border-radius: 4px;
  text-align: center;
  font-weight: 500;
}

.count-input:focus {
  outline: none;
  border-color: #fd7e14;
  box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.total-cell {
  font-weight: 600;
}

.total-badge {
  background: #17a2b8;
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-weight: 600;
}

.highlight-row {
  background: #fff3cd;
}

.total-row {
  background: #e9ecef;
  font-weight: 600;
}

.grand-total {
  color: #fd7e14;
  font-size: 1.1em;
}

.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.summary-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.summary-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  background: #e9ecef;
  color: #495057;
}

.grand-total-card .summary-icon {
  background: #fd7e14;
  color: white;
}

.summary-content h4 {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
  color: #212529;
}

.summary-content p {
  margin: 0;
  color: #6c757d;
  font-size: 0.875rem;
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