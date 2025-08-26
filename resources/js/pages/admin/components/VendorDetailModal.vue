<template>
    <div class="modal-overlay" @click.self="$emit('close')">
        <div class="modal vendor-detail-modal">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="header-content">
                    <div class="vendor-header">
                        <div class="vendor-badge">
                            <span :class="['vendor-type', vendor.tipe_perusahaan.toLowerCase()]">
                                {{ getTypeName(vendor.tipe_perusahaan) }}
                            </span>
                        </div>
                        <h2>{{ vendor.nama_perusahaan }}</h2>
                        <span class="vendor-number">{{ vendor.nomor_vendor }}</span>
                    </div>
                    <button @click="$emit('close')" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Tab Navigation -->
                <div class="tab-navigation">
                    <button 
                        v-for="tab in tabs" 
                        :key="tab.key"
                        @click="activeTab = tab.key"
                        :class="['tab-button', { 'active': activeTab === tab.key }]"
                    >
                        <i :class="tab.icon"></i>
                        {{ tab.label }}
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="tab-content">
                    <!-- General Tab -->
                    <VendorGeneralTab 
                        v-if="activeTab === 'general'"
                        :vendor="vendor"
                    />
                    
                    <!-- Specific Tab (Forwarder/Distributor/etc) -->
                    <component
                        v-if="activeTab === getSpecificTabKey() && specificTabComponent"
                        :is="specificTabComponent"
                        :vendor="vendor"
                        :data="specificData"
                    />
                    
                    <!-- Debug info jika komponen tidak ditemukan -->
                    <div v-if="activeTab === getSpecificTabKey() && !specificTabComponent" class="debug-info">
                        <h3>Debug Info:</h3>
                        <p>Active Tab: {{ activeTab }}</p>
                        <p>Specific Tab Key: {{ getSpecificTabKey() }}</p>
                        <p>Vendor Type: {{ vendor.tipe_perusahaan }}</p>
                        <p>Component Found: {{ !!specificTabComponent }}</p>
                        <p>Data Available: {{ Object.keys(specificData).length }} keys</p>
                        <pre>{{ JSON.stringify(specificData, null, 2) }}</pre>
                    </div>
                    
                    <!-- Documents Tab -->
                    <VendorDocumentsTab 
                        v-if="activeTab === 'documents'"
                        :vendor="vendor"
                        :documents="documents"
                        @refresh="loadVendorData"
                    />
                    
                    <!-- Loading State -->
                    <div v-if="loading" class="loading-state">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                            <p>Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <div class="footer-actions">
                    <button @click="$emit('close')" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Tutup
                    </button>
                    <button 
                        v-if="!vendor.verified_at"
                        @click="$emit('verify', vendor)"
                        class="btn-verify"
                    >
                        <i class="fas fa-check"></i>
                        Verifikasi
                    </button>
                    <button 
                        v-if="vendor.verified_at"
                        @click="$emit('reject', vendor)"
                        class="btn-reject"
                    >
                        <i class="fas fa-times"></i>
                        Batalkan Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
// import VendorGeneralTab from './VendorTabs/VendorGeneralTab.vue'
import VendorDocumentsTab from './VendorTabs/VendorDocumentsTab.vue'

// FIX: Import dengan path yang benar
import SubcontractorTab from './VendorTabs/Subcontractor/index.vue'
import DistributorTab from './VendorTabs/Distributor/index.vue'
import ForwarderTab from './VendorTabs/Forwarder/index.vue'
import ManufacturerTab from './VendorTabs/Manufacturer/index.vue'

const props = defineProps({
    vendor: { type: Object, required: true }
})
const emit = defineEmits(['close', 'verify', 'reject'])

const loading = ref(false)
const specificData = ref({})
const documents = ref([])
const activeTab = ref('')

const tabComponents = {
    subcontractor: SubcontractorTab,
    distributor: DistributorTab,
    forwarder: ForwarderTab,
    manufacturer: ManufacturerTab
}

const tabs = computed(() => {
    const arr = []
    const specificTab = getSpecificTab(props.vendor.tipe_perusahaan)
    if (specificTab) arr.push(specificTab)
    arr.push({ key: 'documents', label: 'Dokumen', icon: 'fas fa-file-alt' })
    return arr
})

function getSpecificTab(type) {
    const map = {
        SC: { key: 'subcontractor', label: 'Detail Subcontractor', icon: 'fas fa-tools' },
        DS: { key: 'distributor', label: 'Detail Distributor', icon: 'fas fa-truck' },
        FW: { key: 'forwarder', label: 'Detail Forwarder', icon: 'fas fa-ship' },
        MF: { key: 'manufacturer', label: 'Detail Manufacturer', icon: 'fas fa-industry' }
    }
    return map[type] || null
}

function getSpecificTabKey() {
    const tab = getSpecificTab(props.vendor.tipe_perusahaan)
    return tab?.key
}

const specificTabComponent = computed(() => {
    const key = getSpecificTabKey()
    return tabComponents[key]
})

function getTypeName(type) {
    const types = {
        DS: "Distributor", SC: "Subcontractor", MF: "Manufacturer", FW: "Forwarder"
    }
    return types[type] || type
}

async function loadVendorData() {
    loading.value = true
    try {
        const response = await axios.get(`/api/admin/vendors/${props.vendor.id}`)
        if (response.data.success) {
            specificData.value = response.data.data.specific_data || {}
            documents.value = response.data.data.documents || []
        }
    } catch (error) {
        console.error('Error loading vendor data:', error)
        specificData.value = {}
        documents.value = []
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    // Set active tab ke tab spesifik vendor sebagai default
    const specificTabKey = getSpecificTabKey()
    activeTab.value = specificTabKey || 'documents'
    
    loadVendorData()
})
</script>

<style scoped>
/* ===== MODAL OVERLAY ===== */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6); /* Tingkatkan opacity dari 0.5 ke 0.6 */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999; /* Tingkatkan z-index */
    padding: 20px;
    backdrop-filter: blur(2px); /* Tambahkan blur effect */
}

/* ===== MODAL CONTAINER ===== */
.modal {
    background: white;
    border-radius: 12px;
    box-shadow: 
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 20px 25px -5px rgba(0, 0, 0, 0.1); /* Tingkatkan shadow */
    position: relative;
    z-index: 10000; /* Pastikan modal di atas overlay */
}

.vendor-detail-modal {
    max-width: 1200px;
    width: 95vw;
    max-height: 95vh;
    display: flex;
    flex-direction: column;
    background: white; /* Pastikan background solid */
}

/* ===== MODAL HEADER ===== */
.modal-header {
    padding: 0;
    border-bottom: none;
    background: white; /* Pastikan background solid */
    border-radius: 12px 12px 0 0;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    background: white; /* Pastikan background solid */
}

.vendor-header {
    display: flex;
    align-items: center;
    gap: 16px;
}

.vendor-badge {
    display: flex;
    align-items: center;
}

.vendor-type {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.vendor-type.ds {
    background: #e0e7ff;
    color: #3730a3;
}

.vendor-type.sc {
    background: #fef3c7;
    color: #92400e;
}

.vendor-type.mf {
    background: #d1fae5;
    color: #065f46;
}

.vendor-type.fw {
    background: #fed7d7;
    color: #991b1b;
}

.vendor-header h2 {
    margin: 0;
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 700;
}

.modal-close {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    font-size: 1.25rem;
    color: #6b7280;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #e5e7eb;
    color: #374151;
    border-color: #d1d5db;
}

/* ===== TAB NAVIGATION ===== */
.tab-navigation {
    display: flex;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    overflow-x: auto;
    border-radius: 0; /* Remove border radius */
}

.tab-button {
    flex: 1;
    padding: 16px 24px;
    background: none;
    border: none;
    color: #6b7280;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
    min-width: 150px;
    font-size: 0.875rem;
}

.tab-button:hover {
    color: #374151;
    background: #f3f4f6;
}

.tab-button.active {
    color: #1f2937;
    background: white;
    border-bottom-color: #1f2937;
    font-weight: 700;
}

/* ===== MODAL BODY ===== */
.modal-body {
    flex: 1;
    padding: 0;
    overflow-y: auto;
    min-height: 0;
    background: white; /* Pastikan background solid */
}

.tab-content {
    height: 100%;
    background: white; /* Pastikan background solid */
}

/* ===== MODAL FOOTER ===== */
.modal-footer {
    padding: 24px;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    border-radius: 0 0 12px 12px;
}

.footer-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
}

.btn-verify {
    background: #059669;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-verify:hover {
    background: #047857;
}

.btn-reject {
    background: #dc2626;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-reject:hover {
    background: #b91c1c;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .vendor-detail-modal {
        width: 100vw;
        height: 100vh;
        max-width: 100vw;
        max-height: 100vh;
        border-radius: 0;
    }
    
    .modal-header {
        border-radius: 0;
    }
    
    .header-content {
        padding: 16px;
    }
    
    .vendor-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .tab-button {
        min-width: 120px;
        padding: 12px 16px;
        font-size: 0.875rem;
    }
    
    .footer-actions {
        flex-direction: column;
    }
    
    .modal-footer {
        border-radius: 0;
    }
}

/* ===== ENSURE NO TRANSPARENCY ===== */
.modal,
.modal-header,
.modal-body,
.modal-footer,
.tab-content {
    background-color: white !important;
    opacity: 1 !important;
}

.tab-navigation {
    background-color: #f9fafb !important;
    opacity: 1 !important;
}

/* ===== DEBUG & LOADING STYLES ===== */
.debug-info {
    padding: 24px;
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 8px;
    margin: 24px;
}

.debug-info h3 {
    margin: 0 0 16px 0;
    color: #92400e;
}

.debug-info p {
    margin: 8px 0;
    color: #92400e;
    font-family: monospace;
}

.debug-info pre {
    background: #fffbeb;
    padding: 16px;
    border-radius: 4px;
    overflow-x: auto;
    font-size: 0.875rem;
    color: #92400e;
    max-height: 300px;
    overflow-y: auto;
}

.loading-state {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    text-align: center;
}

.loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.loading-spinner i {
    font-size: 2rem;
    color: #3b82f6;
}

.loading-spinner p {
    margin: 0;
    color: #6b7280;
    font-weight: 500;
}
</style>