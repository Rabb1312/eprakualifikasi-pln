<template>
  <div class="forwarder-insurance">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-shield-alt text-primary me-2"></i>
          Insurance Management
          <span class="subtitle">Configure insurance coverage and claims</span>
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="saveData">
          <!-- Insurance Coverage Section -->
          <div class="section-wrapper">
            <div class="section-header">
              <i class="fas fa-umbrella"></i>
              <h6 class="section-title">Insurance Coverage Options</h6>
            </div>

            <div class="insurance-grid">
              <div class="insurance-options">
                <div class="checkbox-grid">
                  <div class="checkbox-card" :class="{ 'disabled': formData.insurance_none }">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="insurance_none"
                      v-model="formData.insurance_none"
                    />
                    <label class="checkbox-label" for="insurance_none">
                      <i class="fas fa-ban"></i>
                      <span>Tidak Ada Asuransi</span>
                    </label>
                  </div>

                  <div class="checkbox-card" :class="{ 'disabled': formData.insurance_none }">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="insurance_all_risk_covered_by_company"
                      v-model="formData.insurance_all_risk_covered_by_company"
                      :disabled="formData.insurance_none"
                    />
                    <label class="checkbox-label" for="insurance_all_risk_covered_by_company">
                      <i class="fas fa-shield-check"></i>
                      <span>All Risk Ditanggung Perusahaan</span>
                    </label>
                  </div>

                  <div class="checkbox-card" :class="{ 'disabled': formData.insurance_none }">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="insurance_liability_with_insurance_company"
                      v-model="formData.insurance_liability_with_insurance_company"
                      :disabled="formData.insurance_none"
                    />
                    <label class="checkbox-label" for="insurance_liability_with_insurance_company">
                      <i class="fas fa-handshake"></i>
                      <span>Kerjasama dengan Perusahaan Asuransi</span>
                    </label>
                  </div>

                  <div class="checkbox-card" :class="{ 'disabled': formData.insurance_none }">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="insurance_negotiable_with_without_insurance"
                      v-model="formData.insurance_negotiable_with_without_insurance"
                      :disabled="formData.insurance_none"
                    />
                    <label class="checkbox-label" for="insurance_negotiable_with_without_insurance">
                      <i class="fas fa-comments-dollar"></i>
                      <span>Dapat Dinegosiasi (Dengan/Tanpa)</span>
                    </label>
                  </div>

                  <div class="checkbox-card" :class="{ 'disabled': formData.insurance_none }">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      id="insurance_others"
                      v-model="formData.insurance_others"
                      :disabled="formData.insurance_none"
                    />
                    <label class="checkbox-label" for="insurance_others">
                      <i class="fas fa-plus-circle"></i>
                      <span>Lainnya</span>
                    </label>
                  </div>
                </div>

                <!-- Others Description -->
                <div 
                  v-if="formData.insurance_others && !formData.insurance_none" 
                  class="others-description animate-fade-in"
                >
                  <div class="form-group">
                    <label for="insurance_others_description" class="form-label">
                      <i class="fas fa-edit me-1"></i>
                      Deskripsi Asuransi Lainnya
                    </label>
                    <textarea
                      class="form-control"
                      id="insurance_others_description"
                      v-model="formData.insurance_others_description"
                      rows="3"
                      placeholder="Jelaskan jenis asuransi lainnya yang tersedia..."
                    ></textarea>
                  </div>
                </div>
              </div>

              <div class="claim-section">
                <div class="form-group">
                  <label for="insurance_claim_arrangement_description" class="form-label">
                    <i class="fas fa-file-invoice-dollar me-1"></i>
                    Deskripsi Pengaturan Klaim
                  </label>
                  <textarea
                    class="form-control"
                    id="insurance_claim_arrangement_description"
                    v-model="formData.insurance_claim_arrangement_description"
                    rows="6"
                    placeholder="Jelaskan prosedur dan pengaturan klaim asuransi..."
                  ></textarea>
                  <small class="form-text">
                    Jelaskan bagaimana proses klaim dilakukan, syarat-syarat yang diperlukan, dan waktu penyelesaian.
                  </small>
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
  name: 'ForwarderInsurance',
  props: { 
    forwarder: Object 
  },
  emits: ['save'],
  data() {
    return {
      saving: false,
      formData: {
        insurance_none: false,
        insurance_all_risk_covered_by_company: false,
        insurance_liability_with_insurance_company: false,
        insurance_negotiable_with_without_insurance: false,
        insurance_others: false,
        insurance_others_description: '',
        insurance_claim_arrangement_description: ''
      }
    }
  },
  watch: {
    forwarder: { 
      handler(newVal) { 
        if (newVal) this.loadData() 
      }, 
      immediate: true 
    },
    'formData.insurance_none'(val) {
      if (val) {
        this.formData.insurance_all_risk_covered_by_company = false
        this.formData.insurance_liability_with_insurance_company = false
        this.formData.insurance_negotiable_with_without_insurance = false
        this.formData.insurance_others = false
        this.formData.insurance_others_description = ''
      }
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
    
    async saveData() {
      try {
        this.saving = true
        this.$emit('save', this.formData)
      } catch (error) {
        console.error('Error saving insurance data:', error)
      } finally {
        this.saving = false
      }
    }
  }
}
</script>

<style scoped>
.forwarder-insurance {
  max-width: 1000px;
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

.insurance-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  align-items: start;
}

.insurance-options {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.checkbox-grid {
  display: grid;
  gap: 1rem;
}

.checkbox-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  padding: 1rem;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.checkbox-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.checkbox-card:hover::before {
  left: 100%;
}

.checkbox-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.checkbox-card.disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.checkbox-card .form-check-input {
  position: absolute;
  opacity: 0;
}

.checkbox-label {
  display: flex;
  align-items: center;
  font-size: 0.95rem;
  font-weight: 500;
  color: #495057;
  cursor: pointer;
  width: 100%;
  gap: 0.75rem;
}

.checkbox-label i {
  font-size: 1.1rem;
  color: #6c757d;
  transition: color 0.3s ease;
  width: 20px;
  text-align: center;
}

.checkbox-card:has(.form-check-input:checked) {
  border-color: #007bff;
  background: linear-gradient(145deg, #e3f2fd 0%, #ffffff 100%);
}

.checkbox-card:has(.form-check-input:checked) .checkbox-label i {
  color: #007bff;
}

.others-description {
  margin-top: 1rem;
  padding: 1.5rem;
  background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 12px;
  border: 1px solid #e9ecef;
}

.claim-section {
  background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1.5rem;
  border: 1px solid #e9ecef;
  height: fit-content;
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

.form-control,
textarea {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
  resize: vertical;
}

.form-control:focus,
textarea:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.form-text {
  color: #6c757d;
  font-size: 0.875rem;
  margin-top: 0.5rem;
  display: block;
  font-style: italic;
}

.btn {
  padding: 0.875rem 2rem;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.95rem;
  min-width: 160px;
  justify-content: center;
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

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
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

.animate-fade-in {
  animation: fadeIn 0.5s ease-in;
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

@keyframes spinner-border {
  100% {
    transform: rotate(360deg);
  }
}

@media (max-width: 1024px) {
  .forwarder-insurance {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .insurance-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
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
  
  .insurance-grid {
    grid-template-columns: 1fr;
  }
}
</style>