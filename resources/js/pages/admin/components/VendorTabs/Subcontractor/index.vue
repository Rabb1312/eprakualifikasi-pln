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
                <p>
                    Data khusus subcontractor belum dilengkapi untuk vendor ini.
                </p>
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
                        :class="[
                            'sub-tab-button',
                            { active: activeSubTab === tab.id },
                        ]"
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
import { ref, computed, onMounted } from "vue";

// Import komponen khusus subcontractor
import GeneralInfoCard from "./GeneralInfoCard.vue";
import HoldingCompanyCard from "./HoldingCompanyCard.vue";
import FacilitiesCard from "./FacilitiesCard.vue";
import ScopeOfWorkCard from "./ScopeOfWorkCard.vue";
import MajorProjectsCard from "./MajorProjectsCard.vue";
import KnowledgeCard from "./KnowledgeCard.vue";
import DocumentsCard from "./DocumentsCard.vue";

const props = defineProps({
    vendor: {
        type: Object,
        required: true,
    },
    data: {
        type: Object,
        default: () => ({}),
    },
});

const loading = ref(false);
const activeSubTab = ref("general");

// Data checker
const hasGeneralData = computed(() => {
    // Sesuaikan field yang wajib untuk data general
    return !!props.vendor?.nama_perusahaan;
});

const subTabs = computed(() => [
    {
        id: "general",
        label: "General Information",
        icon: "fas fa-info-circle",
        hasData: hasGeneralData.value,
    },
    {
        id: "holding",
        label: "Holding Company",
        icon: "fas fa-building",
        hasData: !!(props.data?.holding_nama_perusahaan || props.data?.holding),
    },
    {
        id: "facilities",
        label: "Facilities",
        icon: "fas fa-tools",
        hasData: !!props.data?.facilities,
    },
    {
        id: "scope",
        label: "Scope of Work",
        icon: "fas fa-tasks",
        hasData: !!props.data?.scope_of_work,
    },
    {
        id: "projects",
        label: "Major Projects",
        icon: "fas fa-project-diagram",
        hasData: !!props.data?.major_projects,
    },
    {
        id: "knowledge",
        label: "Knowledge",
        icon: "fas fa-brain",
        hasData: !!props.data?.local_regulation_knowledge,
    },
    // {
    //     id: "documents",
    //     label: "Documents",
    //     icon: "fas fa-file-alt",
    //     hasData: true,
    // },
]);

// FIX: Mapping id tab ke variable komponen!
const componentMap = {
    general: GeneralInfoCard,
    holding: HoldingCompanyCard,
    facilities: FacilitiesCard,
    scope: ScopeOfWorkCard,
    projects: MajorProjectsCard,
    knowledge: KnowledgeCard,
    documents: DocumentsCard,
};

const currentSubTabComponent = computed(() => {
    // Ambil variable, bukan string!
    return componentMap[activeSubTab.value] || GeneralInfoCard;
});

onMounted(() => {
    // Set first tab with data as active
    const firstTabWithData = subTabs.value.find((tab) => tab.hasData);
    if (firstTabWithData) {
        activeSubTab.value = firstTabWithData.id;
    }
});
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
