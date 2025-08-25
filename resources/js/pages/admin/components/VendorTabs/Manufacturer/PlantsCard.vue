<template>
    <div class="plants-card">
        <div v-if="hasData" class="plants-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="header-info">
                    <h3>Plants & Facilities</h3>
                    <p>Fasilitas pabrik dan infrastruktur yang dimiliki manufacturer</p>
                </div>
                <div class="plants-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ plantsList.length }}</span>
                        <span class="stat-label">Plants</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Plants Grid -->
                <div class="plants-grid">
                    <div 
                        v-for="(plant, index) in plantsList"
                        :key="index"
                        class="plant-item"
                    >
                        <div class="plant-header">
                            <div class="plant-icon">
                                <i :class="getPlantIcon(plant.type)"></i>
                            </div>
                            <div class="plant-info">
                                <h5>{{ plant.name || plant.location || `Plant ${index + 1}` }}</h5>
                                <span class="plant-type">{{ plant.type || 'Manufacturing Plant' }}</span>
                                <div class="plant-location" v-if="plant.location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ plant.location }}</span>
                                </div>
                            </div>
                            <div class="plant-status">
                                <span :class="['status-badge', getPlantStatus(plant.status)]">
                                    <i :class="getStatusIcon(plant.status)"></i>
                                    {{ getStatusText(plant.status) }}
                                </span>
                            </div>
                        </div>

                        <div class="plant-details">
                            <div class="plant-specifications" v-if="plant.area || plant.capacity || plant.employees">
                                <h6>Plant Specifications:</h6>
                                <div class="specs-grid">
                                    <div v-if="plant.area" class="spec-item">
                                        <label>Area:</label>
                                        <span>{{ plant.area }}</span>
                                    </div>
                                    <div v-if="plant.capacity" class="spec-item">
                                        <label>Capacity:</label>
                                        <span>{{ plant.capacity }}</span>
                                    </div>
                                    <div v-if="plant.employees" class="spec-item">
                                        <label>Employees:</label>
                                        <span>{{ plant.employees }}</span>
                                    </div>
                                    <div v-if="plant.established" class="spec-item">
                                        <label>Established:</label>
                                        <span>{{ plant.established }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="plant-facilities" v-if="plant.facilities">
                                <h6>Facilities & Equipment:</h6>
                                <div class="facilities-list">
                                    <div 
                                        v-for="(facility, facilityIndex) in getFacilities(plant.facilities)"
                                        :key="facilityIndex"
                                        class="facility-item"
                                    >
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ facility }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="plant-production" v-if="plant.production_lines">
                                <h6>Production Lines:</h6>
                                <div class="production-lines">
                                    <div 
                                        v-for="(line, lineIndex) in getProductionLines(plant.production_lines)"
                                        :key="lineIndex"
                                        class="production-line"
                                    >
                                        <div class="line-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="line-info">
                                            <span class="line-name">{{ line.name || line }}</span>
                                            <span class="line-capacity" v-if="line.capacity">{{ line.capacity }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="plant-certifications" v-if="plant.certifications">
                                <h6>Plant Certifications:</h6>
                                <div class="certifications-tags">
                                    <span 
                                        v-for="(cert, certIndex) in getCertifications(plant.certifications)"
                                        :key="certIndex"
                                        class="certification-tag"
                                    >
                                        <i class="fas fa-certificate"></i>
                                        {{ cert }}
                                    </span>
                                </div>
                            </div>

                            <div class="plant-utilities" v-if="plant.utilities">
                                <h6>Utilities & Infrastructure:</h6>
                                <div class="utilities-grid">
                                    <div 
                                        v-for="(utility, utilityIndex) in getUtilities(plant.utilities)"
                                        :key="utilityIndex"
                                        class="utility-item"
                                    >
                                        <i :class="getUtilityIcon(utility)"></i>
                                        <span>{{ utility }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="plant-description" v-if="plant.description">
                                <h6>Description:</h6>
                                <p>{{ plant.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plants Summary -->
                <div class="plants-summary">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Plants Summary
                    </h4>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ plantsList.length }}</span>
                                <span class="summary-label">Total Plants</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getTotalArea() }}</span>
                                <span class="summary-label">Total Area</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getTotalEmployees() }}</span>
                                <span class="summary-label">Total Employees</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getOperationalPlants() }}</span>
                                <span class="summary-label">Operational Plants</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-industry"></i>
                <h4>Belum Ada Data Plants</h4>
                <p>Data fasilitas pabrik belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.plants)
})

const plantsList = computed(() => {
    if (!props.data.plants) return []
    
    // Handle JSON field dari database
    if (Array.isArray(props.data.plants)) {
        return props.data.plants
    }
    
    if (typeof props.data.plants === 'string') {
        try {
            const parsed = JSON.parse(props.data.plants)
            return Array.isArray(parsed) ? parsed : [parsed]
        } catch {
            return []
        }
    }
    
    if (typeof props.data.plants === 'object') {
        return Object.entries(props.data.plants).map(([key, value]) => ({
            name: key,
            ...value
        }))
    }
    
    return []
})

function getPlantIcon(type) {
    const typeLower = (type || '').toLowerCase()
    const icons = {
        'manufacturing': 'fas fa-industry',
        'assembly': 'fas fa-puzzle-piece',
        'production': 'fas fa-cogs',
        'warehouse': 'fas fa-warehouse',
        'distribution': 'fas fa-shipping-fast',
        'research': 'fas fa-flask',
        'testing': 'fas fa-vial',
        'quality': 'fas fa-check-double'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (typeLower.includes(key)) return icon
    }
    return 'fas fa-building'
}

function getPlantStatus(status) {
    return (status || 'operational').toLowerCase()
}

function getStatusIcon(status) {
    const statusLower = (status || 'operational').toLowerCase()
    const icons = {
        'operational': 'fas fa-play-circle',
        'maintenance': 'fas fa-wrench',
        'construction': 'fas fa-hard-hat',
        'closed': 'fas fa-stop-circle'
    }
    return icons[statusLower] || 'fas fa-play-circle'
}

function getStatusText(status) {
    const statusLower = (status || 'operational').toLowerCase()
    const texts = {
        'operational': 'Operational',
        'maintenance': 'Under Maintenance',
        'construction': 'Under Construction',
        'closed': 'Closed'
    }
    return texts[statusLower] || 'Operational'
}

function getFacilities(facilities) {
    if (Array.isArray(facilities)) return facilities
    if (typeof facilities === 'string') return facilities.split(',').map(f => f.trim())
    return []
}

function getProductionLines(lines) {
    if (Array.isArray(lines)) return lines
    if (typeof lines === 'string') return lines.split(',').map(l => ({ name: l.trim() }))
    return []
}

function getCertifications(certifications) {
    if (Array.isArray(certifications)) return certifications
    if (typeof certifications === 'string') return certifications.split(',').map(c => c.trim())
    return []
}

function getUtilities(utilities) {
    if (Array.isArray(utilities)) return utilities
    if (typeof utilities === 'string') return utilities.split(',').map(u => u.trim())
    return []
}

function getUtilityIcon(utility) {
    const utilityLower = (utility || '').toLowerCase()
    const icons = {
        'power': 'fas fa-bolt',
        'water': 'fas fa-tint',
        'gas': 'fas fa-fire',
        'steam': 'fas fa-cloud',
        'air': 'fas fa-wind',
        'waste': 'fas fa-recycle'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (utilityLower.includes(key)) return icon
    }
    return 'fas fa-plug'
}

function getTotalArea() {
    const areas = plantsList.value
        .map(plant => plant.area)
        .filter(area => area)
        .map(area => {
            const match = area.match(/(\d+(?:\.\d+)?)/)
            return match ? parseFloat(match[1]) : 0
        })
    
    if (areas.length === 0) return '-'
    
    const total = areas.reduce((sum, area) => sum + area, 0)
    return `${total.toLocaleString()} m²`
}

function getTotalEmployees() {
    const employees = plantsList.value
        .map(plant => plant.employees)
        .filter(emp => emp)
        .map(emp => {
            const match = emp.toString().match(/(\d+)/)
            return match ? parseInt(match[1]) : 0
        })
    
    if (employees.length === 0) return '-'
    
    return employees.reduce((sum, emp) => sum + emp, 0).toLocaleString()
}

function getOperationalPlants() {
    return plantsList.value.filter(plant => 
        getPlantStatus(plant.status) === 'operational'
    ).length
}
</script>

<style scoped>
.plants-card {
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
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
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

.plants-stats {
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

.plants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 24px;
}

.plant-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.plant-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.plant-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.plant-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #34d399 0%, #059669 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.plant-info {
    flex: 1;
}

.plant-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.plant-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.plant-location {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
}

.plant-location i {
    font-size: 0.75rem;
}

.plant-status {
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

.status-badge.operational {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.maintenance {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.construction {
    background: #e0e7ff;
    color: #3730a3;
}

.status-badge.closed {
    background: #fef2f2;
    color: #991b1b;
}

.plant-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.plant-specifications,
.plant-facilities,
.plant-production,
.plant-certifications,
.plant-utilities,
.plant-description {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.plant-specifications h6,
.plant-facilities h6,
.plant-production h6,
.plant-certifications h6,
.plant-utilities h6,
.plant-description h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
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

.facilities-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.facility-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.facility-item i {
    color: #059669;
    font-size: 0.75rem;
}

.production-lines {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.production-line {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.line-icon {
    width: 32px;
    height: 32px;
    background: #059669;
    color: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.line-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.line-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.line-capacity {
    font-size: 0.75rem;
    color: #6b7280;
}

.certifications-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.certification-tag {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.utilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.utility-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
    color: #374151;
}

.utility-item i {
    color: #059669;
    font-size: 0.875rem;
}

.plant-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.plants-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.plants-summary h4 {
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

.plants-summary h4 i {
    color: #8b5cf6;
}

.summary-grid {
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
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
    .plants-card {
        padding: 16px;
    }
    
    .plants-grid,
    .summary-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .plant-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .specs-grid,
    .utilities-grid {
        grid-template-columns: 1fr;
    }
}
</style>