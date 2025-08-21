<template>
  <div class="manufacture-subcontracting">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-people-carry text-primary me-2"></i>
          Subcontracting Management
          <span class="subtitle">Configure outsourced work portions</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Subcontracting Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-handshake"></i>
            <h6 class="section-title">Subcontracting Configuration</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Jelaskan bagian pekerjaan mana yang biasanya disubkontrakkan kepada pihak ketiga dalam proses manufaktur.
            </p>
          </div>

          <div class="subcontracting-card">
            <div class="subcontracting-header">
              <i class="fas fa-clipboard-list"></i>
              <h6>Outsourced Work Portions</h6>
            </div>
            <div class="subcontracting-body">
              <label for="subcontracting_portion" class="form-label">
                <i class="fas fa-tasks me-1"></i>
                Bagian Pekerjaan yang Disubkontrakkan
              </label>
              
              <div class="textarea-container">
                <textarea
                  id="subcontracting_portion"
                  v-model="data.subcontracting_portion"
                  rows="6"
                  class="form-control"
                  placeholder="Jelaskan bagian pekerjaan yang biasanya disubkontrakkan, contoh:
• Pengelasan khusus dan fabrikasi logam
• Proses pengecatan dan finishing surface
• Layanan logistik dan transportasi
• Machining precision dan CNC operations
• Quality testing dan inspeksi
• Packaging dan assembly tertentu"
                ></textarea>
                <div class="character-counter">
                  <span>{{ data.subcontracting_portion.length }} karakter</span>
                </div>
              </div>

              <div class="helper-section">
                <div class="helper-card">
                  <div class="helper-header">
                    <i class="fas fa-lightbulb text-warning"></i>
                    <span>Tips Pengisian</span>
                  </div>
                  <div class="helper-content">
                    <ul>
                      <li>Sebutkan jenis pekerjaan spesifik yang di-outsource</li>
                      <li>Jelaskan alasan mengapa bagian tersebut disubkontrakkan</li>
                      <li>Cantumkan persentase atau volume pekerjaan jika memungkinkan</li>
                      <li>Sertakan kriteria pemilihan subkontraktor jika ada</li>
                    </ul>
                  </div>
                </div>

                <div class="examples-card">
                  <div class="examples-header">
                    <i class="fas fa-list-ul text-info"></i>
                    <span>Contoh Kategori Subcontracting</span>
                  </div>
                  <div class="examples-content">
                    <div class="example-categories">
                      <div class="category-item">
                        <i class="fas fa-tools"></i>
                        <span>Manufacturing Processes</span>
                        <small>Welding, Machining, Assembly</small>
                      </div>
                      <div class="category-item">
                        <i class="fas fa-paint-brush"></i>
                        <span>Finishing Services</span>
                        <small>Painting, Coating, Polishing</small>
                      </div>
                      <div class="category-item">
                        <i class="fas fa-truck"></i>
                        <span>Logistics & Transport</span>
                        <small>Delivery, Storage, Distribution</small>
                      </div>
                      <div class="category-item">
                        <i class="fas fa-microscope"></i>
                        <span>Quality & Testing</span>
                        <small>Inspection, Testing, Certification</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <small class="form-text">
                Berikan informasi detail untuk membantu pelanggan memahami aspek outsourcing dalam proses produksi
              </small>
            </div>
          </div>

          <!-- Summary Section -->
          <div v-if="data.subcontracting_portion.trim()" class="summary-section">
            <div class="summary-card">
              <div class="summary-header">
                <i class="fas fa-chart-bar me-2"></i>
                <span>Ringkasan Subcontracting</span>
              </div>
              <div class="summary-body">
                <div class="summary-grid">
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Status Konfigurasi</span>
                      <span class="summary-value text-success">Terkonfigurasi</span>
                    </div>
                  </div>

                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-file-alt text-info"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Detail Length</span>
                      <span class="summary-value text-info">{{ data.subcontracting_portion.length }} karakter</span>
                    </div>
                  </div>
                </div>

                <div class="content-preview">
                  <div class="preview-header">
                    <i class="fas fa-eye me-2"></i>
                    <span>Preview Content</span>
                  </div>
                  <div class="preview-content">
                    {{ data.subcontracting_portion.substring(0, 200) }}{{ data.subcontracting_portion.length > 200 ? '...' : '' }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="saveSubcontracting" 
            :disabled="loading || !data.subcontracting_portion.trim()" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Konfigurasi Subcontracting" }}
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
import { ref, watch } from "vue";
import axios from "axios";
import AlertComponent from "../AlertComponent.vue";

const props = defineProps({ manufacture: Object });
const emit = defineEmits(["save"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const data = ref({ subcontracting_portion: "" });

watch(
  () => props.manufacture,
  (newData) => {
    data.value.subcontracting_portion = newData?.subcontracting || "";
  },
  { immediate: true, deep: true }
);

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function saveSubcontracting() {
  if (!data.value.subcontracting_portion.trim()) {
    showAlert("error", "Mohon isi informasi subcontracting terlebih dahulu");
    return;
  }

  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/manufacture", {
      subcontracting: data.value.subcontracting_portion
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    if (response.data.success) {
      showAlert("success", "Konfigurasi subcontracting berhasil disimpan!");
      emit("save", { subcontracting: data.value.subcontracting_portion });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan konfigurasi subcontracting");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan konfigurasi subcontracting");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-subcontracting {
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

.subcontracting-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
  margin-bottom: 2rem;
}

.subcontracting-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.subcontracting-card:hover::before {
  left: 100%;
}

.subcontracting-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.subcontracting-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.subcontracting-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.subcontracting-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.subcontracting-body {
  padding: 1.5rem;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 1rem;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.form-label i {
  color: #007bff;
  font-size: 0.9rem;
}

.textarea-container {
  position: relative;
  margin-bottom: 1.5rem;
}

.form-control {
  padding: 1rem;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
  resize: vertical;
  min-height: 150px;
  line-height: 1.6;
}

.form-control:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-2px);
}

.character-counter {
  position: absolute;
  bottom: 8px;
  right: 12px;
  font-size: 0.75rem;
  color: #6c757d;
  background: rgba(255, 255, 255, 0.9);
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
}

.helper-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
  margin-bottom: 1rem;
}

.helper-card,
.examples-card {
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border: 1px solid #e9ecef;
  border-radius: 8px;
  overflow: hidden;
}

.helper-header,
.examples-header {
  padding: 0.75rem 1rem;
  background: white;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  font-size: 0.9rem;
  color: #495057;
}

.helper-content,
.examples-content {
  padding: 1rem;
}

.helper-content ul {
  margin: 0;
  padding-left: 1.2rem;
  color: #6c757d;
  font-size: 0.85rem;
  line-height: 1.5;
}

.helper-content li {
  margin-bottom: 0.5rem;
}

.example-categories {
  display: grid;
  gap: 0.75rem;
}

.category-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem;
  background: white;
  border-radius: 6px;
  border: 1px solid #e9ecef;
  font-size: 0.85rem;
}

.category-item i {
  color: #007bff;
  width: 16px;
  text-align: center;
}

.category-item span {
  font-weight: 600;
  color: #495057;
}

.category-item small {
  color: #6c757d;
  margin-left: auto;
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

.content-preview {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
}

.preview-header {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.75rem;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
}

.preview-header i {
  color: #007bff;
}

.preview-content {
  color: #6c757d;
  font-size: 0.9rem;
  line-height: 1.5;
  font-style: italic;
}

.text-success {
  color: #28a745 !important;
}

.text-info {
  color: #17a2b8 !important;
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
  min-width: 280px;
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
  .manufacture-subcontracting {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .helper-section {
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
  
  .helper-section {
    grid-template-columns: 1fr;
  }
  
  .category-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .category-item small {
    margin-left: 0;
  }
}
</style>