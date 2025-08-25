<template>
    <div class="knowledge-card">
        <div v-if="hasKnowledge" class="knowledge-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="header-info">
                    <h3>Knowledge of Local Regulations</h3>
                    <p>Pengetahuan subcontractor terhadap regulasi dan peraturan lokal</p>
                </div>
            </div>

            <div class="knowledge-main">
                <!-- Knowledge Status -->
                <div class="knowledge-status-section">
                    <div class="status-header">
                        <h4>
                            <i class="fas fa-question-circle"></i>
                            Status Pengetahuan Regulasi Lokal
                        </h4>
                    </div>
                    <div class="status-content">
                        <div class="status-indicator">
                            <div :class="['status-icon', knowledgeStatus]">
                                <i :class="getStatusIcon()"></i>
                            </div>
                            <div class="status-info">
                                <h5>{{ getStatusText() }}</h5>
                                <p>{{ getStatusDescription() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Knowledge Details -->
                <div v-if="data.regulation_knowledge_details" class="knowledge-details-section">
                    <div class="details-header">
                        <h4>
                            <i class="fas fa-file-alt"></i>
                            Detail Pengetahuan
                        </h4>
                    </div>
                    <div class="details-content">
                        <div class="details-text">
                            <p>{{ data.regulation_knowledge_details }}</p>
                        </div>
                    </div>
                </div>

                <!-- Regulation Categories (if can be extracted) -->
                <div v-if="regulationCategories.length > 0" class="regulation-categories">
                    <div class="categories-header">
                        <h4>
                            <i class="fas fa-list-check"></i>
                            Kategori Regulasi
                        </h4>
                    </div>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in regulationCategories"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getCategoryIcon(category)"></i>
                            </div>
                            <span>{{ category }}</span>
                        </div>
                    </div>
                </div>

                <!-- Compliance Level -->
                <div class="compliance-level">
                    <div class="compliance-header">
                        <h4>
                            <i class="fas fa-chart-line"></i>
                            Tingkat Pemahaman
                        </h4>
                    </div>
                    <div class="compliance-content">
                        <div class="compliance-meter">
                            <div class="meter-background">
                                <div 
                                    :class="['meter-fill', knowledgeStatus]"
                                    :style="{ width: getCompliancePercentage() + '%' }"
                                ></div>
                            </div>
                            <div class="meter-labels">
                                <span class="meter-percentage">{{ getCompliancePercentage() }}%</span>
                                <span class="meter-label">{{ getComplianceLabel() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-brain"></i>
                <h4>Belum Ada Data Knowledge</h4>
                <p>Data pengetahuan regulasi lokal belum dilengkapi untuk subcontractor ini</p>
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

const hasKnowledge = computed(() => {
    return !!(props.data.local_regulation_knowledge)
})

const knowledgeStatus = computed(() => {
    return props.data.local_regulation_knowledge === 'yes' ? 'positive' : 'negative'
})

const regulationCategories = computed(() => {
    if (!props.data.regulation_knowledge_details) return []
    
    const text = props.data.regulation_knowledge_details.toLowerCase()
    const categories = []
    
    const categoryKeywords = {
        'Keselamatan Kerja (K3)': ['k3', 'keselamatan', 'safety', 'ohsas'],
        'Lingkungan Hidup': ['lingkungan', 'amdal', 'environment', 'waste'],
        'Ketenagakerjaan': ['tenaga kerja', 'labor', 'pekerja', 'buruh'],
        'Perpajakan': ['pajak', 'tax', 'pph', 'ppn'],
        'Perizinan Usaha': ['izin', 'permit', 'license', 'siup'],
        'Standar Teknis': ['standar', 'sni', 'iso', 'technical standard'],
        'Peraturan Daerah': ['perda', 'regional', 'daerah', 'lokal']
    }
    
    Object.entries(categoryKeywords).forEach(([category, keywords]) => {
        if (keywords.some(keyword => text.includes(keyword))) {
            categories.push(category)
        }
    })
    
    return categories
})

function getStatusIcon() {
    return knowledgeStatus.value === 'positive' ? 'fas fa-check-circle' : 'fas fa-times-circle'
}

function getStatusText() {
    return knowledgeStatus.value === 'positive' ? 'Ya, Mengetahui' : 'Tidak Mengetahui'
}

function getStatusDescription() {
    return knowledgeStatus.value === 'positive' 
        ? 'Subcontractor memiliki pengetahuan tentang regulasi lokal yang berlaku'
        : 'Subcontractor belum memiliki pengetahuan yang cukup tentang regulasi lokal'
}

function getCompliancePercentage() {
    if (knowledgeStatus.value === 'positive') {
        // Base 70% for knowing, plus points for details and categories
        let percentage = 70
        if (props.data.regulation_knowledge_details) percentage += 20
        if (regulationCategories.value.length > 0) percentage += 10
        return Math.min(percentage, 100)
    }
    return 0
}

function getComplianceLabel() {
    const percentage = getCompliancePercentage()
    if (percentage >= 90) return 'Excellent'
    if (percentage >= 70) return 'Good'
    if (percentage >= 50) return 'Fair'
    return 'Poor'
}

function getCategoryIcon(category) {
    const icons = {
        'Keselamatan Kerja (K3)': 'fas fa-hard-hat',
        'Lingkungan Hidup': 'fas fa-leaf',
        'Ketenagakerjaan': 'fas fa-users',
        'Perpajakan': 'fas fa-receipt',
        'Perizinan Usaha': 'fas fa-certificate',
        'Standar Teknis': 'fas fa-cogs',
        'Peraturan Daerah': 'fas fa-map-marker-alt'
    }
    return icons[category] || 'fas fa-check-circle'
}
</script>

<style scoped>
.knowledge-card {
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
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
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

.knowledge-main {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.knowledge-status-section,
.knowledge-details-section,
.regulation-categories,
.compliance-level {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.status-header,
.details-header,
.categories-header,
.compliance-header {
    background: #f8fafc;
    padding: 16px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.status-header h4,
.details-header h4,
.categories-header h4,
.compliance-header h4 {
    margin: 0;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-header h4 i {
    color: #8b5cf6;
}

.details-header h4 i {
    color: #3b82f6;
}

.categories-header h4 i {
    color: #10b981;
}

.compliance-header h4 i {
    color: #f59e0b;
}

.status-content,
.details-content,
.compliance-content {
    padding: 24px;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 20px;
}

.status-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.status-icon.positive {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 3px solid #10b981;
}

.status-icon.negative {
    background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
    color: #991b1b;
    border: 3px solid #ef4444;
}

.status-info h5 {
    margin: 0 0 8px 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.status-info p {
    margin: 0;
    color: #6b7280;
    line-height: 1.5;
}

.details-text {
    background: #f8fafc;
    padding: 24px;
    border-radius: 8px;
    border-left: 4px solid #8b5cf6;
}

.details-text p {
    margin: 0;
    color: #374151;
    line-height: 1.8;
    font-size: 1rem;
}

.categories-grid {
    padding: 24px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-radius: 8px;
    border: 1px solid #d1fae5;
    transition: all 0.3s ease;
}

.category-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

.category-icon {
    width: 36px;
    height: 36px;
    background: #10b981;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.category-item span {
    font-weight: 600;
    color: #065f46;
    font-size: 0.875rem;
}

.compliance-meter {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.meter-background {
    width: 100%;
    height: 20px;
    background: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}

.meter-fill {
    height: 100%;
    border-radius: 10px;
    transition: width 1s ease-in-out;
    position: relative;
}

.meter-fill.positive {
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}

.meter-fill.negative {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}

.meter-labels {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.meter-percentage {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.meter-label {
    font-size: 1rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
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
    .knowledge-card {
        padding: 16px;
    }
    
    .status-indicator {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .status-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .meter-labels {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>