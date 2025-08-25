<template>
    <div class="quality-control-card">
        <div v-if="hasData" class="quality-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="header-info">
                    <h3>Quality Control</h3>
                    <p>Sistem kontrol kualitas dan standar yang diterapkan dalam produksi</p>
                </div>
                <div class="quality-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ qualityProcesses.length }}</span>
                        <span class="stat-label">QC Processes</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Quality Management System -->
                <div class="qms-overview" v-if="data.quality_management_system">
                    <h4>
                        <i class="fas fa-clipboard-check"></i>
                        Quality Management System
                    </h4>
                    <div class="qms-content">
                        <div class="qms-info">
                            <div class="qms-details">
                                <div class="detail-item" v-if="data.quality_management_system.standard">
                                    <label>Standard:</label>
                                    <span class="standard-badge">{{ data.quality_management_system.standard }}</span>
                                </div>
                                <div class="detail-item" v-if="data.quality_management_system.certification_date">
                                    <label>Certified Since:</label>
                                    <span>{{ formatDate(data.quality_management_system.certification_date) }}</span>
                                </div>
                                <div class="detail-item" v-if="data.quality_management_system.certifying_body">
                                    <label>Certifying Body:</label>
                                    <span>{{ data.quality_management_system.certifying_body }}</span>
                                </div>
                                <div class="detail-item" v-if="data.quality_management_system.validity_period">
                                    <label>Valid Until:</label>
                                    <span>{{ formatDate(data.quality_management_system.validity_period) }}</span>
                                </div>
                            </div>
                            
                            <div class="qms-description" v-if="data.quality_management_system.description">
                                <p>{{ data.quality_management_system.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quality Control Processes -->
                <div class="quality-processes">
                    <h4>
                        <i class="fas fa-cogs"></i>
                        Quality Control Processes
                    </h4>
                    <div class="processes-grid">
                        <div 
                            v-for="(process, index) in qualityProcesses"
                            :key="index"
                            class="process-item"
                        >
                            <div class="process-header">
                                <div class="process-icon">
                                    <i :class="getProcessIcon(process.type)"></i>
                                </div>
                                <div class="process-info">
                                    <h5>{{ process.name || process.type }}</h5>
                                    <span class="process-type">{{ process.category || 'Quality Check' }}</span>
                                </div>
                                <div class="process-status">
                                    <span class="status-badge active">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                </div>
                            </div>

                            <div class="process-details">
                                <div class="process-description" v-if="process.description">
                                    <p>{{ process.description }}</p>
                                </div>

                                <div class="process-steps" v-if="process.steps && process.steps.length > 0">
                                    <h6>Process Steps:</h6>
                                    <div class="steps-list">
                                        <div 
                                            v-for="(step, stepIndex) in process.steps"
                                            :key="stepIndex"
                                            class="step-item"
                                        >
                                            <div class="step-number">{{ stepIndex + 1 }}</div>
                                            <div class="step-content">
                                                <span class="step-title">{{ step.title || step.name || step }}</span>
                                                <span class="step-description" v-if="step.description">{{ step.description }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="process-metrics" v-if="process.metrics">
                                    <h6>Key Metrics:</h6>
                                    <div class="metrics-grid">
                                        <div class="metric-item" v-if="process.metrics.frequency">
                                            <label>Frequency:</label>
                                            <span>{{ process.metrics.frequency }}</span>
                                        </div>
                                        <div class="metric-item" v-if="process.metrics.sample_size">
                                            <label>Sample Size:</label>
                                            <span>{{ process.metrics.sample_size }}</span>
                                        </div>
                                        <div class="metric-item" v-if="process.metrics.tolerance_level">
                                            <label>Tolerance:</label>
                                            <span class="tolerance-value">{{ process.metrics.tolerance_level }}</span>
                                        </div>
                                        <div class="metric-item" v-if="process.metrics.pass_rate">
                                            <label>Pass Rate:</label>
                                            <span class="pass-rate-value">{{ process.metrics.pass_rate }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="process-equipment" v-if="process.equipment && process.equipment.length > 0">
                                    <h6>Equipment Used:</h6>
                                    <div class="equipment-tags">
                                        <span 
                                            v-for="(equipment, equipIndex) in process.equipment"
                                            :key="equipIndex"
                                            class="equipment-tag"
                                        >
                                            <i :class="getEquipmentIcon(equipment.type)"></i>
                                            {{ equipment.name || equipment }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testing Procedures -->
                <div class="testing-procedures" v-if="testingProcedures.length > 0">
                    <h4>
                        <i class="fas fa-flask"></i>
                        Testing Procedures
                    </h4>
                    <div class="testing-grid">
                        <div 
                            v-for="(test, index) in testingProcedures"
                            :key="index"
                            class="test-item"
                        >
                            <div class="test-header">
                                <div class="test-icon">
                                    <i :class="getTestIcon(test.type)"></i>
                                </div>
                                <div class="test-info">
                                    <h6>{{ test.name || test.type }}</h6>
                                    <span class="test-category">{{ test.category || 'Quality Test' }}</span>
                                </div>
                            </div>

                            <div class="test-details">
                                <div class="test-specs">
                                    <div class="spec-item" v-if="test.standard">
                                        <label>Standard:</label>
                                        <span>{{ test.standard }}</span>
                                    </div>
                                    <div class="spec-item" v-if="test.duration">
                                        <label>Duration:</label>
                                        <span>{{ test.duration }}</span>
                                    </div>
                                    <div class="spec-item" v-if="test.temperature">
                                        <label>Temperature:</label>
                                        <span>{{ test.temperature }}</span>
                                    </div>
                                    <div class="spec-item" v-if="test.pressure">
                                        <label>Pressure:</label>
                                        <span>{{ test.pressure }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quality Metrics Dashboard -->
                <div class="quality-metrics">
                    <h4>
                        <i class="fas fa-chart-line"></i>
                        Quality Metrics
                    </h4>
                    <div class="metrics-dashboard">
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ getOverallPassRate() }}%</span>
                                <span class="metric-label">Overall Pass Rate</span>
                            </div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ qualityProcesses.length }}</span>
                                <span class="metric-label">QC Processes</span>
                            </div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-flask"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ testingProcedures.length }}</span>
                                <span class="metric-label">Testing Procedures</span>
                            </div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ getCertificationCount() }}</span>
                                <span class="metric-label">Certifications</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Process Categories -->
                <div class="process-categories" v-if="processCategories.length > 0">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Process Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in processCategories"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getProcessIcon(category.name)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} processes</span>
                            </div>
                            <div class="category-percentage">
                                <span class="percentage-value">{{ getCategoryPercentage(category.count) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-check-double"></i>
                <h4>Belum Ada Data Quality Control</h4>
                <p>Data sistem kontrol kualitas belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.quality_control)
})

const qualityProcesses = computed(() => {
    if (!props.data.quality_control?.processes) return []
    return Array.isArray(props.data.quality_control.processes) 
        ? props.data.quality_control.processes 
        : []
})

const testingProcedures = computed(() => {
    if (!props.data.quality_control?.testing_procedures) return []
    return Array.isArray(props.data.quality_control.testing_procedures) 
        ? props.data.quality_control.testing_procedures 
        : []
})

const processCategories = computed(() => {
    if (qualityProcesses.value.length === 0) return []
    
    const categories = {}
    qualityProcesses.value.forEach(process => {
        const category = process.category || process.type || 'General'
        if (!categories[category]) {
            categories[category] = { name: category, count: 0 }
        }
        categories[category].count++
    })
    
    return Object.values(categories).sort((a, b) => b.count - a.count)
})

function getProcessIcon(type) {
    const icons = {
        'incoming_inspection': 'fas fa-search',
        'in_process_inspection': 'fas fa-cogs',
        'final_inspection': 'fas fa-check-circle',
        'material_testing': 'fas fa-flask',
        'dimensional_check': 'fas fa-ruler',
        'visual_inspection': 'fas fa-eye',
        'functional_testing': 'fas fa-play-circle',
        'packaging_inspection': 'fas fa-box'
    }
    return icons[type?.toLowerCase()] || 'fas fa-clipboard-check'
}

function getTestIcon(type) {
    const icons = {
        'tensile_test': 'fas fa-arrows-alt-h',
        'compression_test': 'fas fa-compress',
        'hardness_test': 'fas fa-hammer',
        'corrosion_test': 'fas fa-tint',
        'thermal_test': 'fas fa-thermometer-half',
        'electrical_test': 'fas fa-bolt',
        'chemical_analysis': 'fas fa-flask'
    }
    return icons[type?.toLowerCase()] || 'fas fa-vial'
}

function getEquipmentIcon(type) {
    const icons = {
        'caliper': 'fas fa-ruler',
        'microscope': 'fas fa-search-plus',
        'spectrometer': 'fas fa-wave-square',
        'gauge': 'fas fa-tachometer-alt',
        'scale': 'fas fa-weight'
    }
    return icons[type?.toLowerCase()] || 'fas fa-tools'
}

function getOverallPassRate() {
    const passRates = qualityProcesses.value
        .map(process => process.metrics?.pass_rate)
        .filter(rate => rate)
        .map(rate => parseFloat(rate.replace('%', '')))
        .filter(rate => !isNaN(rate))
    
    if (passRates.length === 0) return 95
    
    const average = passRates.reduce((sum, rate) => sum + rate, 0) / passRates.length
    return Math.round(average)
}

function getCertificationCount() {
    let count = 0
    if (props.data.quality_control?.quality_management_system?.standard) count++
    if (props.data.quality_control?.certifications) {
        count += Array.isArray(props.data.quality_control.certifications) 
            ? props.data.quality_control.certifications.length 
            : 1
    }
    return count
}

function getCategoryPercentage(count) {
    if (qualityProcesses.value.length === 0) return 0
    return Math.round((count / qualityProcesses.value.length) * 100)
}

function formatDate(dateString) {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}
</script>

<style scoped>
.quality-control-card {
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
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

.quality-stats {
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

.qms-overview,
.quality-processes,
.testing-procedures,
.quality-metrics,
.process-categories {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.qms-overview h4,
.quality-processes h4,
.testing-procedures h4,
.quality-metrics h4,
.process-categories h4 {
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

.qms-overview h4 i {
    color: #10b981;
}

.quality-processes h4 i {
    color: #3b82f6;
}

.testing-procedures h4 i {
    color: #f59e0b;
}

.quality-metrics h4 i {
    color: #8b5cf6;
}

.process-categories h4 i {
    color: #ef4444;
}

.qms-content {
    padding: 24px;
}

.qms-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.qms-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.detail-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.detail-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.standard-badge {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    padding: 4px 12px;
    border-radius: 12px;
    font-weight: 700 !important;
}

.qms-description {
    background: white;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.qms-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.processes-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 24px;
}

.process-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.process-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.process-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.process-info {
    flex: 1;
}

.process-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.process-type {
    font-size: 0.875rem;
    color: #6b7280;
}

.process-status {
    flex-shrink: 0;
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

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.process-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.process-description {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.process-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.process-steps {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.process-steps h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.steps-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 6px;
}

.step-number {
    width: 24px;
    height: 24px;
    background: #10b981;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.step-title {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.step-description {
    color: #6b7280;
    font-size: 0.8125rem;
    line-height: 1.4;
}

.process-metrics {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.process-metrics h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.metric-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
}

.metric-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.metric-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.tolerance-value {
    color: #f59e0b !important;
}

.pass-rate-value {
    color: #10b981 !important;
}

.process-equipment {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.process-equipment h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.equipment-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.equipment-tag {
    display: flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.testing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    padding: 24px;
}

.test-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    transition: all 0.3s ease;
}

.test-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.test-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.test-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.test-info h6 {
    margin: 0 0 2px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.test-category {
    font-size: 0.75rem;
    color: #6b7280;
}

.test-specs {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 8px;
    background: white;
    border-radius: 4px;
    font-size: 0.75rem;
}

.spec-item label {
    color: #6b7280;
    font-weight: 500;
}

.spec-item span {
    color: #1f2937;
    font-weight: 600;
}

.metrics-dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.metric-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    border-radius: 8px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.metric-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.metric-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.metric-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.category-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.category-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.category-info {
    flex: 1;
}

.category-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.category-count {
    font-size: 0.875rem;
    color: #6b7280;
}

.category-percentage {
    flex-shrink: 0;
}

.percentage-value {
    font-weight: 700;
    color: #ef4444;
    font-size: 1.125rem;
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
    .quality-control-card {
        padding: 16px;
    }
    
    .processes-grid,
    .testing-grid,
    .metrics-dashboard,
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .process-header,
    .test-header {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .qms-details,
    .metrics-grid {
        grid-template-columns: 1fr;
    }
}
</style>