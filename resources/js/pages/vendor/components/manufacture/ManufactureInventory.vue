<template>
  <div class="manufacture-inventory">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-boxes text-primary me-2"></i>
          Inventory Management
          <span class="subtitle">Configure direct purchase availability</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Inventory Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-warehouse"></i>
            <h6 class="section-title">Inventory Configuration</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Konfigurasikan ketersediaan barang perusahaan untuk pembelian langsung oleh pelanggan.
            </p>
          </div>

          <div class="inventory-options">
            <!-- Direct Purchase Availability -->
            <div class="option-card">
              <div class="option-header">
                <i class="fas fa-shopping-cart"></i>
                <h6>Direct Purchase Availability</h6>
              </div>
              <div class="option-body">
                <label for="available_goods_direct_purchase" class="form-label">
                  <i class="fas fa-box me-1"></i>
                  Available goods for direct purchase
                </label>
                <div class="selection-cards">
                  <div class="selection-card" :class="{ 'selected': data.available_goods_direct_purchase === 'yes' }">
                    <input 
                      type="radio" 
                      id="direct_purchase_yes"
                      v-model="data.available_goods_direct_purchase" 
                      value="yes"
                      class="radio-input"
                    />
                    <label for="direct_purchase_yes" class="radio-label">
                      <i class="fas fa-check-circle"></i>
                      <div class="radio-content">
                        <span class="radio-title">Ya, Tersedia</span>
                        <small>Barang dapat dibeli langsung dari stok</small>
                      </div>
                    </label>
                  </div>

                  <div class="selection-card" :class="{ 'selected': data.available_goods_direct_purchase === 'no' }">
                    <input 
                      type="radio" 
                      id="direct_purchase_no"
                      v-model="data.available_goods_direct_purchase" 
                      value="no"
                      class="radio-input"
                    />
                    <label for="direct_purchase_no" class="radio-label">
                      <i class="fas fa-times-circle"></i>
                      <div class="radio-content">
                        <span class="radio-title">Tidak Tersedia</span>
                        <small>Barang tidak tersedia untuk pembelian langsung</small>
                      </div>
                    </label>
                  </div>
                </div>
                <small class="form-text">
                  Pilih apakah perusahaan menyediakan barang untuk pembelian langsung dari inventori
                </small>
              </div>
            </div>

            <!-- Conditional Note Field -->
            <div 
              v-if="data.available_goods_direct_purchase === 'no'" 
              class="option-card animate-fade-in"
            >
              <div class="option-header">
                <i class="fas fa-sticky-note"></i>
                <h6>Additional Information</h6>
              </div>
              <div class="option-body">
                <label for="inventory_note" class="form-label">
                  <i class="fas fa-comment-alt me-1"></i>
                  Penjelasan jika tidak tersedia pembelian langsung
                </label>
                <textarea 
                  id="inventory_note"
                  v-model="data.inventory_note" 
                  rows="4" 
                  class="form-control"
                  placeholder="Jelaskan alasan mengapa pembelian langsung tidak tersedia, alternatif yang ditawarkan, atau prosedur khusus yang harus diikuti pelanggan."
                ></textarea>
                <small class="form-text">
                  Berikan penjelasan detail untuk membantu pelanggan memahami alternatif yang tersedia
                </small>
              </div>
            </div>
          </div>

          <!-- Summary Section -->
          <div v-if="data.available_goods_direct_purchase" class="summary-section">
            <div class="summary-card">
              <div class="summary-header">
                <i class="fas fa-chart-pie me-2"></i>
                <span>Ringkasan Konfigurasi Inventory</span>
              </div>
              <div class="summary-body">
                <div class="summary-grid">
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-shopping-cart" :class="data.available_goods_direct_purchase === 'yes' ? 'text-success' : 'text-warning'"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Direct Purchase</span>
                      <span class="summary-value" :class="data.available_goods_direct_purchase === 'yes' ? 'text-success' : 'text-warning'">
                        {{ data.available_goods_direct_purchase === 'yes' ? 'Tersedia' : 'Tidak Tersedia' }}
                      </span>
                    </div>
                  </div>

                  <div class="summary-item" v-if="data.inventory_note">
                    <div class="summary-icon">
                      <i class="fas fa-sticky-note text-info"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Additional Note</span>
                      <span class="summary-value text-info">Penjelasan tersedia</span>
                    </div>
                  </div>
                </div>

                <!-- Inventory Status -->
                <div class="inventory-status">
                  <div class="status-indicator" :class="getStatusClass()">
                    <i :class="getStatusIcon()"></i>
                    <div class="status-content">
                      <h6 class="status-title">{{ getStatusTitle() }}</h6>
                      <p class="status-description">{{ getStatusDescription() }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="saveInventory" 
            :disabled="loading" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Konfigurasi Inventory" }}
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

const data = ref({
  available_goods_direct_purchase: "",
  inventory_note: ""
});

watch(
  () => props.manufacture,
  (newData) => {
    if (newData && newData.inventory) {
      data.value = { ...newData.inventory };
    } else {
      data.value = { 
        available_goods_direct_purchase: "", 
        inventory_note: "" 
      };
    }
  },
  { immediate: true, deep: true }
);

function getStatusClass() {
  if (data.value.available_goods_direct_purchase === 'yes') {
    return 'status-available';
  } else if (data.value.available_goods_direct_purchase === 'no') {
    return 'status-unavailable';
  }
  return 'status-pending';
}

function getStatusIcon() {
  if (data.value.available_goods_direct_purchase === 'yes') {
    return 'fas fa-check-circle';
  } else if (data.value.available_goods_direct_purchase === 'no') {
    return 'fas fa-exclamation-triangle';
  }
  return 'fas fa-question-circle';
}

function getStatusTitle() {
  if (data.value.available_goods_direct_purchase === 'yes') {
    return 'Inventory Tersedia';
  } else if (data.value.available_goods_direct_purchase === 'no') {
    return 'Inventory Tidak Tersedia';
  }
  return 'Status Belum Ditentukan';
}

function getStatusDescription() {
  if (data.value.available_goods_direct_purchase === 'yes') {
    return 'Pelanggan dapat melakukan pembelian langsung dari inventori yang tersedia.';
  } else if (data.value.available_goods_direct_purchase === 'no') {
    return 'Pembelian langsung tidak tersedia. Pelanggan perlu mengikuti prosedur khusus.';
  }
  return 'Silakan pilih status ketersediaan barang untuk pembelian langsung.';
}

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function saveInventory() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/manufacture", {
      inventory: data.value
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    
    if (response.data.success) {
      showAlert("success", "Konfigurasi inventory berhasil disimpan!");
      emit("save", { inventory: data.value });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan konfigurasi inventory");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan konfigurasi inventory");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-inventory {
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

.inventory-options {
  display: grid;
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
  margin-bottom: 1rem;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.form-label i {
  color: #007bff;
  font-size: 0.9rem;
}

.selection-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

.selection-card {
  background: #f8f9fa;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
}

.selection-card:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.selection-card.selected {
  border-color: #007bff;
  background: linear-gradient(145deg, #e3f2fd 0%, #ffffff 100%);
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
}

.radio-input {
  position: absolute;
  opacity: 0;
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.25rem;
  cursor: pointer;
  width: 100%;
}

.radio-label i {
  font-size: 1.5rem;
  color: #6c757d;
  transition: color 0.3s ease;
  width: 30px;
  text-align: center;
}

.selection-card.selected .radio-label i {
  color: #007bff;
}

.radio-content {
  flex: 1;
}

.radio-title {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
  font-size: 1rem;
}

.radio-content small {
  color: #6c757d;
  font-size: 0.85rem;
  line-height: 1.3;
}

.form-control {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
  margin-bottom: 0.5rem;
  resize: vertical;
}

.form-control:focus {
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

.inventory-status {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  border: 1px solid #e9ecef;
}

.status-indicator {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.status-available {
  background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
  border-left: 4px solid #28a745;
}

.status-unavailable {
  background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
  border-left: 4px solid #ffc107;
}

.status-pending {
  background: linear-gradient(135deg, #e2e3e5 0%, #ffffff 100%);
  border-left: 4px solid #6c757d;
}

.status-indicator i {
  font-size: 2rem;
  width: 50px;
  text-align: center;
}

.status-available i {
  color: #28a745;
}

.status-unavailable i {
  color: #ffc107;
}

.status-pending i {
  color: #6c757d;
}

.status-content {
  flex: 1;
}

.status-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #495057;
}

.status-description {
  margin: 0;
  color: #6c757d;
  font-size: 0.9rem;
  line-height: 1.4;
}

.text-success {
  color: #28a745 !important;
}

.text-warning {
  color: #ffc107 !important;
}

.text-info {
  color: #17a2b8 !important;
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
  .manufacture-inventory {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .selection-cards {
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
  
  .status-indicator {
    flex-direction: column;
    text-align: center;
  }
  
  .status-indicator i {
    width: auto;
    margin-bottom: 0.5rem;
  }
}
</style>    