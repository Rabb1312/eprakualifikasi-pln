<template>
    <div class="subcontractor-container">
        <div v-if="loading" class="loading-state">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat data subcontractor...</p>
            </div>
        </div>

        <div v-else-if="!data" class="empty-state">
            <div class="empty-content">
                <i class="fas fa-info-circle"></i>
                <h3>Belum Ada Data Subcontractor</h3>
                <p>Data khusus subcontractor belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>

        <div v-else class="subcontractor-tabs">
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

// Import komponen khusus subcontractor
import HoldingCompanyCard from './HoldingCompanyCard.vue'
import FacilitiesCard from './FacilitiesCard.vue'
import ScopeOfWorkCard from './ScopeOfWorkCard.vue'
import MajorProjectsCard from './MajorProjectsCard.vue'
import KnowledgeCard from './KnowledgeCard.vue'
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
const activeSubTab = ref('holding')

const subTabs = computed(() => [
    {
        id: 'holding',
        label: 'Holding Company',
        icon: 'fas fa-building',
        component: 'HoldingCompanyCard',
        hasData: hasHoldingData.value
    },
    {
        id: 'facilities',
        label: 'Facilities',
        icon: 'fas fa-tools',
        component: 'FacilitiesCard',
        hasData: !!(props.data?.facilities)
    },
    {
        id: 'scope',
        label: 'Scope of Work',
        icon: 'fas fa-tasks',
        component: 'ScopeOfWorkCard',
        hasData: !!(props.data?.scope_of_work)
    },
    {
        id: 'projects',
        label: 'Major Projects',
        icon: 'fas fa-project-diagram',
        component: 'MajorProjectsCard',
        hasData: !!(props.data?.major_projects)
    },
    {
        id: 'knowledge',
        label: 'Knowledge',
        icon: 'fas fa-brain',
        component: 'KnowledgeCard',
        hasData: !!(props.data?.local_regulation_knowledge)
    },
    {
        id: 'documents',
        label: 'Documents',
        icon: 'fas fa-file-alt',
        component: 'DocumentsCard',
        hasData: true
    }
])

const hasHoldingData = computed(() => {
    if (!props.data) return false
    
    return !!(
        props.data.holding_nama_perusahaan ||
        props.data.holding_tanggal_berdiri ||
        props.data.holding_alamat ||
        props.data.holding_phone ||
        props.data.holding_modal_dasar ||
        props.data.holding_modal_dikeluarkan ||
        props.data.holding_pemegang_saham ||
        props.data.holding_contact_person ||
        props.data.holding_nama_direktur
    )
})

const currentSubTabComponent = computed(() => {
    const tab = subTabs.value.find(t => t.id === activeSubTab.value)
    return tab?.component || 'HoldingCompanyCard'
})

onMounted(() => {
    // Set first tab with data as active
    const firstTabWithData = subTabs.value.find(tab => tab.hasData)
    if (firstTabWithData) {
        activeSubTab.value = firstTabWithData.id
    }
})
</script>

<style scoped>
.subcontractor-container {
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

.subcontractor-tabs {
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
    color: #1f2937;
    border-bottom-color: #1f2937;
    background: rgba(0, 0, 0, 0.02);
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