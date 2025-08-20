<!-- resources/js/views/components/subcontractor/SubcontractorKnowledge.vue -->
<template>
    <div class="subcontractor-knowledge">
        <div class="tab-header">
            <h3>
                <i class="fas fa-brain"></i>
                Knowledgeable
            </h3>
            <p>
                Does your company aware and knowledgeable about local region
                regulation, policy, and law? / Apakah Perusahaan Anda memiliki
                kesadaran dan pengetahuan mengenai hukum, kebijakan dan
                peraturan daerah setempat?
            </p>
        </div>

        <div class="knowledge-section">
            <!-- Main Question -->
            <div class="main-question">
                <h4>Local Region Regulation Knowledge</h4>
                <div class="radio-group">
                    <label class="radio-option">
                        <input
                            type="radio"
                            value="yes"
                            v-model="formData.local_regulation_knowledge"
                        />
                        <span class="radio-custom"></span>
                        <div class="option-content">
                            <strong>Yes, we are knowledgeable</strong>
                            <span
                                >Ya, kami memiliki pengetahuan dan
                                kesadaran</span
                            >
                        </div>
                    </label>

                    <!-- <label class="radio-option">
            <input 
              type="radio" 
              value="no" 
              v-model="formData.local_regulation_knowledge"
            />
            <span class="radio-custom"></span>
            <div class="option-content">
              <strong>No, we need guidance</strong>
              <span>Tidak, kami memerlukan bimbingan</span>
            </div>
          </label> -->
                </div>
            </div>

            <!-- Knowledge Details -->
            <div
                class="knowledge-details"
                v-if="formData.local_regulation_knowledge"
            >
                <div class="form-group">
                    <label
                        >Please provide details about your knowledge and
                        understanding</label
                    >
                    <textarea
                        v-model="formData.regulation_knowledge_details"
                        rows="8"
                        :placeholder="getPlaceholderText()"
                        class="knowledge-textarea"
                    ></textarea>
                </div>
            </div>

            <!-- Knowledge Areas Assessment -->
            <!-- <div class="knowledge-areas" v-if="formData.local_regulation_knowledge === 'yes'">
        <h4>Knowledge Areas Assessment</h4>
        <p class="section-description">Please rate your company's knowledge level in the following areas:</p>
        
        <div class="assessment-grid">
          <div 
            v-for="area in knowledgeAreas" 
            :key="area.id"
            class="assessment-item"
          >
            <div class="area-header">
              <h5>{{ area.title }}</h5>
              <span class="area-description">{{ area.description }}</span>
            </div>
            
            <div class="rating-group">
              <label 
                v-for="level in ratingLevels" 
                :key="level.value"
                class="rating-option"
              >
                <input 
                  type="radio" 
                  :value="level.value"
                  v-model="knowledgeRatings[area.id]"
                />
                <span class="rating-badge" :class="level.class">
                  {{ level.label }}
                </span>
              </label>
            </div>
          </div>
        </div>
      </div> -->

            <!-- Compliance Checklist -->
            <!-- <div class="compliance-section">
        <h4>Regulatory Compliance Checklist</h4>
        <p class="section-description">Check all regulations that your company is familiar with and complies with:</p>
        
        <div class="compliance-grid">
          <div 
            v-for="regulation in regulationTypes" 
            :key="regulation.id"
            class="compliance-item"
          >
            <label class="compliance-checkbox">
              <input 
                type="checkbox" 
                :value="regulation.id"
                v-model="selectedRegulations"
              />
              <span class="checkmark"></span>
              <div class="regulation-content">
                <span class="regulation-title">{{ regulation.title }}</span>
                <span class="regulation-description">{{ regulation.description }}</span>
              </div>
            </label>
          </div>
        </div>
      </div> -->

            <!-- Training & Certification -->
            <!-- <div class="training-section" v-if="formData.local_regulation_knowledge === 'yes'">
        <h4>Training & Certification</h4>
        
        <div class="form-row">
          <div class="form-group">
            <label>Staff Training Programs</label>
            <textarea 
              v-model="formData.staff_training"
              rows="4"
              placeholder="Describe any training programs your staff has attended related to local regulations..."
            ></textarea>
          </div>
          
          <div class="form-group">
            <label>Certifications Held</label>
            <textarea 
              v-model="formData.certifications_held"
              rows="4"
              placeholder="List any certifications your company or staff hold related to regulatory compliance..."
            ></textarea>
          </div>
        </div>
      </div> -->

            <!-- Support Needed -->
            <!-- <div class="support-section" v-if="formData.local_regulation_knowledge === 'no'">
        <h4>Support and Guidance Needed</h4>
        
        <div class="form-group">
          <label>Areas where you need guidance</label>
          <textarea 
            v-model="formData.guidance_needed"
            rows="6"
            placeholder="Please describe specific areas where your company needs guidance regarding local regulations, policies, and laws..."
          ></textarea>
        </div>
        
        <div class="form-group">
          <label>Preferred Training Methods</label>
          <div class="training-methods">
            <label 
              v-for="method in trainingMethods" 
              :key="method.id"
              class="method-checkbox"
            >
              <input 
                type="checkbox" 
                :value="method.id"
                v-model="selectedTrainingMethods"
              />
              <span class="checkmark-small"></span>
              <span>{{ method.label }}</span>
            </label>
          </div>
        </div>
      </div> -->
        </div>

        <div class="form-actions">
            <button
                @click="saveKnowledge"
                :disabled="loading"
                class="btn btn-primary"
            >
                <i class="fas fa-save"></i>
                {{ loading ? "Saving..." : "Save Knowledge Assessment" }}
            </button>

            <!-- <button @click="resetForm" class="btn btn-outline">
        <i class="fas fa-undo"></i>
        Reset Form
      </button> -->
        </div>

        <!-- Alert -->
        <AlertComponent
            v-if="alertMsg"
            :type="alertType"
            :message="alertMsg"
            @close="alertMsg = ''"
        />
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

const knowledgeRatings = ref({});
const selectedRegulations = ref([]);
const selectedTrainingMethods = ref([]);

const ratingLevels = [
    { value: "excellent", label: "Excellent", class: "excellent" },
    { value: "good", label: "Good", class: "good" },
    { value: "fair", label: "Fair", class: "fair" },
    { value: "poor", label: "Poor", class: "poor" },
];

const knowledgeAreas = [
    {
        id: "environmental",
        title: "Environmental Regulations",
        description: "AMDAL, UKL-UPL, environmental permits",
    },
    {
        id: "safety",
        title: "Occupational Safety & Health",
        description: "SMK3, workplace safety standards, OHSAS",
    },
    {
        id: "construction",
        title: "Construction Permits",
        description: "IMB, construction licenses, zoning regulations",
    },
    {
        id: "labor",
        title: "Labor Laws",
        description: "Employment regulations, worker rights, social security",
    },
    {
        id: "tax",
        title: "Tax Regulations",
        description: "Corporate tax, VAT, local taxes",
    },
    {
        id: "electrical",
        title: "Electrical Standards",
        description: "SNI standards, electrical safety, grid connection",
    },
];

const regulationTypes = [
    {
        id: "amdal",
        title: "AMDAL (Environmental Impact Assessment)",
        description: "Environmental impact analysis and management",
    },
    {
        id: "ukl_upl",
        title: "UKL-UPL (Environmental Management)",
        description: "Environmental management and monitoring efforts",
    },
    {
        id: "smk3",
        title: "SMK3 (Occupational Safety Management)",
        description: "Workplace safety management system",
    },
    {
        id: "construction_permits",
        title: "Construction Permits (IMB)",
        description: "Building permits and construction licenses",
    },
    {
        id: "labor_laws",
        title: "Labor Laws (UU Ketenagakerjaan)",
        description: "Employment and labor regulations",
    },
    {
        id: "tax_regulations",
        title: "Tax Regulations",
        description: "Corporate and local tax compliance",
    },
    {
        id: "electrical_standards",
        title: "Electrical Standards (SNI)",
        description: "National electrical standards and safety",
    },
    {
        id: "local_permits",
        title: "Local Government Permits",
        description: "Regional and municipal permits",
    },
];

const trainingMethods = [
    { id: "workshop", label: "Workshops/Seminars" },
    { id: "online", label: "Online Training" },
    { id: "consultation", label: "One-on-one Consultation" },
    { id: "documentation", label: "Written Guidelines/Manuals" },
    { id: "site_visit", label: "Site Visits/Practical Training" },
];

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
    formData.regulation_knowledge_details =
        data.regulation_knowledge_details || "";
    formData.staff_training = data.staff_training || "";
    formData.certifications_held = data.certifications_held || "";
    formData.guidance_needed = data.guidance_needed || "";

    // Initialize ratings
    if (data.knowledge_ratings && typeof data.knowledge_ratings === "object") {
        knowledgeRatings.value = { ...data.knowledge_ratings };
    }

    // Initialize regulations
    if (data.selected_regulations && Array.isArray(data.selected_regulations)) {
        selectedRegulations.value = [...data.selected_regulations];
    }

    // Initialize training methods
    if (
        data.selected_training_methods &&
        Array.isArray(data.selected_training_methods)
    ) {
        selectedTrainingMethods.value = [...data.selected_training_methods];
    }
}

function getPlaceholderText() {
    if (formData.local_regulation_knowledge === "yes") {
        return `Please provide details about your company's knowledge and understanding of local regulations, including:

• Specific regulations you are familiar with
• How you stay updated with regulatory changes
• Examples of compliance implementation
• Any challenges faced in regulatory compliance
• Training programs your staff has attended
• Certifications or permits held`;
    } else if (formData.local_regulation_knowledge === "no") {
        return `Please describe:

• Areas where you lack knowledge
• Specific regulations you need to understand
• Why you need guidance
• Previous challenges faced
• Preferred learning methods
• Expected timeline for improvement`;
    }
    return "Please provide details...";
}

function resetForm() {
    if (
        confirm("Are you sure you want to reset all knowledge assessment data?")
    ) {
        formData.local_regulation_knowledge = "";
        formData.regulation_knowledge_details = "";
        formData.staff_training = "";
        formData.certifications_held = "";
        formData.guidance_needed = "";
        knowledgeRatings.value = {};
        selectedRegulations.value = [];
        selectedTrainingMethods.value = [];
        showAlert("info", "Form has been reset");
    }
}

async function saveKnowledge() {
    if (!formData.local_regulation_knowledge) {
        showAlert(
            "warning",
            "Please select your knowledge level regarding local regulations"
        );
        return;
    }

    if (
        formData.local_regulation_knowledge === "yes" &&
        !formData.regulation_knowledge_details.trim()
    ) {
        showAlert(
            "warning",
            "Please provide details about your regulatory knowledge"
        );
        return;
    }

    if (
        formData.local_regulation_knowledge === "no" &&
        !formData.guidance_needed.trim()
    ) {
        showAlert(
            "warning",
            "Please describe the areas where you need guidance"
        );
        return;
    }

    loading.value = true;

    try {
        const dataToSave = {
            local_regulation_knowledge: formData.local_regulation_knowledge,
            regulation_knowledge_details:
                formData.regulation_knowledge_details.trim(),
            staff_training: formData.staff_training.trim(),
            certifications_held: formData.certifications_held.trim(),
            guidance_needed: formData.guidance_needed.trim(),
            knowledge_ratings: knowledgeRatings.value,
            selected_regulations: selectedRegulations.value,
            selected_training_methods: selectedTrainingMethods.value,
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
            showAlert("success", "Knowledge assessment saved successfully!");
            emit("updated", response.data.data.subcontractor_data);
        } else {
            showAlert(
                "error",
                response.data.message || "Failed to save knowledge assessment"
            );
        }
    } catch (error) {
        console.error("Save knowledge error:", error);
        showAlert(
            "error",
            error.response?.data?.message ||
                "Failed to save knowledge assessment"
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
.subcontractor-knowledge {
    max-width: 900px;
}

.tab-header {
    margin-bottom: 2rem;
}

.tab-header h3 {
    color: #212529;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tab-header p {
    color: #6c757d;
    margin: 0;
    font-style: italic;
    line-height: 1.5;
}

.knowledge-section {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.main-question {
    margin-bottom: 2rem;
}

.main-question h4 {
    color: #495057;
    margin-bottom: 1rem;
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.radio-option {
    display: flex;
    align-items: center;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.2s;
    gap: 1rem;
}

.radio-option:hover {
    border-color: #007bff;
}

.radio-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #ced4da;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s;
    flex-shrink: 0;
}

.radio-option input[type="radio"]:checked ~ .radio-custom {
    border-color: #007bff;
    background-color: #007bff;
}

.radio-option input[type="radio"]:checked ~ .radio-custom:after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
    transform: translate(-50%, -50%);
}

.option-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.option-content strong {
    color: #495057;
}

.option-content span {
    color: #6c757d;
    font-size: 0.875rem;
    font-style: italic;
}

.knowledge-details,
.knowledge-areas,
.compliance-section,
.training-section,
.support-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #dee2e6;
}

.knowledge-details h4,
.knowledge-areas h4,
.compliance-section h4,
.training-section h4,
.support-section h4 {
    color: #495057;
    margin-bottom: 1rem;
}

.section-description {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1.5rem;
}

.form-group label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.knowledge-textarea,
.form-group textarea {
    padding: 1rem;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 0.875rem;
    line-height: 1.5;
    resize: vertical;
    font-family: inherit;
}

.knowledge-textarea:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.assessment-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.assessment-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
}

.area-header {
    margin-bottom: 1rem;
}

.area-header h5 {
    color: #495057;
    margin: 0 0 0.25rem 0;
}

.area-description {
    color: #6c757d;
    font-size: 0.875rem;
}

.rating-group {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.rating-option {
    cursor: pointer;
}

.rating-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.rating-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    border: 2px solid transparent;
    transition: all 0.2s;
}

.rating-badge.excellent {
    background: #d4edda;
    color: #155724;
}

.rating-badge.good {
    background: #cce5ff;
    color: #004085;
}

.rating-badge.fair {
    background: #fff3cd;
    color: #856404;
}

.rating-badge.poor {
    background: #f8d7da;
    color: #721c24;
}

.rating-option input[type="radio"]:checked ~ .rating-badge {
    border-color: #007bff;
    transform: scale(1.05);
}

.compliance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.compliance-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 1rem;
}

.compliance-checkbox {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    gap: 1rem;
    width: 100%;
}

.compliance-checkbox input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    width: 20px;
    height: 20px;
    background-color: #f8f9fa;
    border: 2px solid #ced4da;
    border-radius: 4px;
    position: relative;
    transition: all 0.2s;
    flex-shrink: 0;
    margin-top: 2px;
}

.compliance-checkbox input[type="checkbox"]:checked ~ .checkmark {
    background-color: #007bff;
    border-color: #007bff;
}

.compliance-checkbox input[type="checkbox"]:checked ~ .checkmark:after {
    content: "";
    position: absolute;
    left: 6px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.regulation-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.regulation-title {
    font-weight: 500;
    color: #495057;
}

.regulation-description {
    font-size: 0.875rem;
    color: #6c757d;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.training-methods {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.method-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
    gap: 0.75rem;
}

.method-checkbox input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark-small {
    width: 16px;
    height: 16px;
    background-color: #f8f9fa;
    border: 2px solid #ced4da;
    border-radius: 3px;
    position: relative;
    transition: all 0.2s;
    flex-shrink: 0;
}

.method-checkbox input[type="checkbox"]:checked ~ .checkmark-small {
    background-color: #007bff;
    border-color: #007bff;
}

.method-checkbox input[type="checkbox"]:checked ~ .checkmark-small:after {
    content: "";
    position: absolute;
    left: 4px;
    top: 1px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.form-actions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    gap: 1rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #e56b00;
}

.btn-outline {
    background: white;
    color: #6c757d;
    border: 1px solid #ced4da;
}

.btn-outline:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .compliance-grid {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .rating-group {
        justify-content: center;
    }
}
</style>
