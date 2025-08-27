<template>
    <div class="distributor-container">
        <div v-if="loading" class="loading-state">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat data distributor...</p>
            </div>
        </div>

        <div v-else-if="!data" class="empty-state">
            <div class="empty-content">
                <i class="fas fa-info-circle"></i>
                <h3>Belum Ada Data Distributor</h3>
                <p>Data khusus distributor belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>

        <div v-else class="distributor-tabs">
            <!-- Sub Tab Navigation -->
            <div class="sub-tab-navigation">
                <div class="nav-wrapper">
                    <button
                        v-for="tab in subTabs"
                        :key="tab.id"
                        @click="activeSubTab = tab.id"
                        :class="['sub-tab-button', { active: activeSubTab === tab.id }]"
                    >
                        <i :class="tab.icon"></i>
                        <span>{{ tab.label }}</span>
                        <div v-if="tab.hasData" class="data-indicator">
                            <i class="fas fa-check"></i>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Sub Tab Content -->
            <div class="sub-tab-content">
                <KeepAlive>
                    <component 
                        :is="currentSubTabComponent" 
                        :data="data"
                        :vendor="vendor"
                    />
                </KeepAlive>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Import sub tab components
import GeneralInfoCard from './GeneralInfoCard.vue'
import EngineeringCard from './EngineeringCard.vue'
import AfterSalesCard from './AfterSalesCard.vue'
import DocumentsCard from './DocumentsCard.vue'

const props = defineProps({
    vendor: {
        type: Object,
        required: true
    },
    data: {
        type: Object,
        default: () => ({})
    }
})

const loading = ref(false)
const activeSubTab = ref('general')

// FIX: Data checker untuk General Info - selalu true jika ada vendor
const hasGeneralData = computed(() => {
    // General info selalu ada jika vendor ada
    return !!(props.vendor?.nama_perusahaan)
})

// Data checker untuk Engineering
const hasEngineeringData = computed(() => {
    return !!(
        props.data?.engineering_activities || 
        props.data?.engineering?.engineering_activities
    )
})

// Data checker untuk After Sales
const hasAfterSalesData = computed(() => {
    return !!(
        props.data?.priority_treatment ||
        props.data?.complaint_agreement ||
        props.data?.has_contact_centre ||
        props.data?.after_sales
    )
})

// Data checker untuk Documents
const hasDocumentsData = computed(() => {
    return !!(props.data?.documents && Array.isArray(props.data.documents) && props.data.documents.length > 0)
})

const subTabs = computed(() => [
    {
        id: 'general',
        label: 'General Information',
        icon: 'fas fa-info-circle',
        hasData: hasGeneralData.value // sekarang akan selalu true
    },
    {
        id: 'engineering',
        label: 'Engineering',
        icon: 'fas fa-cogs',
        hasData: hasEngineeringData.value
    },
    {
        id: 'aftersales',
        label: 'After Sales',
        icon: 'fas fa-headset',
        hasData: hasAfterSalesData.value
    },
    // {
    //     id: 'documents',
    //     label: 'Attachments/Documents',
    //     icon: 'fas fa-file-alt',
    //     hasData: true // selalu tampil, meski kosong
    // }
])

// Mapping tab id ke variable komponen
const componentMap = {
    general: GeneralInfoCard,
    engineering: EngineeringCard,
    aftersales: AfterSalesCard,
    documents: DocumentsCard
}

const currentSubTabComponent = computed(() => {
    return componentMap[activeSubTab.value] || GeneralInfoCard
})

onMounted(() => {
    // FIX: Selalu mulai dari general, tidak perlu cari tab dengan data
    activeSubTab.value = 'general'
    
    // Atau jika ingin tetap logic pencarian, tapi prioritaskan general:
    // if (hasGeneralData.value) {
    //     activeSubTab.value = 'general'
    // } else {
    //     const firstTabWithData = subTabs.value.find(tab => tab.hasData)
    //     if (firstTabWithData) {
    //         activeSubTab.value = firstTabWithData.id
    //     } else {
    //         activeSubTab.value = 'general'
    //     }
    // }
})
</script>

<style scoped>
.distributor-container {
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 600px;
}

.loading-state,
.empty-state {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    text-align: center;
}

.loading-spinner i {
    font-size: 2rem;
    color: #3b82f6;
    margin-bottom: 16px;
}

.empty-content {
    max-width: 400px;
}

.empty-content i {
    font-size: 3rem;
    color: #6b7280;
    margin-bottom: 16px;
}

.empty-content h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 1.25rem;
}

.empty-content p {
    margin: 0;
    color: #6b7280;
    line-height: 1.5;
}

.distributor-tabs {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.sub-tab-navigation {
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    overflow-x: auto;
}

.nav-wrapper {
    display: flex;
    min-width: max-content;
    padding: 0 24px;
}

.sub-tab-button {
    background: none;
    border: none;
    padding: 12px 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.3s ease;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
    position: relative;
    font-size: 0.875rem;
}

.sub-tab-button:hover {
    background: rgba(0, 0, 0, 0.02);
    color: #374151;
}

.sub-tab-button.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
    background: rgba(37, 99, 235, 0.05);
    font-weight: 600;
}

.data-indicator {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 12px;
    height: 12px;
    background: #10b981;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.data-indicator i {
    font-size: 0.5rem;
    color: white;
}

.sub-tab-content {
    flex: 1;
    overflow-y: auto;
    background: white;
}

@media (max-width: 768px) {
    .nav-wrapper {
        padding: 0 16px;
    }
    
    .sub-tab-button {
        padding: 10px 12px;
    }
    
    .sub-tab-button span {
        display: none;
    }
    
    .sub-tab-button i {
        font-size: 1.125rem;
    }
}
</style>