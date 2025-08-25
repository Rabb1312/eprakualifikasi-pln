<template>
    <div class="manufacturer-container">
        <div v-if="loading" class="loading-state">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat data manufacturer...</p>
            </div>
        </div>

        <div v-else-if="!data && !vendor" class="empty-state">
            <div class="empty-content">
                <i class="fas fa-info-circle"></i>
                <h3>Belum Ada Data Manufacturer</h3>
                <p>Data khusus manufacturer belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>

        <div v-else class="manufacturer-tabs">
            <!-- Debug Info -->
            <!-- <div v-if="showDebug" class="debug-panel">
                <h4>Debug Info (Manufacturer):</h4>
                <p>Vendor: {{ vendor?.nama_perusahaan || 'No vendor' }}</p>
                <p>Data keys: {{ Object.keys(data || {}).length }}</p>
                <p>Active Tab: {{ activeSubTab }}</p>
                <p>Current Component: {{ currentComponentName }}</p>
                <p>Available Tabs: {{ subTabs.map(t => `${t.id}(${t.hasData})`).join(', ') }}</p>
                <details>
                    <summary>Database Fields</summary>
                    <div class="field-check">
                        <p>product_types: {{ !!data?.product_types }} {{ Array.isArray(data?.product_types) ? `(${data.product_types.length} items)` : typeof data?.product_types === 'string' ? '(text)' : typeof data?.product_types === 'object' ? '(object)' : '' }}</p>
                        <p>personnel: {{ !!data?.personnel }} {{ Array.isArray(data?.personnel) ? `(${data.personnel.length} items)` : typeof data?.personnel === 'string' ? '(text)' : typeof data?.personnel === 'object' ? '(object)' : '' }}</p>
                        <p>plants: {{ !!data?.plants }} {{ Array.isArray(data?.plants) ? `(${data.plants.length} items)` : typeof data?.plants === 'string' ? '(text)' : typeof data?.plants === 'object' ? '(object)' : '' }}</p>
                        <p>after_sales: {{ !!data?.after_sales }} {{ Array.isArray(data?.after_sales) ? `(${data.after_sales.length} items)` : typeof data?.after_sales === 'string' ? '(text)' : typeof data?.after_sales === 'object' ? '(object)' : '' }}</p>
                        <p>engineering_design: {{ !!data?.engineering_design }} {{ Array.isArray(data?.engineering_design) ? `(${data.engineering_design.length} items)` : typeof data?.engineering_design === 'string' ? '(text)' : typeof data?.engineering_design === 'object' ? '(object)' : '' }}</p>
                        <p>inventory: {{ !!data?.inventory }} {{ Array.isArray(data?.inventory) ? `(${data.inventory.length} items)` : typeof data?.inventory === 'string' ? '(text)' : typeof data?.inventory === 'object' ? '(object)' : '' }}</p>
                        <p>subcontracting: {{ !!data?.subcontracting }} {{ typeof data?.subcontracting === 'string' ? `(${data.subcontracting.length} chars)` : '' }}</p>
                        <p>code_standard: {{ !!data?.code_standard }} {{ Array.isArray(data?.code_standard) ? `(${data.code_standard.length} items)` : typeof data?.code_standard === 'string' ? '(text)' : typeof data?.code_standard === 'object' ? '(object)' : '' }}</p>
                    </div>
                </details>
                <details>
                    <summary>Raw Data Sample</summary>
                    <pre>{{ JSON.stringify(Object.fromEntries(Object.entries(data || {}).slice(0, 2)), null, 2) }}</pre>
                </details>
            </div> -->

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
                        :data="data || {}"
                        :vendor="vendor || {}"
                        @error="handleComponentError"
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
import ProductTypesCard from './ProductTypesCard.vue'
import PersonnelCard from './PersonnelCard.vue'
import PlantsCard from './PlantsCard.vue'
import AfterSalesCard from './AfterSalesCard.vue'
import EngineeringCard from './EngineeringCard.vue'
import InventoryCard from './InventoryCard.vue'
import SubcontractingCard from './SubcontractingCard.vue'
import CodeStandardCard from './CodeStandardCard.vue'
import DocumentsCard from './DocumentsCard.vue'


const props = defineProps({
    vendor: {
        type: Object,
        default: () => ({})
    },
    data: {
        type: Object,
        default: () => ({})
    }
})

const loading = ref(false)
const activeSubTab = ref('general')
const showDebug = ref(process.env.NODE_ENV === 'development')

// Data checkers sesuai dengan struktur database manufacturers
const hasGeneralData = computed(() => {
    return !!(props.vendor?.nama_perusahaan)
})

const hasProductTypesData = computed(() => {
    const data = props.data || {}
    return !!(data.product_types)
})

const hasPersonnelData = computed(() => {
    const data = props.data || {}
    return !!(data.personnel)
})

const hasPlantsData = computed(() => {
    const data = props.data || {}
    return !!(data.plants)
})

const hasAfterSalesData = computed(() => {
    const data = props.data || {}
    return !!(data.after_sales)
})

const hasEngineeringData = computed(() => {
    const data = props.data || {}
    return !!(data.engineering_design)
})

const hasInventoryData = computed(() => {
    const data = props.data || {}
    return !!(data.inventory)
})

const hasSubcontractingData = computed(() => {
    const data = props.data || {}
    return !!(data.subcontracting)
})

const hasCodeStandardData = computed(() => {
    const data = props.data || {}
    return !!(data.code_standard)
})

// Tab structure sesuai dengan database fields
const subTabs = computed(() => [
    {
        id: 'general',
        label: 'General Information',
        icon: 'fas fa-info-circle',
        hasData: hasGeneralData.value
    },
    {
        id: 'product_types',
        label: 'Product Types',
        icon: 'fas fa-boxes',
        hasData: hasProductTypesData.value
    },
    {
        id: 'personnel',
        label: 'Personnel',
        icon: 'fas fa-users',
        hasData: hasPersonnelData.value
    },
    {
        id: 'plants',
        label: 'Plants & Facilities',
        icon: 'fas fa-industry',
        hasData: hasPlantsData.value
    },
    {
        id: 'after_sales',
        label: 'After Sales Service',
        icon: 'fas fa-wrench',
        hasData: hasAfterSalesData.value
    },
    {
        id: 'engineering',
        label: 'Engineering Design',
        icon: 'fas fa-drafting-compass',
        hasData: hasEngineeringData.value
    },
    {
        id: 'inventory',
        label: 'Inventory Management',
        icon: 'fas fa-warehouse',
        hasData: hasInventoryData.value
    },
    {
        id: 'subcontracting',
        label: 'Subcontracting',
        icon: 'fas fa-handshake',
        hasData: hasSubcontractingData.value
    },
    {
        id: 'standards',
        label: 'Code & Standards',
        icon: 'fas fa-certificate',
        hasData: hasCodeStandardData.value
    },
    {
        id: 'documents',
        label: 'Documents',
        icon: 'fas fa-file-alt',
        hasData: true
    }
])

// Component mapping dengan komponen yang baru dibuat
const componentMap = {
    general: { component: GeneralInfoCard, name: 'GeneralInfoCard' },
    product_types: { component: ProductTypesCard, name: 'ProductTypesCard' },
    personnel: { component: PersonnelCard, name: 'PersonnelCard' },
    plants: { component: PlantsCard, name: 'PlantsCard' },
    after_sales: { component: AfterSalesCard, name: 'AfterSalesCard' },
    engineering: { component: EngineeringCard, name: 'EngineeringCard' },
    inventory: { component: InventoryCard, name: 'InventoryCard' },
    subcontracting: { component: SubcontractingCard, name: 'SubcontractingCard' },
    standards: { component: CodeStandardCard, name: 'CodeStandardCard' },
    documents: { component: DocumentsCard, name: 'DocumentsCard' }
}

const currentSubTabComponent = computed(() => {
    const tabData = componentMap[activeSubTab.value]
    if (!tabData) {
        console.error(`Component not found for tab: ${activeSubTab.value}`)
        return componentMap.general.component
    }
    return tabData.component
})

const currentComponentName = computed(() => {
    const tabData = componentMap[activeSubTab.value]
    return tabData ? tabData.name : 'Unknown'
})

function handleComponentError(error) {
    console.error('Component error:', error)
    activeSubTab.value = 'general'
}

onMounted(() => {
    console.log('Manufacturer tab mounted:', {
        vendor: props.vendor?.nama_perusahaan,
        dataKeys: Object.keys(props.data || {}),
        hasData: Object.keys(props.data || {}).length > 0,
        activeTab: activeSubTab.value,
        availableTabs: subTabs.value.map(t => `${t.id}(${t.hasData})`).join(', ')
    })
    
    // Set first tab with data as active
    const firstTabWithData = subTabs.value.find(tab => tab.hasData)
    if (firstTabWithData) {
        activeSubTab.value = firstTabWithData.id
    } else {
        activeSubTab.value = 'general'
    }
})
</script>

<style scoped>
.manufacturer-container {
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

.debug-panel {
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 8px;
    padding: 16px;
    margin: 16px 24px;
    font-size: 0.875rem;
}

.debug-panel h4 {
    margin: 0 0 12px 0;
    color: #92400e;
}

.debug-panel p {
    margin: 4px 0;
    color: #92400e;
    font-family: monospace;
}

.debug-panel details {
    margin-top: 12px;
}

.debug-panel summary {
    cursor: pointer;
    font-weight: 600;
    color: #92400e;
}

.debug-panel pre {
    background: #fffbeb;
    padding: 12px;
    border-radius: 4px;
    overflow-x: auto;
    font-size: 0.75rem;
    max-height: 200px;
    overflow-y: auto;
}

.field-check p {
    margin: 2px 0;
    font-size: 0.8rem;
}

.manufacturer-tabs {
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
    color: #7c3aed;
    border-bottom-color: #7c3aed;
    background: rgba(124, 58, 237, 0.05);
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
    
    .debug-panel {
        margin: 16px;
    }
}
</style>