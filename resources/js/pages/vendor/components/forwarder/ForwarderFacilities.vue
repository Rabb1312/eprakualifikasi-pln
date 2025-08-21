<template>
    <div class="forwarder-facilities">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-building text-primary me-2"></i>
                    Facilities Management
                    <span class="subtitle">Configure your facility settings</span>
                </h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="saveData">
                    <!-- Monitoring Management -->
                    <div class="section-wrapper">
                        <div class="section-header">
                            <i class="fas fa-monitor-waveform"></i>
                            <h6 class="section-title">Monitoring Management</h6>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="monitoring_management" class="form-label">
                                    <i class="fas fa-cogs me-1"></i>
                                    Monitoring management type
                                    <span class="text-danger">*</span>
                                </label>
                                <select
                                    class="form-select"
                                    id="monitoring_management"
                                    v-model="formData.monitoring_management"
                                    required
                                >
                                    <option value="">Select monitoring type...</option>
                                    <option
                                        v-for="(label, value) in options.monitoring_management"
                                        :key="value"
                                        :value="value"
                                    >
                                        {{ label }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="monitoring_coordination_description" class="form-label">
                                    <i class="fas fa-file-alt me-1"></i>
                                    Monitoring coordination description
                                </label>
                                <textarea
                                    class="form-control"
                                    id="monitoring_coordination_description"
                                    v-model="formData.monitoring_coordination_description"
                                    rows="3"
                                    placeholder="Describe your monitoring coordination process..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Software Systems -->
                    <div class="section-wrapper">
                        <div class="section-header">
                            <i class="fas fa-laptop-code"></i>
                            <h6 class="section-title">Software Systems</h6>
                        </div>

                        <div class="checkbox-grid">
                            <div class="checkbox-card" :class="{ 'disabled': formData.software_none }">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="software_none"
                                    v-model="formData.software_none"
                                />
                                <label class="checkbox-label" for="software_none">
                                    <i class="fas fa-ban"></i>
                                    <span>None</span>
                                </label>
                            </div>

                            <div class="checkbox-card" :class="{ 'disabled': formData.software_none }">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="software_existing"
                                    v-model="formData.software_existing"
                                    :disabled="formData.software_none"
                                />
                                <label class="checkbox-label" for="software_existing">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Existing</span>
                                </label>
                            </div>

                            <div class="checkbox-card" :class="{ 'disabled': formData.software_none }">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="software_shipment_tracking"
                                    v-model="formData.software_shipment_tracking"
                                    :disabled="formData.software_none"
                                />
                                <label class="checkbox-label" for="software_shipment_tracking">
                                    <i class="fas fa-shipping-fast"></i>
                                    <span>Shipment tracking</span>
                                </label>
                            </div>

                            <div class="checkbox-card" :class="{ 'disabled': formData.software_none }">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="software_others"
                                    v-model="formData.software_others"
                                    :disabled="formData.software_none"
                                />
                                <label class="checkbox-label" for="software_others">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Others</span>
                                </label>
                            </div>
                        </div>

                        <!-- Software Descriptions -->
                        <div class="form-grid" v-if="!formData.software_none">
                            <div 
                                v-if="formData.software_shipment_tracking"
                                class="form-group animate-fade-in"
                            >
                                <label for="software_shipment_tracking_description" class="form-label">
                                    <i class="fas fa-truck me-1"></i>
                                    Shipment tracking description
                                </label>
                                <textarea
                                    class="form-control"
                                    id="software_shipment_tracking_description"
                                    v-model="formData.software_shipment_tracking_description"
                                    rows="2"
                                    placeholder="Describe shipment tracking system..."
                                ></textarea>
                            </div>

                            <div 
                                v-if="formData.software_others"
                                class="form-group animate-fade-in"
                            >
                                <label for="software_others_description" class="form-label">
                                    <i class="fas fa-tools me-1"></i>
                                    Other software description
                                </label>
                                <textarea
                                    class="form-control"
                                    id="software_others_description"
                                    v-model="formData.software_others_description"
                                    rows="2"
                                    placeholder="Describe other software systems..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Communication System -->
                    <div class="section-wrapper">
                        <div class="section-header">
                            <i class="fas fa-comments"></i>
                            <h6 class="section-title">Communication System</h6>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="communication_system" class="form-label">
                                    <i class="fas fa-network-wired me-1"></i>
                                    Communication system type
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="communication_system"
                                    v-model="formData.communication_system"
                                    placeholder="e.g., Email, Phone, WhatsApp, etc."
                                />
                            </div>

                            <div class="form-group">
                                <label for="communication_system_description" class="form-label">
                                    <i class="fas fa-comment-dots me-1"></i>
                                    Communication system description
                                </label>
                                <textarea
                                    class="form-control"
                                    id="communication_system_description"
                                    v-model="formData.communication_system_description"
                                    rows="3"
                                    placeholder="Describe your communication system..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Project Execution -->
                    <div class="section-wrapper">
                        <div class="section-header">
                            <i class="fas fa-project-diagram"></i>
                            <h6 class="section-title">Project Execution</h6>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="project_execution_procedure" class="form-label">
                                    <i class="fas fa-list-ol me-1"></i>
                                    Project execution procedure
                                </label>
                                <textarea
                                    class="form-control"
                                    id="project_execution_procedure"
                                    v-model="formData.project_execution_procedure"
                                    rows="4"
                                    placeholder="Describe your project execution procedure..."
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="project_execution_sop_list" class="form-label">
                                    <i class="fas fa-clipboard-list me-1"></i>
                                    SOP list for project execution
                                </label>
                                <textarea
                                    class="form-control"
                                    id="project_execution_sop_list"
                                    v-model="formData.project_execution_sop_list"
                                    rows="4"
                                    placeholder="List your SOPs for project execution..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="submit-section">
                        <button
                            type="submit"
                            class="btn btn-primary"
                            :disabled="saving"
                        >
                            <i class="fas fa-save"></i>
                            <span
                                v-if="saving"
                                class="spinner-border spinner-border-sm me-2"
                            ></span>
                            {{ saving ? "Saving..." : "Save Changes" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ForwarderFacilities",
    props: {
        forwarder: Object,
        options: Object,
    },
    emits: ["save"],
    data() {
        return {
            saving: false,
            formData: {
                monitoring_management: "",
                monitoring_coordination_description: "",
                software_none: false,
                software_existing: false,
                software_shipment_tracking: false,
                software_shipment_tracking_description: "",
                software_others: false,
                software_others_description: "",
                communication_system: "",
                communication_system_description: "",
                project_execution_procedure: "",
                project_execution_sop_list: "",
            },
        };
    },
    watch: {
        forwarder: {
            handler(newVal) {
                if (newVal) {
                    this.loadData();
                }
            },
            immediate: true,
        },
        "formData.software_none"(newVal) {
            if (newVal) {
                this.formData.software_existing = false;
                this.formData.software_shipment_tracking = false;
                this.formData.software_others = false;
                this.formData.software_shipment_tracking_description = "";
                this.formData.software_others_description = "";
            }
        },
    },
    methods: {
        loadData() {
            if (this.forwarder) {
                Object.keys(this.formData).forEach((key) => {
                    if (this.forwarder[key] !== undefined) {
                        this.formData[key] = this.forwarder[key];
                    }
                });
            }
        },

        async saveData() {
            try {
                this.saving = true;
                this.$emit("save", this.formData);
            } catch (error) {
                console.error("Error saving facilities data:", error);
            } finally {
                this.saving = false;
            }
        },
    },
};
</script>

<style scoped>
.forwarder-facilities {
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
    /* margin-bottom: 0.5rem; */
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

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
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

.form-select,
.form-control,
textarea,
input[type="text"] {
    padding: 0.875rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
    width: 100%;
    position: relative;
}

.form-select:focus,
.form-control:focus,
textarea:focus,
input:focus {
    border-color: #007bff;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    transform: translateY(-1px);
}

.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.checkbox-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.checkbox-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
    transition: left 0.5s ease;
}

.checkbox-card:hover::before {
    left: 100%;
}

.checkbox-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.checkbox-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.checkbox-card .form-check-input {
    position: absolute;
    opacity: 0;
}

.checkbox-label {
    display: flex;
    align-items: center;
    font-size: 0.95rem;
    font-weight: 500;
    color: #495057;
    cursor: pointer;
    width: 100%;
}

.checkbox-label i {
    font-size: 1.1rem;
    margin-right: 0.75rem;
    color: #6c757d;
    transition: color 0.3s ease;
}

.checkbox-card:has(.form-check-input:checked) {
    border-color: #007bff;
    background: linear-gradient(145deg, #e3f2fd 0%, #ffffff 100%);
}

.checkbox-card:has(.form-check-input:checked) .checkbox-label i {
    color: #007bff;
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
    min-width: 160px;
    justify-content: center;
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

@keyframes spinner-border {
    100% {
        transform: rotate(360deg);
    }
}

@media (max-width: 1024px) {
    .forwarder-facilities {
        max-width: 100%;
        margin: 10px;
        padding: 0 10px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .checkbox-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
    
    .checkbox-grid {
        grid-template-columns: 1fr;
    }
}
</style>