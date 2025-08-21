<template>
  <div class="manufacture-code-standard">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-certificate text-primary me-2"></i>
          Code Standard Capability Management
          <span class="subtitle">Manage code standards and certifications</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Code Standard Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-award"></i>
            <h6 class="section-title">Code Standard Configuration</h6>
            <div class="section-actions">
              <button 
                @click="addCodeStandard" 
                class="btn btn-add" 
                type="button"
              >
                <i class="fas fa-plus me-1"></i>
                Tambah Standar
              </button>
            </div>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Daftarkan standar kode dan sertifikasi yang dimiliki perusahaan Anda untuk menunjukkan kapabilitas manufaktur.
            </p>
          </div>

          <div v-if="codeStandard.length === 0" class="empty-state">
            <i class="fas fa-certificate"></i>
            <p>Belum ada standar kode yang ditambahkan</p>
            <small>Klik "Tambah Standar" untuk menambahkan standar kode baru</small>
          </div>

          <div v-else class="standards-container">
            <!-- Standards Table for Desktop -->
            <div class="table-responsive d-none d-lg-block">
              <table class="standards-table">
                <thead>
                  <tr>
                    <th class="col-no">No</th>
                    <th class="col-authority">Authority</th>
                    <th class="col-section">Section</th>
                    <th class="col-classes">Classes</th>
                    <th class="col-stamps">Stamps Held</th>
                    <th class="col-action">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr 
                    v-for="(item, idx) in codeStandard" 
                    :key="'standard_' + idx"
                    class="standard-row"
                  >
                    <td class="row-number">{{ idx + 1 }}</td>
                    <td>
                      <input 
                        v-model="item.authority" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="ASME, API, SNI, ANSI, Others"
                      />
                    </td>
                    <td>
                      <input 
                        v-model="item.section" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="Section VIII, Div 1"
                      />
                    </td>
                    <td>
                      <input 
                        v-model="item.classes" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="Class 1, 2, 3"
                      />
                    </td>
                    <td>
                      <input 
                        v-model="item.stamps_held" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="U, UM, S, PP"
                      />
                    </td>
                    <td class="text-center">
                      <button 
                        @click="removeCodeStandard(idx)" 
                        class="btn btn-delete-small" 
                        type="button"
                        title="Hapus Standar"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Standards Cards for Mobile/Tablet -->
            <div class="standards-grid d-lg-none">
              <div 
                v-for="(item, idx) in codeStandard" 
                :key="'mobile_standard_' + idx"
                class="standard-card"
              >
                <div class="standard-card-header">
                  <div class="standard-card-title">
                    <i class="fas fa-certificate me-2"></i>
                    <span>Standar {{ idx + 1 }}</span>
                  </div>
                  <button 
                    @click="removeCodeStandard(idx)" 
                    class="btn btn-delete-small" 
                    type="button"
                    title="Hapus Standar"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>

                <div class="standard-card-body">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-building me-1"></i>
                      Authority
                    </label>
                    <input 
                      v-model="item.authority" 
                      type="text" 
                      class="form-control"
                      placeholder="ASME, API, SNI, ANSI, Others"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-layer-group me-1"></i>
                      Section
                    </label>
                    <input 
                      v-model="item.section" 
                      type="text" 
                      class="form-control"
                      placeholder="Section VIII, Div 1"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-tags me-1"></i>
                      Classes
                    </label>
                    <input 
                      v-model="item.classes" 
                      type="text" 
                      class="form-control"
                      placeholder="Class 1, 2, 3"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-stamp me-1"></i>
                      Stamps Held
                    </label>
                    <input 
                      v-model="item.stamps_held" 
                      type="text" 
                      class="form-control"
                      placeholder="U, UM, S, PP"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Standards Summary -->
            <div class="standards-summary">
              <div class="summary-card">
                <div class="summary-header">
                  <i class="fas fa-chart-bar me-2"></i>
                  <span>Ringkasan Standar Kode</span>
                </div>
                <div class="summary-body">
                  <div class="summary-grid">
                    <div class="summary-item">
                      <span class="summary-label">Total Standar:</span>
                      <span class="summary-value">{{ codeStandard.length }} standar</span>
                    </div>
                    <div class="summary-item">
                      <span class="summary-label">Standar Lengkap:</span>
                      <span class="summary-value">{{ completeStandards }} standar</span>
                    </div>
                    <div class="summary-item">
                      <span class="summary-label">Authority Unik:</span>
                      <span class="summary-value">{{ uniqueAuthorities.length }} authority</span>
                    </div>
                  </div>

                  <div v-if="uniqueAuthorities.length > 0" class="authorities-badges">
                    <h6 class="badges-title">
                      <i class="fas fa-award me-2"></i>
                      Authorities yang Dimiliki:
                    </h6>
                    <div class="badges-container">
                      <span 
                        v-for="authority in uniqueAuthorities" 
                        :key="authority"
                        class="authority-badge"
                      >
                        <i class="fas fa-certificate"></i>
                        {{ authority }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Reference Guide -->
          <div class="reference-guide">
            <div class="guide-card">
              <div class="guide-header">
                <i class="fas fa-book text-info"></i>
                <span>Panduan Referensi</span>
              </div>
              <div class="guide-content">
                <div class="guide-grid">
                  <div class="guide-item">
                    <h6>Common Authorities:</h6>
                    <ul>
                      <li><strong>ASME</strong> - American Society of Mechanical Engineers</li>
                      <li><strong>API</strong> - American Petroleum Institute</li>
                      <li><strong>AWS</strong> - American Welding Society</li>
                      <li><strong>SNI</strong> - Standar Nasional Indonesia</li>
                    </ul>
                  </div>
                  <div class="guide-item">
                    <h6>Example Stamps:</h6>
                    <ul>
                      <li><strong>U</strong> - Pressure Vessels</li>
                      <li><strong>S</strong> - Power Boilers</li>
                      <li><strong>PP</strong> - Pressure Piping</li>
                      <li><strong>UM</strong> - Miniature Pressure Vessels</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="saveCodeStandard" 
            :disabled="loading" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Standar Kode" }}
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
const codeStandard = ref([]);

// Computed properties
const completeStandards = computed(() => {
  return codeStandard.value.filter(item => 
    item.authority && item.section && item.classes && item.stamps_held
  ).length;
});

const uniqueAuthorities = computed(() => {
  const authorities = codeStandard.value
    .map(item => item.authority)
    .filter(authority => authority && authority.trim())
    .map(authority => authority.trim().toUpperCase());
  return [...new Set(authorities)];
});

watch(
  () => props.manufacture,
  (newData) => {
    codeStandard.value = Array.isArray(newData?.code_standard) ? [...newData.code_standard] : [];
  },
  { immediate: true, deep: true }
);

function addCodeStandard() {
  codeStandard.value.push({ 
    authority: "", 
    section: "", 
    classes: "", 
    stamps_held: "" 
  });
}

function removeCodeStandard(idx) {
  codeStandard.value.splice(idx, 1);
}

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function saveCodeStandard() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/manufacture", {
      code_standard: codeStandard.value
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    if (response.data.success) {
      showAlert("success", "Data standar kode berhasil disimpan!");
      emit("save", { code_standard: codeStandard.value });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan data standar kode");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan data standar kode");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-code-standard {
  max-width: 1400px;
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

.standards-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e9ecef;
}

.table-responsive {
  border-radius: 12px;
  overflow: hidden;
}

.standards-table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}

.standards-table thead th {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  color: #495057;
  font-weight: 600;
  padding: 1rem;
  border-bottom: 2px solid #dee2e6;
  font-size: 0.95rem;
  text-align: left;
}

.col-no { width: 60px; text-align: center; }
.col-authority { width: 25%; }
.col-section { width: 20%; }
.col-classes { width: 20%; }
.col-stamps { width: 20%; }
.col-action { width: 80px; text-align: center; }

.standard-row {
  transition: all 0.3s ease;
  border-bottom: 1px solid #e9ecef;
}

.standard-row:hover {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.standard-row:last-child {
  border-bottom: none;
}

.standards-table td {
  padding: 1rem;
  vertical-align: middle;
}

.row-number {
  text-align: center;
  font-weight: 600;
  color: #007bff;
  font-size: 1rem;
}

.table-input {
  border: 2px solid transparent;
  background: #f8f9fa;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
}

.table-input:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
}

.standards-grid {
  display: grid;
  gap: 1.5rem;
  padding: 1rem;
}

.standard-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.standard-card:hover {
  border-color: #007bff;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.standard-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.standard-card-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
}

.standard-card-title i {
  color: #007bff;
}

.standard-card-body {
  padding: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
}

.form-label i {
  color: #007bff;
  font-size: 0.8rem;
}

.form-control {
  padding: 0.75rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
}

.form-control:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.standards-summary {
  margin-top: 2rem;
  padding: 1rem;
}

.summary-card {
  background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1.5rem;
  border-left: 4px solid #007bff;
}

.summary-header {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1.5rem;
  font-size: 1rem;
}

.summary-header i {
  color: #007bff;
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1rem;
  background: white;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.summary-label {
  color: #6c757d;
  font-size: 0.9rem;
}

.summary-value {
  font-weight: 600;
  color: #007bff;
  font-size: 1rem;
}

.authorities-badges {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
}

.badges-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
}

.badges-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.authority-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  border-radius: 15px;
  font-size: 0.85rem;
  font-weight: 500;
}

.authority-badge i {
  font-size: 0.8rem;
}

.reference-guide {
  margin-top: 2rem;
}

.guide-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  border: 1px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
}

.guide-header {
  padding: 1rem 1.5rem;
  background: white;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  font-size: 0.95rem;
  color: #495057;
}

.guide-content {
  padding: 1.5rem;
}

.guide-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
}

.guide-item h6 {
  color: #495057;
  font-weight: 600;
  margin-bottom: 0.75rem;
  font-size: 0.95rem;
}

.guide-item ul {
  margin: 0;
  padding-left: 1.2rem;
  color: #6c757d;
  font-size: 0.85rem;
  line-height: 1.6;
}

.guide-item li {
  margin-bottom: 0.5rem;
}

.guide-item strong {
  color: #007bff;
  font-weight: 600;
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

@keyframes spinner-border {
  100% {
    transform: rotate(360deg);
  }
}

@media (max-width: 1400px) {
  .manufacture-code-standard {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
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
  
  .summary-grid {
    grid-template-columns: 1fr;
  }
  
  .guide-grid {
    grid-template-columns: 1fr;
  }
  
  .badges-container {
    flex-direction: column;
    align-items: stretch;
  }
  
  .authority-badge {
    justify-content: center;
  }
}
</style>