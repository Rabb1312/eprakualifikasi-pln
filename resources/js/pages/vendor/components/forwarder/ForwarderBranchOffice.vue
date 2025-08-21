<template>
  <div class="forwarder-branch-office">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-map-marker-alt text-primary me-2"></i>
          Branch Office Management
          <span class="subtitle">Manage your office locations</span>
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="saveData">
          <!-- Indonesian Branch Offices -->
          <div class="section-wrapper">
            <div class="section-header">
              <i class="fas fa-flag"></i>
              <h6 class="section-title">Indonesian Branch Offices</h6>
              <div class="section-actions">
                <button 
                  type="button" 
                  class="btn btn-add"
                  @click="addIndonesianBranch"
                >
                  <i class="fas fa-plus me-1"></i>
                  Add Branch
                </button>
              </div>
            </div>
            
            <div v-if="formData.indonesian_branch_offices.length === 0" class="empty-state">
              <i class="fas fa-building"></i>
              <p>Belum ada kantor cabang Indonesia yang ditambahkan</p>
              <small>Klik "Add Branch" untuk menambahkan kantor cabang baru</small>
            </div>
            
            <div class="branch-grid">
              <div 
                v-for="(branch, index) in formData.indonesian_branch_offices" 
                :key="'indonesian_' + index"
                class="branch-card"
              >
                <div class="branch-header">
                  <div class="branch-title">
                    <i class="fas fa-building me-2"></i>
                    <span>Cabang {{ index + 1 }}</span>
                  </div>
                  <button 
                    type="button" 
                    class="btn btn-delete"
                    @click="removeIndonesianBranch(index)"
                    title="Hapus Cabang"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
                
                <div class="branch-body">
                  <div class="form-grid">
                    <div class="form-group full-width">
                      <label :for="'indo_city_' + index" class="form-label">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Kota & Alamat
                        <span class="text-danger">*</span>
                      </label>
                      <textarea 
                        :id="'indo_city_' + index"
                        class="form-control" 
                        v-model="branch.city_address"
                        rows="3"
                        placeholder="Contoh: Jakarta - Jl. Sudirman No. 123, Menteng, Jakarta Pusat 10310"
                        required
                      ></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label :for="'indo_phone_' + index" class="form-label">
                        <i class="fas fa-phone me-1"></i>
                        Telepon
                      </label>
                      <input 
                        type="text" 
                        :id="'indo_phone_' + index"
                        class="form-control" 
                        v-model="branch.phone"
                        placeholder="Contoh: (021) 1234-5678"
                      >
                    </div>
                    
                    <div class="form-group">
                      <label :for="'indo_fax_' + index" class="form-label">
                        <i class="fas fa-fax me-1"></i>
                        Fax
                      </label>
                      <input 
                        type="text" 
                        :id="'indo_fax_' + index"
                        class="form-control" 
                        v-model="branch.fax"
                        placeholder="Contoh: (021) 1234-5679"
                      >
                    </div>
                    
                    <div class="form-group full-width">
                      <label :for="'indo_contact_' + index" class="form-label">
                        <i class="fas fa-user me-1"></i>
                        Kontak Person
                      </label>
                      <input 
                        type="text" 
                        :id="'indo_contact_' + index"
                        class="form-control" 
                        v-model="branch.contact"
                        placeholder="Contoh: John Doe - john@company.com - 0812-3456-7890"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Overseas Branch Offices -->
          <div class="section-wrapper">
            <div class="section-header">
              <i class="fas fa-globe"></i>
              <h6 class="section-title">Overseas Branch Offices</h6>
              <div class="section-actions">
                <button 
                  type="button" 
                  class="btn btn-add"
                  @click="addOverseasBranch"
                >
                  <i class="fas fa-plus me-1"></i>
                  Add Branch
                </button>
              </div>
            </div>
            
            <div v-if="formData.overseas_branch_offices.length === 0" class="empty-state">
              <i class="fas fa-globe-americas"></i>
              <p>Belum ada kantor cabang luar negeri yang ditambahkan</p>
              <small>Klik "Add Branch" untuk menambahkan kantor cabang baru</small>
            </div>
            
            <div class="branch-grid">
              <div 
                v-for="(branch, index) in formData.overseas_branch_offices" 
                :key="'overseas_' + index"
                class="branch-card"
              >
                <div class="branch-header">
                  <div class="branch-title">
                    <i class="fas fa-globe me-2"></i>
                    <span>Overseas {{ index + 1 }}</span>
                  </div>
                  <button 
                    type="button" 
                    class="btn btn-delete"
                    @click="removeOverseasBranch(index)"
                    title="Hapus Cabang"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
                
                <div class="branch-body">
                  <div class="form-grid">
                    <div class="form-group full-width">
                      <label :for="'overseas_city_' + index" class="form-label">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        Negara, Kota & Alamat
                        <span class="text-danger">*</span>
                      </label>
                      <textarea 
                        :id="'overseas_city_' + index"
                        class="form-control" 
                        v-model="branch.city_address"
                        rows="3"
                        placeholder="Contoh: Singapore - 123 Robinson Road, #12-01, Singapore 068902"
                        required
                      ></textarea>
                    </div>
                    
                    <div class="form-group">
                      <label :for="'overseas_phone_' + index" class="form-label">
                        <i class="fas fa-phone me-1"></i>
                        Telepon
                      </label>
                      <input 
                        type="text" 
                        :id="'overseas_phone_' + index"
                        class="form-control" 
                        v-model="branch.phone"
                        placeholder="Contoh: +65 6123-4567"
                      >
                    </div>
                    
                    <div class="form-group">
                      <label :for="'overseas_fax_' + index" class="form-label">
                        <i class="fas fa-fax me-1"></i>
                        Fax
                      </label>
                      <input 
                        type="text" 
                        :id="'overseas_fax_' + index"
                        class="form-control" 
                        v-model="branch.fax"
                        placeholder="Contoh: +65 6123-4568"
                      >
                    </div>
                    
                    <div class="form-group full-width">
                      <label :for="'overseas_contact_' + index" class="form-label">
                        <i class="fas fa-user me-1"></i>
                        Kontak Person
                      </label>
                      <input 
                        type="text" 
                        :id="'overseas_contact_' + index"
                        class="form-control" 
                        v-model="branch.contact"
                        placeholder="Contoh: Jane Smith - jane@company.com - +65 9123-4567"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="submit-section">
            <button type="submit" class="btn btn-primary" :disabled="saving">
              <i class="fas fa-save"></i>
              <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
              {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ForwarderBranchOffice',
  props: {
    forwarder: Object
  },
  emits: ['save'],
  data() {
    return {
      saving: false,
      formData: {
        indonesian_branch_offices: [],
        overseas_branch_offices: []
      }
    }
  },
  watch: {
    forwarder: {
      handler(newVal) {
        if (newVal) {
          this.loadData()
        }
      },
      immediate: true
    }
  },
  methods: {
    loadData() {
      if (this.forwarder) {
        this.formData.indonesian_branch_offices = this.forwarder.indonesian_branch_offices || []
        this.formData.overseas_branch_offices = this.forwarder.overseas_branch_offices || []
      }
    },
    
    addIndonesianBranch() {
      this.formData.indonesian_branch_offices.push({
        city_address: '',
        phone: '',
        fax: '',
        contact: ''
      })
    },
    
    removeIndonesianBranch(index) {
      this.formData.indonesian_branch_offices.splice(index, 1)
    },
    
    addOverseasBranch() {
      this.formData.overseas_branch_offices.push({
        city_address: '',
        phone: '',
        fax: '',
        contact: ''
      })
    },
    
    removeOverseasBranch(index) {
      this.formData.overseas_branch_offices.splice(index, 1)
    },
    
    async saveData() {
      try {
        this.saving = true
        this.$emit('save', this.formData)
      } catch (error) {
        console.error('Error saving branch office data:', error)
      } finally {
        this.saving = false
      }
    }
  }
}
</script>

<style scoped>
.forwarder-branch-office {
  max-width: 1200px;
  margin: 20px auto;
  padding: 0 20px;
}

.card {
  border-radius: 20px;
  box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
  border: none;
  background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
  overflow: hidden;
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 12px 50px rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
}

.card-header {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  border-bottom: none;
  padding: 2rem 2.5rem 1.5rem 2.5rem;
  color: white;
  position: relative;
}

.card-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.3;
}

.card-header h5 {
  font-size: 1.8rem;
  font-weight: 700;
  /* margin-bottom: 0.5rem; */
  position: relative;
  z-index: 1;
}

.subtitle {
  display: block;
  font-size: 0.9rem;
  font-weight: 400;
  opacity: 0.9;
  margin-top: 0.5rem;
}

.card-body {
  padding: 2.5rem;
  background: white;
}

.section-wrapper {
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 16px;
  padding: 2rem;
  margin-bottom: 2rem;
  border: 1px solid rgba(0, 123, 255, 0.1);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.section-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
  transition: width 0.3s ease;
}

.section-wrapper:hover::before {
  width: 6px;
}

.section-wrapper:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid rgba(0, 123, 255, 0.1);
}

.section-header .section-title {
  display: flex;
  align-items: center;
  font-size: 1.2rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
}

.section-header i {
  font-size: 1.2rem;
  color: #007bff;
  margin-right: 0.75rem;
  width: 20px;
  text-align: center;
}

.empty-state {
  text-align: center;
  padding: 3rem 2rem;
  color: #6c757d;
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 12px;
  border: 2px dashed #dee2e6;
}

.empty-state i {
  font-size: 3rem;
  color: #dee2e6;
  margin-bottom: 1rem;
}

.empty-state p {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.empty-state small {
  font-size: 0.9rem;
  color: #8e9ba8;
}

.branch-grid {
  display: grid;
  gap: 1.5rem;
}

.branch-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.branch-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.branch-card:hover::before {
  left: 100%;
}

.branch-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.branch-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.branch-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.branch-title i {
  color: #007bff;
}

.branch-body {
  padding: 1.5rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.form-label i {
  color: #007bff;
  font-size: 0.9rem;
}

.form-control,
textarea {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
}

.form-control:focus,
textarea:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  position: relative;
  overflow: hidden;
}

.btn::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: all 0.3s ease;
}

.btn:hover::before {
  width: 300px;
  height: 300px;
}

.btn-add {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-delete {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
  padding: 0.5rem;
  min-width: auto;
}

.btn-delete:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
  min-width: 180px;
  justify-content: center;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none !important;
}

.submit-section {
  text-align: center;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 2px solid rgba(0, 123, 255, 0.1);
}

.spinner-border {
  width: 1.2em;
  height: 1.2em;
  border-width: 0.15em;
}

@keyframes spinner-border {
  100% {
    transform: rotate(360deg);
  }
}

@media (max-width: 1024px) {
  .forwarder-branch-office {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .card-header,
  .card-body {
    padding: 1.5rem !important;
  }
  
  .section-wrapper {
    padding: 1.5rem;
  }
  
  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .branch-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>