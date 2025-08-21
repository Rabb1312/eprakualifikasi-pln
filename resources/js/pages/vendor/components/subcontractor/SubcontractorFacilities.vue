<template>
  <div class="subcontractor-facilities">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-tools text-primary me-2"></i>
          Facilities Management
          <span class="subtitle">Configure company facilities and capabilities</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Facilities Selection Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-cogs"></i>
            <h6 class="section-title">Available Facilities</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Pilih semua fasilitas yang tersedia di perusahaan Anda. Anda dapat memilih lebih dari satu opsi untuk menunjukkan kapabilitas lengkap perusahaan.
            </p>
          </div>

          <div class="facilities-container">
            <div class="facilities-grid">
              <div
                v-for="(label, key) in facilitiesOptions"
                :key="key"
                class="facility-card"
                :class="{ 'selected': selectedFacilities.includes(key) }"
              >
                <label class="facility-checkbox">
                  <input
                    type="checkbox"
                    :value="key"
                    v-model="selectedFacilities"
                    class="checkbox-input"
                  />
                  <div class="facility-content">
                    <div class="facility-icon">
                      <i :class="getFacilityIcon(key)"></i>
                    </div>
                    <div class="facility-info">
                      <span class="facility-label">{{ label }}</span>
                      <div class="facility-checkmark">
                        <i class="fas fa-check"></i>
                      </div>
                    </div>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Other Services Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-plus-circle"></i>
            <h6 class="section-title">Additional Services</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Jelaskan layanan atau fasilitas tambahan lainnya yang tidak termasuk dalam daftar di atas.
            </p>
          </div>

          <div class="other-services-card">
            <div class="other-services-header">
              <i class="fas fa-edit"></i>
              <h6>Other Services Description</h6>
            </div>
            <div class="other-services-body">
              <label for="other_services" class="form-label">
                <i class="fas fa-list-ul me-1"></i>
                Layanan Tambahan Lainnya
              </label>
              <textarea
                id="other_services"
                v-model="formData.other_services"
                rows="5"
                class="form-control"
                placeholder="Jelaskan layanan atau fasilitas tambahan yang tersedia di perusahaan Anda yang tidak tercantum dalam daftar di atas. Contoh:
• Layanan konsultasi teknis
• Pelatihan khusus untuk operator
• Sertifikasi custom sesuai kebutuhan
• Maintenance dan support 24/7"
              ></textarea>
              <small class="form-text">
                Berikan detail lengkap untuk membantu pelanggan memahami value added services yang Anda tawarkan
              </small>
            </div>
          </div>
        </div>

        <!-- Selected Facilities Summary -->
        <div v-if="selectedFacilities.length > 0" class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <h6 class="section-title">Facilities Summary</h6>
          </div>

          <div class="summary-card">
            <div class="summary-header">
              <i class="fas fa-clipboard-check me-2"></i>
              <span>Selected Facilities Overview</span>
            </div>
            <div class="summary-body">
              <div class="summary-stats">
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                  </div>
                  <div class="stat-content">
                    <span class="stat-number">{{ selectedFacilities.length }}</span>
                    <span class="stat-label">Fasilitas Dipilih</span>
                  </div>
                </div>
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                  </div>
                  <div class="stat-content">
                    <span class="stat-number">{{ coveragePercentage }}%</span>
                    <span class="stat-label">Coverage</span>
                  </div>
                </div>
              </div>

              <div class="selected-facilities-tags">
                <h6 class="tags-title">
                  <i class="fas fa-tags me-2"></i>
                  Fasilitas yang Dipilih:
                </h6>
                <div class="tags-container">
                  <span
                    v-for="facility in selectedFacilities"
                    :key="facility"
                    class="facility-tag"
                  >
                    <i :class="getFacilityIcon(facility)"></i>
                    {{ facilitiesOptions[facility] }}
                  </span>
                </div>
              </div>

              <div v-if="formData.other_services" class="additional-services">
                <h6 class="additional-title">
                  <i class="fas fa-plus me-2"></i>
                  Layanan Tambahan:
                </h6>
                <div class="additional-content">
                  {{ formData.other_services.substring(0, 200) }}{{ formData.other_services.length > 200 ? '...' : '' }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="selectedFacilities.length === 0" class="empty-state">
          <i class="fas fa-tools"></i>
          <p>Belum ada fasilitas yang dipilih</p>
          <small>Pilih fasilitas yang tersedia di perusahaan Anda dari daftar di atas</small>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button
            @click="saveFacilities"
            :disabled="loading"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Informasi Fasilitas" }}
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
import { ref, reactive, watch, computed } from "vue";
import axios from "axios";
import AlertComponent from "../AlertComponent.vue";

const props = defineProps({
  subcontractorData: Object,
  facilitiesOptions: Object,
});

const emit = defineEmits(["updated"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const formData = reactive({
  other_services: "",
});

const selectedFacilities = ref([]);

// Computed properties
const coveragePercentage = computed(() => {
  const totalOptions = Object.keys(props.facilitiesOptions || {}).length;
  if (totalOptions === 0) return 0;
  return Math.round((selectedFacilities.value.length / totalOptions) * 100);
});

watch(
  () => props.subcontractorData,
  (newData) => {
    if (newData) {
      initializeFormData();
    }
  },
  { immediate: true, deep: true }
);

function initializeFormData() {
  if (!props.subcontractorData) return;

  const data = props.subcontractorData;

  formData.other_services = data.other_services || "";

  // Initialize selected facilities
  if (data.facilities && Array.isArray(data.facilities)) {
    selectedFacilities.value = [...data.facilities];
  } else {
    selectedFacilities.value = [];
  }
}

function getFacilityIcon(facilityKey) {
  // Map facility keys to appropriate icons
  const iconMap = {
    'welding': 'fas fa-fire',
    'machining': 'fas fa-cog',
    'fabrication': 'fas fa-hammer',
    'assembly': 'fas fa-puzzle-piece',
    'painting': 'fas fa-paint-brush',
    'testing': 'fas fa-microscope',
    'logistics': 'fas fa-truck',
    'engineering': 'fas fa-drafting-compass',
    'quality_control': 'fas fa-check-double',
    'maintenance': 'fas fa-wrench'
  };
  
  // Check if the facility key contains certain keywords and return appropriate icon
  const key = facilityKey.toLowerCase();
  if (key.includes('weld')) return 'fas fa-fire';
  if (key.includes('machine')) return 'fas fa-cog';
  if (key.includes('paint')) return 'fas fa-paint-brush';
  if (key.includes('test')) return 'fas fa-microscope';
  if (key.includes('transport') || key.includes('logistic')) return 'fas fa-truck';
  if (key.includes('engineer')) return 'fas fa-drafting-compass';
  if (key.includes('quality')) return 'fas fa-check-double';
  if (key.includes('maintenance')) return 'fas fa-wrench';
  if (key.includes('assembly')) return 'fas fa-puzzle-piece';
  if (key.includes('fabricat')) return 'fas fa-hammer';
  
  return iconMap[facilityKey] || 'fas fa-tools';
}

async function saveFacilities() {
  loading.value = true;

  try {
    const dataToSave = {
      facilities: selectedFacilities.value,
      other_services: formData.other_services,
    };

    const token = localStorage.getItem("token");
    const response = await axios.put(
      "/api/vendor/subcontractor",
      dataToSave,
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );

    if (response.data.success) {
      showAlert("success", "Informasi fasilitas berhasil disimpan!");
      emit("updated", response.data.data.subcontractor_data);
    } else {
      showAlert(
        "error",
        response.data.message || "Gagal menyimpan informasi fasilitas"
      );
    }
  } catch (error) {
    console.error("Save facilities error:", error);
    showAlert(
      "error",
      error.response?.data?.message ||
        "Gagal menyimpan informasi fasilitas"
    );
  } finally {
    loading.value = false;
  }
}

function showAlert(type, message) {
  alertType.value = type;
  alertMsg.value = message;
  setTimeout(() => {
    alertMsg.value = "";
  }, 5000);
}
</script>

<style scoped>
.subcontractor-facilities {
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

.facilities-container {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  border: 1px solid #e9ecef;
}

.facilities-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}

.facility-card {
  background: #f8f9fa;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}

.facility-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.facility-card:hover::before {
  left: 100%;
}

.facility-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
}

.facility-card.selected {
  border-color: #007bff;
  background: linear-gradient(145deg, #e3f2fd 0%, #ffffff 100%);
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
}

.facility-checkbox {
  display: block;
  cursor: pointer;
  width: 100%;
}

.checkbox-input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.facility-content {
  display: flex;
  align-items: center;
  padding: 1.5rem;
  gap: 1rem;
}

.facility-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #6c757d;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.facility-card.selected .facility-icon {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
}

.facility-info {
  flex: 1;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.facility-label {
  font-weight: 600;
  color: #495057;
  line-height: 1.4;
}

.facility-checkmark {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.facility-card.selected .facility-checkmark {
  background: #28a745;
  color: white;
}

.facility-checkmark i {
  font-size: 0.8rem;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.facility-card.selected .facility-checkmark i {
  opacity: 1;
}

.other-services-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.other-services-card:hover {
  border-color: #007bff;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.other-services-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.other-services-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.other-services-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.other-services-body {
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

.form-control {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
  resize: vertical;
  margin-bottom: 0.5rem;
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

.summary-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  color: #28a745;
}

.stat-content {
  flex: 1;
}

.stat-number {
  display: block;
  font-size: 1.5rem;
  font-weight: 700;
  color: #28a745;
  line-height: 1;
}

.stat-label {
  font-size: 0.85rem;
  color: #6c757d;
}

.selected-facilities-tags {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
  margin-bottom: 1rem;
}

.tags-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.facility-tag {
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

.facility-tag i {
  font-size: 0.8rem;
}

.additional-services {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
}

.additional-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
}

.additional-content {
  color: #6c757d;
  font-size: 0.9rem;
  line-height: 1.5;
  font-style: italic;
}

.empty-state {
  text-align: center;
  padding: 3rem 2rem;
  color: #6c757d;
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 12px;
  border: 2px dashed #dee2e6;
  margin-bottom: 2rem;
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
  .subcontractor-facilities {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .facilities-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
  
  .facilities-grid {
    grid-template-columns: 1fr;
  }
  
  .summary-stats {
    grid-template-columns: 1fr;
  }
  
  .tags-container {
    flex-direction: column;
    align-items: stretch;
  }
  
  .facility-tag {
    justify-content: center;
  }
}
</style>