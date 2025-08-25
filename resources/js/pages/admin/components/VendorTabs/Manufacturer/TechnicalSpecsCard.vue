<template>
    <div class="technical-specs-card">
        <div v-if="hasData" class="specs-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="header-info">
                    <h3>Technical Specifications</h3>
                    <p>Spesifikasi teknis dan kemampuan manufacturing yang dimiliki</p>
                </div>
                <div class="specs-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ technicalCategories.length }}</span>
                        <span class="stat-label">Categories</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Manufacturing Capabilities -->
                <div class="manufacturing-capabilities" v-if="manufacturingCapabilities.length > 0">
                    <h4>
                        <i class="fas fa-industry"></i>
                        Manufacturing Capabilities
                    </h4>
                    <div class="capabilities-grid">
                        <div 
                            v-for="(capability, index) in manufacturingCapabilities"
                            :key="index"
                            class="capability-item"
                        >
                            <div class="capability-header">
                                <div class="capability-icon">
                                    <i :class="getCapabilityIcon(capability.type)"></i>
                                </div>
                                <div class="capability-info">
                                    <h5>{{ capability.name || capability.type }}</h5>
                                    <span class="capability-category">{{ capability.category || 'Manufacturing Process' }}</span>
                                </div>
                            </div>

                            <div class="capability-details">
                                <div class="specs-grid" v-if="capability.specifications">
                                    <div 
                                        v-for="(spec, specIndex) in getSpecifications(capability.specifications)"
                                        :key="specIndex"
                                        class="spec-item"
                                    >
                                        <label>{{ spec.name }}:</label>
                                        <span>{{ spec.value }}</span>
                                    </div>
                                </div>

                                <div class="capability-description" v-if="capability.description">
                                    <p>{{ capability.description }}</p>
                                </div>

                                <div class="capability-materials" v-if="capability.materials && capability.materials.length > 0">
                                    <h6>Supported Materials:</h6>
                                    <div class="materials-tags">
                                        <span 
                                            v-for="(material, materialIndex) in capability.materials"
                                            :key="materialIndex"
                                            class="material-tag"
                                        >
                                            {{ material }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipment & Machinery -->
                <div class="equipment-machinery" v-if="equipmentList.length > 0">
                    <h4>
                        <i class="fas fa-tools"></i>
                        Equipment & Machinery
                    </h4>
                    <div class="equipment-grid">
                        <div 
                            v-for="(equipment, index) in equipmentList"
                            :key="index"
                            class="equipment-item"
                        >
                            <div class="equipment-header">
                                <div class="equipment-icon">
                                    <i :class="getEquipmentIcon(equipment.type)"></i>
                                </div>
                                <div class="equipment-info">
                                    <h5>{{ equipment.name || equipment.model }}</h5>
                                    <span class="equipment-type">{{ equipment.type || 'Manufacturing Equipment' }}</span>
                                    <div class="equipment-brand" v-if="equipment.brand">
                                        <span class="brand-badge">{{ equipment.brand }}</span>
                                    </div>
                                </div>
                                <div class="equipment-quantity" v-if="equipment.quantity">
                                    <span class="quantity-badge">{{ equipment.quantity }}x</span>
                                </div>
                            </div>

                            <div class="equipment-details">
                                <div class="equipment-specs" v-if="equipment.specifications">
                                    <div class="specs-grid">
                                        <div 
                                            v-for="(spec, specIndex) in getSpecifications(equipment.specifications)"
                                            :key="specIndex"
                                            class="spec-item"
                                        >
                                            <label>{{ spec.name }}:</label>
                                            <span>{{ spec.value }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="equipment-capabilities" v-if="equipment.capabilities && equipment.capabilities.length > 0">
                                    <h6>Capabilities:</h6>
                                    <div class="capabilities-list">
                                        <div 
                                            v-for="(capability, capIndex) in equipment.capabilities"
                                            :key="capIndex"
                                            class="capability-point"
                                        >
                                            <i class="fas fa-check"></i>
                                            <span>{{ capability }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="equipment-status">
                                    <div class="status-info">
                                        <span class="status-label">Status:</span>
                                        <span :class="['status-value', getEquipmentStatus(equipment)]">
                                            {{ getStatusText(equipment.status) }}
                                        </span>
                                    </div>
                                    <div class="age-info" v-if="equipment.installation_year">
                                        <span class="age-label">Age:</span>
                                        <span class="age-value">{{ getEquipmentAge(equipment.installation_year) }} years</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quality Standards -->
                <div class="quality-standards" v-if="qualityStandards.length > 0">
                    <h4>
                        <i class="fas fa-check-double"></i>
                        Quality Standards
                    </h4>
                    <div class="standards-grid">
                        <div 
                            v-for="(standard, index) in qualityStandards"
                            :key="index"
                            class="standard-item"
                        >
                            <div class="standard-header">
                                <div class="standard-icon">
                                    <i :class="getStandardIcon(standard.type)"></i>
                                </div>
                                <div class="standard-info">
                                    <h6>{{ standard.name || standard.standard }}</h6>
                                    <span class="standard-type">{{ standard.type || 'Quality Standard' }}</span>
                                </div>
                                <div class="standard-status">
                                    <span class="status-badge compliant">
                                        <i class="fas fa-check-circle"></i>
                                        Compliant
                                    </span>
                                </div>
                            </div>

                            <div class="standard-details">
                                <div class="standard-specs" v-if="standard.requirements">
                                    <h6>Requirements:</h6>
                                    <div class="requirements-list">
                                        <div 
                                            v-for="(requirement, reqIndex) in standard.requirements"
                                            :key="reqIndex"
                                            class="requirement-item"
                                        >
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ requirement }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="standard-tolerance" v-if="standard.tolerance_levels">
                                    <h6>Tolerance Levels:</h6>
                                    <div class="tolerance-grid">
                                        <div 
                                            v-for="(tolerance, tolIndex) in getSpecifications(standard.tolerance_levels)"
                                            :key="tolIndex"
                                            class="tolerance-item"
                                        >
                                            <label>{{ tolerance.name }}:</label>
                                            <span class="tolerance-value">{{ tolerance.value }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Summary -->
                <div class="technical-summary">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Technical Summary
                    </h4>
                    <div class="summary-stats">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ manufacturingCapabilities.length }}</span>
                                <span class="summary-label">Manufacturing Capabilities</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getTotalEquipment() }}</span>
                                <span class="summary-label">Equipment Units</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ qualityStandards.length }}</span>
                                <span class="summary-label">Quality Standards</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getAverageEquipmentAge() }}</span>
                                <span class="summary-label">Avg. Equipment Age</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-cogs"></i>
                <h4>Belum Ada Data Technical Specifications</h4>
                <p>Data spesifikasi teknis belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.technical_specifications)
})

const manufacturingCapabilities = computed(() => {
    if (!props.data.technical_specifications?.manufacturing_capabilities) return []
    return Array.isArray(props.data.technical_specifications.manufacturing_capabilities) 
        ? props.data.technical_specifications.manufacturing_capabilities 
        : []
})

const equipmentList = computed(() => {
    if (!props.data.technical_specifications?.equipment) return []
    return Array.isArray(props.data.technical_specifications.equipment) 
        ? props.data.technical_specifications.equipment 
        : []
})

const qualityStandards = computed(() => {
    if (!props.data.technical_specifications?.quality_standards) return []
    return Array.isArray(props.data.technical_specifications.quality_standards) 
        ? props.data.technical_specifications.quality_standards 
        : []
})

const technicalCategories = computed(() => {
    const categories = new Set()
    manufacturingCapabilities.value.forEach(cap => categories.add(cap.category || 'Manufacturing'))
    equipmentList.value.forEach(eq => categories.add(eq.type || 'Equipment'))
    qualityStandards.value.forEach(std => categories.add(std.type || 'Quality'))
    return Array.from(categories)
})

function getCapabilityIcon(type) {
    const icons = {
        'machining': 'fas fa-cog',
        'welding': 'fas fa-fire',
        'casting': 'fas fa-cube',
        'forging': 'fas fa-hammer',
        'assembly': 'fas fa-puzzle-piece',
        'testing': 'fas fa-flask',
        'finishing': 'fas fa-paint-brush',
        'packaging': 'fas fa-box'
    }
    return icons[type?.toLowerCase()] || 'fas fa-industry'
}

function getEquipmentIcon(type) {
    const icons = {
        'cnc_machine': 'fas fa-cog',
        'lathe': 'fas fa-circle',
        'mill': 'fas fa-square',
        'press': 'fas fa-compress',
        'robot': 'fas fa-robot',
        'conveyor': 'fas fa-arrow-right',
        'crane': 'fas fa-truck-pickup',
        'furnace': 'fas fa-fire-alt'
    }
    return icons[type?.toLowerCase()] || 'fas fa-tools'
}

function getStandardIcon(type) {
    const icons = {
        'iso': 'fas fa-globe',
        'astm': 'fas fa-vial',
        'din': 'fas fa-ruler',
        'jis': 'fas fa-yen-sign',
        'ansi': 'fas fa-flag-usa',
        'quality': 'fas fa-check-double'
    }
    return icons[type?.toLowerCase()] || 'fas fa-certificate'
}

function getSpecifications(specs) {
    if (Array.isArray(specs)) {
        return specs
    }
    if (typeof specs === 'object') {
        return Object.entries(specs).map(([name, value]) => ({ name, value }))
    }
    return []
}

function getEquipmentStatus(equipment) {
    return equipment.status?.toLowerCase() || 'operational'
}

function getStatusText(status) {
    const texts = {
        'operational': 'Operational',
        'maintenance': 'Under Maintenance',
        'idle': 'Idle',
        'retired': 'Retired'
    }
    return texts[status?.toLowerCase()] || 'Operational'
}

function getEquipmentAge(installationYear) {
    const currentYear = new Date().getFullYear()
    return currentYear - parseInt(installationYear)
}

function getTotalEquipment() {
    return equipmentList.value.reduce((total, equipment) => {
        return total + (parseInt(equipment.quantity) || 1)
    }, 0)
}

function getAverageEquipmentAge() {
    const ages = equipmentList.value
        .filter(eq => eq.installation_year)
        .map(eq => getEquipmentAge(eq.installation_year))
    
    if (ages.length === 0) return '-'
    
    const average = ages.reduce((sum, age) => sum + age, 0) / ages.length
    return `${Math.round(average)} years`
}
</script>

<style scoped>
.technical-specs-card {
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

.specs-stats {
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

.manufacturing-capabilities,
.equipment-machinery,
.quality-standards,
.technical-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.manufacturing-capabilities h4,
.equipment-machinery h4,
.quality-standards h4,
.technical-summary h4 {
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

.manufacturing-capabilities h4 i {
    color: #8b5cf6;
}

.equipment-machinery h4 i {
    color: #f59e0b;
}

.quality-standards h4 i {
    color: #10b981;
}

.technical-summary h4 i {
    color: #ef4444;
}

.capabilities-grid,
.equipment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    padding: 24px;
}

.capability-item,
.equipment-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.capability-item:hover,
.equipment-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.capability-header,
.equipment-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.capability-icon,
.equipment-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.equipment-icon {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}

.capability-info,
.equipment-info {
    flex: 1;
}

.capability-info h5,
.equipment-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.capability-category,
.equipment-type {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-bottom: 8px;
}

.equipment-brand {
    margin-top: 4px;
}

.brand-badge {
    background: #e5e7eb;
    color: #374151;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.equipment-quantity {
    flex-shrink: 0;
}

.quantity-badge {
    background: #1f2937;
    color: white;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.capability-details,
.equipment-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.spec-item label {
    color: #6b7280;
    font-weight: 500;
}

.spec-item span {
    color: #1f2937;
    font-weight: 600;
}

.capability-description {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.capability-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.capability-materials {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.capability-materials h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.materials-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.material-tag {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    padding: 2px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.equipment-capabilities {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.equipment-capabilities h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.capabilities-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.capability-point {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.capability-point i {
    color: #10b981;
    font-size: 0.75rem;
}

.equipment-status {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.status-info,
.age-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-label,
.age-label {
    color: #6b7280;
    font-weight: 500;
}

.status-value {
    font-weight: 600;
}

.status-value.operational {
    color: #10b981;
}

.status-value.maintenance {
    color: #f59e0b;
}

.status-value.idle {
    color: #6b7280;
}

.status-value.retired {
    color: #ef4444;
}

.age-value {
    color: #374151;
    font-weight: 600;
}

.standards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    padding: 24px;
}

.standard-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.standard-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.standard-icon {
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

.standard-info {
    flex: 1;
}

.standard-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.standard-type {
    font-size: 0.875rem;
    color: #6b7280;
}

.standard-status {
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

.status-badge.compliant {
    background: #d1fae5;
    color: #065f46;
}

.standard-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.standard-specs {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.standard-specs h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.requirements-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.requirement-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.requirement-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.standard-tolerance {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.standard-tolerance h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.tolerance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 8px;
}

.tolerance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 8px;
    background: #f8fafc;
    border-radius: 4px;
    font-size: 0.75rem;
}

.tolerance-item label {
    color: #6b7280;
    font-weight: 500;
}

.tolerance-value {
    color: #10b981;
    font-weight: 700;
}

.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-radius: 8px;
}

.summary-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.summary-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.summary-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.summary-label {
    font-size: 0.875rem;
    opacity: 0.8;
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
    .technical-specs-card {
        padding: 16px;
    }
    
    .capabilities-grid,
    .equipment-grid,
    .standards-grid,
    .summary-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .capability-header,
    .equipment-header,
    .standard-header {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .specs-grid,
    .tolerance-grid {
        grid-template-columns: 1fr;
    }
    
    .equipment-status {
        flex-direction: column;
        gap: 8px;
    }
}
</style>