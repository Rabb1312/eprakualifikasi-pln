<template>
  <div class="manufacture-personnel">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-users text-primary me-2"></i>
          Personnel Management
          <span class="subtitle">Manage organizational structure and workforce</span>
        </h5>
      </div>
      <div class="card-body">

        <!-- Organization Chart Section -->
        <!-- <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-sitemap"></i>
            <h6 class="section-title">Organization Chart</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Upload struktur organisasi terbaru perusahaan Anda (format PDF atau gambar).
            </p>
          </div>

          <div class="upload-section">
            <div class="form-group">
              <label for="org_chart_upload" class="form-label">
                <i class="fas fa-upload me-1"></i>
                Upload Bagan Organisasi
              </label>
              <div class="file-upload-area">
                <input 
                  type="file" 
                  id="org_chart_upload"
                  @change="onChartUpload" 
                  accept=".pdf,.jpg,.jpeg,.png"
                  class="file-input"
                />
                <div class="file-upload-content">
                  <div v-if="!orgChartName" class="upload-placeholder">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Klik untuk memilih file atau drag & drop</span>
                    <small>Format: PDF, JPG, JPEG, PNG (Max: 10MB)</small>
                  </div>
                  <div v-else class="uploaded-file">
                    <div class="file-info">
                      <i class="fas fa-file-alt"></i>
                      <span>{{ orgChartName }}</span>
                    </div>
                    <button 
                      @click="removeChart" 
                      type="button"
                      class="btn btn-delete-small"
                      title="Hapus File"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <!-- Personnel Data Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-user-friends"></i>
            <h6 class="section-title">Permanent Employees by Category</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Masukkan jumlah karyawan tetap berdasarkan kategori departemen.
            </p>
          </div>

          <div class="personnel-grid">
            <div 
              v-for="cat in categories" 
              :key="cat.key"
              class="personnel-card"
            >
              <div class="personnel-card-header">
                <i :class="getCategoryIcon(cat.key)"></i>
                <span>{{ cat.label }}</span>
              </div>
              <div class="personnel-card-body">
                <div class="form-group">
                  <label :for="'personnel_' + cat.key" class="form-label sr-only">
                    {{ cat.label }}
                  </label>
                  <input
                    :id="'personnel_' + cat.key"
                    type="number"
                    v-model="personnelData[cat.key]"
                    min="0"
                    class="form-control personnel-input"
                    placeholder="0"
                  />
                  <small class="form-text">orang</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Workforce -->
          <div class="total-section">
            <div class="total-card">
              <div class="total-header">
                <i class="fas fa-users"></i>
                <h6>Total Workforce</h6>
              </div>
              <div class="total-body">
                <div class="total-number">{{ personnelData.total_workforce }}</div>
                <small>Total Karyawan Tetap</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="savePersonnel" 
            :disabled="loading" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Data Personnel" }}
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
import { ref, reactive, watch } from "vue";
import axios from "axios";
import AlertComponent from "../AlertComponent.vue";

const props = defineProps({ manufacture: Object });
const emit = defineEmits(["save"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const categories = [
  { key: "management", label: "Management" },
  { key: "admin_finance", label: "Admin/Finance" },
  { key: "engineering", label: "Engineering" },
  { key: "procurement", label: "Procurement" },
  { key: "warehousing", label: "Warehousing" },
  { key: "sales_marketing", label: "Sales/Marketing" },
  { key: "production", label: "Production" },
  { key: "qa_qc", label: "QA/QC" },
  { key: "service", label: "Service" }
];

const personnelData = reactive({
  management: 0,
  admin_finance: 0,
  engineering: 0,
  procurement: 0,
  warehousing: 0,
  sales_marketing: 0,
  production: 0,
  qa_qc: 0,
  service: 0,
  total_workforce: 0,
  org_chart: null
});
const orgChartName = ref("");

watch(
  () => props.manufacture,
  (newData) => {
    if (newData && newData.personnel) {
      Object.assign(personnelData, newData.personnel);
      orgChartName.value = newData.personnel?.org_chart_name || "";
    }
  },
  { immediate: true, deep: true }
);

watch(
  [
    () => personnelData.management, 
    () => personnelData.admin_finance, 
    () => personnelData.engineering, 
    () => personnelData.procurement, 
    () => personnelData.warehousing, 
    () => personnelData.sales_marketing, 
    () => personnelData.production, 
    () => personnelData.qa_qc, 
    () => personnelData.service
  ],
  () => {
    personnelData.total_workforce =
      Number(personnelData.management) +
      Number(personnelData.admin_finance) +
      Number(personnelData.engineering) +
      Number(personnelData.procurement) +
      Number(personnelData.warehousing) +
      Number(personnelData.sales_marketing) +
      Number(personnelData.production) +
      Number(personnelData.qa_qc) +
      Number(personnelData.service);
  }
);

function getCategoryIcon(key) {
  const icons = {
    'management': 'fas fa-user-tie',
    'admin_finance': 'fas fa-calculator',
    'engineering': 'fas fa-cogs',
    'procurement': 'fas fa-shopping-cart',
    'warehousing': 'fas fa-warehouse',
    'sales_marketing': 'fas fa-chart-line',
    'production': 'fas fa-industry',
    'qa_qc': 'fas fa-check-double',
    'service': 'fas fa-tools'
  }
  return icons[key] || 'fas fa-user'
}

function onChartUpload(e) {
  const file = e.target.files[0];
  if (file) {
    // Validate file size (10MB max)
    if (file.size > 10 * 1024 * 1024) {
      showAlert("error", "Ukuran file terlalu besar. Maksimal 10MB.");
      return;
    }
    personnelData.org_chart = file;
    orgChartName.value = file.name;
  }
}

function removeChart() {
  personnelData.org_chart = null;
  orgChartName.value = "";
  // Reset file input
  const fileInput = document.getElementById('org_chart_upload');
  if (fileInput) fileInput.value = '';
}

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function savePersonnel() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const form = new FormData();
    
    Object.keys(personnelData).forEach((key) => {
      if (key === "org_chart" && personnelData.org_chart) {
        form.append("org_chart", personnelData.org_chart);
      } else if (key !== "org_chart") {
        form.append(`personnel[${key}]`, personnelData[key]);
      }
    });
    
    form.append("org_chart_name", orgChartName.value);
    
    const response = await axios.put("/api/vendor/manufacture", form, {
      headers: { 
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    });
    
    if (response.data.success) {
      showAlert("success", "Data personnel berhasil disimpan!");
      emit("save", { personnel: { ...personnelData, org_chart_name: orgChartName.value } });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan data personnel");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan data personnel");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-personnel {
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
  margin-bottom: 1.5rem;
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

.upload-section {
  margin-bottom: 1.5rem;
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

.file-upload-area {
  position: relative;
  border: 2px dashed #dee2e6;
  border-radius: 12px;
  background: #f8f9fa;
  transition: all 0.3s ease;
  overflow: hidden;
}

.file-upload-area:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.file-input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
  z-index: 2;
}

.file-upload-content {
  padding: 2rem;
  text-align: center;
}

.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  color: #6c757d;
}

.upload-placeholder i {
  font-size: 2rem;
  color: #007bff;
  margin-bottom: 0.5rem;
}

.upload-placeholder span {
  font-weight: 600;
  font-size: 1rem;
}

.upload-placeholder small {
  font-size: 0.85rem;
  color: #8e9ba8;
}

.uploaded-file {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: white;
  padding: 1rem;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.file-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #007bff;
  font-weight: 600;
}

.file-info i {
  font-size: 1.2rem;
}

.personnel-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.personnel-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.personnel-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.personnel-card:hover::before {
  left: 100%;
}

.personnel-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.personnel-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 600;
  color: #495057;
  border-bottom: 1px solid #dee2e6;
}

.personnel-card-header i {
  color: #007bff;
  font-size: 1.1rem;
  width: 20px;
  text-align: center;
}

.personnel-card-body {
  padding: 1.5rem;
}

.personnel-input {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 600;
  text-align: center;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
}

.personnel-input:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.form-text {
  display: block;
  text-align: center;
  margin-top: 0.5rem;
  color: #6c757d;
  font-size: 0.85rem;
  font-style: italic;
}

.total-section {
  margin-top: 2rem;
  display: flex;
  justify-content: center;
}

.total-card {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border-radius: 16px;
  padding: 2rem;
  text-align: center;
  min-width: 250px;
  box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
  position: relative;
  overflow: hidden;
}

.total-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
}

.total-header {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
  position: relative;
  z-index: 1;
}

.total-header i {
  font-size: 1.5rem;
}

.total-header h6 {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
}

.total-body {
  position: relative;
  z-index: 1;
}

.total-number {
  font-size: 3rem;
  font-weight: 700;
  line-height: 1;
  margin-bottom: 0.5rem;
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

.btn-delete-small {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
  padding: 0.5rem;
  min-width: auto;
  font-size: 0.8rem;
}

.btn-delete-small:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
  min-width: 200px;
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

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

@keyframes spinner-border {
  100% {
    transform: rotate(360deg);
  }
}

@media (max-width: 1024px) {
  .manufacture-personnel {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .personnel-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
  
  .personnel-grid {
    grid-template-columns: 1fr;
  }
  
  .total-card {
    min-width: 200px;
    padding: 1.5rem;
  }
  
  .total-number {
    font-size: 2.5rem;
  }
}
</style>