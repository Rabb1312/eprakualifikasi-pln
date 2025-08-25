<template>
    <div class="manufacture-tab">
        <div class="tab-content-wrapper">
            <!-- Product Types -->
            <div class="info-section" v-if="data?.product_types">
                <h3 class="section-title">
                    <i class="fas fa-boxes"></i>
                    Jenis Produk
                </h3>
                <ProductTypesCard :products="data.product_types" />
            </div>

            <!-- Personnel -->
            <div class="info-section" v-if="data?.personnel">
                <h3 class="section-title">
                    <i class="fas fa-users-cog"></i>
                    Personel & Keahlian
                </h3>
                <PersonnelCard :personnel="data.personnel" />
            </div>

            <!-- Manufacturing Plants -->
            <div class="info-section" v-if="data?.plants">
                <h3 class="section-title">
                    <i class="fas fa-industry"></i>
                    Fasilitas Pabrik
                </h3>
                <ManufacturingPlantsCard :plants="data.plants" />
            </div>

            <!-- After Sales Service -->
            <div class="info-section" v-if="data?.after_sales">
                <h3 class="section-title">
                    <i class="fas fa-tools"></i>
                    Layanan Purna Jual
                </h3>
                <AfterSalesCard :services="data.after_sales" />
            </div>

            <!-- Engineering & Design -->
            <div class="info-section" v-if="data?.engineering_design">
                <h3 class="section-title">
                    <i class="fas fa-drafting-compass"></i>
                    Engineering & Design
                </h3>
                <EngineeringDesignCard :design="data.engineering_design" />
            </div>

            <!-- Inventory Management -->
            <div class="info-section" v-if="data?.inventory">
                <h3 class="section-title">
                    <i class="fas fa-warehouse"></i>
                    Manajemen Inventori
                </h3>
                <InventoryCard :inventory="data.inventory" />
            </div>

            <!-- Subcontracting -->
            <div class="info-section" v-if="data?.subcontracting">
                <h3 class="section-title">
                    <i class="fas fa-handshake"></i>
                    Subcontracting
                </h3>
                <div class="subcontracting-card">
                    <div class="subcontracting-content">
                        {{ data.subcontracting }}
                    </div>
                </div>
            </div>

            <!-- Code Standards -->
            <div class="info-section" v-if="data?.code_standard">
                <h3 class="section-title">
                    <i class="fas fa-certificate"></i>
                    Standar & Sertifikasi
                </h3>
                <CodeStandardCard :standards="data.code_standard" />
            </div>

            <!-- Manufacturing Capabilities Overview -->
            <div class="info-section" v-if="hasCapabilitiesData">
                <h3 class="section-title">
                    <i class="fas fa-chart-pie"></i>
                    Ringkasan Kemampuan Manufacturing
                </h3>
                <div class="capabilities-overview">
                    <div class="overview-grid">
                        <div class="capability-metric">
                            <div class="metric-icon products">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-number">{{ getProductTypesCount() }}</div>
                                <div class="metric-label">Jenis Produk</div>
                            </div>
                        </div>

                        <div class="capability-metric">
                            <div class="metric-icon plants">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-number">{{ getPlantsCount() }}</div>
                                <div class="metric-label">Fasilitas Pabrik</div>
                            </div>
                        </div>

                        <div class="capability-metric">
                            <div class="metric-icon standards">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-number">{{ getStandardsCount() }}</div>
                                <div class="metric-label">Standar/Sertifikat</div>
                            </div>
                        </div>

                        <div class="capability-metric">
                            <div class="metric-icon services">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="metric-content">
                                <div class="metric-number">{{ getServicesCount() }}</div>
                                <div class="metric-label">Layanan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Capability Score -->
                    <div class="capability-score">
                        <div class="score-header">
                            <h4>Manufacturing Capability Score</h4>
                            <p>Berdasarkan kelengkapan data dan kemampuan yang tersedia</p>
                        </div>
                        <div class="score-display">
                            <div class="score-circle" :class="getScoreClass()">
                                <span class="score-value">{{ getCapabilityScore() }}</span>
                                <span class="score-max">/100</span>
                            </div>
                            <div class="score-breakdown">
                                <div class="breakdown-item">
                                    <span class="breakdown-label">Produk & Layanan</span>
                                    <div class="breakdown-bar">
                                        <div class="breakdown-fill" :style="{ width: getProductScore() + '%' }"></div>
                                    </div>
                                    <span class="breakdown-value">{{ getProductScore() }}%</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="breakdown-label">Fasilitas & SDM</span>
                                    <div class="breakdown-bar">
                                        <div class="breakdown-fill" :style="{ width: getFacilityScore() + '%' }"></div>
                                    </div>
                                    <span class="breakdown-value">{{ getFacilityScore() }}%</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="breakdown-label">Standar & Kualitas</span>
                                    <div class="breakdown-bar">
                                        <div class="breakdown-fill" :style="{ width: getQualityScore() + '%' }"></div>
                                    </div>
                                    <span class="breakdown-value">{{ getQualityScore() }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Data State -->
            <div v-if="!data" class="no-data-state">
                <i class="fas fa-info-circle"></i>
                <h4>Belum Ada Data Manufacturer</h4>
                <p>Data khusus manufacturer belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import ProductTypesCard from '../shared/ProductTypesCard.vue'
import PersonnelCard from '../shared/PersonnelCard.vue'
import ManufacturingPlantsCard from '../shared/ManufacturingPlantsCard.vue'
import AfterSalesCard from '../shared/AfterSalesCard.vue'
import EngineeringDesignCard from '../shared/EngineeringDesignCard.vue'
import InventoryCard from '../shared/InventoryCard.vue'
import CodeStandardCard from '../shared/CodeStandardCard.vue'

const props = defineProps({
    vendor: {
        type: Object,
        required: true
    },
    data: {
        type: Object,
        default: null
    }
})

const hasCapabilitiesData = computed(() => {
    return props.data?.product_types || props.data?.plants || 
           props.data?.code_standard || props.data?.after_sales
})

function getProductTypesCount() {
    if (!props.data?.product_types) return 0
    return Array.isArray(props.data.product_types) ? props.data.product_types.length : 1
}

function getPlantsCount() {
    if (!props.data?.plants) return 0
    return Array.isArray(props.data.plants) ? props.data.plants.length : 1
}

function getStandardsCount() {
    if (!props.data?.code_standard) return 0
    return Array.isArray(props.data.code_standard) ? props.data.code_standard.length : 1
}

function getServicesCount() {
    let count = 0
    if (props.data?.after_sales) count += Array.isArray(props.data.after_sales) ? props.data.after_sales.length : 1
    if (props.data?.engineering_design) count += Array.isArray(props.data.engineering_design) ? props.data.engineering_design.length : 1
    if (props.data?.inventory) count += 1
    if (props.data?.subcontracting) count += 1
    return count
}

function getProductScore() {
    let score = 0
    if (props.data?.product_types) score += 40
    if (props.data?.after_sales) score += 30
    if (props.data?.engineering_design) score += 30
    return Math.min(score, 100)
}

function getFacilityScore() {
    let score = 0
    if (props.data?.plants) score += 50
    if (props.data?.personnel) score += 30
    if (props.data?.inventory) score += 20
    return Math.min(score, 100)
}

function getQualityScore() {
    let score = 0
    if (props.data?.code_standard) score += 60
    if (props.data?.subcontracting) score += 40
    return Math.min(score, 100)
}

function getCapabilityScore() {
    const productScore = getProductScore()
    const facilityScore = getFacilityScore()
    const qualityScore = getQualityScore()
    return Math.round((productScore + facilityScore + qualityScore) / 3)
}

function getScoreClass() {
    const score = getCapabilityScore()
    if (score >= 80) return 'excellent'
    if (score >= 60) return 'good'
    if (score >= 40) return 'fair'
    return 'poor'
}
</script>

<style scoped>
.manufacture-tab {
    padding: 24px;
}

.tab-content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.info-section {
    background: #f9fafb;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 20px 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}

.section-title i {
    color: #6b7280;
}

.subcontracting-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.subcontracting-content {
    line-height: 1.6;
    color: #374151;
    font-size: 1rem;
}

.capabilities-overview {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1px;
    background: #e5e7eb;
}

.capability-metric {
    background: white;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.metric-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.metric-icon.products {
    background: #dbeafe;
    color: #1e40af;
}

.metric-icon.plants {
    background: #d1fae5;
    color: #065f46;
}

.metric-icon.standards {
    background: #fef3c7;
    color: #92400e;
}

.metric-icon.services {
    background: #e0e7ff;
    color: #3730a3;
}

.metric-content {
    flex: 1;
}

.metric-number {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 4px;
}

.metric-label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
}

.capability-score {
    padding: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.score-header {
    text-align: center;
    margin-bottom: 32px;
}

.score-header h4 {
    margin: 0 0 8px 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.score-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 0.875rem;
}

.score-display {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 48px;
    align-items: center;
}

.score-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 4px solid rgba(255, 255, 255, 0.3);
    position: relative;
}

.score-circle.excellent {
    background: rgba(16, 185, 129, 0.2);
    border-color: #10b981;
}

.score-circle.good {
    background: rgba(59, 130, 246, 0.2);
    border-color: #3b82f6;
}

.score-circle.fair {
    background: rgba(245, 158, 11, 0.2);
    border-color: #f59e0b;
}

.score-circle.poor {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
}

.score-value {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
}

.score-max {
    font-size: 1.25rem;
    opacity: 0.8;
}

.score-breakdown {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.breakdown-item {
    display: grid;
    grid-template-columns: 1fr 2fr auto;
    gap: 16px;
    align-items: center;
}

.breakdown-label {
    font-weight: 600;
    font-size: 0.875rem;
}

.breakdown-bar {
    height: 8px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    overflow: hidden;
}

.breakdown-fill {
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.breakdown-value {
    font-weight: 700;
    font-size: 0.875rem;
    min-width: 40px;
    text-align: right;
}

.no-data-state {
    text-align: center;
    padding: 64px 32px;
    color: #6b7280;
}

.no-data-state i {
    font-size: 3rem;
    margin-bottom: 16px;
    color: #d1d5db;
}

.no-data-state h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 1.125rem;
}

.no-data-state p {
    margin: 0;
    color: #6b7280;
}

@media (max-width: 1024px) {
    .score-display {
        grid-template-columns: 1fr;
        gap: 32px;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .manufacture-tab {
        padding: 16px;
    }
    
    .tab-content-wrapper {
        gap: 24px;
    }
    
    .info-section {
        padding: 20px;
    }
    
    .overview-grid {
        grid-template-columns: 1fr;
    }
    
    .capability-score {
        padding: 24px;
    }
    
    .breakdown-item {
        grid-template-columns: 1fr;
        gap: 8px;
        text-align: center;
    }
}
</style>