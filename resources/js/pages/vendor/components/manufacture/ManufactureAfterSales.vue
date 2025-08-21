<template>
  <div class="manufacture-after-sales">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-headset text-primary me-2"></i>
          After Sales Support Management
          <span class="subtitle">Configure post-sales service and support</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- After Sales Support Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-hands-helping"></i>
            <h6 class="section-title">After Sales Support Configuration</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Konfigurasikan layanan after sales support yang tersedia untuk pelanggan perusahaan Anda.
            </p>
          </div>

          <div class="support-options">
            <div class="option-card">
              <div class="option-header">
                <i class="fas fa-exclamation-triangle"></i>
                <h6>Priority Treatment for Issues</h6>
              </div>
              <div class="option-body">
                <label for="priority_treatment" class="form-label">
                  <i class="fas fa-star me-1"></i>
                  Priority treatment for over/short/damaged requests
                </label>
                <select 
                  id="priority_treatment"
                  v-model="data.priority_treatment"
                  class="form-select"
                >
                  <option value="">Pilih ketersediaan layanan...</option>
                  <option value="yes">Ya, tersedia</option>
                  <option value="no">Tidak tersedia</option>
                </select>
                <small class="form-text">
                  Apakah perusahaan memberikan prioritas khusus untuk keluhan kelebihan/kekurangan/kerusakan barang?
                </small>
              </div>
            </div>

            <div class="option-card">
              <div class="option-header">
                <i class="fas fa-undo"></i>
                <h6>Returned Goods Procedure</h6>
              </div>
              <div class="option-body">
                <label for="procedure_returned_goods" class="form-label">
                  <i class="fas fa-clipboard-list me-1"></i>
                  Procedure for returned goods
                </label>
                <select 
                  id="procedure_returned_goods"
                  v-model="data.procedure_returned_goods"
                  class="form-select"
                >
                  <option value="">Pilih ketersediaan prosedur...</option>
                  <option value="yes">Ya, ada prosedur</option>
                  <option value="no">Tidak ada prosedur</option>
                </select>
                <small class="form-text">
                  Apakah terdapat prosedur khusus untuk pengembalian barang dari pelanggan?
                </small>
              </div>
            </div>

            <div class="option-card">
              <div class="option-header">
                <i class="fas fa-phone-alt"></i>
                <h6>Contact Centre Service</h6>
              </div>
              <div class="option-body">
                <label for="contact_centre" class="form-label">
                  <i class="fas fa-headset me-1"></i>
                  Contact centre number
                </label>
                <select 
                  id="contact_centre"
                  v-model="data.contact_centre"
                  class="form-select"
                >
                  <option value="">Pilih ketersediaan contact centre...</option>
                  <option value="yes">Ya, tersedia</option>
                  <option value="no">Tidak tersedia</option>
                </select>
                <small class="form-text">
                  Apakah tersedia nomor contact centre untuk layanan pelanggan?
                </small>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div v-if="hasSelectedOptions" class="summary-section">
            <div class="summary-card">
              <div class="summary-header">
                <i class="fas fa-chart-pie me-2"></i>
                <span>Ringkasan Layanan</span>
              </div>
              <div class="summary-body">
                <div class="summary-grid">
                  <div class="summary-item" v-if="data.priority_treatment">
                    <div class="summary-icon">
                      <i class="fas fa-star" :class="data.priority_treatment === 'yes' ? 'text-success' : 'text-danger'"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Priority Treatment</span>
                      <span class="summary-value" :class="data.priority_treatment === 'yes' ? 'text-success' : 'text-danger'">
                        {{ data.priority_treatment === 'yes' ? 'Tersedia' : 'Tidak Tersedia' }}
                      </span>
                    </div>
                  </div>

                  <div class="summary-item" v-if="data.procedure_returned_goods">
                    <div class="summary-icon">
                      <i class="fas fa-undo" :class="data.procedure_returned_goods === 'yes' ? 'text-success' : 'text-danger'"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Returned Goods Procedure</span>
                      <span class="summary-value" :class="data.procedure_returned_goods === 'yes' ? 'text-success' : 'text-danger'">
                        {{ data.procedure_returned_goods === 'yes' ? 'Ada Prosedur' : 'Tidak Ada Prosedur' }}
                      </span>
                    </div>
                  </div>

                  <div class="summary-item" v-if="data.contact_centre">
                    <div class="summary-icon">
                      <i class="fas fa-phone-alt" :class="data.contact_centre === 'yes' ? 'text-success' : 'text-danger'"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Contact Centre</span>
                      <span class="summary-value" :class="data.contact_centre === 'yes' ? 'text-success' : 'text-danger'">
                        {{ data.contact_centre === 'yes' ? 'Tersedia' : 'Tidak Tersedia' }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="service-score">
                  <div class="score-label">Service Coverage Score</div>
                  <div class="score-value">{{ serviceScore }}%</div>
                  <div class="score-bar">
                    <div class="score-fill" :style="{ width: serviceScore + '%' }"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="saveAfterSales" 
            :disabled="loading" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Konfigurasi After Sales" }}
          </button>
        </div>

        <!-- Alert Component -->
        <AlertComponent 
          v-if="alertMsg" 
          :type="alertType" 
          :message="alertMsg" 
          @close="alertMsg = ''"
          class="alert-container"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import axios from "axios";
import AlertComponent from "../AlertComponent.vue";

const props = defineProps({ manufacture: Object });
const emit = defineEmits(["save"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const data = ref({
  priority_treatment: "",
  procedure_returned_goods: "",
  contact_centre: ""
});

// Computed properties
const hasSelectedOptions = computed(() => {
  return data.value.priority_treatment || 
         data.value.procedure_returned_goods || 
         data.value.contact_centre;
});

const serviceScore = computed(() => {
  const options = [
    data.value.priority_treatment,
    data.value.procedure_returned_goods,
    data.value.contact_centre
  ];
  
  const totalOptions = options.filter(option => option !== "").length;
  const yesOptions = options.filter(option => option === "yes").length;
  
  if (totalOptions === 0) return 0;
  return Math.round((yesOptions / totalOptions) * 100);
});

watch(
  () => props.manufacture,
  (newData) => {
    if (newData && newData.after_sales) {
      data.value = { ...newData.after_sales };
    } else {
      data.value = {
        priority_treatment: "",
        procedure_returned_goods: "",
        contact_centre: ""
      };
    }
  },
  { immediate: true, deep: true }
);

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function saveAfterSales() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/manufacture", {
      after_sales: data.value
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    if (response.data.success) {
      showAlert("success", "Konfigurasi after sales support berhasil disimpan!");
      emit("save", { after_sales: data.value });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan konfigurasi after sales");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan konfigurasi after sales");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-after-sales {
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

.support-options {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.option-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.option-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.option-card:hover::before {
  left: 100%;
}

.option-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.option-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.option-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.option-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.option-body {
  padding: 1.5rem;
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

.form-select {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
  margin-bottom: 0.5rem;
}

.form-select:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.form-text {
  color: #6c757d;
  font-size: 0.85rem;
  font-style: italic;
  line-height: 1.4;
}

.summary-section {
  margin-top: 2rem;
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
}

.service-score {
  text-align: center;
  padding: 1.5rem;
  background: white;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.score-label {
  font-size: 0.9rem;
  color: #6c757d;
  margin-bottom: 0.5rem;
}

.score-value {
  font-size: 2rem;
  font-weight: 700;
  color: #28a745;
  margin-bottom: 1rem;
}

.score-bar {
  width: 100%;
  height: 8px;
  background: #e9ecef;
  border-radius: 4px;
  overflow: hidden;
}

.score-fill {
  height: 100%;
  background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
  transition: width 0.5s ease;
  border-radius: 4px;
}

.text-success {
  color: #28a745 !important;
}

.text-danger {
  color: #dc3545 !important;
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

.spinner-border {
  width: 1.2em;
  height: 1.2em;
  border-width: 0.15em;
}

.alert-container {
  margin-top: 1.5rem;
}

@keyframes spinner-border {
  100% {
    transform: rotate(360deg);
  }
}

@media (max-width: 1024px) {
  .manufacture-after-sales {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .support-options {
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
  
  .summary-grid {
    grid-template-columns: 1fr;
  }
}
</style>