<template>
    <div class="vendor-dashboard">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="vendor-info">
                <div class="vendor-avatar">
                    <i class="fas fa-hard-hat"></i>
                </div>
                <div class="vendor-details">
                    <h2>{{ vendor.nama_perusahaan || "Loading..." }}</h2>
                    <div class="vendor-meta">
                        <span class="vendor-number">{{
                            vendor.nomor_vendor
                        }}</span>
                        <span class="vendor-type">{{ vendorTypeName }}</span>
                        <span class="completion-badge" :class="completionClass">
                            {{ vendor.completion_percentage || 0 }}% Complete
                        </span>
                    </div>
                </div>
            </div>

            <div class="header-actions">
                <button class="btn btn-secondary" @click="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-container">
            <div class="spinner"></div>
            <p>Loading vendor data...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="error-container">
            <i class="fas fa-exclamation-triangle"></i>
            <p>{{ error }}</p>
            <button @click="loadData" class="btn btn-primary">Retry</button>
        </div>

        <!-- Tab System for Subcontractor -->
        <div v-else-if="vendor.tipe_perusahaan === 'SC'">
            <!-- Tab navigation & content: SUBCONTRACTOR - HARDCODE -->
            <div class="dashboard-main">
                <div class="tab-navigation">
                    <button
                        v-for="tab in subcontractorTabs"
                        :key="tab.id"
                        :class="['tab-btn', { active: activeTab === tab.id }]"
                        @click="activeTab = tab.id"
                    >
                        {{ tab.label }}
                    </button>
                </div>

                <div class="tab-content">
                    <SubcontractorGeneral
                        v-if="activeTab === 'general'"
                        :vendor="vendor"
                        @updated="onVendorUpdated"
                    />
                    <SubcontractorHolding
                        v-if="activeTab === 'holding'"
                        :subcontractor-data="subcontractorData"
                        @updated="onSubcontractorUpdated"
                    />
                    <SubcontractorFacilities
                        v-if="activeTab === 'facilities'"
                        :subcontractor-data="subcontractorData"
                        :facilities-options="facilitiesOptions"
                        @updated="onSubcontractorUpdated"
                    />
                    <SubcontractorEmployees
                        v-if="activeTab === 'employees'"
                        :subcontractor-data="subcontractorData"
                        :employee-classifications="employeeClassifications"
                        @updated="onSubcontractorUpdated"
                    />
                    <SubcontractorScope
                        v-if="activeTab === 'scope'"
                        :subcontractor-data="subcontractorData"
                        @updated="onSubcontractorUpdated"
                    />
                    <SubcontractorProjects
                        v-if="activeTab === 'projects'"
                        :subcontractor-data="subcontractorData"
                        @updated="onSubcontractorUpdated"
                    />
                    <SubcontractorKnowledge
                        v-if="activeTab === 'knowledge'"
                        :subcontractor-data="subcontractorData"
                        @updated="onSubcontractorUpdated"
                    />
                    <SubcontractorDocuments
                        v-if="activeTab === 'documents'"
                        :subcontractor-data="subcontractorData"
                        :document-checklist="documentChecklist"
                        @updated="onSubcontractorUpdated"
                    />
                </div>
            </div>
        </div>

        <!-- Tab System for Distributor -->
        <div v-else-if="vendor.tipe_perusahaan === 'DS'">
            <div class="dashboard-main">
                <div class="tab-navigation">
                    <button
                        v-for="tab in distributorTabs"
                        :key="tab.id"
                        :class="['tab-btn', { active: activeTab === tab.id }]"
                        @click="activeTab = tab.id"
                    >
                        <i :class="tab.icon"></i>
                        <span>{{ tab.label }}</span>
                    </button>
                </div>
                <div class="tab-content">
                    <component
                        :is="getDistributorComponent(activeTab)"
                        :vendor="vendor"
                        :distributor="distributor"
                        :options="options"
                        @save="handleDistributorSave"
                    />
                </div>
            </div>
        </div>

        <!-- Tab System for Forwarder -->
        <div v-else-if="vendor.tipe_perusahaan === 'FW'">
            <div class="dashboard-main">
                <div class="tab-navigation">
                    <button
                        v-for="tab in forwarderTabs"
                        :key="tab.id"
                        :class="['tab-btn', { active: activeTab === tab.id }]"
                        @click="activeTab = tab.id"
                    >
                        <i :class="tab.icon"></i>
                        <span>{{ tab.label }}</span>
                    </button>
                </div>
                <div class="tab-content">
                    <component
                        :is="getForwarderComponent(activeTab)"
                        :vendor="vendor"
                        :forwarder="forwarder"
                        :options="forwarderOptions"
                        @save="handleForwarderSave"
                    />
                </div>
            </div>
        </div>

        <div v-else-if="vendor.tipe_perusahaan === 'MF'">
            <div class="dashboard-main">
                <div class="tab-navigation">
                    <button
                        v-for="tab in manufactureTabs"
                        :key="tab.id"
                        :class="['tab-btn', { active: activeTab === tab.id }]"
                        @click="activeTab = tab.id"
                    >
                        <i :class="tab.icon"></i>
                        <span>{{ tab.label }}</span>
                    </button>
                </div>
                <div class="tab-content">
                    <component
                        :is="getManufactureComponent(activeTab)"
                        :vendor="vendor"
                        :manufacture="manufacture"
                        @save="handleManufactureSave"
                    />
                </div>
            </div>
        </div>

        <!-- Alert Component -->
        <AlertComponent
            v-if="alertMsg"
            :type="alertType"
            :message="alertMsg"
            @close="alertMsg = ''"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

// Import components
import SubcontractorGeneral from "./components/subcontractor/SubcontractorGeneral.vue";
import SubcontractorHolding from "./components/subcontractor/SubcontractorHolding.vue";
import SubcontractorFacilities from "./components/subcontractor/SubcontractorFacilities.vue";
import SubcontractorEmployees from "./components/subcontractor/SubcontractorEmployees.vue";
import SubcontractorScope from "./components/subcontractor/SubcontractorScope.vue";
import SubcontractorProjects from "./components/subcontractor/SubcontractorProjects.vue";
import SubcontractorKnowledge from "./components/subcontractor/SubcontractorKnowledge.vue";
import SubcontractorDocuments from "./components/subcontractor/SubcontractorDocuments.vue";

import DistributorGeneral from "./components/distributor/DistributorGeneral.vue";
import DistributorEngineering from "./components/distributor/DistributorEngineering.vue";
import DistributorAfterSales from "./components/distributor/DistributorAfterSales.vue";
import DistributorDocuments from "./components/distributor/DistributorDocuments.vue";

import ForwarderGeneral from "./components/forwarder/ForwarderGeneral.vue";
import ForwarderOtherServices from "./components/forwarder/ForwarderOtherServices.vue";
import ForwarderFacilities from "./components/forwarder/ForwarderFacilities.vue";
import ForwarderBranchOffice from "./components/forwarder/ForwarderBranchOffice.vue";
import ForwarderLineService from "./components/forwarder/ForwarderLineService.vue";
import ForwarderInsurance from "./components/forwarder/ForwarderInsurance.vue";
import ForwarderManpowerSupport from "./components/forwarder/ForwarderManpowerSupport.vue";
import ForwarderDocuments from "./components/forwarder/ForwarderDocuments.vue";

import ManufactureGeneral from "./components/manufacture/ManufactureGeneral.vue";
import ManufactureProductTypes from "./components/manufacture/ManufactureProductTypes.vue";
import ManufacturePersonnel from "./components/manufacture/ManufacturePersonnel.vue";
import ManufacturePlants from "./components/manufacture/ManufacturePlants.vue";
import ManufactureAfterSales from "./components/manufacture/ManufactureAfterSales.vue";
import ManufactureEngineering from "./components/manufacture/ManufactureEngineering.vue";
import ManufactureInventory from "./components/manufacture/ManufactureInventory.vue";
import ManufactureSubcontracting from "./components/manufacture/ManufactureSubcontracting.vue";
import ManufactureCodeStandard from "./components/manufacture/ManufactureCodeStandard.vue";
import ManufactureDocuments from "./components/manufacture/ManufactureDocuments.vue";

import AlertComponent from "./components/AlertComponent.vue";

const router = useRouter();

// Reactive data
const loading = ref(true);
const error = ref("");
const activeTab = ref("general");
const user = ref({});
const vendor = ref({});
const subcontractorData = ref({});
const subcontractorTabs = ref([
    { id: "general", label: "General Information" },
    { id: "holding", label: "Holding Company" },
    { id: "facilities", label: "Facilities Company" },
    { id: "employees", label: "Total Permanent Construction Employees" },
    { id: "scope", label: "Scope of Work" },
    { id: "projects", label: "Major Projects 3-5" },
    { id: "knowledge", label: "Knowledgeable" },
    { id: "documents", label: "Documents/Attachments" },
]);
const facilitiesOptions = ref({});
const employeeClassifications = ref([]);
const documentChecklist = ref([]);
const alertMsg = ref("");
const alertType = ref("success");
const options = ref({});

// Distributor
const distributorTabs = ref([]);
const distributor = ref({});

// Forwrder
const forwarderTabs = ref([]);
const forwarder = ref({});
const forwarderOptions = ref({});

// Manufacture
const manufactureTabs = ref([]);
const manufacture = ref({});

axios.defaults.baseURL = "http://eprakualifikasi-pln.test";

// Computed
const vendorTypeName = computed(() => {
    switch (vendor.value.tipe_perusahaan) {
        case "SC":
            return "Subcontractor";
        case "DS":
            return "Distributor";
        case "FW":
            return "Forwarder";
        case "MF":
            return "Manufacturer/Fabrication";
        default:
            return "Unknown Type";
    }
});

const completionClass = computed(() => {
    const percentage = vendor.value.completion_percentage || 0;
    if (percentage >= 80) return "high";
    if (percentage >= 50) return "medium";
    return "low";
});

// Lifecycle & Data Load
onMounted(async () => {
    await loadData();
});

async function loadData() {
    loading.value = true;
    error.value = "";

    try {
        await loadUserData();
        if (vendor.value.tipe_perusahaan === "SC") {
            await loadSubcontractorTabs();
        } else if (vendor.value.tipe_perusahaan === "DS") {
            await loadDistributorTabs();
        } else if (vendor.value.tipe_perusahaan === "FW") {
            await loadForwarderTabs();
        } else if (vendor.value.tipe_perusahaan === "MF") {
            await loadManufactureTabs();
        }
        // Add more if you support other vendor types
        console.log("✅ Vendor data loaded successfully");
    } catch (err) {
        console.error("❌ Error loading data:", err);
        error.value = err.message || "Failed to load data";
    } finally {
        loading.value = false;
    }
}

async function loadUserData() {
    try {
        const token = localStorage.getItem("token");
        if (!token) {
            router.push("/login");
            return;
        }

        const response = await axios.get("/api/me", {
            headers: { Authorization: `Bearer ${token}` },
        });

        if (response.data.success) {
            user.value = response.data.data.user;
            vendor.value = response.data.data.vendor || {};
        } else {
            throw new Error("Failed to load user data");
        }
    } catch (error) {
        if (error.response?.status === 401) {
            localStorage.removeItem("token");
            router.push("/login");
        } else {
            throw error;
        }
    }
}

async function loadSubcontractorTabs() {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/api/vendor/subcontractor/tabs", {
            headers: { Authorization: `Bearer ${token}` },
        });
        if (response.data.success) {
            const data = response.data.data;
            subcontractorTabs.value = data.tabs || subcontractorTabs.value;
            subcontractorData.value = data.subcontractor_data || {};
            facilitiesOptions.value = data.facilities_options || {};
            employeeClassifications.value = data.employee_classifications || [];
            // documentChecklist.value = data.document_checklist || []
        } else {
            throw new Error(
                response.data.message || "Failed to load subcontractor tabs"
            );
        }
    } catch (error) {
        throw error;
    }
}

async function loadDistributorTabs() {
    try {
        const token = localStorage.getItem("token");
        const res = await axios.get(
            "/api/vendor/distributor/tabs",
            {},
            {
                headers: { Authorization: `Bearer ${token}` },
            }
        );
        if (res.data.success) {
            distributorTabs.value = res.data.data.tabs;
            distributor.value = res.data.data.distributor;
            options.value = res.data.data.options;
            // You may want to load checklist/documents if needed
        } else {
            throw new Error(
                res.data.message || "Failed to load distributor tabs"
            );
        }
    } catch (err) {
        throw err;
    }
}

async function loadForwarderTabs() {
    try {
        const token = localStorage.getItem("token");
        const res = await axios.get("/api/vendor/forwarder/tabs", {
            headers: { Authorization: `Bearer ${token}` },
        });
        if (res.data.success) {
            forwarderTabs.value = res.data.data.tabs;
            forwarder.value = res.data.data.forwarder_data;
            forwarderOptions.value = res.data.data.options;
        } else {
            throw new Error(
                res.data.message || "Failed to load forwarder tabs"
            );
        }
    } catch (err) {
        throw err;
    }
}

async function loadManufactureTabs() {
    try {
        const token = localStorage.getItem("token");
        const res = await axios.get("/api/vendor/manufacture/tabs", {
            headers: { Authorization: `Bearer ${token}` },
        });
        if (res.data.success) {
            manufactureTabs.value = res.data.data.tabs;
            manufacture.value = res.data.data.manufacture_data;
        } else {
            throw new Error(
                res.data.message || "Failed to load manufacture tabs"
            );
        }
    } catch (err) {
        throw err;
    }
}

// Mapping distributor tab id to component
function getDistributorComponent(tabId) {
    switch (tabId) {
        case "general":
            return DistributorGeneral;
        case "engineering":
            return DistributorEngineering;
        case "after_sales":
            return DistributorAfterSales;
        case "documents":
            return DistributorDocuments;
        default:
            return null;
    }
}

function getForwarderComponent(tabId) {
    switch (tabId) {
        case "general":
            return ForwarderGeneral;
        case "other_services":
            return ForwarderOtherServices;
        case "facilities":
            return ForwarderFacilities;
        case "branch_office":
            return ForwarderBranchOffice;
        case "line_service":
            return ForwarderLineService;
        case "insurance":
            return ForwarderInsurance;
        case "manpower_support":
            return ForwarderManpowerSupport;
        case "documents":
            return ForwarderDocuments;
        default:
            return null;
    }
}

function getManufactureComponent(tabId) {
    switch (tabId) {
        case "general":
            return ManufactureGeneral;
        case "product_types":
            return ManufactureProductTypes;
        case "personnel":
            return ManufacturePersonnel;
        case "plants":
            return ManufacturePlants;
        case "after_sales":
            return ManufactureAfterSales;
        case "engineering_design":
            return ManufactureEngineering;
        case "inventory":
            return ManufactureInventory;
        case "subcontracting":
            return ManufactureSubcontracting;
        case "code_standard":
            return ManufactureCodeStandard;
        case "documents":
            return ManufactureDocuments;
        default:
            return null;
    }
}

// Handler for save distributor data
async function handleDistributorSave(data) {
    try {
        const token = localStorage.getItem("token");
        const res = await axios.put("/api/vendor/distributor", data, {
            headers: { Authorization: `Bearer ${token}` },
        });
        if (res.data.success) {
            distributor.value = res.data.data.distributor;
            vendor.value.completion_percentage =
                res.data.data.completion_percentage;
            showAlert("success", "Distributor data updated successfully");
            await loadDistributorTabs(); // refresh tabs and completed status
        } else {
            showAlert("error", res.data.message);
        }
    } catch (err) {
        showAlert("error", err.message || "Failed to save distributor data");
    }
}

async function handleManufactureSave(data) {
    try {
        const token = localStorage.getItem("token");
        const res = await axios.put("/api/vendor/manufacture", data, {
            headers: { Authorization: `Bearer ${token}` },
        });
        if (res.data.success) {
            manufacture.value = res.data.data.manufacture_data;
            showAlert("success", "Manufacturer data updated successfully");
            await loadManufactureTabs();
        } else {
            showAlert("error", res.data.message);
        }
    } catch (err) {
        showAlert("error", err.message || "Failed to save manufacturer data");
    }
}

// Handler for save subcontractor data
async function onVendorUpdated(updatedVendor) {
    vendor.value = { ...vendor.value, ...updatedVendor };
    showAlert("success", "Vendor data updated successfully");
}

async function onSubcontractorUpdated(updatedData) {
    subcontractorData.value = { ...subcontractorData.value, ...updatedData };
    showAlert("success", "Subcontractor data updated successfully");
}

// Logout
async function logout() {
    try {
        const token = localStorage.getItem("token");
        if (token) {
            await axios.post("/api/logout", null, {
                headers: { Authorization: `Bearer ${token}` },
            });
        }
    } catch (error) {
        console.error("Logout error:", error);
    } finally {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        router.push("/login");
    }
}

// Alert utility
function showAlert(type, message) {
    alertType.value = type;
    alertMsg.value = message;
    setTimeout(() => {
        alertMsg.value = "";
    }, 5000);
}
</script>

<style scoped>
.vendor-dashboard {
    min-height: 100vh;
    background: #f8f9fa;
}

.dashboard-header {
    background: white;
    padding: 2rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.vendor-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.vendor-avatar {
    width: 60px;
    height: 60px;
    background: #007bff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.vendor-details h2 {
    margin: 0 0 0.5rem 0;
    color: #212529;
}

.vendor-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.vendor-number {
    background: #e9ecef;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.vendor-type {
    background: #007bff;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.completion-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.completion-badge.high {
    background: #d4edda;
    color: #155724;
}

.completion-badge.medium {
    background: #fff3cd;
    color: #856404;
}

.completion-badge.low {
    background: #f8d7da;
    color: #721c24;
}

.loading-container,
.error-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    gap: 1rem;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.dashboard-main {
    display: flex;
    min-height: calc(100vh - 120px);
}

.tab-navigation {
    width: 280px;
    background: white;
    border-right: 1px solid #dee2e6;
    padding: 1rem 0;
}

.tab-btn {
    width: 100%;
    padding: 1rem 1.5rem;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #6c757d;
}

.tab-btn:hover {
    background: #f8f9fa;
    color: #495057;
}

.tab-btn.active {
    background: #007bff;
    color: white;
    border-right: 3px solid #0056b3;
}

.tab-btn i {
    width: 20px;
    font-size: 16px;
}

.tab-content {
    flex: 1;
    padding: 2rem;
    background: white;
    overflow-y: auto;
}

.btn {
    padding: 0.5rem 1rem;
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
    background: #0056b3;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    margin-right: 10px;
}

.btn-secondary:hover:not(:disabled) {
    background: #ff0000;
}
</style>
