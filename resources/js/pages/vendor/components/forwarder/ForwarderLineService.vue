<template>
  <div class="forwarder-line-service">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-shipping-fast text-primary me-2"></i>
          Line Service Management
          <span class="subtitle">Manage shipping lines and airlines</span>
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="saveData">
          <!-- Shipping Lines Section -->
          <div class="section-wrapper">
            <div class="section-header">
              <i class="fas fa-ship"></i>
              <h6 class="section-title">Shipping Lines</h6>
            </div>

            <div class="form-grid">
              <div class="form-group">
                <label for="shipping_line_relation" class="form-label">
                  <i class="fas fa-handshake me-1"></i>
                  Shipping Line Relation
                </label>
                <select 
                  class="form-select" 
                  id="shipping_line_relation"
                  v-model="formData.shipping_line_relation"
                >
                  <option value="">Pilih hubungan dengan shipping line...</option>
                  <option 
                    v-for="(label, value) in options.shipping_line_relation" 
                    :key="value"
                    :value="value"
                  >
                    {{ label }}
                  </option>
                </select>
              </div>
            </div>

            <div class="subsection">
              <div class="subsection-header">
                <h6 class="subsection-title">
                  <i class="fas fa-anchor me-2"></i>
                  Daftar Shipping Lines
                </h6>
                <button 
                  class="btn btn-add" 
                  @click="addShippingLine" 
                  type="button"
                >
                  <i class="fas fa-plus me-1"></i>
                  Tambah Line
                </button>
              </div>

              <div v-if="formData.shipping_lines.length === 0" class="empty-state">
                <i class="fas fa-ship"></i>
                <p>Belum ada shipping line yang ditambahkan</p>
                <small>Klik "Tambah Line" untuk menambahkan shipping line baru</small>
              </div>

              <div class="items-grid">
                <div 
                  v-for="(line, idx) in formData.shipping_lines" 
                  :key="'shipping_' + idx" 
                  class="item-card"
                >
                  <div class="item-header">
                    <div class="item-title">
                      <i class="fas fa-ship me-2"></i>
                      <span>Shipping Line {{ idx + 1 }}</span>
                    </div>
                    <button 
                      class="btn btn-delete" 
                      @click="removeShippingLine(idx)" 
                      type="button"
                      title="Hapus Shipping Line"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>

                  <div class="item-body">
                    <div class="form-group">
                      <label :for="'shipping_name_' + idx" class="form-label">
                        <i class="fas fa-tag me-1"></i>
                        Nama Shipping Line
                      </label>
                      <input 
                        :id="'shipping_name_' + idx"
                        v-model="line.shipping_line_name" 
                        class="form-control" 
                        placeholder="Contoh: Maersk Line, COSCO Shipping"
                        type="text"
                      >
                    </div>

                    <div class="checkbox-section">
                      <h6 class="checkbox-section-title">Jenis Layanan</h6>
                      <div class="checkbox-grid-inline">
                        <div class="checkbox-card-inline">
                          <input 
                            class="form-check-input" 
                            type="checkbox" 
                            :id="'domestic_' + idx"
                            v-model="line.domestic"
                          >
                          <label class="checkbox-label-inline" :for="'domestic_' + idx">
                            <i class="fas fa-flag"></i>
                            <span>Domestik</span>
                          </label>
                        </div>

                        <div class="checkbox-card-inline">
                          <input 
                            class="form-check-input" 
                            type="checkbox" 
                            :id="'foreign_' + idx"
                            v-model="line.foreign"
                          >
                          <label class="checkbox-label-inline" :for="'foreign_' + idx">
                            <i class="fas fa-globe"></i>
                            <span>Luar Negeri</span>
                          </label>
                        </div>

                        <div class="checkbox-card-inline">
                          <input 
                            class="form-check-input" 
                            type="checkbox" 
                            :id="'containerized_' + idx"
                            v-model="line.containerized"
                          >
                          <label class="checkbox-label-inline" :for="'containerized_' + idx">
                            <i class="fas fa-cube"></i>
                            <span>Kontainer</span>
                          </label>
                        </div>

                        <div class="checkbox-card-inline">
                          <input 
                            class="form-check-input" 
                            type="checkbox" 
                            :id="'break_bulk_' + idx"
                            v-model="line.break_bulk"
                          >
                          <label class="checkbox-label-inline" :for="'break_bulk_' + idx">
                            <i class="fas fa-boxes"></i>
                            <span>Break Bulk</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Airlines Section -->
          <div class="section-wrapper">
            <div class="section-header">
              <i class="fas fa-plane"></i>
              <h6 class="section-title">Airlines</h6>
            </div>

            <div class="form-grid">
              <div class="form-group">
                <label for="airlines_relation" class="form-label">
                  <i class="fas fa-handshake me-1"></i>
                  Airlines Relation
                </label>
                <select 
                  class="form-select" 
                  id="airlines_relation"
                  v-model="formData.airlines_relation"
                >
                  <option value="">Pilih hubungan dengan airlines...</option>
                  <option 
                    v-for="(label, value) in options.airlines_relation" 
                    :key="value"
                    :value="value"
                  >
                    {{ label }}
                  </option>
                </select>
              </div>
            </div>

            <div class="subsection">
              <div class="subsection-header">
                <h6 class="subsection-title">
                  <i class="fas fa-plane me-2"></i>
                  Daftar Airlines
                </h6>
                <button 
                  class="btn btn-add" 
                  @click="addAirline" 
                  type="button"
                >
                  <i class="fas fa-plus me-1"></i>
                  Tambah Airline
                </button>
              </div>

              <div v-if="formData.airlines.length === 0" class="empty-state">
                <i class="fas fa-plane"></i>
                <p>Belum ada airline yang ditambahkan</p>
                <small>Klik "Tambah Airline" untuk menambahkan airline baru</small>
              </div>

              <div class="items-grid">
                <div 
                  v-for="(airline, idx) in formData.airlines" 
                  :key="'airline_' + idx" 
                  class="item-card"
                >
                  <div class="item-header">
                    <div class="item-title">
                      <i class="fas fa-plane me-2"></i>
                      <span>Airline {{ idx + 1 }}</span>
                    </div>
                    <button 
                      class="btn btn-delete" 
                      @click="removeAirline(idx)" 
                      type="button"
                      title="Hapus Airline"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>

                  <div class="item-body">
                    <div class="form-group">
                      <label :for="'airline_name_' + idx" class="form-label">
                        <i class="fas fa-tag me-1"></i>
                        Nama Airline
                      </label>
                      <input 
                        :id="'airline_name_' + idx"
                        v-model="airline.airline_name" 
                        class="form-control" 
                        placeholder="Contoh: Garuda Indonesia, Singapore Airlines"
                        type="text"
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
  name: 'ForwarderLineService',
  props: { 
    forwarder: Object, 
    options: Object 
  },
  emits: ['save'],
  data() {
    return {
      saving: false,
      formData: {
        shipping_line_relation: '',
        shipping_lines: [],
        airlines_relation: '',
        airlines: []
      }
    }
  },
  watch: {
    forwarder: { 
      handler(newVal) { 
        if (newVal) this.loadData() 
      }, 
      immediate: true 
    }
  },
  methods: {
    loadData() {
      if (this.forwarder) {
        Object.keys(this.formData).forEach(key => {
          if (this.forwarder[key] !== undefined) {
            this.formData[key] = this.forwarder[key]
          }
        })
      }
    },
    
    addShippingLine() {
      this.formData.shipping_lines.push({
        shipping_line_name: '', 
        domestic: false, 
        foreign: false, 
        containerized: false, 
        break_bulk: false
      })
    },
    
    removeShippingLine(idx) { 
      this.formData.shipping_lines.splice(idx, 1) 
    },
    
    addAirline() {
      this.formData.airlines.push({ 
        airline_name: '' 
      })
    },
    
    removeAirline(idx) { 
      this.formData.airlines.splice(idx, 1) 
    },
    
    async saveData() {
      try {
        this.saving = true
        this.$emit('save', this.formData)
      } catch (error) {
        console.error('Error saving line service data:', error)
      } finally {
        this.saving = false
      }
    }
  }
}
</script>

<style scoped>
.forwarder-line-service {
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
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid rgba(0, 123, 255, 0.1);
}

.section-header i {
  font-size: 1.2rem;
  color: #007bff;
  margin-right: 0.75rem;
  width: 20px;
  text-align: center;
}

.section-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.form-group {
  position: relative;
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

.form-select,
.form-control {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
}

.form-select:focus,
.form-control:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.subsection {
  margin-top: 2rem;
}

.subsection-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid rgba(0, 123, 255, 0.1);
}

.subsection-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #495057;
  margin: 0;
  display: flex;
  align-items: center;
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

.items-grid {
  display: grid;
  gap: 1.5rem;
}

.item-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.item-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.item-card:hover::before {
  left: 100%;
}

.item-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.item-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.item-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.item-title i {
  color: #007bff;
}

.item-body {
  padding: 1.5rem;
}

.checkbox-section {
  margin-top: 1.5rem;
}

.checkbox-section-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1rem;
}

.checkbox-grid-inline {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 0.75rem;
}

.checkbox-card-inline {
  background: #f8f9fa;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  padding: 0.75rem;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
}

.checkbox-card-inline:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.checkbox-card-inline:has(.form-check-input:checked) {
  border-color: #007bff;
  background: linear-gradient(145deg, #e3f2fd 0%, #ffffff 100%);
}

.checkbox-card-inline .form-check-input {
  position: absolute;
  opacity: 0;
}

.checkbox-label-inline {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
  font-weight: 500;
  color: #495057;
  cursor: pointer;
  text-align: center;
}

.checkbox-label-inline i {
  font-size: 1.2rem;
  color: #6c757d;
  transition: color 0.3s ease;
}

.checkbox-card-inline:has(.form-check-input:checked) .checkbox-label-inline i {
  color: #007bff;
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
  .forwarder-line-service {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .checkbox-grid-inline {
    grid-template-columns: repeat(2, 1fr);
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
  
  .subsection-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .checkbox-grid-inline {
    grid-template-columns: 1fr;
  }
}
</style>