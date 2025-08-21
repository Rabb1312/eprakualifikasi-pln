<template>
  <div class="manufacture-engineering">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-drafting-compass text-primary me-2"></i>
          Engineering Design Management
          <span class="subtitle">Configure engineering capabilities and design procedures</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Engineering Design Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-cogs"></i>
            <h6 class="section-title">Engineering Design Configuration</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Konfigurasikan kapabilitas engineering dan prosedur design yang tersedia di perusahaan Anda.
            </p>
          </div>

          <div class="engineering-grid">
            <!-- Principal Engineering Capability -->
            <div class="capability-card">
              <div class="capability-header">
                <i class="fas fa-tools"></i>
                <h6>Principal Engineering Capability</h6>
              </div>
              <div class="capability-body">
                <label for="principal_engineering_capability" class="form-label">
                  <i class="fas fa-wrench me-1"></i>
                  Kapabilitas Engineering Utama
                </label>
                <textarea 
                  id="principal_engineering_capability"
                  v-model="data.principal_engineering_capability" 
                  rows="4" 
                  class="form-control"
                  placeholder="Jelaskan kapabilitas engineering utama perusahaan, seperti: mechanical design, electrical engineering, structural analysis, CAD/CAM capabilities, dll."
                ></textarea>
                <small class="form-text">
                  Deskripsikan secara detail kemampuan engineering yang dimiliki perusahaan
                </small>
              </div>
            </div>

            <!-- Design Control Responsibility -->
            <div class="capability-card">
              <div class="capability-header">
                <i class="fas fa-user-cog"></i>
                <h6>Design Control Responsibility</h6>
              </div>
              <div class="capability-body">
                <label for="responsible_design_control" class="form-label">
                  <i class="fas fa-clipboard-check me-1"></i>
                  Penanggung Jawab Design Control
                </label>
                <textarea 
                  id="responsible_design_control"
                  v-model="data.responsible_design_control" 
                  rows="3" 
                  class="form-control"
                  placeholder="Sebutkan posisi/departemen yang bertanggung jawab atas design control, contoh: Chief Engineer, Design Manager, Engineering Department"
                ></textarea>
                <small class="form-text">
                  Sebutkan siapa yang bertanggung jawab atas kontrol dan validasi design
                </small>
              </div>
            </div>
          </div>

          <!-- Procedures and Involvement -->
          <div class="procedures-section">
            <div class="procedures-grid">
              <!-- Documented Procedures -->
              <div class="procedure-card">
                <div class="procedure-header">
                  <i class="fas fa-file-alt"></i>
                  <h6>Documented Procedures</h6>
                </div>
                <div class="procedure-body">
                  <label for="documented_procedures" class="form-label">
                    <i class="fas fa-book me-1"></i>
                    Prosedur Terdokumentasi untuk Design Control
                  </label>
                  <select 
                    id="documented_procedures"
                    v-model="data.documented_procedures"
                    class="form-select"
                  >
                    <option value="">Pilih ketersediaan prosedur...</option>
                    <option value="yes">Ya, tersedia</option>
                    <option value="no">Tidak tersedia</option>
                  </select>
                  <small class="form-text">
                    Apakah terdapat prosedur tertulis untuk mengontrol proses design?
                  </small>
                </div>
              </div>

              <!-- Design Involvement Depth -->
              <div class="procedure-card">
                <div class="procedure-header">
                  <i class="fas fa-layer-group"></i>
                  <h6>Design Involvement Depth</h6>
                </div>
                <div class="procedure-body">
                  <label class="form-label">
                    <i class="fas fa-ruler-combined me-1"></i>
                    Kedalaman Keterlibatan Design
                  </label>
                  <div class="depth-options">
                    <div class="depth-checkbox-card">
                      <input 
                        type="checkbox" 
                        id="design_works"
                        v-model="data.depth_design_involvement" 
                        value="design_works"
                        class="checkbox-input"
                      />
                      <label for="design_works" class="checkbox-label">
                        <i class="fas fa-pencil-ruler"></i>
                        <div class="checkbox-content">
                          <span class="checkbox-title">Design Works</span>
                          <small>Basic Design & Conceptual Engineering</small>
                        </div>
                      </label>
                    </div>

                    <div class="depth-checkbox-card">
                      <input 
                        type="checkbox" 
                        id="shop_drawings"
                        v-model="data.depth_design_involvement" 
                        value="shop_drawings"
                        class="checkbox-input"
                      />
                      <label for="shop_drawings" class="checkbox-label">
                        <i class="fas fa-drafting-compass"></i>
                        <div class="checkbox-content">
                          <span class="checkbox-title">Shop Drawings</span>
                          <small>Detail Design & Technical Drawings</small>
                        </div>
                      </label>
                    </div>
                  </div>
                  <small class="form-text">
                    Pilih tingkat keterlibatan dalam proses design (bisa pilih keduanya)
                  </small>
                </div>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div v-if="hasConfiguration" class="summary-section">
            <div class="summary-card">
              <div class="summary-header">
                <i class="fas fa-chart-line me-2"></i>
                <span>Ringkasan Konfigurasi Engineering</span>
              </div>
              <div class="summary-body">
                <div class="summary-grid">
                  <div class="summary-item" v-if="data.principal_engineering_capability">
                    <div class="summary-icon">
                      <i class="fas fa-tools text-primary"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Engineering Capability</span>
                      <span class="summary-value">Terdefinisi</span>
                    </div>
                  </div>

                  <div class="summary-item" v-if="data.responsible_design_control">
                    <div class="summary-icon">
                      <i class="fas fa-user-cog text-primary"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Design Control</span>
                      <span class="summary-value">Penanggung jawab ditentukan</span>
                    </div>
                  </div>

                  <div class="summary-item" v-if="data.documented_procedures">
                    <div class="summary-icon">
                      <i class="fas fa-file-alt" :class="data.documented_procedures === 'yes' ? 'text-success' : 'text-warning'"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Documented Procedures</span>
                      <span class="summary-value" :class="data.documented_procedures === 'yes' ? 'text-success' : 'text-warning'">
                        {{ data.documented_procedures === 'yes' ? 'Tersedia' : 'Tidak tersedia' }}
                      </span>
                    </div>
                  </div>

                  <div class="summary-item" v-if="data.depth_design_involvement.length > 0">
                    <div class="summary-icon">
                      <i class="fas fa-layer-group text-primary"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Design Involvement</span>
                      <span class="summary-value">{{ data.depth_design_involvement.length }} tingkat</span>
                    </div>
                  </div>
                </div>

                <div class="involvement-details" v-if="data.depth_design_involvement.length > 0">
                  <h6 class="involvement-title">
                    <i class="fas fa-list-ul me-2"></i>
                    Detail Keterlibatan Design:
                  </h6>
                  <div class="involvement-badges">
                    <span 
                      v-for="involvement in data.depth_design_involvement" 
                      :key="involvement"
                      class="involvement-badge"
                    >
                      <i :class="getInvolvementIcon(involvement)"></i>
                      {{ getInvolvementLabel(involvement) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="saveEngineering" 
            :disabled="loading" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Konfigurasi Engineering" }}
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
  principal_engineering_capability: "",
  responsible_design_control: "",
  documented_procedures: "",
  depth_design_involvement: []
});

// Computed properties
const hasConfiguration = computed(() => {
  return data.value.principal_engineering_capability || 
         data.value.responsible_design_control || 
         data.value.documented_procedures ||
         data.value.depth_design_involvement.length > 0;
});

watch(
  () => props.manufacture,
  (newData) => {
    if (newData && newData.engineering_design) {
      data.value = { ...newData.engineering_design };
      data.value.depth_design_involvement = Array.isArray(newData.engineering_design?.depth_design_involvement)
        ? [...newData.engineering_design.depth_design_involvement]
        : [];
    } else {
      data.value = {
        principal_engineering_capability: "",
        responsible_design_control: "",
        documented_procedures: "",
        depth_design_involvement: []
      };
    }
  },
  { immediate: true, deep: true }
);

function getInvolvementIcon(involvement) {
  const icons = {
    'design_works': 'fas fa-pencil-ruler',
    'shop_drawings': 'fas fa-drafting-compass'
  };
  return icons[involvement] || 'fas fa-cog';
}

function getInvolvementLabel(involvement) {
  const labels = {
    'design_works': 'Design Works (Basic Design)',
    'shop_drawings': 'Shop Drawings (Detail Design)'
  };
  return labels[involvement] || involvement;
}

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function saveEngineering() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/manufacture", {
      engineering_design: data.value
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    if (response.data.success) {
      showAlert("success", "Konfigurasi engineering design berhasil disimpan!");
      emit("save", { engineering_design: data.value });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan konfigurasi engineering design");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan konfigurasi engineering design");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-engineering {
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

.engineering-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.capability-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.capability-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.capability-card:hover::before {
  left: 100%;
}

.capability-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.capability-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.capability-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.capability-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.capability-body {
  padding: 1.5rem;
}

.procedures-section {
  margin-top: 2rem;
}

.procedures-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.procedure-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.procedure-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.procedure-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.procedure-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.procedure-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.procedure-body {
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

.form-control,
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

.form-control:focus,
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

.depth-options {
  display: grid;
  gap: 1rem;
  margin-bottom: 0.5rem;
}

.depth-checkbox-card {
  background: #f8f9fa;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
}

.depth-checkbox-card:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.depth-checkbox-card:has(.checkbox-input:checked) {
  border-color: #007bff;
  background: linear-gradient(145deg, #e3f2fd 0%, #ffffff 100%);
}

.checkbox-input {
  position: absolute;
  opacity: 0;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  cursor: pointer;
  width: 100%;
}

.checkbox-label i {
  font-size: 1.5rem;
  color: #6c757d;
  transition: color 0.3s ease;
  width: 30px;
  text-align: center;
}

.depth-checkbox-card:has(.checkbox-input:checked) .checkbox-label i {
  color: #007bff;
}

.checkbox-content {
  flex: 1;
}

.checkbox-title {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
}

.checkbox-content small {
  color: #6c757d;
  font-size: 0.85rem;
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
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
  color: #495057;
}

.involvement-details {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  border: 1px solid #e9ecef;
}

.involvement-title {
  font-size: 1rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
}

.involvement-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.involvement-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
}

.involvement-badge i {
  font-size: 0.9rem;
}

.text-primary {
  color: #007bff !important;
}

.text-success {
  color: #28a745 !important;
}

.text-warning {
  color: #ffc107 !important;
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
  .manufacture-engineering {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .engineering-grid,
  .procedures-grid {
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
  
  .involvement-badges {
    flex-direction: column;
    align-items: stretch;
  }
  
  .involvement-badge {
    justify-content: center;
  }
}
</style>