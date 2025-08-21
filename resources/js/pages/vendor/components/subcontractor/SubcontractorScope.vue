<template>
  <div class="subcontractor-scope">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-tasks text-primary me-2"></i>
          Scope of Work Management
          <span class="subtitle">Define your company's subcontracting capabilities</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Scope of Work Description Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-clipboard-list"></i>
            <h6 class="section-title">Scope of Work Description</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Jelaskan secara detail lingkup pekerjaan yang biasanya dikerjakan oleh perusahaan Anda sebagai subkontraktor. Sertakan jenis proyek, layanan khusus, dan kemampuan teknis yang dimiliki.
            </p>
          </div>

          <div class="scope-input-card">
            <div class="scope-input-header">
              <i class="fas fa-edit"></i>
              <h6>Detailed Work Scope</h6>
              <div class="character-indicator">
                <span class="character-count" :class="getCharacterCountClass()">
                  {{ characterCount }}
                </span>
                <span class="character-max">/ {{ maxCharacters }}</span>
              </div>
            </div>
            <div class="scope-input-body">
              <div class="textarea-container">
                <textarea
                  v-model="formData.scope_of_work"
                  rows="15"
                  class="scope-textarea"
                  placeholder="Jelaskan secara detail lingkup pekerjaan yang biasanya dikerjakan perusahaan Anda sebagai subkontraktor. Sertakan informasi berikut:

• Jenis proyek konstruksi yang dikerjakan
• Layanan spesifik yang disediakan
• Bidang keahlian utama
• Kemampuan teknis dan teknologi
• Skala dan kompleksitas proyek
• Cakupan geografis
• Peralatan atau metodologi khusus yang digunakan

Contoh:
- Instalasi listrik untuk proyek transmisi tenaga
- Konstruksi sipil untuk pondasi gardu induk
- Pemasangan peralatan mekanik pembangkit listrik
- Instalasi sistem instrumentasi dan kontrol
- Layanan testing dan commissioning
- Maintenance dan repair equipment
- Fabrikasi struktur baja dan equipment"
                ></textarea>
                
                <div class="textarea-tools">
                  <div class="writing-suggestions">
                    <h6 class="suggestions-title">
                      <i class="fas fa-lightbulb me-2"></i>
                      Tips Penulisan:
                    </h6>
                    <ul class="suggestions-list">
                      <li>Gunakan bullet points untuk kemudahan membaca</li>
                      <li>Sertakan pengalaman proyek yang spesifik</li>
                      <li>Cantumkan sertifikasi atau standar yang dimiliki</li>
                      <li>Jelaskan value proposition yang unik</li>
                      <li>Sebutkan kapasitas dan kapabilitas tim</li>
                    </ul>
                  </div>

                  <div class="scope-categories-reference">
                    <h6 class="reference-title">
                      <i class="fas fa-list-alt me-2"></i>
                      Kategori Umum:
                    </h6>
                    <div class="category-tags">
                      <span 
                        v-for="category in scopeCategories" 
                        :key="category.id"
                        class="category-tag"
                        @click="insertCategoryTemplate(category)"
                        :title="'Klik untuk menambahkan template: ' + category.description"
                      >
                        <i class="fas fa-plus-circle"></i>
                        {{ category.title }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="scope-status">
                <div class="status-indicator" :class="getScopeStatusClass()">
                  <i :class="getScopeStatusIcon()"></i>
                  <div class="status-content">
                    <span class="status-title">{{ getScopeStatusTitle() }}</span>
                    <small class="status-description">{{ getScopeStatusDescription() }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Preview Section -->
        <div v-if="formData.scope_of_work.trim()" class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-eye"></i>
            <h6 class="section-title">Preview & Summary</h6>
          </div>

          <div class="preview-card">
            <div class="preview-header">
              <i class="fas fa-file-alt me-2"></i>
              <span>Scope of Work Preview</span>
            </div>
            <div class="preview-body">
              <div class="preview-content">
                {{ formData.scope_of_work }}
              </div>
              
              <div class="preview-stats">
                <div class="stat-grid">
                  <div class="stat-item">
                    <i class="fas fa-align-left"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getWordCount() }}</span>
                      <span class="stat-label">Kata</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <i class="fas fa-paragraph"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getParagraphCount() }}</span>
                      <span class="stat-label">Paragraf</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <i class="fas fa-list-ul"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getBulletPointCount() }}</span>
                      <span class="stat-label">Bullet Points</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getReadingTime() }}</span>
                      <span class="stat-label">Menit Baca</span>
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
            @click="saveScope"
            :disabled="loading || !formData.scope_of_work.trim()"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Scope of Work" }}
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
});

const emit = defineEmits(["updated"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");
const maxCharacters = 2000;

const formData = reactive({
  scope_of_work: "",
  additional_notes: "",
});

const selectedCategories = ref([]);

const scopeCategories = [
  {
    id: "electrical_installation",
    title: "Electrical Installation",
    description: "Power distribution, transmission lines, substation electrical work",
    template: "• Instalasi sistem kelistrikan untuk proyek transmisi tenaga\n• Pemasangan peralatan distribusi listrik\n• Pekerjaan electrical substation"
  },
  {
    id: "civil_construction",
    title: "Civil Construction", 
    description: "Foundations, structures, roads, drainage systems",
    template: "• Konstruksi pondasi dan struktur beton\n• Pembangunan jalan dan sistem drainase\n• Pekerjaan earthwork dan site preparation"
  },
  {
    id: "mechanical_installation",
    title: "Mechanical Installation",
    description: "Equipment installation, piping, HVAC systems", 
    template: "• Instalasi peralatan mekanik dan piping\n• Pemasangan sistem HVAC\n• Assembly dan erection equipment"
  },
  {
    id: "instrumentation",
    title: "Instrumentation & Control",
    description: "Control systems, automation, monitoring equipment",
    template: "• Instalasi sistem instrumentasi dan kontrol\n• Pemasangan automation equipment\n• Programming dan commissioning control systems"
  },
  {
    id: "testing_commissioning", 
    title: "Testing & Commissioning",
    description: "System testing, performance verification, startup services",
    template: "• Testing dan commissioning sistem\n• Performance verification dan optimization\n• Startup services dan training operator"
  },
  {
    id: "maintenance",
    title: "Maintenance Services",
    description: "Preventive maintenance, repairs, overhauls",
    template: "• Preventive dan corrective maintenance\n• Overhaul dan repair equipment\n• 24/7 maintenance support services"
  }
];

// Computed properties
const characterCount = computed(() => {
  return formData.scope_of_work.length;
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

  formData.scope_of_work = data.scope_of_work || "";
  formData.additional_notes = data.additional_notes || "";

  if (data.scope_categories && Array.isArray(data.scope_categories)) {
    selectedCategories.value = [...data.scope_categories];
  } else {
    selectedCategories.value = [];
  }
}

function getCharacterCountClass() {
  const percentage = (characterCount.value / maxCharacters) * 100;
  if (percentage >= 90) return 'text-danger';
  if (percentage >= 75) return 'text-warning';
  return 'text-primary';
}

function getScopeStatusClass() {
  const length = formData.scope_of_work.trim().length;
  if (length === 0) return 'status-empty';
  if (length < 200) return 'status-minimal';
  if (length < 500) return 'status-basic';
  if (length < 1000) return 'status-good';
  return 'status-excellent';
}

function getScopeStatusIcon() {
  const length = formData.scope_of_work.trim().length;
  if (length === 0) return 'fas fa-exclamation-circle';
  if (length < 200) return 'fas fa-info-circle';
  if (length < 500) return 'fas fa-check-circle';
  if (length < 1000) return 'fas fa-thumbs-up';
  return 'fas fa-star';
}

function getScopeStatusTitle() {
  const length = formData.scope_of_work.trim().length;
  if (length === 0) return 'Belum Ada Deskripsi';
  if (length < 200) return 'Deskripsi Minimal';
  if (length < 500) return 'Deskripsi Dasar';
  if (length < 1000) return 'Deskripsi Baik';
  return 'Deskripsi Sangat Lengkap';
}

function getScopeStatusDescription() {
  const length = formData.scope_of_work.trim().length;
  if (length === 0) return 'Mulai menulis deskripsi scope of work Anda';
  if (length < 200) return 'Tambahkan lebih banyak detail untuk memperjelas scope';
  if (length < 500) return 'Scope sudah cukup jelas, bisa ditambahkan contoh spesifik';
  if (length < 1000) return 'Deskripsi sudah baik dan informatif';
  return 'Deskripsi sangat lengkap dan komprehensif';
}

function getWordCount() {
  return formData.scope_of_work.trim().split(/\s+/).filter(word => word.length > 0).length;
}

function getParagraphCount() {
  return formData.scope_of_work.trim().split(/\n\s*\n/).filter(para => para.trim().length > 0).length;
}

function getBulletPointCount() {
  return (formData.scope_of_work.match(/[•\-\*]/g) || []).length;
}

function getReadingTime() {
  const wordsPerMinute = 200;
  const words = getWordCount();
  return Math.max(1, Math.ceil(words / wordsPerMinute));
}

function insertCategoryTemplate(category) {
  if (formData.scope_of_work.trim()) {
    formData.scope_of_work += '\n\n' + category.template;
  } else {
    formData.scope_of_work = category.template;
  }
}

async function saveScope() {
  if (!formData.scope_of_work.trim()) {
    showAlert("warning", "Mohon berikan deskripsi scope of work perusahaan Anda");
    return;
  }

  if (formData.scope_of_work.length > maxCharacters) {
    showAlert("warning", `Deskripsi scope of work tidak boleh melebihi ${maxCharacters} karakter`);
    return;
  }

  loading.value = true;

  try {
    const dataToSave = {
      scope_of_work: formData.scope_of_work.trim(),
      additional_notes: formData.additional_notes.trim(),
      scope_categories: selectedCategories.value,
    };

    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/subcontractor", dataToSave, {
      headers: { Authorization: `Bearer ${token}` },
    });

    if (response.data.success) {
      showAlert("success", "Scope of work berhasil disimpan!");
      emit("updated", response.data.data.subcontractor_data);
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan scope of work");
    }
  } catch (error) {
    console.error("Save scope error:", error);
    showAlert("error", error.response?.data?.message || "Gagal menyimpan scope of work");
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
.subcontractor-scope {
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

.scope-input-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.scope-input-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.scope-input-card:hover::before {
  left: 100%;
}

.scope-input-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.scope-input-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.scope-input-header h6 {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.scope-input-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.character-indicator {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.85rem;
}

.character-count {
  font-weight: 700;
  transition: color 0.3s ease;
}

.character-max {
  color: #6c757d;
}

.text-primary { color: #007bff !important; }
.text-warning { color: #ffc107 !important; }
.text-danger { color: #dc3545 !important; }

.scope-input-body {
  padding: 1.5rem;
}

.textarea-container {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.scope-textarea {
  padding: 1rem;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 0.95rem;
  line-height: 1.6;
  resize: vertical;
  font-family: inherit;
  transition: all 0.3s ease;
  background: #f8f9fa;
  min-height: 400px;
}

.scope-textarea:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.textarea-tools {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.writing-suggestions {
  background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
  border: 1px solid #ffeaa7;
  border-radius: 8px;
  padding: 1rem;
}

.suggestions-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: #856404;
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
}

.suggestions-title i {
  color: #f39c12;
}

.suggestions-list {
  margin: 0;
  padding-left: 1.2rem;
  color: #856404;
  font-size: 0.8rem;
  line-height: 1.5;
}

.suggestions-list li {
  margin-bottom: 0.25rem;
}

.scope-categories-reference {
  background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);
  border: 1px solid #b3d9ff;
  border-radius: 8px;
  padding: 1rem;
}

.reference-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: #0056b3;
  margin-bottom: 0.75rem;
  display: flex;
  align-items: center;
}

.reference-title i {
  color: #007bff;
}

.category-tags {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.category-tag {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  background: white;
  border: 1px solid #007bff;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
  color: #007bff;
  cursor: pointer;
  transition: all 0.3s ease;
}

.category-tag:hover {
  background: #007bff;
  color: white;
  transform: translateX(5px);
}

.category-tag i {
  font-size: 0.7rem;
}

.scope-status {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 1rem;
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

.status-empty {
  background: linear-gradient(135deg, #f8d7da 0%, #ffffff 100%);
  border-left: 4px solid #dc3545;
}

.status-minimal {
  background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
  border-left: 4px solid #ffc107;
}

.status-basic {
  background: linear-gradient(135deg, #d1ecf1 0%, #ffffff 100%);
  border-left: 4px solid #17a2b8;
}

.status-good {
  background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
  border-left: 4px solid #28a745;
}

.status-excellent {
  background: linear-gradient(135deg, #e2e3e5 0%, #ffffff 100%);
  border-left: 4px solid #6c757d;
}

.status-indicator i {
  font-size: 1.5rem;
  width: 30px;
  text-align: center;
}

.status-empty i { color: #dc3545; }
.status-minimal i { color: #ffc107; }
.status-basic i { color: #17a2b8; }
.status-good i { color: #28a745; }
.status-excellent i { color: #6c757d; }

.status-content {
  flex: 1;
}

.status-title {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
  display: block;
}

.status-description {
  color: #6c757d;
  font-size: 0.85rem;
}

.preview-card {
  background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1.5rem;
  border-left: 4px solid #28a745;
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.preview-header {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}

.preview-header i {
  color: #28a745;
}

.preview-content {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  border: 1px solid #e9ecef;
  margin-bottom: 1.5rem;
  white-space: pre-wrap;
  line-height: 1.6;
  color: #495057;
  font-size: 0.9rem;
  max-height: 300px;
  overflow-y: auto;
}

.preview-stats {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
}

.stat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 1rem;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.stat-item i {
  font-size: 1.2rem;
  color: #007bff;
  width: 20px;
  text-align: center;
}

.stat-content {
  flex: 1;
}

.stat-number {
  display: block;
  font-size: 1.2rem;
  font-weight: 700;
  color: #495057;
  line-height: 1;
}

.stat-label {
  font-size: 0.75rem;
  color: #6c757d;
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
  .subcontractor-scope {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .textarea-container {
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
  
  .stat-grid {
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  }
  
  .scope-textarea {
    min-height: 300px;
  }
}
</style>