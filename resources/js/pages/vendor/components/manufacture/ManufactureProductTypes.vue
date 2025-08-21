<template>
  <div class="manufacture-product-types">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-cube text-primary me-2"></i>
          Product Types Management
          <span class="subtitle">Manage manufactured product specifications</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Product Types Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-industry"></i>
            <h6 class="section-title">Types of Product Manufactured</h6>
            <div class="section-actions">
              <button 
                @click="addProductType" 
                class="btn btn-add" 
                type="button"
              >
                <i class="fas fa-plus me-1"></i>
                Tambah Produk
              </button>
            </div>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Daftarkan jenis produk yang diproduksi oleh perusahaan Anda beserta spesifikasinya.
            </p>
          </div>

          <div v-if="productTypes.length === 0" class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>Belum ada jenis produk yang ditambahkan</p>
            <small>Klik "Tambah Produk" untuk menambahkan jenis produk baru</small>
          </div>

          <div v-else class="products-container">
            <!-- Products Table for Desktop -->
            <div class="table-responsive d-none d-md-block">
              <table class="products-table">
                <thead>
                  <tr>
                    <th class="col-no">No</th>
                    <th class="col-type">Jenis Produk</th>
                    <th class="col-size">Rentang Ukuran</th>
                    <th class="col-specialty">Spesialisasi</th>
                    <th class="col-action">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr 
                    v-for="(item, idx) in productTypes" 
                    :key="'product_' + idx"
                    class="product-row"
                  >
                    <td class="row-number">{{ idx + 1 }}</td>
                    <td>
                      <input 
                        v-model="item.type" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="Contoh: Steel Pipes, Machinery Parts"
                      />
                    </td>
                    <td>
                      <input 
                        v-model="item.size_range" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="Contoh: 10-50mm, Small to Large"
                      />
                    </td>
                    <td>
                      <input 
                        v-model="item.speciality" 
                        type="text" 
                        class="form-control table-input"
                        placeholder="Contoh: High precision, Corrosion resistant"
                      />
                    </td>
                    <td class="text-center">
                      <button 
                        @click="removeProductType(idx)" 
                        class="btn btn-delete-small" 
                        type="button"
                        title="Hapus Produk"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Products Cards for Mobile -->
            <div class="products-grid d-md-none">
              <div 
                v-for="(item, idx) in productTypes" 
                :key="'mobile_product_' + idx"
                class="product-card"
              >
                <div class="product-card-header">
                  <div class="product-card-title">
                    <i class="fas fa-box me-2"></i>
                    <span>Produk {{ idx + 1 }}</span>
                  </div>
                  <button 
                    @click="removeProductType(idx)" 
                    class="btn btn-delete-small" 
                    type="button"
                    title="Hapus Produk"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>

                <div class="product-card-body">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-tag me-1"></i>
                      Jenis Produk
                    </label>
                    <input 
                      v-model="item.type" 
                      type="text" 
                      class="form-control"
                      placeholder="Contoh: Steel Pipes, Machinery Parts"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-ruler me-1"></i>
                      Rentang Ukuran
                    </label>
                    <input 
                      v-model="item.size_range" 
                      type="text" 
                      class="form-control"
                      placeholder="Contoh: 10-50mm, Small to Large"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label">
                      <i class="fas fa-star me-1"></i>
                      Spesialisasi
                    </label>
                    <input 
                      v-model="item.speciality" 
                      type="text" 
                      class="form-control"
                      placeholder="Contoh: High precision, Corrosion resistant"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="saveProductTypes" 
            :disabled="loading" 
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Jenis Produk" }}
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
const productTypes = ref([]);

watch(
  () => props.manufacture,
  (newData) => {
    if (newData && newData.product_types) {
      productTypes.value = [...newData.product_types];
    } else {
      productTypes.value = [];
    }
  },
  { immediate: true, deep: true }
);

function addProductType() {
  productTypes.value.push({ type: "", size_range: "", speciality: "" });
}

function removeProductType(idx) {
  productTypes.value.splice(idx, 1);
}

function showAlert(type, msg) {
  alertType.value = type;
  alertMsg.value = msg;
  setTimeout(() => { alertMsg.value = ""; }, 4000);
}

async function saveProductTypes() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/manufacture", {
      product_types: productTypes.value
    }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    if (response.data.success) {
      showAlert("success", "Jenis produk berhasil disimpan!");
      emit("save", { product_types: productTypes.value });
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan jenis produk");
    }
  } catch (err) {
    showAlert("error", err.response?.data?.message || "Gagal menyimpan jenis produk");
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.manufacture-product-types {
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

.products-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e9ecef;
}

.table-responsive {
  border-radius: 12px;
  overflow: hidden;
}

.products-table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}

.products-table thead th {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  color: #495057;
  font-weight: 600;
  padding: 1rem;
  border-bottom: 2px solid #dee2e6;
  font-size: 0.95rem;
  text-align: left;
}

.col-no { width: 60px; text-align: center; }
.col-type { width: 30%; }
.col-size { width: 25%; }
.col-specialty { width: 30%; }
.col-action { width: 80px; text-align: center; }

.product-row {
  transition: all 0.3s ease;
  border-bottom: 1px solid #e9ecef;
}

.product-row:hover {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.product-row:last-child {
  border-bottom: none;
}

.products-table td {
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

.products-grid {
  display: grid;
  gap: 1.5rem;
  padding: 1rem;
}

.product-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.product-card:hover {
  border-color: #007bff;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.product-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.product-card-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
}

.product-card-title i {
  color: #007bff;
}

.product-card-body {
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
  .manufacture-product-types {
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
}
</style>