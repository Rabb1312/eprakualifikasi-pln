<template>
    <div class="facilities-card">
        <div v-if="hasFacilities" class="facilities-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="header-info">
                    <h3>Facilities</h3>
                    <p>Fasilitas yang dimiliki oleh forwarder untuk mendukung operasional</p>
                </div>
                <div class="facilities-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ facilitiesList.length }}</span>
                        <span class="stat-label">Facilities</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Facilities Overview -->
                <div class="facilities-overview">
                    <h4>
                        <i class="fas fa-building"></i>
                        Facility Details
                    </h4>
                    <div class="facilities-grid">
                        <div 
                            v-for="(facility, index) in facilitiesList"
                            :key="index"
                            class="facility-item"
                        >
                            <div class="facility-header">
                                <div class="facility-icon">
                                    <i :class="getFacilityIcon(facility.type)"></i>
                                </div>
                                <div class="facility-info">
                                    <h5>{{ facility.name || facility.type || 'Facility' }}</h5>
                                    <span class="facility-category">{{ facility.category || 'General Facility' }}</span>
                                </div>
                                <div class="facility-status">
                                    <span class="status-badge operational">
                                        <i class="fas fa-check-circle"></i>
                                        Operational
                                    </span>
                                </div>
                            </div>

                            <div class="facility-details">
                                <div class="details-grid">
                                    <div class="detail-item" v-if="facility.capacity">
                                        <label>Capacity:</label>
                                        <span>{{ facility.capacity }}</span>
                                    </div>
                                    <div class="detail-item" v-if="facility.area">
                                        <label>Area:</label>
                                        <span>{{ facility.area }}</span>
                                    </div>
                                    <div class="detail-item" v-if="facility.location">
                                        <label>Location:</label>
                                        <span>{{ facility.location }}</span>
                                    </div>
                                    <div class="detail-item" v-if="facility.equipment_count">
                                        <label>Equipment:</label>
                                        <span>{{ facility.equipment_count }} units</span>
                                    </div>
                                </div>

                                <div class="facility-description" v-if="facility.description">
                                    <p>{{ facility.description }}</p>
                                </div>
                            </div>

                            <div class="facility-equipment" v-if="facility.equipment && facility.equipment.length > 0">
                                <h6>Equipment & Tools:</h6>
                                <div class="equipment-list">
                                    <div 
                                        v-for="(equipment, equipIndex) in facility.equipment"
                                        :key="equipIndex"
                                        class="equipment-item"
                                    >
                                        <i :class="getEquipmentIcon(equipment.type)"></i>
                                        <span>{{ equipment.name || equipment.type }}</span>
                                        <span class="equipment-count" v-if="equipment.quantity">
                                            {{ equipment.quantity }}x
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="facility-services" v-if="facility.services && facility.services.length > 0">
                                <h6>Available Services:</h6>
                                <div class="services-tags">
                                    <span 
                                        v-for="(service, serviceIndex) in facility.services"
                                        :key="serviceIndex"
                                        class="service-tag"
                                    >
                                        {{ service }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facility Types Summary -->
                <div class="facility-types" v-if="facilityTypes.length > 0">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Facility Types
                    </h4>
                    <div class="types-grid">
                        <div 
                            v-for="(type, index) in facilityTypes"
                            :key="index"
                            class="type-item"
                        >
                            <div class="type-icon">
                                <i :class="getFacilityIcon(type.name)"></i>
                            </div>
                            <div class="type-info">
                                <h6>{{ type.name }}</h6>
                                <span class="type-count">{{ type.count }} facilities</span>
                            </div>
                            <div class="type-percentage">
                                <span class="percentage-value">{{ getTypePercentage(type.count) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Facility Capacity Summary -->
                <div class="capacity-summary">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Capacity Summary
                    </h4>
                    <div class="capacity-stats">
                        <div class="capacity-item">
                            <div class="capacity-icon">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div class="capacity-info">
                                <span class="capacity-number">{{ getTotalCapacity() }}</span>
                                <span class="capacity-label">Total Storage Capacity</span>
                            </div>
                        </div>
                        <div class="capacity-item">
                            <div class="capacity-icon">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </div>
                            <div class="capacity-info">
                                <span class="capacity-number">{{ getTotalArea() }}</span>
                                <span class="capacity-label">Total Area</span>
                            </div>
                        </div>
                        <div class="capacity-item">
                            <div class="capacity-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="capacity-info">
                                <span class="capacity-number">{{ getTotalEquipment() }}</span>
                                <span class="capacity-label">Total Equipment</span>
                            </div>
                        </div>
                        <div class="capacity-item">
                            <div class="capacity-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="capacity-info">
                                <span class="capacity-number">{{ getUniqueLocations() }}</span>
                                <span class="capacity-label">Locations</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-warehouse"></i>
                <h4>Belum Ada Data Fasilitas</h4>
                <p>Data fasilitas belum dilengkapi untuk forwarder ini</p>
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

const hasFacilities = computed(() => {
    return !!(props.data.facilities && Array.isArray(props.data.facilities) && props.data.facilities.length > 0)
})

const facilitiesList = computed(() => {
    if (!props.data.facilities) return []
    return Array.isArray(props.data.facilities) ? props.data.facilities : []
})

const facilityTypes = computed(() => {
    if (facilitiesList.value.length === 0) return []
    
    const types = {}
    facilitiesList.value.forEach(facility => {
        const type = facility.type || facility.category || 'General'
        if (!types[type]) {
            types[type] = { name: type, count: 0 }
        }
        types[type].count++
    })
    
    return Object.values(types).sort((a, b) => b.count - a.count)
})

function getFacilityIcon(type) {
    const icons = {
        'warehouse': 'fas fa-warehouse',
        'storage': 'fas fa-boxes',
        'office': 'fas fa-building',
        'terminal': 'fas fa-truck-loading',
        'depot': 'fas fa-industry',
        'yard': 'fas fa-square',
        'workshop': 'fas fa-tools',
        'maintenance': 'fas fa-wrench',
        'container_yard': 'fas fa-layer-group',
        'cold_storage': 'fas fa-thermometer-half'
    }
    return icons[type?.toLowerCase()] || 'fas fa-building'
}

function getEquipmentIcon(type) {
    const icons = {
        'crane': 'fas fa-truck-pickup',
        'forklift': 'fas fa-pallet',
        'conveyor': 'fas fa-arrow-right',
        'scanner': 'fas fa-barcode',
        'scale': 'fas fa-weight',
        'truck': 'fas fa-truck',
        'container': 'fas fa-box'
    }
    return icons[type?.toLowerCase()] || 'fas fa-cog'
}

function getTypePercentage(count) {
    if (facilitiesList.value.length === 0) return 0
    return Math.round((count / facilitiesList.value.length) * 100)
}

function getTotalCapacity() {
    const total = facilitiesList.value.reduce((sum, facility) => {
        const capacity = facility.capacity || '0'
        const number = parseFloat(capacity.toString().replace(/[^\d.]/g, ''))
        return sum + (isNaN(number) ? 0 : number)
    }, 0)
    
    if (total >= 1000) {
        return `${(total / 1000).toFixed(1)}K`
    }
    return total.toString()
}

function getTotalArea() {
    const total = facilitiesList.value.reduce((sum, facility) => {
        const area = facility.area || '0'
        const number = parseFloat(area.toString().replace(/[^\d.]/g, ''))
        return sum + (isNaN(number) ? 0 : number)
    }, 0)
    
    if (total >= 1000) {
        return `${(total / 1000).toFixed(1)}K m²`
    }
    return `${total} m²`
}

function getTotalEquipment() {
    return facilitiesList.value.reduce((sum, facility) => {
        const equipment = facility.equipment || []
        return sum + equipment.reduce((equipSum, equip) => {
            return equipSum + (parseInt(equip.quantity) || 1)
        }, 0)
    }, 0)
}

function getUniqueLocations() {
    const locations = new Set()
    facilitiesList.value.forEach(facility => {
        if (facility.location) {
            locations.add(facility.location)
        }
    })
    return locations.size
}
</script>

<style scoped>
.facilities-card {
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

.facilities-stats {
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

.facilities-overview,
.facility-types,
.capacity-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.facilities-overview h4,
.facility-types h4,
.capacity-summary h4 {
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

.facilities-overview h4 i {
    color: #7c3aed;
}

.facility-types h4 i {
    color: #f59e0b;
}

.capacity-summary h4 i {
    color: #10b981;
}

.facilities-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 24px;
}

.facility-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.facility-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.facility-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.facility-info {
    flex: 1;
}

.facility-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.facility-category {
    font-size: 0.875rem;
    color: #6b7280;
}

.facility-status {
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

.facility-details {
    padding: 16px 20px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
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

.facility-description {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    margin-top: 12px;
}

.facility-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.facility-equipment,
.facility-services {
    padding: 16px 20px;
    border-top: 1px solid #f3f4f6;
}

.facility-equipment h6,
.facility-services h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.equipment-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.equipment-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.equipment-item i {
    color: #7c3aed;
    font-size: 0.75rem;
    width: 16px;
}

.equipment-count {
    margin-left: auto;
    font-weight: 600;
    color: #6b7280;
}

.services-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.service-tag {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.type-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.type-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.type-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.type-info {
    flex: 1;
}

.type-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.type-count {
    font-size: 0.875rem;
    color: #6b7280;
}

.type-percentage {
    flex-shrink: 0;
}

.percentage-value {
    font-weight: 700;
    color: #f59e0b;
    font-size: 1.125rem;
}

.capacity-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.capacity-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 8px;
}

.capacity-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.capacity-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.capacity-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.capacity-label {
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
    .facilities-card {
        padding: 16px;
    }
    
    .facilities-grid,
    .types-grid,
    .capacity-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .facility-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
}
</style>