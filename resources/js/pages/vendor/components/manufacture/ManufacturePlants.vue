<template>
  <div class="manufacture-plants">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-industry text-primary me-2"></i>
          Manufacturing Plants Management
          <span class="subtitle">Manage other manufacturing facilities worldwide</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Manufacturing Plants Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-building"></i>
            <h6 class="section-title">Other Manufacturing Plants (If Any)</h6>
            <div class="section-actions">
              <button 
                @click="addPlant" 
                class="btn btn-add" 
                type="button"
              >
                <i class="fas fa-plus me-1"></i>
                Tambah Pabrik
              </button>
            </div>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Sebutkan pabrik manufaktur lainnya di seluruh dunia dan produk-produk yang dihasilkan oleh perusahaan Anda.
            </p>
          </div>

          <div v-if="plants.length === 0" class="empty-state">
            <i class="fas fa-industry"></i>
            <p>Belum ada pabrik manufaktur lain yang ditambahkan</p>
            <small>Klik "Tambah Pabrik" untuk menambahkan pabrik baru</small>
          </div>

          <div v-else class="plants-container">
            <!-- Plants Table for Desktop -->
            <div class="table-responsive d-none d-md-block">
              <table class="plants-table">
                <thead>
                  <tr>
                    <th class="col-no">No</th>
                    <th class="col-company">Nama Perusahaan</th>
                    <th class="col-products">Produk yang Diproduksi</th>
                    <th class="col-action">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr 
                    v-for="(plant, idx) in plants" 
                    :key="'plant_' + idx"
                    class="plant-row"
                  >
                    <td class="row-number">{{ idx + 1 }}</td>
                    <td>
                      <input 
                        v-model="plant.company_name" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="Contoh: PT Manufacturing Indonesia"
                      />
                    </td>
                    <td>
                      <input 
                        v-model="plant.products_manufactured" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="Contoh: Steel Components, Automotive Parts"
                      />
                    </td>
                    <td class="text-center">
                      <button 
                        @click="removePlant(idx)" 
                        class="btn btn-delete-small" 
                        type="button"
                        title="Hapus Pabrik"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Plants Cards for Mobile -->
            <div class="plants-grid d-md-none">
              <div 
                v-for="(plant, idx) in plants" 
                :key="'mobile_plant_' + idx"
                class="plant-card"
              >
                <div class="plant-card-header">
                  <div class="plant-card-title">
                    <i class="fas fa-industry me-2"></i>
                    <span>Pabrik {{ idx + 1 }}</span>
                  </div>
                  <button 
                    @click="removePlant(idx)" 
                    class="btn btn-delete-small" 
                    type="button"
                    title="Hapus Pabrik"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>

                <div class="plant-card-body">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-building me-1"></i>
                      Nama Perusahaan
                    </label>
                    <input 
                      v-model="plant.company_name" 
                      type="text" 
                      class="form-control"
                      placeholder="Contoh: PT Manufacturing Indonesia"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-cogs me-1"></i>
                      Produk yang Diproduksi
                    </label>
                    <input 
                      v-model="plant.products_manufactured" 
                      type="text" 
                      class="form-control"
                      placeholder="Contoh: Steel Components, Automotive Parts"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Plants Summary -->
            <div v-if="plants.length > 0" class="plants-summary">
              <div class="summary-card">
                <div class="summary-header">
                  <i class="fas fa-chart-bar me-2"></i>
                  <span>Ringkasan Pabrik</span>
                </div>
                <div class="summary-body">
                  <div class="summary-item">
                    <span class="summary-label">Total Pabrik:</span>
                    <span class="summary-value">{{ plants.length }} pabrik</span>
                  </div>
                  <div class="summary-item">
                    <span class="summary-label">Pabrik Aktif:</span>
                    <span class="summary-value">{{ plants.filter(p => p.company_name).length }} pabrik</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="savePlants" 
            :disabled="loading" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Data Pabrik" }}
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
const plants = ref([]);

watch(
  () => props.manufacture,
  (newData) => {
    plants.value = Array.isArray(newData?.plants) ? [...newData.plants] : [];
  },
  { immediate: true, deep: true }
);

function addPlant() {
  plants.value.push({ 
    company_name: "", 
    products_manufactured: "" 
  });
}

function removePlant(idx) {
  plants.value.splice(idx, 1);
}

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function savePlants() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/manufacture", {
      plants: plants.value
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    if (response.data.success) {
      showAlert("success", "Data pabrik berhasil disimpan!");
      emit("save", { plants: plants.value });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan data pabrik");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan data pabrik");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-plants {
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

.plants-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e9ecef;
}

.table-responsive {
  border-radius: 12px;
  overflow: hidden;
}

.plants-table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}

.plants-table thead th {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  color: #495057;
  font-weight: 600;
  padding: 1rem;
  border-bottom: 2px solid #dee2e6;
  font-size: 0.95rem;
  text-align: left;
}

.col-no { width: 60px; text-align: center; }
.col-company { width: 40%; }
.col-products { width: 45%; }
.col-action { width: 80px; text-align: center; }

.plant-row {
  transition: all 0.3s ease;
  border-bottom: 1px solid #e9ecef;
}

.plant-row:hover {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.plant-row:last-child {
  border-bottom: none;
}

.plants-table td {
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

.plants-grid {
  display: grid;
  gap: 1.5rem;
  padding: 1rem;
}

.plant-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.plant-card:hover {
  border-color: #007bff;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.plant-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.plant-card-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
}

.plant-card-title i {
  color: #007bff;
}

.plant-card-body {
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

.plants-summary {
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
  margin-bottom: 1rem;
  font-size: 1rem;
}

.summary-header i {
  color: #007bff;
}

.summary-body {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
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

@media (max-width: 1024px) {
  .manufacture-plants {
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
  
  .summary-body {
    grid-template-columns: 1fr;
  }
}
</style>