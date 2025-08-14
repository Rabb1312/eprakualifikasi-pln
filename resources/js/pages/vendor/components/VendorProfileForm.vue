<!-- File: src/pages/vendor/components/VendorProfileForm.vue -->
<template>
  <div class="vendor-profile-form">
    <form @submit.prevent="saveProfile">
      <div v-if="alertMsg" :class="['alert', alertTypeClass]" v-html="alertMsg" />
      
      <!-- Group fields by sections -->
      <div v-for="section in sections" :key="section.id" class="form-section">
        <div class="section-header">
          <i :class="section.icon"></i>
          <h3>{{ section.title }}</h3>
        </div>
        
        <div class="section-fields">
          <div 
            v-for="field in section.fields" 
            :key="field.id"
            :class="['form-group', field.type === 'json_array' ? 'json-array-group' : '', field.readonly ? 'readonly-field' : '']"
          >
            <!-- Regular input fields -->
            <template v-if="field.type !== 'json_array'">
              <label :for="field.field">
                {{ field.label }}
                <span v-if="field.required" class="required">*</span>
                <span v-if="field.readonly" class="readonly-badge">Tidak dapat diubah</span>
              </label>
              
              <!-- Text Input -->
              <input 
    v-if="field.type === 'text'"
    v-model="formData[field.field]"
    :id="field.field"
    type="text"
    :required="field.required && !field.readonly"
    :readonly="field.readonly"
    :placeholder="field.placeholder || (field.readonly ? 'Data dari registrasi' : '')"
    :pattern="field.pattern && field.pattern.trim() ? field.pattern : null"
    :maxlength="field.maxlength && field.maxlength > 0 ? field.maxlength : null"
    :class="{ 'readonly-input': field.readonly }"
    @invalid="onFieldInvalid($event, field)"
  />
              
              <!-- Textarea -->
              <textarea 
                v-else-if="field.type === 'textarea'"
                v-model="formData[field.field]"
                :id="field.field"
                :required="field.required && !field.readonly"
                :readonly="field.readonly"
                :placeholder="field.readonly ? 'Data dari registrasi' : ''"
                rows="3"
                :class="{ 'readonly-input': field.readonly }"
              ></textarea>
              
              <!-- Select -->
              <select 
                v-else-if="field.type === 'select'"
                v-model="formData[field.field]"
                :id="field.field"
                :required="field.required && !field.readonly"
                :disabled="field.readonly"
                :class="{ 'readonly-input': field.readonly }"
              >
                <option value="">{{ field.readonly ? 'Data dari registrasi' : 'Pilih ' + field.label }}</option>
                <option 
                  v-for="(label, value) in field.options" 
                  :key="value"
                  :value="value"
                >
                  {{ label }}
                </option>
              </select>
              
              <!-- Date -->
              <input 
                v-else-if="field.type === 'date'"
                v-model="formData[field.field]"
                :id="field.field"
                type="date"
                :required="field.required && !field.readonly"
                :readonly="field.readonly"
                :class="{ 'readonly-input': field.readonly }"
              />
              
              <!-- Number -->
              <input 
                v-else-if="field.type === 'number'"
                v-model.number="formData[field.field]"
                :id="field.field"
                type="number"
                :required="field.required && !field.readonly"
                :readonly="field.readonly"
                min="0"
                :class="{ 'readonly-input': field.readonly }"
              />
              
              <!-- Currency -->
              <input 
                v-else-if="field.type === 'currency'"
                v-model="formData[field.field]"
                :id="field.field"
                type="text"
                :required="field.required && !field.readonly"
                :readonly="field.readonly"
                @input="formatCurrency(field.field)"
                placeholder="Rp 0"
                :class="{ 'readonly-input': field.readonly }"
              />
              
              <!-- URL -->
              <input 
                v-else-if="field.type === 'url'"
                v-model="formData[field.field]"
                :id="field.field"
                type="url"
                :required="field.required && !field.readonly"
                :readonly="field.readonly"
                placeholder="https://..."
                :class="{ 'readonly-input': field.readonly }"
              />
              
              <!-- Tel -->
              <input 
                v-else-if="field.type === 'tel'"
                v-model="formData[field.field]"
                :id="field.field"
                type="tel"
                :required="field.required && !field.readonly"
                :readonly="field.readonly"
                placeholder="+62..."
                :class="{ 'readonly-input': field.readonly }"
              />
              
              <!-- Readonly Information -->
              <small v-if="field.readonly" class="readonly-info">
                <i class="fas fa-info-circle"></i>
                {{ getReadonlyInfo(field.field) }}
              </small>
            </template>
            
            <!-- JSON Array Fields (Contact Person, etc.) - unchanged -->
            <template v-else>
              <label>
                {{ field.label }}
                <span v-if="field.required" class="required">*</span>
              </label>
              
              <div class="json-array-container">
                <div 
                  v-for="(item, index) in formData[field.field]" 
                  :key="index"
                  class="json-array-item"
                >
                  <div class="array-item-header">
                    <h4>{{ field.label }} {{ index + 1 }}</h4>
                    <button 
                      v-if="formData[field.field].length > 1"
                      type="button" 
                      class="remove-btn"
                      @click="removeArrayItem(field.field, index)"
                    >
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  
                  <div class="array-item-fields">
                    <div 
                      v-for="subField in field.fields" 
                      :key="subField"
                      class="sub-field"
                    >
                      <label>{{ getSubFieldLabel(subField) }}</label>
                      <input 
                        v-model="item[subField]"
                        :type="getSubFieldType(subField)"
                        :required="field.required && index === 0"
                      />
                    </div>
                  </div>
                </div>
                
                <button 
                  type="button" 
                  class="add-btn"
                  @click="addArrayItem(field.field, field.fields)"
                >
                  <i class="fas fa-plus"></i>
                  Tambah {{ field.label }}
                </button>
              </div>
            </template>
          </div>
        </div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="save-btn" :disabled="loading">
          <span v-if="!loading">
            <i class="fas fa-save"></i>
            Simpan Profil
          </span>
          <span v-else>
            <i class="fas fa-spinner fa-spin"></i>
            Menyimpan...
          </span>
        </button>
        
        <!-- <button type="button" class="calculate-btn" @click="calculateCompletion">
          <i class="fas fa-calculator"></i>
          Hitung Kelengkapan
        </button> -->
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  fields: Array,
  vendor: Object,
  vendorConfig: Object
})

const emit = defineEmits(['updated'])

const formData = ref({})
const loading = ref(false)
const alertMsg = ref('')
const alertType = ref('success')

const alertTypeClass = computed(() => {
  return {
    error: 'alert-error',
    success: 'alert-success',
    info: 'alert-info',
  }[alertType.value]
})

const sections = computed(() => {
  const sectionConfig = {
    basic: { id: 'basic', title: 'Informasi Dasar', icon: 'fas fa-info-circle' },
    company: { id: 'company', title: 'Data Perusahaan', icon: 'fas fa-building' },
    address: { id: 'address', title: 'Alamat', icon: 'fas fa-map-marker-alt' },
    contact: { id: 'contact', title: 'Kontak', icon: 'fas fa-phone' },
    personnel: { id: 'personnel', title: 'Personil', icon: 'fas fa-users' },
    legal: { id: 'legal', title: 'Legal', icon: 'fas fa-gavel' },
    financial: { id: 'financial', title: 'Keuangan', icon: 'fas fa-dollar-sign' },
    management: { id: 'management', title: 'Manajemen', icon: 'fas fa-user-tie' },
    source: { id: 'source', title: 'Sumber Informasi', icon: 'fas fa-source' },
    assets: { id: 'assets', title: 'Aset', icon: 'fas fa-warehouse' },
    additional: { id: 'additional', title: 'Tambahan', icon: 'fas fa-plus' }
  }
  
  const grouped = {}
  props.fields.forEach(field => {
    const section = field.section || 'additional'
    if (!grouped[section]) {
      grouped[section] = {
        ...sectionConfig[section],
        fields: []
      }
    }
    grouped[section].fields.push(field)
  })
  
  return Object.values(grouped)
})

watch(() => props.vendor, (newVendor) => {
  if (newVendor) {
    initializeFormData()
  }
}, { immediate: true })

onMounted(() => {
  initializeFormData()
})

function initializeFormData() {
  if (!props.vendor) return
  
  formData.value = { ...props.vendor }
  
  // Initialize JSON array fields
  props.fields.forEach(field => {
    if (field.type === 'json_array') {
      if (!formData.value[field.field] || !Array.isArray(formData.value[field.field])) {
        formData.value[field.field] = [createEmptyArrayItem(field.fields)]
      }
    }
  })
}

function onFieldInvalid(event, field) {
  console.log('❌ Field validation error:', {
    field: field.field,
    label: field.label,
    value: formData.value[field.field],
    pattern: field.pattern,
    required: field.required,
    validity: event.target.validity
  })
  
  // Show user-friendly error message
  if (event.target.validity.patternMismatch) {
    event.target.setCustomValidity(`Format ${field.label} tidak sesuai`)
  } else if (event.target.validity.valueMissing) {
    event.target.setCustomValidity(`${field.label} wajib diisi`)
  }
}

function onFieldInput(event, field) {
  event.target.setCustomValidity('')
}

function createEmptyArrayItem(fields) {
  const item = {}
  fields.forEach(field => {
    item[field] = ''
  })
  return item
}

function addArrayItem(fieldName, subFields) {
  formData.value[fieldName].push(createEmptyArrayItem(subFields))
}

function removeArrayItem(fieldName, index) {
  formData.value[fieldName].splice(index, 1)
}

function getSubFieldLabel(subField) {
  const labels = {
    nama: 'Nama',
    jabatan: 'Jabatan',
    telepon: 'Telepon',
    email: 'Email'
  }
  return labels[subField] || subField
}

function getSubFieldType(subField) {
  const types = {
    nama: 'text',
    jabatan: 'text',
    telepon: 'tel',
    email: 'email'
  }
  return types[subField] || 'text'
}

function formatCurrency(fieldName) {
  let value = formData.value[fieldName]
  if (!value) return
  
  // Remove non-numeric characters except dots
  value = value.toString().replace(/[^\d]/g, '')
  
  // Format as currency
  if (value) {
    const formatter = new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    })
    formData.value[fieldName] = formatter.format(parseInt(value))
  }
}

function showAlert(type, msg) {
  alertType.value = type
  alertMsg.value = msg
  setTimeout(() => (alertMsg.value = ''), 5000)
}

async function saveProfile() {
  loading.value = true
  
  try {
    // ✅ ADD: Check form validity before submit
    const form = document.querySelector('form')
    if (!form.checkValidity()) {
      console.log('❌ Form validation failed')
      
      // Find first invalid field
      const firstInvalid = form.querySelector(':invalid')
      if (firstInvalid) {
        firstInvalid.focus()
        const fieldName = firstInvalid.id
        const field = props.fields.find(f => f.field === fieldName)
        
        showAlert('error', `Mohon periksa field: ${field?.label || fieldName}`)
        return
      }
    }
    
    console.log('✅ Form validation passed, sending data:', formData.value)
    
    const token = localStorage.getItem('token')
    const response = await axios.put('/api/vendor/profile', formData.value, {
      headers: { Authorization: `Bearer ${token}` }
    })
    
    console.log('📥 Save response:', response.data)
    
    if (response.data.success) {
      showAlert('success', 'Profil berhasil disimpan!')
      emit('updated', response.data.data.vendor)
    } else {
      showAlert('error', response.data.message || 'Gagal menyimpan profil')
    }
  } catch (error) {
    console.error('❌ Save profile error:', error)
    
    if (error.response?.data?.errors) {
      // Laravel validation errors
      const errors = Object.values(error.response.data.errors).flat()
      showAlert('error', errors.join('<br>'))
    } else {
      showAlert('error', error.response?.data?.message || 'Terjadi kesalahan saat menyimpan')
    }
  } finally {
    loading.value = false
  }
}


async function calculateCompletion() {
  try {
    const token = localStorage.getItem('token')
    const response = await axios.post('/api/vendor/calculate-completion', {}, {
      headers: { Authorization: `Bearer ${token}` }
    })
    
    if (response.data.success) {
      showAlert('info', `Kelengkapan profil: ${response.data.data.completion_percentage}%`)
      emit('updated', response.data.data.vendor)
    }
  } catch (error) {
    console.error('Calculate completion error:', error)
  }
}

function getReadonlyInfo(fieldName) {
  const messages = {
    'nomor_vendor': 'Nomor vendor dibuat otomatis saat registrasi',
    'tipe_perusahaan': 'Tipe perusahaan dipilih saat registrasi',
    'nama_perusahaan': 'Nama perusahaan diisi saat registrasi',
    'sumber_informasi': 'Sumber informasi dipilih saat registrasi',
    'rekomendasi_dari': 'Data dari sumber rekomendasi saat registrasi',
    'lainnya': 'Data tambahan dari registrasi'
  }
  return messages[fieldName] || 'Data ini tidak dapat diubah'
}
</script>

<style scoped>
.vendor-profile-form {
  max-width: 1200px;
  margin: 0 auto;
}

.form-section {
  margin-bottom: 40px;
  background: #f8f9fa;
  border-radius: 12px;
  overflow: hidden;
}

.section-header {
  background: #1e3c72;
  color: white;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 15px;
}

.section-header i {
  font-size: 1.3em;
}

.section-header h3 {
  margin: 0;
  font-size: 1.2em;
}

.section-fields {
  padding: 25px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 600;
  color: #1e3c72;
  margin-bottom: 8px;
}

.required {
  color: #dc3545;
}

.form-group input,
.form-group select,
.form-group textarea {
  padding: 12px;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #1e3c72;
  outline: none;
  box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
}

.form-group input:read-only,
.form-group select:read-only {
  background: #f8f9fa;
  color: #666;
}

.json-array-group {
  grid-column: 1 / -1;
}

.json-array-container {
  border: 2px solid #e9ecef;
  border-radius: 8px;
  padding: 20px;
  background: white;
}

.json-array-item {
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 15px;
  background: #f8f9fa;
}

.array-item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.array-item-header h4 {
  margin: 0;
  color: #1e3c72;
}

.remove-btn {
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  cursor: pointer;
  transition: background 0.3s;
}

.remove-btn:hover {
  background: #c82333;
}

.array-item-fields {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.sub-field {
  display: flex;
  flex-direction: column;
}

.sub-field label {
  font-size: 13px;
  font-weight: 600;
  color: #666;
  margin-bottom: 5px;
}

.sub-field input {
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 13px;
}

.add-btn {
  background: #28a745;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.3s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.add-btn:hover {
  background: #218838;
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin-top: 40px;
  padding-top: 30px;
  border-top: 1px solid #e9ecef;
}

.save-btn {
  background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
  color: white;
  border: none;
  padding: 15px 30px;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 10px;
}

.save-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(30, 60, 114, 0.3);
}

.save-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.calculate-btn {
  background: #28a745;
  color: white;
  border: none;
  padding: 15px 30px;
  border-radius: 12px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 10px;
}

.calculate-btn:hover {
  background: #218838;
  transform: translateY(-2px);
}

.alert {
  padding: 15px;
  margin-bottom: 25px;
  border-radius: 8px;
  font-weight: 500;
}

.alert-success {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
}

.alert-error {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

.alert-info {
  background-color: #cce7ff;
  border: 1px solid #b8daff;
  color: #004085;
}

@media (max-width: 768px) {
  .section-fields {
    grid-template-columns: 1fr;
    padding: 20px;
  }
  
  .array-item-fields {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
    align-items: stretch;
  }
}

.readonly-field {
  opacity: 0.8;
}

.readonly-badge {
  background: #6c757d;
  color: white;
  font-size: 0.7em;
  padding: 2px 6px;
  border-radius: 4px;
  margin-left: 8px;
  font-weight: normal;
}

.readonly-input {
  background: #f8f9fa !important;
  color: #6c757d !important;
  border-color: #dee2e6 !important;
  cursor: not-allowed;
}

.readonly-input:focus {
  box-shadow: none !important;
  border-color: #dee2e6 !important;
}

.readonly-info {
  display: flex;
  align-items: center;
  gap: 5px;
  color: #6c757d;
  font-style: italic;
  margin-top: 5px;
  font-size: 0.85em;
}

.readonly-info i {
  color: #17a2b8;
}
</style>