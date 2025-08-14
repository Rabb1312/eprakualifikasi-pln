<!-- resources/js/views/components/subcontractor/SubcontractorHolding.vue -->
<template>
  <div class="subcontractor-holding">
    <div class="tab-header">
      <h3>
        <i class="fas fa-building"></i>
        Holding Company
      </h3>
      <p>Information about your holding company (if applicable).</p>
    </div>

    <div class="form-grid">
      <!-- Basic Company Info -->
      <div class="form-section">
        <h4>Company Information</h4>
        
        <div class="form-row">
          <div class="form-group">
            <label>Nama Perusahaan Induk</label>
            <input 
              v-model="formData.holding_nama_perusahaan"
              type="text"
              placeholder="Nama perusahaan holding"
            />
          </div>
          <div class="form-group">
            <label>Tanggal Berdiri</label>
            <input 
              v-model="formData.holding_tanggal_berdiri"
              type="date"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group full-width">
            <label>Alamat</label>
            <textarea 
              v-model="formData.holding_alamat"
              rows="3"
              placeholder="Alamat lengkap perusahaan holding"
            ></textarea>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Nomor Telepon</label>
            <input 
              v-model="formData.holding_phone"
              type="tel"
              placeholder="+62 xxx xxxx xxxx"
            />
          </div>
          <div class="form-group">
            <label>Nama Direktur</label>
            <input 
              v-model="formData.holding_nama_direktur"
              type="text"
              placeholder="Nama direktur utama"
            />
          </div>
        </div>
      </div>

      <!-- Financial Information -->
      <div class="form-section">
        <h4>Financial Information</h4>
        
        <div class="form-row">
          <div class="form-group">
            <label>Modal Dasar</label>
            <div class="currency-input">
              <span class="currency-prefix">Rp</span>
              <input 
                v-model="formData.holding_modal_dasar"
                type="number"
                placeholder="0"
                step="0.01"
              />
            </div>
          </div>
          <div class="form-group">
            <label>Modal Dikeluarkan</label>
            <div class="currency-input">
              <span class="currency-prefix">Rp</span>
              <input 
                v-model="formData.holding_modal_dikeluarkan"
                type="number"
                placeholder="0"
                step="0.01"
              />
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group full-width">
            <label>Pemegang Saham</label>
            <textarea 
              v-model="formData.holding_pemegang_saham"
              rows="4"
              placeholder="Daftar pemegang saham dan persentase kepemilikan"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Contact Person -->
      <div class="form-section">
        <h4>Contact Person</h4>
        
        <div class="contact-person-list">
          <div 
            v-for="(contact, index) in contactPersons" 
            :key="index"
            class="contact-item"
          >
            <div class="contact-header">
              <h5>Contact Person {{ index + 1 }}</h5>
              <button 
                v-if="contactPersons.length > 1"
                @click="removeContact(index)"
                class="btn btn-danger btn-sm"
              >
                <i class="fas fa-trash"></i>
              </button>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Nama</label>
                <input 
                  v-model="contact.nama"
                  type="text"
                  placeholder="Nama lengkap"
                />
              </div>
              <div class="form-group">
                <label>Jabatan</label>
                <input 
                  v-model="contact.jabatan"
                  type="text"
                  placeholder="Jabatan"
                />
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Telepon</label>
                <input 
                  v-model="contact.telepon"
                  type="tel"
                  placeholder="+62 xxx xxxx xxxx"
                />
              </div>
              <div class="form-group">
                <label>Email</label>
                <input 
                  v-model="contact.email"
                  type="email"
                  placeholder="email@example.com"
                />
              </div>
            </div>
          </div>
        </div>

        <button @click="addContact" class="btn btn-outline">
          <i class="fas fa-plus"></i>
          Add Contact Person
        </button>
      </div>
    </div>

    <div class="form-actions">
      <button @click="saveHolding" :disabled="loading" class="btn btn-primary">
        <i class="fas fa-save"></i>
        {{ loading ? 'Saving...' : 'Save Holding Company Info' }}
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
import { ref, reactive, watch, onMounted } from 'vue'
import axios from 'axios'
import AlertComponent from '../AlertComponent.vue'

const props = defineProps({
  subcontractorData: Object
})

const emit = defineEmits(['updated'])

const loading = ref(false)
const alertMsg = ref('')
const alertType = ref('success')

const formData = reactive({
  holding_nama_perusahaan: '',
  holding_tanggal_berdiri: '',
  holding_alamat: '',
  holding_phone: '',
  holding_modal_dasar: '',
  holding_modal_dikeluarkan: '',
  holding_pemegang_saham: '',
  holding_nama_direktur: ''
})

const contactPersons = ref([
  { nama: '', jabatan: '', telepon: '', email: '' }
])

watch(() => props.subcontractorData, (newData) => {
  if (newData) {
    initializeFormData()
  }
}, { immediate: true, deep: true })

function initializeFormData() {
  if (!props.subcontractorData) return
  
  const data = props.subcontractorData
  
  formData.holding_nama_perusahaan = data.holding_nama_perusahaan || ''
  formData.holding_tanggal_berdiri = data.holding_tanggal_berdiri || ''
  formData.holding_alamat = data.holding_alamat || ''
  formData.holding_phone = data.holding_phone || ''
  formData.holding_modal_dasar = data.holding_modal_dasar || ''
  formData.holding_modal_dikeluarkan = data.holding_modal_dikeluarkan || ''
  formData.holding_pemegang_saham = data.holding_pemegang_saham || ''
  formData.holding_nama_direktur = data.holding_nama_direktur || ''
  
  // Initialize contact persons
  if (data.holding_contact_person && Array.isArray(data.holding_contact_person)) {
    contactPersons.value = data.holding_contact_person.map(contact => ({
      nama: contact.nama || '',
      jabatan: contact.jabatan || '',
      telepon: contact.telepon || '',
      email: contact.email || ''
    }))
  }
  
  // Ensure at least one contact person
  if (contactPersons.value.length === 0) {
    contactPersons.value = [{ nama: '', jabatan: '', telepon: '', email: '' }]
  }
}

function addContact() {
  contactPersons.value.push({ nama: '', jabatan: '', telepon: '', email: '' })
}

function removeContact(index) {
  if (contactPersons.value.length > 1) {
    contactPersons.value.splice(index, 1)
  }
}

async function saveHolding() {
  loading.value = true
  
  try {
    const dataToSave = {
      ...formData,
      holding_contact_person: contactPersons.value
    }

    const token = localStorage.getItem('token')
    const response = await axios.put('/api/vendor/subcontractor', dataToSave, {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (response.data.success) {
      showAlert('success', 'Holding company information saved successfully!')
      emit('updated', response.data.data.subcontractor_data)
    } else {
      showAlert('error', response.data.message || 'Failed to save holding company information')
    }
  } catch (error) {
    console.error('Save holding error:', error)
    showAlert('error', error.response?.data?.message || 'Failed to save holding company information')
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
.subcontractor-holding {
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
}

.form-grid {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.form-section {
  padding: 1.5rem;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  background: #f8f9fa;
}

.form-section h4 {
  color: #495057;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #dee2e6;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

.form-group input,
.form-group textarea {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 0.875rem;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
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
  left: 0.75rem;
  color: #6c757d;
  font-weight: 500;
  z-index: 1;
}

.currency-input input {
  padding-left: 2.5rem;
}

.contact-person-list {
  margin-bottom: 1rem;
}

.contact-item {
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  padding: 1.5rem;
  margin-bottom: 1rem;
}

.contact-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.contact-header h5 {
  color: #495057;
  margin: 0;
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

.btn-outline {
  background: white;
  color: #fd7e14;
  border: 1px solid #fd7e14;
}

.btn-outline:hover {
  background: #fd7e14;
  color: white;
}

.btn-danger {
  background: #dc3545;
  color: white;
}

.btn-danger:hover {
  background: #c82333;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.form-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #dee2e6;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>