<template>
  <div class="subcontractor-knowledge">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-brain text-primary me-2"></i>
          Regulatory Knowledge Assessment
          <span class="subtitle">Evaluate local regulation awareness and compliance</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Knowledge Assessment Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-scale-balanced"></i>
            <h6 class="section-title">Local Regulation Knowledge</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Evaluasi tingkat kesadaran dan pengetahuan perusahaan Anda mengenai hukum, kebijakan, dan peraturan daerah setempat yang berlaku dalam industri konstruksi.
            </p>
          </div>

          <div class="knowledge-question-card">
            <div class="question-header">
              <i class="fas fa-question-circle"></i>
              <h6>Regulatory Awareness Assessment</h6>
            </div>
            <div class="question-body">
              <div class="question-text">
                <p class="question-main">
                  Apakah perusahaan Anda memiliki kesadaran dan pengetahuan mengenai hukum, kebijakan, dan peraturan daerah setempat?
                </p>
                <small class="question-sub">
                  Does your company aware and knowledgeable about local region regulation, policy, and law?
                </small>
              </div>

              <div class="knowledge-options">
                <div class="option-card" :class="{ 'selected': formData.local_regulation_knowledge === 'yes' }">
                  <label class="option-label">
                    <input
                      type="radio"
                      value="yes"
                      v-model="formData.local_regulation_knowledge"
                      class="radio-input"
                    />
                    <div class="option-content">
                      <div class="option-icon">
                        <i class="fas fa-check-circle"></i>
                      </div>
                      <div class="option-info">
                        <span class="option-title">Ya, Kami Berpengetahuan</span>
                        <span class="option-subtitle">Yes, we are knowledgeable</span>
                        <small class="option-description">
                          Perusahaan memiliki pemahaman yang baik tentang regulasi lokal dan menerapkannya dalam operasional
                        </small>
                      </div>
                      <div class="option-checkmark">
                        <i class="fas fa-check"></i>
                      </div>
                    </div>
                  </label>
                </div>

                <!-- <div class="option-card disabled-option">
                  <div class="option-content">
                    <div class="option-icon">
                      <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="option-info">
                      <span class="option-title">Tidak, Memerlukan Bimbingan</span>
                      <span class="option-subtitle">No, we need guidance</span>
                      <small class="option-description">
                        Opsi ini sementara tidak tersedia
                      </small>
                    </div>
                    <div class="disabled-indicator">
                      <i class="fas fa-lock"></i>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>

        <!-- Knowledge Details Section -->
        <div v-if="formData.local_regulation_knowledge === 'yes'" class="section-wrapper animate-fade-in">
          <div class="section-header">
            <i class="fas fa-clipboard-list"></i>
            <h6 class="section-title">Knowledge Details & Documentation</h6>
          </div>

          <div class="knowledge-details-card">
            <div class="details-header">
              <i class="fas fa-edit"></i>
              <h6>Detailed Knowledge Assessment</h6>
            </div>
            <div class="details-body">
              <div class="textarea-container">
                <label for="regulation_knowledge_details" class="form-label">
                  <i class="fas fa-file-alt me-1"></i>
                  Pengetahuan dan Pemahaman Regulasi
                </label>
                <textarea
                  id="regulation_knowledge_details"
                  v-model="formData.regulation_knowledge_details"
                  rows="10"
                  class="form-control knowledge-textarea"
                  :placeholder="getPlaceholderText()"
                ></textarea>
                <div class="character-counter">
                  <span>{{ formData.regulation_knowledge_details.length }} karakter</span>
                </div>
              </div>

              <div class="knowledge-guidelines">
                <div class="guidelines-card">
                  <div class="guidelines-header">
                    <i class="fas fa-lightbulb text-warning"></i>
                    <span>Panduan Pengisian</span>
                  </div>
                  <div class="guidelines-content">
                    <h6>Sertakan informasi berikut dalam penjelasan Anda:</h6>
                    <ul class="guidelines-list">
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Regulasi spesifik yang Anda pahami dan terapkan</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Cara perusahaan mengikuti perkembangan regulasi terbaru</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Contoh implementasi kepatuhan dalam operasional</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Program pelatihan staff terkait regulasi</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Sertifikasi atau izin yang dimiliki perusahaan</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Tantangan yang dihadapi dalam kepatuhan regulasi</span>
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="regulation-categories">
                  <div class="categories-header">
                    <i class="fas fa-tags text-info"></i>
                    <span>Kategori Regulasi Umum</span>
                  </div>
                  <div class="categories-content">
                    <div class="category-tags">
                      <div class="category-tag" v-for="category in regulationCategories" :key="category.id">
                        <i :class="category.icon"></i>
                        <div class="tag-content">
                          <span class="tag-title">{{ category.title }}</span>
                          <small class="tag-description">{{ category.description }}</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Knowledge Status Section -->
        <div v-if="formData.local_regulation_knowledge" class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-chart-line"></i>
            <h6 class="section-title">Knowledge Assessment Summary</h6>
          </div>

          <div class="status-card">
            <div class="status-header">
              <i class="fas fa-clipboard-check me-2"></i>
              <span>Assessment Status Overview</span>
            </div>
            <div class="status-body">
              <div class="status-grid">
                <div class="status-item">
                  <div class="status-icon">
                    <i class="fas fa-brain text-primary"></i>
                  </div>
                  <div class="status-content">
                    <span class="status-label">Knowledge Level</span>
                    <span class="status-value text-primary">
                      {{ formData.local_regulation_knowledge === 'yes' ? 'Berpengetahuan' : 'Memerlukan Bimbingan' }}
                    </span>
                  </div>
                </div>

                <div class="status-item">
                  <div class="status-icon">
                    <i class="fas fa-file-alt text-info"></i>
                  </div>
                  <div class="status-content">
                    <span class="status-label">Documentation</span>
                    <span class="status-value text-info">
                      {{ formData.regulation_knowledge_details.trim() ? 'Tersedia' : 'Belum Diisi' }}
                    </span>
                  </div>
                </div>

                <div class="status-item">
                  <div class="status-icon">
                    <i class="fas fa-percentage text-success"></i>
                  </div>
                  <div class="status-content">
                    <span class="status-label">Completion</span>
                    <span class="status-value text-success">{{ getCompletionPercentage() }}%</span>
                  </div>
                </div>

                <div class="status-item">
                  <div class="status-icon">
                    <i class="fas fa-shield-check text-warning"></i>
                  </div>
                  <div class="status-content">
                    <span class="status-label">Compliance Status</span>
                    <span class="status-value text-warning">{{ getComplianceStatus() }}</span>
                  </div>
                </div>
              </div>

              <div v-if="formData.regulation_knowledge_details.trim()" class="knowledge-preview">
                <div class="preview-header">
                  <i class="fas fa-eye me-2"></i>
                  <span>Knowledge Details Preview</span>
                </div>
                <div class="preview-content">
                  {{ formData.regulation_knowledge_details.substring(0, 300) }}
                  {{ formData.regulation_knowledge_details.length > 300 ? '...' : '' }}
                </div>
                <div class="preview-stats">
                  <span class="stat-item">
                    <i class="fas fa-align-left"></i>
                    {{ getWordCount() }} kata
                  </span>
                  <span class="stat-item">
                    <i class="fas fa-paragraph"></i>
                    {{ getParagraphCount() }} paragraf
                  </span>
                  <span class="stat-item">
                    <i class="fas fa-clock"></i>
                    {{ getReadingTime() }} menit baca
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button
            @click="saveKnowledge"
            :disabled="loading || !isFormValid()"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Assessment Pengetahuan" }}
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

const props = defineProps({
  subcontractorData: Object,
});

const emit = defineEmits(["updated"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const formData = reactive({
  local_regulation_knowledge: "",
  regulation_knowledge_details: "",
  staff_training: "",
  certifications_held: "",
  guidance_needed: "",
});

const regulationCategories = [
  {
    id: "environmental",
    title: "AMDAL & Lingkungan",
    description: "Analisis dampak lingkungan, UKL-UPL",
    icon: "fas fa-leaf"
  },
  {
    id: "safety",
    title: "K3 & SMK3",
    description: "Keselamatan kerja, sistem manajemen K3",
    icon: "fas fa-hard-hat"
  },
  {
    id: "construction",
    title: "IMB & Perizinan",
    description: "Izin mendirikan bangunan, zonasi",
    icon: "fas fa-building"
  },
  {
    id: "labor",
    title: "Ketenagakerjaan",
    description: "UU Ketenagakerjaan, jamsostek",
    icon: "fas fa-users"
  },
  {
    id: "tax",
    title: "Perpajakan",
    description: "Pajak perusahaan, PPN, pajak daerah",
    icon: "fas fa-receipt"
  },
  {
    id: "electrical",
    title: "SNI Kelistrikan",
    description: "Standar nasional, keamanan listrik",
    icon: "fas fa-bolt"
  }
];

const knowledgeRatings = ref({});
const selectedRegulations = ref([]);
const selectedTrainingMethods = ref([]);

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

  formData.local_regulation_knowledge = data.local_regulation_knowledge || "";
  formData.regulation_knowledge_details = data.regulation_knowledge_details || "";
  formData.staff_training = data.staff_training || "";
  formData.certifications_held = data.certifications_held || "";
  formData.guidance_needed = data.guidance_needed || "";

  if (data.knowledge_ratings && typeof data.knowledge_ratings === "object") {
    knowledgeRatings.value = { ...data.knowledge_ratings };
  }

  if (data.selected_regulations && Array.isArray(data.selected_regulations)) {
    selectedRegulations.value = [...data.selected_regulations];
  }

  if (data.selected_training_methods && Array.isArray(data.selected_training_methods)) {
    selectedTrainingMethods.value = [...data.selected_training_methods];
  }
}

function getPlaceholderText() {
  return `Jelaskan secara detail pengetahuan dan pemahaman perusahaan Anda tentang regulasi lokal:

• Regulasi Spesifik yang Dipahami:
  - Sebutkan regulasi, undang-undang, atau peraturan daerah yang perusahaan pahami
  - Contoh: AMDAL, UKL-UPL, SMK3, IMB, UU Ketenagakerjaan, dll.

• Cara Mengikuti Perkembangan Regulasi:
  - Sumber informasi yang digunakan (website resmi, konsultan, asosiasi)
  - Frekuensi update pengetahuan regulasi

• Implementasi Kepatuhan:
  - Contoh konkret penerapan regulasi dalam operasional
  - Prosedur internal yang dibuat untuk memastikan kepatuhan

• Program Pelatihan Staff:
  - Pelatihan regulasi yang pernah diikuti oleh karyawan
  - Frekuensi dan materi pelatihan

• Sertifikasi dan Izin:
  - Daftar sertifikasi yang dimiliki perusahaan
  - Izin-izin yang aktif dan masa berlakunya

• Tantangan dan Solusi:
  - Kesulitan yang dihadapi dalam memahami atau menerapkan regulasi
  - Cara mengatasi tantangan tersebut`;
}

function getCompletionPercentage() {
  let completion = 0;
  if (formData.local_regulation_knowledge) completion += 50;
  if (formData.regulation_knowledge_details.trim()) completion += 50;
  return completion;
}

function getComplianceStatus() {
  if (formData.local_regulation_knowledge === 'yes' && formData.regulation_knowledge_details.trim()) {
    return 'Compliant';
  } else if (formData.local_regulation_knowledge === 'yes') {
    return 'Partial';
  }
  return 'Pending';
}

function getWordCount() {
  return formData.regulation_knowledge_details.trim().split(/\s+/).filter(word => word.length > 0).length;
}

function getParagraphCount() {
  return formData.regulation_knowledge_details.trim().split(/\n\s*\n/).filter(para => para.trim().length > 0).length;
}

function getReadingTime() {
  const wordsPerMinute = 200;
  const words = getWordCount();
  return Math.max(1, Math.ceil(words / wordsPerMinute));
}

function isFormValid() {
  return formData.local_regulation_knowledge && 
         (formData.local_regulation_knowledge === 'no' || formData.regulation_knowledge_details.trim());
}

async function saveKnowledge() {
  if (!formData.local_regulation_knowledge) {
    showAlert("warning", "Mohon pilih tingkat pengetahuan perusahaan mengenai regulasi lokal");
    return;
  }

  if (formData.local_regulation_knowledge === "yes" && !formData.regulation_knowledge_details.trim()) {
    showAlert("warning", "Mohon berikan detail tentang pengetahuan regulasi perusahaan Anda");
    return;
  }

  loading.value = true;

  try {
    const dataToSave = {
      local_regulation_knowledge: formData.local_regulation_knowledge,
      regulation_knowledge_details: formData.regulation_knowledge_details.trim(),
      staff_training: formData.staff_training.trim(),
      certifications_held: formData.certifications_held.trim(),
      guidance_needed: formData.guidance_needed.trim(),
      knowledge_ratings: knowledgeRatings.value,
      selected_regulations: selectedRegulations.value,
      selected_training_methods: selectedTrainingMethods.value,
    };

    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/subcontractor", dataToSave, {
      headers: { Authorization: `Bearer ${token}` },
    });

    if (response.data.success) {
      showAlert("success", "Assessment pengetahuan berhasil disimpan!");
      emit("updated", response.data.data.subcontractor_data);
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan assessment pengetahuan");
    }
  } catch (error) {
    console.error("Save knowledge error:", error);
    showAlert("error", error.response?.data?.message || "Gagal menyimpan assessment pengetahuan");
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
.subcontractor-knowledge {
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

.knowledge-question-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.knowledge-question-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.knowledge-question-card:hover::before {
  left: 100%;
}

.knowledge-question-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.question-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.question-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.question-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.question-body {
  padding: 1.5rem;
}

.question-text {
  margin-bottom: 2rem;
}

.question-main {
  font-size: 1.1rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  line-height: 1.4;
}

.question-sub {
  color: #6c757d;
  font-style: italic;
  font-size: 0.9rem;
}

.knowledge-options {
  display: grid;
  gap: 1.5rem;
}

.option-card {
  background: #f8f9fa;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  overflow: hidden;
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
  background: #e3f2fd;
}

.option-card.selected {
  border-color: #007bff;
  background: linear-gradient(145deg, #e3f2fd 0%, #ffffff 100%);
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
}

.disabled-option {
  opacity: 0.6;
  cursor: not-allowed;
  background: #e9ecef;
}

.disabled-option:hover {
  border-color: #e9ecef;
  background: #e9ecef;
}

.option-label {
  display: block;
  cursor: pointer;
  width: 100%;
}

.radio-input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.option-content {
  display: flex;
  align-items: center;
  padding: 1.5rem;
  gap: 1rem;
}

.option-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  color: #6c757d;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.option-card.selected .option-icon {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
}

.option-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.option-title {
  font-weight: 600;
  color: #495057;
  font-size: 1.1rem;
  line-height: 1.2;
}

.option-subtitle {
  color: #6c757d;
  font-size: 0.9rem;
  font-style: italic;
}

.option-description {
  color: #6c757d;
  font-size: 0.8rem;
  line-height: 1.3;
  margin-top: 0.25rem;
}

.option-checkmark {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.option-card.selected .option-checkmark {
  background: #28a745;
  color: white;
}

.option-checkmark i {
  font-size: 0.9rem;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.option-card.selected .option-checkmark i {
  opacity: 1;
}

.disabled-indicator {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  flex-shrink: 0;
}

.knowledge-details-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.knowledge-details-card:hover {
  border-color: #007bff;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.details-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.details-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.details-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.details-body {
  padding: 1.5rem;
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 2rem;
}

.textarea-container {
  display: flex;
  flex-direction: column;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.75rem;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.form-label i {
  color: #007bff;
  font-size: 0.9rem;
}

.knowledge-textarea {
  padding: 1rem;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 0.95rem;
  line-height: 1.6;
  resize: vertical;
  font-family: inherit;
  transition: all 0.3s ease;
  background: #f8f9fa;
  min-height: 300px;
  margin-bottom: 0.5rem;
}

.knowledge-textarea:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.character-counter {
  text-align: right;
  font-size: 0.8rem;
  color: #6c757d;
}

.knowledge-guidelines {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.guidelines-card,
.regulation-categories {
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border: 1px solid #e9ecef;
  border-radius: 8px;
  overflow: hidden;
}

.guidelines-header,
.categories-header {
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

.guidelines-content,
.categories-content {
  padding: 1rem;
}

.guidelines-content h6 {
  color: #495057;
  font-size: 0.85rem;
  margin-bottom: 0.75rem;
}

.guidelines-list {
  margin: 0;
  padding: 0;
  list-style: none;
}

.guidelines-list li {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
  font-size: 0.8rem;
  line-height: 1.4;
}

.guidelines-list i {
  font-size: 0.7rem;
  margin-top: 0.1rem;
  flex-shrink: 0;
}

.category-tags {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.category-tag {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.category-tag:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.category-tag i {
  color: #007bff;
  font-size: 1rem;
  width: 16px;
  text-align: center;
  flex-shrink: 0;
}

.tag-content {
  flex: 1;
}

.tag-title {
  font-weight: 600;
  color: #495057;
  font-size: 0.8rem;
  display: block;
  margin-bottom: 0.1rem;
}

.tag-description {
  color: #6c757d;
  font-size: 0.7rem;
}

.status-card {
  background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1.5rem;
  border-left: 4px solid #28a745;
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.status-header {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}

.status-header i {
  color: #28a745;
}

.status-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.status-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.status-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #f8f9fa;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.status-content {
  flex: 1;
}

.status-label {
  display: block;
  font-size: 0.85rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
}

.status-value {
  font-weight: 600;
  font-size: 0.95rem;
  color: #495057;
}

.knowledge-preview {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
  grid-column: 1 / -1;
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
  font-size: 0.85rem;
  line-height: 1.5;
  margin-bottom: 1rem;
  white-space: pre-wrap;
}

.preview-stats {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8rem;
  color: #6c757d;
}

.stat-item i {
  color: #007bff;
  font-size: 0.7rem;
}

.text-primary { color: #007bff !important; }
.text-info { color: #17a2b8 !important; }
.text-success { color: #28a745 !important; }
.text-warning { color: #ffc107 !important; }

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
  .subcontractor-knowledge {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .details-body {
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
  
  .status-grid {
    grid-template-columns: 1fr;
  }
  
  .option-content {
    flex-direction: column;
    text-align: center;
    gap: 1rem;
  }
  
  .knowledge-textarea {
    min-height: 250px;
  }
}
</style>