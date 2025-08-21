<template>
  <div class="distributor-aftersales">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-headset text-primary me-2"></i>
          After Sales Support Management
          <span class="subtitle">Configure customer support and complaint handling</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Support Configuration Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-cogs"></i>
            <h6 class="section-title">Support Configuration</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Konfigurasikan sistem dukungan purna jual dan penanganan keluhan pelanggan untuk memastikan kepuasan pelanggan yang optimal.
            </p>
          </div>

          <div class="support-questions">
            <!-- Priority Treatment Question -->
            <div class="question-card">
              <div class="question-header">
                <i class="fas fa-priority-high"></i>
                <h6>Priority Treatment Policy</h6>
              </div>
              <div class="question-body">
                <label for="priority_treatment" class="question-label">
                  <span class="question-main">
                    Apakah Anda memberikan perlakuan prioritas untuk permintaan kelebihan, kekurangan, dan kerusakan barang?
                  </span>
                  <small class="question-sub">
                    Do you give priority treatment to over, short and damaged requests?
                  </small>
                </label>
                <div class="select-wrapper">
                  <select 
                    id="priority_treatment"
                    v-model="form.priority_treatment" 
                    class="form-select"
                  >
                    <option value="">Pilih kebijakan perlakuan prioritas...</option>
                    <option v-for="(text, val) in options.yes_no" :value="val" :key="val">
                      {{ text }}
                    </option>
                  </select>
                  <i class="fas fa-chevron-down select-icon"></i>
                </div>
              </div>
            </div>

            <!-- Complaint Agreement Question -->
            <div class="question-card">
              <div class="question-header">
                <i class="fas fa-handshake"></i>
                <h6>Principal Agreement</h6>
              </div>
              <div class="question-body">
                <label for="complaint_agreement" class="question-label">
                  <span class="question-main">
                    Apakah Anda memiliki perjanjian dengan principal terkait produk untuk penanganan keluhan pelanggan dalam pengembalian barang, kekurangan, dan kerusakan?
                  </span>
                  <small class="question-sub">
                    Do you have any agreement to your principal related your product for any complaint for customer for returning goods, short and damage?
                  </small>
                </label>
                <div class="select-wrapper">
                  <select 
                    id="complaint_agreement"
                    v-model="form.complaint_agreement" 
                    class="form-select"
                  >
                    <option value="">Pilih status perjanjian dengan principal...</option>
                    <option v-for="(text, val) in options.yes_no" :value="val" :key="val">
                      {{ text }}
                    </option>
                  </select>
                  <i class="fas fa-chevron-down select-icon"></i>
                </div>
              </div>
            </div>

            <!-- Contact Centre Question -->
            <div class="question-card">
              <div class="question-header">
                <i class="fas fa-phone-alt"></i>
                <h6>Contact Centre</h6>
              </div>
              <div class="question-body">
                <label for="has_contact_centre" class="question-label">
                  <span class="question-main">
                    Apakah Anda memiliki nomor pusat kontak/call center?
                  </span>
                  <small class="question-sub">
                    Do you have any contact centre number?
                  </small>
                </label>
                <div class="select-wrapper">
                  <select 
                    id="has_contact_centre"
                    v-model="form.has_contact_centre" 
                    class="form-select"
                  >
                    <option value="">Pilih ketersediaan contact centre...</option>
                    <option v-for="(text, val) in options.yes_no" :value="val" :key="val">
                      {{ text }}
                    </option>
                  </select>
                  <i class="fas fa-chevron-down select-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Configuration Summary -->
        <div v-if="hasAnySelection()" class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <h6 class="section-title">Configuration Summary</h6>
          </div>

          <div class="summary-card">
            <div class="summary-header">
              <i class="fas fa-clipboard-check me-2"></i>
              <span>After Sales Support Configuration</span>
            </div>
            <div class="summary-body">
              <div class="summary-grid">
                <div v-if="form.priority_treatment" class="summary-item">
                  <div class="summary-icon">
                    <i class="fas fa-priority-high" :class="form.priority_treatment === 'yes' ? 'text-success' : 'text-warning'"></i>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Priority Treatment</span>
                    <span class="summary-value" :class="form.priority_treatment === 'yes' ? 'text-success' : 'text-warning'">
                      {{ options.yes_no[form.priority_treatment] }}
                    </span>
                  </div>
                </div>

                <div v-if="form.complaint_agreement" class="summary-item">
                  <div class="summary-icon">
                    <i class="fas fa-handshake" :class="form.complaint_agreement === 'yes' ? 'text-success' : 'text-warning'"></i>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Principal Agreement</span>
                    <span class="summary-value" :class="form.complaint_agreement === 'yes' ? 'text-success' : 'text-warning'">
                      {{ options.yes_no[form.complaint_agreement] }}
                    </span>
                  </div>
                </div>

                <div v-if="form.has_contact_centre" class="summary-item">
                  <div class="summary-icon">
                    <i class="fas fa-phone-alt" :class="form.has_contact_centre === 'yes' ? 'text-success' : 'text-warning'"></i>
                  </div>
                  <div class="summary-content">
                    <span class="summary-label">Contact Centre</span>
                    <span class="summary-value" :class="form.has_contact_centre === 'yes' ? 'text-success' : 'text-warning'">
                      {{ options.yes_no[form.has_contact_centre] }}
                    </span>
                  </div>
                </div>
              </div>

              <div class="completion-indicator">
                <div class="completion-bar">
                  <div class="completion-fill" :style="{ width: getCompletionPercentage() + '%' }"></div>
                </div>
                <span class="completion-text">
                  {{ getCompletionPercentage() }}% konfigurasi selesai
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="save" 
            :disabled="!hasAnySelection()"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            Simpan Konfigurasi After Sales
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['distributor', 'options'],
  data() {
    return {
      form: {
        priority_treatment: this.distributor?.priority_treatment || '',
        complaint_agreement: this.distributor?.complaint_agreement || '',
        has_contact_centre: this.distributor?.has_contact_centre || ''
      }
    }
  },
  methods: {
    save() {
      this.$emit('save', this.form)
    },
    hasAnySelection() {
      return this.form.priority_treatment || this.form.complaint_agreement || this.form.has_contact_centre;
    },
    getCompletionPercentage() {
      const totalFields = 3;
      const filledFields = [
        this.form.priority_treatment,
        this.form.complaint_agreement,
        this.form.has_contact_centre
      ].filter(field => field).length;
      
      return Math.round((filledFields / totalFields) * 100);
    }
  }
}
</script>

<style scoped>
.distributor-aftersales {
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
  margin-bottom: 0.5rem;
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

.section-description {
  margin-bottom: 2rem;
  padding: 1rem;
  background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
  border-radius: 8px;
  border-left: 4px solid #007bff;
}

.section-description p {
  margin: 0;
  color: #495057;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.section-description i {
  color: #007bff;
}

.support-questions {
  display: grid;
  gap: 1.5rem;
}

.question-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.question-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.question-card:hover::before {
  left: 100%;
}

.question-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.question-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.question-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.question-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.question-body {
  padding: 1.5rem;
}

.question-label {
  display: block;
  margin-bottom: 1rem;
}

.question-main {
  display: block;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
  line-height: 1.4;
  margin-bottom: 0.5rem;
}

.question-sub {
  color: #6c757d;
  font-style: italic;
  font-size: 0.85rem;
  line-height: 1.3;
}

.select-wrapper {
  position: relative;
}

.form-select {
  width: 100%;
  padding: 0.875rem 2.5rem 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.95rem;
  background: #f8f9fa;
  transition: all 0.3s ease;
  appearance: none;
  cursor: pointer;
}

.form-select:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.select-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6c757d;
  font-size: 0.8rem;
  pointer-events: none;
  transition: transform 0.3s ease;
}

.form-select:focus + .select-icon {
  transform: translateY(-50%) rotate(180deg);
}

.summary-card {
  background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1.5rem;
  border-left: 4px solid #28a745;
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.summary-header {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}

.summary-header i {
  color: #28a745;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.summary-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.summary-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.summary-content {
  flex: 1;
}

.summary-label {
  display: block;
  font-size: 0.85rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
}

.summary-value {
  font-weight: 600;
  font-size: 0.95rem;
  color: #495057;
}

.completion-indicator {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
}

.completion-bar {
  width: 100%;
  height: 8px;
  background: #e9ecef;
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 0.5rem;
}

.completion-fill {
  height: 100%;
  background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
  transition: width 0.3s ease;
}

.completion-text {
  font-size: 0.85rem;
  color: #6c757d;
  font-weight: 500;
}

.text-success { color: #28a745 !important; }
.text-warning { color: #ffc107 !important; }

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
  min-width: 250px;
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

@media (max-width: 768px) {
  .distributor-aftersales {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .card-header,
  .card-body {
    padding: 1.5rem !important;
  }
  
  .section-wrapper {
    padding: 1.5rem;
  }
  
  .summary-grid {
    grid-template-columns: 1fr;
  }
}
</style>