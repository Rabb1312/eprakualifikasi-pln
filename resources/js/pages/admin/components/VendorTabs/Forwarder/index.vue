<template>
    <div class="forwarder-container">
        <div v-if="loading" class="loading-state">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat data forwarder...</p>
            </div>
        </div>

        <div v-else-if="!data && !vendor" class="empty-state">
            <div class="empty-content">
                <i class="fas fa-info-circle"></i>
                <h3>Belum Ada Data Forwarder</h3>
                <p>Data khusus forwarder belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>

        <div v-else class="forwarder-tabs">
            <!-- Debug Info -->
            <!-- <div v-if="showDebug" class="debug-panel">
                <h4>Debug Info:</h4>
                <p>Vendor: {{ vendor?.nama_perusahaan || 'No vendor' }}</p>
                <p>Data keys: {{ Object.keys(data || {}).length }}</p>
                <p>Active Tab: {{ activeSubTab }}</p>
                <p>Current Component: {{ currentComponentName }}</p>
                <p>Available Tabs: {{ subTabs.map(t => `${t.id}(${t.hasData})`).join(', ') }}</p>
                <details>
                    <summary>Component Map</summary>
                    <pre>{{ JSON.stringify(Object.keys(componentMap), null, 2) }}</pre>
                </details>
                <details>
                    <summary>Sample Data (first 5 keys)</summary>
                    <pre>{{ JSON.stringify(Object.fromEntries(Object.entries(data || {}).slice(0, 5)), null, 2) }}</pre>
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
import OtherServicesCard from './OtherServicesCard.vue'
import FacilitiesCard from './FacilitiesCard.vue'
import BranchOfficesCard from './BranchOfficesCard.vue'
import LineServiceCard from './LineServiceCard.vue'
import InsuranceCard from './InsuranceCard.vue'
import ManpowerSupportCard from './ManpowerSupportCard.vue'
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

// Data checkers tetap sama...
const hasGeneralData = computed(() => {
    return !!(props.vendor?.nama_perusahaan)
})

const hasOtherServicesData = computed(() => {
    const data = props.data || {}
    return !!(
        data.other_services_none !== undefined ||
        data.able_to_conduct_expediting !== undefined ||
        data.able_to_submit_regular_status_report !== undefined ||
        data.able_to_have_scope_electrical_supervision !== undefined ||
        data.having_ability_make_temporary_jetty !== undefined ||
        data.other_services_others !== undefined ||
        data.other_services_others_description
    )
})

const hasFacilitiesData = computed(() => {
    const data = props.data || {}
    return !!(
        data.heavy_lift_capability ||
        data.heavy_lift_cooperation_companies ||
        data.monitoring_management ||
        data.monitoring_coordination_description ||
        data.software_none !== undefined ||
        data.software_existing !== undefined ||
        data.software_shipment_tracking !== undefined ||
        data.software_others !== undefined ||
        data.communication_system ||
        data.communication_system_description ||
        data.project_execution_procedure ||
        data.project_execution_sop_list
    )
})

const hasBranchOfficesData = computed(() => {
    const data = props.data || {}
    return !!(
        (Array.isArray(data.indonesian_branch_offices) && data.indonesian_branch_offices.length > 0) ||
        (Array.isArray(data.overseas_branch_offices) && data.overseas_branch_offices.length > 0)
    )
})

const hasLineServiceData = computed(() => {
    const data = props.data || {}
    return !!(
        (Array.isArray(data.shipping_lines) && data.shipping_lines.length > 0) ||
        (Array.isArray(data.airlines) && data.airlines.length > 0) ||
        data.shipping_line_relation ||
        data.airlines_relation
    )
})

const hasInsuranceData = computed(() => {
    const data = props.data || {}
    return !!(
        data.insurance_none !== undefined ||
        data.insurance_all_risk_covered_by_company !== undefined ||
        data.insurance_liability_with_insurance_company !== undefined ||
        data.insurance_negotiable_with_without_insurance !== undefined ||
        data.insurance_others !== undefined ||
        data.insurance_others_description ||
        data.insurance_claim_arrangement_description
    )
})

const hasManpowerData = computed(() => {
    const data = props.data || {}
    return !!(
        data.manpower_support ||
        (Array.isArray(data.manpower_certifications) && data.manpower_certifications.length > 0)
    )
})

const subTabs = computed(() => [
    {
        id: 'general',
        label: 'General Information',
        icon: 'fas fa-info-circle',
        hasData: hasGeneralData.value
    },
    {
        id: 'services',
        label: 'Other Services',
        icon: 'fas fa-concierge-bell',
        hasData: hasOtherServicesData.value
    },
    {
        id: 'facilities',
        label: 'Facilities',
        icon: 'fas fa-warehouse',
        hasData: hasFacilitiesData.value
    },
    {
        id: 'branches',
        label: 'Branch Offices',
        icon: 'fas fa-building',
        hasData: hasBranchOfficesData.value
    },
    {
        id: 'lineservice',
        label: 'Line Service',
        icon: 'fas fa-route',
        hasData: hasLineServiceData.value
    },
    {
        id: 'insurance',
        label: 'Insurance',
        icon: 'fas fa-shield-alt',
        hasData: hasInsuranceData.value
    },
    {
        id: 'manpower',
        label: 'Manpower Support',
        icon: 'fas fa-users',
        hasData: hasManpowerData.value
    },
    {
        id: 'documents',
        label: 'Attachments/Documents',
        icon: 'fas fa-file-alt',
        hasData: true
    }
])

// FIX: Component mapping dengan nama yang jelas
const componentMap = {
    general: { component: GeneralInfoCard, name: 'GeneralInfoCard' },
    services: { component: OtherServicesCard, name: 'OtherServicesCard' },
    facilities: { component: FacilitiesCard, name: 'FacilitiesCard' },
    branches: { component: BranchOfficesCard, name: 'BranchOfficesCard' },
    lineservice: { component: LineServiceCard, name: 'LineServiceCard' },
    insurance: { component: InsuranceCard, name: 'InsuranceCard' },
    manpower: { component: ManpowerSupportCard, name: 'ManpowerSupportCard' },
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
    console.log('Forwarder tab mounted:', {
        vendor: props.vendor?.nama_perusahaan,
        dataKeys: Object.keys(props.data || {}),
        hasData: Object.keys(props.data || {}).length > 0,
        activeTab: activeSubTab.value,
        componentMap: Object.keys(componentMap)
    })
    
    activeSubTab.value = 'general'
})
</script>

<style scoped>
.forwarder-container {
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

.forwarder-tabs {
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
    color: #dc2626;
    border-bottom-color: #dc2626;
    background: rgba(220, 38, 38, 0.05);
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