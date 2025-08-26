<template>
    <div class="engineering-card">
        <div v-if="hasData" class="engineering-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-drafting-compass"></i>
                </div>
                <div class="header-info">
                    <h3>Engineering Design</h3>
                    <p>Kapabilitas engineering dan prosedur design yang tersedia di perusahaan</p>
                </div>
                <!-- <div class="engineering-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ configurationScore }}%</span>
                        <span class="stat-label">Configured</span>
                    </div>
                </div> -->
            </div>

            <div class="content-sections">
                <!-- Engineering Configuration Grid -->
                <div class="engineering-grid">
                    <!-- Principal Engineering Capability -->
                    <div class="capability-item">
                        <div class="capability-header">
                            <div class="capability-icon principal">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="capability-info">
                                <h5>Principal Engineering Capability</h5>
                                <span class="capability-category">Core Engineering Services</span>
                                <div class="capability-status">
                                    <span :class="['status-badge', engineeringData.principal_engineering_capability ? 'configured' : 'not-configured']">
                                        <i :class="engineeringData.principal_engineering_capability ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                                        {{ engineeringData.principal_engineering_capability ? 'Configured' : 'Not Configured' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="capability-details">
                            <div class="capability-description">
                                <h6>Engineering Capabilities:</h6>
                                <div class="description-content">
                                    <p>{{ engineeringData.principal_engineering_capability || 'Kapabilitas engineering utama belum didefinisikan.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Design Control Responsibility -->
                    <div class="capability-item">
                        <div class="capability-header">
                            <div class="capability-icon control">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div class="capability-info">
                                <h5>Design Control Responsibility</h5>
                                <span class="capability-category">Quality Management</span>
                                <div class="capability-status">
                                    <span :class="['status-badge', engineeringData.responsible_design_control ? 'configured' : 'not-configured']">
                                        <i :class="engineeringData.responsible_design_control ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                                        {{ engineeringData.responsible_design_control ? 'Defined' : 'Not Defined' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="capability-details">
                            <div class="responsibility-info">
                                <h6>Design Control Responsibility:</h6>
                                <div class="responsibility-content">
                                    <div class="responsibility-item">
                                        <i class="fas fa-user-check"></i>
                                        <span>{{ engineeringData.responsible_design_control || 'Penanggung jawab design control belum ditentukan.' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documented Procedures -->
                    <div class="capability-item">
                        <div class="capability-header">
                            <div class="capability-icon procedures">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="capability-info">
                                <h5>Documented Procedures</h5>
                                <span class="capability-category">Process Documentation</span>
                                <div class="capability-status">
                                    <span :class="['status-badge', getProceduresStatusClass()]">
                                        <i :class="getProceduresStatusIcon()"></i>
                                        {{ getProceduresStatusText() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="capability-details">
                            <div class="procedures-info">
                                <h6>Procedure Status:</h6>
                                <div class="procedures-content">
                                    <div class="procedure-status-item">
                                        <i :class="engineeringData.documented_procedures === 'yes' ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger'"></i>
                                        <span>{{ engineeringData.documented_procedures === 'yes' ? 'Prosedur terdokumentasi tersedia' : engineeringData.documented_procedures === 'no' ? 'Prosedur tidak tersedia' : 'Status belum ditentukan' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Design Involvement Depth -->
                    <div class="capability-item">
                        <div class="capability-header">
                            <div class="capability-icon involvement">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="capability-info">
                                <h5>Design Involvement Depth</h5>
                                <span class="capability-category">Service Level</span>
                                <div class="capability-status">
                                    <span :class="['status-badge', getInvolvementCount() > 0 ? 'configured' : 'not-configured']">
                                        <i :class="getInvolvementCount() > 0 ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
                                        {{ getInvolvementCount() }} Level{{ getInvolvementCount() !== 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="capability-details">
                            <div class="involvement-levels">
                                <h6>Service Levels:</h6>
                                <div class="levels-grid">
                                    <div class="level-item design-works" :class="{ active: hasDesignWorks() }">
                                        <div class="level-icon">
                                            <i class="fas fa-pencil-ruler"></i>
                                        </div>
                                        <div class="level-info">
                                            <span class="level-title">Design Works</span>
                                            <small>Basic Design & Conceptual Engineering</small>
                                        </div>
                                        <div class="level-status">
                                            <i :class="hasDesignWorks() ? 'fas fa-check-circle text-success' : 'fas fa-minus-circle text-muted'"></i>
                                        </div>
                                    </div>

                                    <div class="level-item shop-drawings" :class="{ active: hasShopDrawings() }">
                                        <div class="level-icon">
                                            <i class="fas fa-drafting-compass"></i>
                                        </div>
                                        <div class="level-info">
                                            <span class="level-title">Shop Drawings</span>
                                            <small>Detail Design & Technical Drawings</small>
                                        </div>
                                        <div class="level-status">
                                            <i :class="hasShopDrawings() ? 'fas fa-check-circle text-success' : 'fas fa-minus-circle text-muted'"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="involvement-summary" v-if="getInvolvementCount() > 0">
                                <h6>Active Services:</h6>
                                <div class="services-badges">
                                    <span 
                                        v-for="involvement in getActiveInvolvements()"
                                        :key="involvement"
                                        class="service-badge"
                                    >
                                        <i :class="getInvolvementIcon(involvement)"></i>
                                        {{ getInvolvementLabel(involvement) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-drafting-compass"></i>
                <h4>Belum Ada Data Engineering Design</h4>
                <p>Konfigurasi engineering design belum dilengkapi untuk manufacturer ini</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    vendor: {
        type: Object,
        required: true
    }
})

const hasData = computed(() => {
    return !!(props.data.engineering_design && Object.keys(props.data.engineering_design).length > 0)
})

const engineeringData = computed(() => {
    if (!props.data.engineering_design) return {}
    
    // Handle JSON field dari database
    if (typeof props.data.engineering_design === 'string') {
        try {
            return JSON.parse(props.data.engineering_design)
        } catch {
            return {}
        }
    }
    
    return props.data.engineering_design || {}
})

// Helper functions sesuai dengan ManufactureEngineering.vue
const configurationScore = computed(() => {
    const items = [
        engineeringData.value.principal_engineering_capability,
        engineeringData.value.responsible_design_control,
        engineeringData.value.documented_procedures,
        engineeringData.value.depth_design_involvement?.length > 0
    ]
    
    const configuredItems = items.filter(item => item && item !== '').length
    return Math.round((configuredItems / items.length) * 100)
})

function getPrincipalCapabilities() {
    const text = engineeringData.value.principal_engineering_capability || ''
    if (!text) return []
    
    // Split by common separators and clean up
    return text
        .split(/[,;.\n]/)
        .map(cap => cap.trim())
        .filter(cap => cap.length > 0)
        .slice(0, 5) // Limit to 5 items for display
}

function getProceduresStatusClass() {
    if (engineeringData.value.documented_procedures === 'yes') return 'available'
    if (engineeringData.value.documented_procedures === 'no') return 'unavailable'
    return 'not-configured'
}

function getProceduresStatusIcon() {
    if (engineeringData.value.documented_procedures === 'yes') return 'fas fa-check-circle'
    if (engineeringData.value.documented_procedures === 'no') return 'fas fa-times-circle'
    return 'fas fa-exclamation-circle'
}

function getProceduresStatusText() {
    if (engineeringData.value.documented_procedures === 'yes') return 'Available'
    if (engineeringData.value.documented_procedures === 'no') return 'Not Available'
    return 'Not Configured'
}

function getInvolvementCount() {
    return Array.isArray(engineeringData.value.depth_design_involvement) 
        ? engineeringData.value.depth_design_involvement.length 
        : 0
}

function hasDesignWorks() {
    return Array.isArray(engineeringData.value.depth_design_involvement) && 
           engineeringData.value.depth_design_involvement.includes('design_works')
}

function hasShopDrawings() {
    return Array.isArray(engineeringData.value.depth_design_involvement) && 
           engineeringData.value.depth_design_involvement.includes('shop_drawings')
}

function getActiveInvolvements() {
    return Array.isArray(engineeringData.value.depth_design_involvement) 
        ? engineeringData.value.depth_design_involvement 
        : []
}

function getInvolvementIcon(involvement) {
    const icons = {
        'design_works': 'fas fa-pencil-ruler',
        'shop_drawings': 'fas fa-drafting-compass'
    }
    return icons[involvement] || 'fas fa-cog'
}

function getInvolvementLabel(involvement) {
    const labels = {
        'design_works': 'Design Works',
        'shop_drawings': 'Shop Drawings'
    }
    return labels[involvement] || involvement
}

function getConfiguredItemsCount() {
    const items = [
        engineeringData.value.principal_engineering_capability,
        engineeringData.value.responsible_design_control,
        engineeringData.value.documented_procedures,
        engineeringData.value.depth_design_involvement?.length > 0
    ]
    
    return items.filter(item => item && item !== '').length
}
</script>

<style scoped>
.engineering-card {
    padding: 24px;
    min-height: 400px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
}

.header-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.header-info {
    flex: 1;
}

.header-info h3 {
    margin: 0 0 4px 0;
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 700;
}

.header-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.engineering-stats {
    flex-shrink: 0;
}

.stat-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 16px;
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    border-radius: 8px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-label {
    font-size: 0.75rem;
    opacity: 0.8;
}

.content-sections {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.engineering-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 24px;
}

.capability-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.capability-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.capability-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.capability-icon {
    width: 56px;
    height: 56px;
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.capability-icon.principal {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.capability-icon.control {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.capability-icon.procedures {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.capability-icon.involvement {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.capability-info {
    flex: 1;
}

.capability-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.capability-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.capability-status {
    margin-top: 8px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-badge.configured,
.status-badge.available {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.unavailable {
    background: #fef2f2;
    color: #991b1b;
}

.status-badge.not-configured {
    background: #f3f4f6;
    color: #6b7280;
}

.capability-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.capability-description,
.responsibility-info,
.procedures-info,
.involvement-levels,
.capability-highlights,
.control-features,
.procedure-benefits,
.involvement-summary {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.capability-description h6,
.responsibility-info h6,
.procedures-info h6,
.involvement-levels h6,
.capability-highlights h6,
.control-features h6,
.procedure-benefits h6,
.involvement-summary h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.description-content p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
    text-align: justify;
}

.highlights-list,
.features-list,
.benefits-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.highlight-item,
.feature-item,
.benefit-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.highlight-item i {
    color: #3b82f6;
    font-size: 0.75rem;
}

.feature-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.benefit-item i {
    color: #f59e0b;
    font-size: 0.75rem;
}

.responsibility-content,
.procedures-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.responsibility-item,
.procedure-status-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.responsibility-item i {
    color: #10b981;
    font-size: 0.875rem;
}

.levels-grid {
    display: grid;
    gap: 12px;
}

.level-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.level-item.active {
    border-color: #7c3aed;
    background: linear-gradient(135deg, #f3f0ff 0%, #ffffff 100%);
}

.level-icon {
    width: 40px;
    height: 40px;
    background: #f3f4f6;
    color: #6b7280;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.level-item.active .level-icon {
    background: #7c3aed;
    color: white;
}

.level-info {
    flex: 1;
}

.level-title {
    display: block;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.level-info small {
    color: #6b7280;
    font-size: 0.75rem;
}

.level-status {
    flex-shrink: 0;
    font-size: 1.125rem;
}

.services-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.service-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: white;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.engineering-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.engineering-summary h4 {
    margin: 0;
    padding: 16px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.engineering-summary h4 i {
    color: #7c3aed;
}

.summary-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.configuration-status {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.status-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.status-item {
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.status-item.capability {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.status-item.control {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.status-item.procedures {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.status-item.involvement {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
}

.status-label {
    font-weight: 600;
    font-size: 0.875rem;
}

.status-value {
    font-weight: 700;
    font-size: 0.875rem;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
}

.statistic-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: white;
    border-radius: 8px;
}

.statistic-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.statistic-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.statistic-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.statistic-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

.text-success {
    color: #059669 !important;
}

.text-danger {
    color: #dc2626 !important;
}

.text-muted {
    color: #9ca3af !important;
}

.no-data {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    text-align: center;
}

.no-data-illustration {
    max-width: 400px;
}

.no-data-illustration i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 20px;
}

.no-data-illustration h4 {
    margin: 0 0 12px 0;
    color: #6b7280;
    font-size: 1.25rem;
    font-weight: 600;
}

.no-data-illustration p {
    margin: 0;
    color: #9ca3af;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .engineering-card {
        padding: 16px;
    }
    
    .engineering-grid,
    .configuration-status,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .capability-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .status-items {
        grid-template-columns: 1fr;
    }
}
</style>