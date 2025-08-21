<template>
  <div class="subcontractor-projects">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-project-diagram text-primary me-2"></i>
          Major Projects Management
          <span class="subtitle">Track major projects from the last 3-5 years</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Projects Input Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-table"></i>
            <h6 class="section-title">Project Details Matrix</h6>
            <div class="section-actions">
              <button @click="clearAllProjects" class="btn btn-reset">
                <i class="fas fa-trash me-1"></i>
                Clear All
              </button>
            </div>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Lengkapi informasi 3 proyek terbesar perusahaan Anda dalam 3-5 tahun terakhir. Data ini akan membantu menunjukkan track record dan kapabilitas perusahaan.
            </p>
          </div>

          <!-- Desktop Table View -->
          <div class="table-container d-none d-xl-block">
            <div class="table-responsive">
              <table class="projects-table">
                <thead>
                  <tr>
                    <th class="field-col">
                      <div class="header-content">
                        <i class="fas fa-list-alt"></i>
                        <span>Project Fields</span>
                      </div>
                    </th>
                    <th class="project-col" v-for="n in 3" :key="'header_' + n">
                      <div class="header-content">
                        <i class="fas fa-project-diagram"></i>
                        <span>Project {{ n }}</span>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(field, fieldIndex) in projectFields" :key="fieldIndex" class="field-row">
                    <td class="field-label">
                      <div class="field-info">
                        <i :class="field.icon"></i>
                        <div class="field-content">
                          <strong>{{ field.label }}</strong>
                          <small>{{ field.labelId }}</small>
                        </div>
                      </div>
                    </td>
                    <td v-for="projectIndex in 3" :key="'project_' + projectIndex" class="project-cell">
                      <div v-if="field.type === 'input'" class="input-wrapper">
                        <input
                          v-model="projects[projectIndex - 1][field.key]"
                          :type="field.inputType || 'text'"
                          :placeholder="field.placeholder"
                          class="project-input"
                        />
                      </div>
                      <div v-else-if="field.type === 'currency'" class="currency-wrapper">
                        <div class="currency-input">
                          <span class="currency-prefix">Rp</span>
                          <input
                            v-model="projects[projectIndex - 1][field.key]"
                            type="text"
                            :placeholder="field.placeholder"
                            class="project-input currency-field"
                            @input="formatCurrency(projectIndex - 1, $event)"
                          />
                        </div>
                      </div>
                      <div v-else-if="field.type === 'textarea'" class="textarea-wrapper">
                        <textarea
                          v-model="projects[projectIndex - 1][field.key]"
                          :rows="field.rows || 3"
                          :placeholder="field.placeholder"
                          class="project-textarea"
                        ></textarea>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Mobile/Tablet Card View -->
          <div class="projects-cards d-xl-none">
            <div
              v-for="(project, projectIndex) in projects"
              :key="'mobile_project_' + projectIndex"
              class="project-card"
              :class="{ 'has-content': isProjectFilled(project) }"
            >
              <div class="project-card-header">
                <div class="project-card-title">
                  <i class="fas fa-project-diagram me-2"></i>
                  <span>Project {{ projectIndex + 1 }}</span>
                </div>
                <div class="completion-indicator">
                  <span class="completion-badge" :class="getCompletionClass(project)">
                    {{ getCompletionPercentage(project) }}%
                  </span>
                </div>
              </div>

              <div class="project-card-body">
                <div class="project-fields">
                  <div
                    v-for="(field, fieldIndex) in projectFields"
                    :key="'field_' + fieldIndex"
                    class="field-group"
                  >
                    <label :for="field.key + '_' + projectIndex" class="field-label">
                      <i :class="field.icon"></i>
                      <div class="label-content">
                        <span class="label-main">{{ field.label }}</span>
                        <small class="label-sub">{{ field.labelId }}</small>
                      </div>
                    </label>

                    <div v-if="field.type === 'input'" class="input-wrapper">
                      <input
                        :id="field.key + '_' + projectIndex"
                        v-model="project[field.key]"
                        :type="field.inputType || 'text'"
                        :placeholder="field.placeholder"
                        class="form-control"
                      />
                    </div>
                    <div v-else-if="field.type === 'currency'" class="currency-wrapper">
                      <div class="currency-input">
                        <span class="currency-prefix">Rp</span>
                        <input
                          :id="field.key + '_' + projectIndex"
                          v-model="project[field.key]"
                          type="text"
                          :placeholder="field.placeholder"
                          class="form-control currency-field"
                          @input="formatCurrency(projectIndex, $event)"
                        />
                      </div>
                    </div>
                    <div v-else-if="field.type === 'textarea'" class="textarea-wrapper">
                      <textarea
                        :id="field.key + '_' + projectIndex"
                        v-model="project[field.key]"
                        :rows="field.rows || 3"
                        :placeholder="field.placeholder"
                        class="form-control"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Projects Summary Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <h6 class="section-title">Projects Overview</h6>
          </div>

          <div class="summary-grid">
            <div
              v-for="(project, index) in projects"
              :key="'summary_' + index"
              class="summary-card"
              :class="getSummaryCardClass(project)"
            >
              <div class="summary-header">
                <div class="summary-title">
                  <i class="fas fa-folder-open me-2"></i>
                  <span>Project {{ index + 1 }}</span>
                </div>
                <div class="completion-circle" :class="getCompletionClass(project)">
                  <span>{{ getCompletionPercentage(project) }}%</span>
                </div>
              </div>

              <div class="summary-content">
                <div v-if="project.project_name" class="summary-item">
                  <i class="fas fa-tag"></i>
                  <div class="item-content">
                    <span class="item-label">Project Name</span>
                    <span class="item-value">{{ project.project_name }}</span>
                  </div>
                </div>

                <div v-if="project.year_of_execution" class="summary-item">
                  <i class="fas fa-calendar-alt"></i>
                  <div class="item-content">
                    <span class="item-label">Year</span>
                    <span class="item-value">{{ project.year_of_execution }}</span>
                  </div>
                </div>

                <div v-if="project.value" class="summary-item">
                  <i class="fas fa-money-bill-wave"></i>
                  <div class="item-content">
                    <span class="item-label">Value</span>
                    <span class="item-value">{{ project.value }}</span>
                  </div>
                </div>

                <div v-if="project.work_duration" class="summary-item">
                  <i class="fas fa-clock"></i>
                  <div class="item-content">
                    <span class="item-label">Duration</span>
                    <span class="item-value">{{ project.work_duration }}</span>
                  </div>
                </div>

                <div v-if="project.project_owner" class="summary-item">
                  <i class="fas fa-building"></i>
                  <div class="item-content">
                    <span class="item-label">Owner</span>
                    <span class="item-value">{{ project.project_owner }}</span>
                  </div>
                </div>

                <div v-if="!isProjectFilled(project)" class="empty-state">
                  <i class="fas fa-plus-circle"></i>
                  <span>Isi data proyek di form di atas</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Overall Statistics -->
          <div class="overall-stats">
            <div class="stats-card">
              <div class="stats-header">
                <i class="fas fa-analytics me-2"></i>
                <span>Project Statistics</span>
              </div>
              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-list-check"></i>
                  </div>
                  <div class="stat-content">
                    <span class="stat-number">{{ getFilledProjectsCount() }}</span>
                    <span class="stat-label">Projects Filled</span>
                  </div>
                </div>
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                  </div>
                  <div class="stat-content">
                    <span class="stat-number">{{ getOverallCompletion() }}%</span>
                    <span class="stat-label">Overall Completion</span>
                  </div>
                </div>
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-calendar-range"></i>
                  </div>
                  <div class="stat-content">
                    <span class="stat-number">{{ getYearRange() }}</span>
                    <span class="stat-label">Year Range</span>
                  </div>
                </div>
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-coins"></i>
                  </div>
                  <div class="stat-content">
                    <span class="stat-number">{{ getTotalValue() }}</span>
                    <span class="stat-label">Total Value</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button
            @click="saveProjects"
            :disabled="loading"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Data Proyek" }}
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

const projectFields = [
  {
    key: 'year_of_execution',
    label: 'Year of Execution',
    labelId: 'Tahun Pelaksanaan',
    type: 'input',
    inputType: 'text',
    placeholder: 'e.g. 2022',
    icon: 'fas fa-calendar-alt'
  },
  {
    key: 'project_name',
    label: 'Project Name',
    labelId: 'Nama Proyek',
    type: 'input',
    placeholder: 'Enter project name',
    icon: 'fas fa-project-diagram'
  },
  {
    key: 'project_owner',
    label: 'Project Owner',
    labelId: 'Pemilik Proyek',
    type: 'input',
    placeholder: 'Owner company name',
    icon: 'fas fa-building'
  },
  {
    key: 'main_contractor',
    label: 'Main Contractor',
    labelId: 'Kontraktor Utama',
    type: 'input',
    placeholder: 'Contractor company name',
    icon: 'fas fa-hard-hat'
  },
  {
    key: 'value',
    label: 'Contract Value',
    labelId: 'Nilai Kontrak',
    type: 'currency',
    placeholder: '0',
    icon: 'fas fa-money-bill-wave'
  },
  {
    key: 'work_package',
    label: 'Work Package',
    labelId: 'Paket Pekerjaan',
    type: 'textarea',
    rows: 3,
    placeholder: 'Describe the work package',
    icon: 'fas fa-box'
  },
  {
    key: 'scope_of_work',
    label: 'Scope of Work',
    labelId: 'Ruang Lingkup',
    type: 'textarea',
    rows: 3,
    placeholder: 'Detailed scope description',
    icon: 'fas fa-tasks'
  },
  {
    key: 'work_duration',
    label: 'Work Duration',
    labelId: 'Durasi Pekerjaan',
    type: 'input',
    placeholder: 'e.g. 12 months',
    icon: 'fas fa-clock'
  },
  {
    key: 'client_representative',
    label: 'Client Representative',
    labelId: 'Perwakilan Klien',
    type: 'input',
    placeholder: 'Contact person name',
    icon: 'fas fa-user-tie'
  },
  {
    key: 'phone',
    label: 'Phone Number',
    labelId: 'Nomor Telepon',
    type: 'input',
    inputType: 'tel',
    placeholder: '+62 xxx xxxx xxxx',
    icon: 'fas fa-phone'
  }
];

const projects = ref([
  {
    year_of_execution: "",
    project_name: "",
    project_owner: "",
    main_contractor: "",
    value: "",
    work_package: "",
    scope_of_work: "",
    work_duration: "",
    client_representative: "",
    phone: "",
  },
  {
    year_of_execution: "",
    project_name: "",
    project_owner: "",
    main_contractor: "",
    value: "",
    work_package: "",
    scope_of_work: "",
    work_duration: "",
    client_representative: "",
    phone: "",
  },
  {
    year_of_execution: "",
    project_name: "",
    project_owner: "",
    main_contractor: "",
    value: "",
    work_package: "",
    scope_of_work: "",
    work_duration: "",
    client_representative: "",
    phone: "",
  },
]);

watch(
  () => props.subcontractorData,
  (newData) => {
    if (newData) {
      initializeProjectData();
    }
  },
  { immediate: true, deep: true }
);

function initializeProjectData() {
  if (!props.subcontractorData) return;

  const data = props.subcontractorData;

  if (data.major_projects && Array.isArray(data.major_projects)) {
    data.major_projects.forEach((project, index) => {
      if (index < 3 && projects.value[index]) {
        projects.value[index] = {
          ...projects.value[index],
          ...project,
        };
      }
    });
  }
}

function formatCurrency(index, event) {
  let value = event.target.value.replace(/[^\d]/g, "");
  if (value) {
    value = parseInt(value).toLocaleString("id-ID");
  }
  projects.value[index].value = value;
}

function isProjectFilled(project) {
  const requiredFields = ["project_name", "year_of_execution", "project_owner"];
  return requiredFields.some((field) => project[field] && project[field].trim());
}

function getCompletionPercentage(project) {
  const totalFields = 10;
  const filledFields = Object.values(project).filter(
    (value) => value && value.toString().trim()
  ).length;
  return Math.round((filledFields / totalFields) * 100);
}

function getCompletionClass(project) {
  const percentage = getCompletionPercentage(project);
  if (percentage >= 80) return "high";
  if (percentage >= 50) return "medium";
  if (percentage > 0) return "low";
  return "empty";
}

function getSummaryCardClass(project) {
  const percentage = getCompletionPercentage(project);
  if (percentage >= 80) return "card-excellent";
  if (percentage >= 50) return "card-good";
  if (percentage > 0) return "card-basic";
  return "card-empty";
}

function getFilledProjectsCount() {
  return projects.value.filter(project => isProjectFilled(project)).length;
}

function getOverallCompletion() {
  const totalCompletion = projects.value.reduce((sum, project) => 
    sum + getCompletionPercentage(project), 0);
  return Math.round(totalCompletion / 3);
}

function getYearRange() {
  const years = projects.value
    .map(p => p.year_of_execution)
    .filter(year => year && year.trim())
    .map(year => parseInt(year))
    .filter(year => !isNaN(year));
  
  if (years.length === 0) return "N/A";
  const min = Math.min(...years);
  const max = Math.max(...years);
  return min === max ? min.toString() : `${min}-${max}`;
}

function getTotalValue() {
  const values = projects.value
    .map(p => p.value)
    .filter(value => value && value.trim())
    .map(value => parseInt(value.replace(/[^\d]/g, '')))
    .filter(value => !isNaN(value));
  
  if (values.length === 0) return "N/A";
  const total = values.reduce((sum, value) => sum + value, 0);
  return total > 0 ? `Rp ${total.toLocaleString('id-ID')}` : "N/A";
}

function clearAllProjects() {
  if (
    confirm(
      "Apakah Anda yakin ingin menghapus semua data proyek? Tindakan ini tidak dapat dibatalkan."
    )
  ) {
    projects.value.forEach((project) => {
      Object.keys(project).forEach((key) => {
        project[key] = "";
      });
    });
    showAlert("info", "Semua data proyek telah dihapus");
  }
}

async function saveProjects() {
  loading.value = true;

  try {
    const dataToSave = {
      major_projects: projects.value,
    };

    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/subcontractor", dataToSave, {
      headers: { Authorization: `Bearer ${token}` },
    });

    if (response.data.success) {
      showAlert("success", "Data proyek utama berhasil disimpan!");
      emit("updated", response.data.data.subcontractor_data);
    } else {
      showAlert("error", response.data.message || "Gagal menyimpan data proyek");
    }
  } catch (error) {
    console.error("Save projects error:", error);
    showAlert("error", error.response?.data?.message || "Gagal menyimpan data proyek");
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
.subcontractor-projects {
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

.table-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e9ecef;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.table-responsive {
  overflow-x: auto;
}

.projects-table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
  min-width: 1000px;
}

.projects-table thead th {
  background: linear-gradient(135deg, #495057 0%, #343a40 100%);
  color: white;
  font-weight: 600;
  padding: 1.25rem 1rem;
  font-size: 0.9rem;
  text-align: center;
  position: sticky;
  top: 0;
  z-index: 10;
}

.header-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.header-content i {
  font-size: 1.1rem;
  opacity: 0.8;
}

.field-col {
  width: 250px;
  min-width: 250px;
  text-align: left !important;
}

.project-col {
  width: 200px;
}

.field-row {
  transition: all 0.3s ease;
  border-bottom: 1px solid #e9ecef;
}

.field-row:hover {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.projects-table td {
  padding: 1rem;
  vertical-align: top;
}

.field-label {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}

.field-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.field-info i {
  color: #007bff;
  font-size: 1.1rem;
  width: 20px;
  text-align: center;
  flex-shrink: 0;
}

.field-content strong {
  color: #495057;
  font-weight: 600;
  display: block;
  margin-bottom: 0.25rem;
}

.field-content small {
  color: #6c757d;
  font-style: italic;
  font-size: 0.8rem;
}

.project-cell {
  text-align: center;
}

.input-wrapper,
.currency-wrapper,
.textarea-wrapper {
  width: 100%;
}

.project-input,
.project-textarea {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.85rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.project-input:focus,
.project-textarea:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.project-textarea {
  resize: vertical;
  font-family: inherit;
  line-height: 1.4;
}

.currency-input {
  position: relative;
  display: flex;
  align-items: center;
}

.currency-prefix {
  position: absolute;
  left: 0.75rem;
  color: #6c757d;
  font-weight: 600;
  z-index: 1;
  font-size: 0.85rem;
}

.currency-field {
  padding-left: 2.5rem;
}

.projects-cards {
  display: grid;
  gap: 2rem;
}

.project-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.project-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.project-card:hover::before {
  left: 100%;
}

.project-card:hover,
.project-card.has-content {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.project-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.project-card-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  font-size: 1.1rem;
}

.project-card-title i {
  color: #007bff;
}

.completion-indicator {
  display: flex;
  align-items: center;
}

.completion-badge {
  padding: 0.375rem 0.75rem;
  border-radius: 15px;
  font-size: 0.8rem;
  font-weight: 600;
}

.completion-badge.high {
  background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
  color: #155724;
}

.completion-badge.medium {
  background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
  color: #856404;
}

.completion-badge.low {
  background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
  color: #721c24;
}

.completion-badge.empty {
  background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
  color: #6c757d;
}

.project-card-body {
  padding: 1.5rem;
}

.project-fields {
  display: grid;
  gap: 1.5rem;
}

.field-group {
  display: flex;
  flex-direction: column;
}

.field-label {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #495057;
}

.field-label i {
  color: #007bff;
  font-size: 0.9rem;
  width: 16px;
  text-align: center;
}

.label-content {
  display: flex;
  flex-direction: column;
}

.label-main {
  font-size: 0.9rem;
  line-height: 1.2;
}

.label-sub {
  font-size: 0.75rem;
  color: #6c757d;
  font-style: italic;
  font-weight: 400;
}

.form-control {
  padding: 0.875rem 1rem;
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

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.summary-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  padding: 1.5rem;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.summary-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.summary-card:hover::before {
  left: 100%;
}

.card-excellent {
  border-color: #28a745;
  background: linear-gradient(145deg, #d4edda 0%, #ffffff 100%);
}

.card-good {
  border-color: #17a2b8;
  background: linear-gradient(145deg, #d1ecf1 0%, #ffffff 100%);
}

.card-basic {
  border-color: #ffc107;
  background: linear-gradient(145deg, #fff3cd 0%, #ffffff 100%);
}

.card-empty {
  border-color: #6c757d;
  background: linear-gradient(145deg, #e9ecef 0%, #ffffff 100%);
}

.summary-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #e9ecef;
}

.summary-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.summary-title i {
  color: #007bff;
}

.completion-circle {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
  border: 3px solid;
}

.completion-circle.high {
  background: #d4edda;
  color: #155724;
  border-color: #28a745;
}

.completion-circle.medium {
  background: #fff3cd;
  color: #856404;
  border-color: #ffc107;
}

.completion-circle.low {
  background: #f8d7da;
  color: #721c24;
  border-color: #dc3545;
}

.completion-circle.empty {
  background: #e9ecef;
  color: #6c757d;
  border-color: #6c757d;
}

.summary-content {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.summary-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.summary-item i {
  color: #007bff;
  font-size: 0.9rem;
  width: 16px;
  text-align: center;
  flex-shrink: 0;
}

.item-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.item-label {
  font-size: 0.75rem;
  color: #6c757d;
  font-weight: 500;
}

.item-value {
  font-size: 0.85rem;
  color: #495057;
  font-weight: 600;
  line-height: 1.2;
}

.empty-state {
  text-align: center;
  color: #6c757d;
  font-style: italic;
  padding: 2rem 1rem;
}

.empty-state i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  display: block;
  opacity: 0.5;
}

.overall-stats {
  margin-top: 2rem;
}

.stats-card {
  background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1.5rem;
  border-left: 4px solid #28a745;
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.stats-header {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}

.stats-header i {
  color: #28a745;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
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
  font-size: 1.1rem;
  color: #28a745;
  flex-shrink: 0;
}

.stat-content {
  flex: 1;
}

.stat-number {
  display: block;
  font-size: 1.25rem;
  font-weight: 700;
  color: #495057;
  line-height: 1;
}

.stat-label {
  font-size: 0.75rem;
  color: #6c757d;
  margin-top: 0.25rem;
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

.btn-reset {
  background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.btn-reset:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
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
  .subcontractor-projects {
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
  
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  }
}
</style>