<template>
    <div class="line-service-card">
        <div v-if="hasData" class="line-service-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-route"></i>
                </div>
                <div class="header-info">
                    <h3>Line Service</h3>
                    <p>Layanan jalur transportasi reguler yang disediakan forwarder</p>
                </div>
                <div class="service-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ lineServices.length }}</span>
                        <span class="stat-label">Routes</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Line Services Overview -->
                <div class="services-overview">
                    <h4>
                        <i class="fas fa-shipping-fast"></i>
                        Available Routes
                    </h4>
                    <div class="services-grid">
                        <div 
                            v-for="(service, index) in lineServices"
                            :key="index"
                            class="service-item"
                        >
                            <div class="service-header">
                                <div class="service-icon">
                                    <i :class="getServiceIcon(service.type)"></i>
                                </div>
                                <div class="service-info">
                                    <h5>{{ service.route_name || `${service.origin} - ${service.destination}` }}</h5>
                                    <span class="service-type">{{ getServiceTypeLabel(service.type) }}</span>
                                </div>
                                <div class="service-status">
                                    <span :class="['status-badge', getServiceStatus(service)]">
                                        <i :class="getStatusIcon(service.status)"></i>
                                        {{ getStatusText(service.status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="route-details">
                                <div class="route-info">
                                    <div class="route-path">
                                        <div class="route-point origin">
                                            <div class="point-icon">
                                                <i class="fas fa-play"></i>
                                            </div>
                                            <div class="point-info">
                                                <h6>Origin</h6>
                                                <span>{{ service.origin || 'Not specified' }}</span>
                                            </div>
                                        </div>
                                        <div class="route-connector">
                                            <div class="connector-line"></div>
                                            <div class="connector-icon">
                                                <i :class="getTransportIcon(service.type)"></i>
                                            </div>
                                        </div>
                                        <div class="route-point destination">
                                            <div class="point-icon">
                                                <i class="fas fa-flag-checkered"></i>
                                            </div>
                                            <div class="point-info">
                                                <h6>Destination</h6>
                                                <span>{{ service.destination || 'Not specified' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="service-specs">
                                    <div class="specs-grid">
                                        <div class="spec-item" v-if="service.frequency">
                                            <label>Frequency:</label>
                                            <span>{{ service.frequency }}</span>
                                        </div>
                                        <div class="spec-item" v-if="service.transit_time">
                                            <label>Transit Time:</label>
                                            <span>{{ service.transit_time }}</span>
                                        </div>
                                        <div class="spec-item" v-if="service.capacity">
                                            <label>Capacity:</label>
                                            <span>{{ service.capacity }}</span>
                                        </div>
                                        <div class="spec-item" v-if="service.vessel_type">
                                            <label>Vessel Type:</label>
                                            <span>{{ service.vessel_type }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="schedule-info" v-if="service.schedule">
                                    <h6>Schedule Information:</h6>
                                    <div class="schedule-details">
                                        <div class="schedule-item" v-if="service.schedule.departure_day">
                                            <i class="fas fa-calendar"></i>
                                            <span>Departure: {{ service.schedule.departure_day }}</span>
                                        </div>
                                        <div class="schedule-item" v-if="service.schedule.departure_time">
                                            <i class="fas fa-clock"></i>
                                            <span>Time: {{ service.schedule.departure_time }}</span>
                                        </div>
                                        <div class="schedule-item" v-if="service.schedule.cutoff_time">
                                            <i class="fas fa-stopwatch"></i>
                                            <span>Cut-off: {{ service.schedule.cutoff_time }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="service-features" v-if="service.features && service.features.length > 0">
                                    <h6>Service Features:</h6>
                                    <div class="features-list">
                                        <div 
                                            v-for="(feature, featureIndex) in service.features"
                                            :key="featureIndex"
                                            class="feature-item"
                                        >
                                            <i class="fas fa-check"></i>
                                            <span>{{ feature }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pricing-info" v-if="service.pricing">
                                    <div class="pricing-header">
                                        <h6>Pricing Information</h6>
                                    </div>
                                    <div class="pricing-details">
                                        <div class="pricing-item" v-if="service.pricing.base_rate">
                                            <label>Base Rate:</label>
                                            <span class="price-value">{{ service.pricing.base_rate }}</span>
                                        </div>
                                        <div class="pricing-item" v-if="service.pricing.currency">
                                            <label>Currency:</label>
                                            <span>{{ service.pricing.currency }}</span>
                                        </div>
                                        <div class="pricing-item" v-if="service.pricing.unit">
                                            <label>Unit:</label>
                                            <span>{{ service.pricing.unit }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Types Summary -->
                <div class="service-types" v-if="serviceTypes.length > 0">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Service Types
                    </h4>
                    <div class="types-grid">
                        <div 
                            v-for="(type, index) in serviceTypes"
                            :key="index"
                            class="type-item"
                        >
                            <div class="type-icon">
                                <i :class="getServiceIcon(type.name)"></i>
                            </div>
                            <div class="type-info">
                                <h6>{{ getServiceTypeLabel(type.name) }}</h6>
                                <span class="type-count">{{ type.count }} routes</span>
                            </div>
                            <div class="type-percentage">
                                <span class="percentage-value">{{ getTypePercentage(type.count) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coverage Map -->
                <div class="coverage-map">
                    <h4>
                        <i class="fas fa-map"></i>
                        Route Coverage
                    </h4>
                    <div class="coverage-stats">
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ lineServices.length }}</span>
                                <span class="coverage-label">Total Routes</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getUniqueDestinations() }}</span>
                                <span class="coverage-label">Destinations</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getAverageFrequency() }}</span>
                                <span class="coverage-label">Avg. Weekly Frequency</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getAverageTransitTime() }}</span>
                                <span class="coverage-label">Avg. Transit Time</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-route"></i>
                <h4>Belum Ada Data Line Service</h4>
                <p>Data layanan jalur transportasi belum dilengkapi untuk forwarder ini</p>
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
    return !!(props.data.line_service)
})

const lineServices = computed(() => {
    if (!props.data.line_service?.routes) return []
    return Array.isArray(props.data.line_service.routes) 
        ? props.data.line_service.routes 
        : []
})

const serviceTypes = computed(() => {
    if (lineServices.value.length === 0) return []
    
    const types = {}
    lineServices.value.forEach(service => {
        const type = service.type || 'general'
        if (!types[type]) {
            types[type] = { name: type, count: 0 }
        }
        types[type].count++
    })
    
    return Object.values(types).sort((a, b) => b.count - a.count)
})

function getServiceIcon(type) {
    const icons = {
        'sea_freight': 'fas fa-ship',
        'air_freight': 'fas fa-plane',
        'land_transport': 'fas fa-truck',
        'rail_transport': 'fas fa-train',
        'multimodal': 'fas fa-route',
        'express': 'fas fa-shipping-fast',
        'regular': 'fas fa-truck-loading'
    }
    return icons[type?.toLowerCase()] || 'fas fa-shipping-fast'
}

function getTransportIcon(type) {
    const icons = {
        'sea_freight': 'fas fa-anchor',
        'air_freight': 'fas fa-plane-departure',
        'land_transport': 'fas fa-road',
        'rail_transport': 'fas fa-subway',
        'multimodal': 'fas fa-exchange-alt'
    }
    return icons[type?.toLowerCase()] || 'fas fa-arrow-right'
}

function getServiceTypeLabel(type) {
    const labels = {
        'sea_freight': 'Sea Freight',
        'air_freight': 'Air Freight',
        'land_transport': 'Land Transport',
        'rail_transport': 'Rail Transport',
        'multimodal': 'Multimodal',
        'express': 'Express Service',
        'regular': 'Regular Service'
    }
    return labels[type?.toLowerCase()] || 'Transport Service'
}

function getServiceStatus(service) {
    return service.status?.toLowerCase() || 'active'
}

function getStatusIcon(status) {
    const icons = {
        'active': 'fas fa-check-circle',
        'inactive': 'fas fa-times-circle',
        'seasonal': 'fas fa-calendar-alt',
        'suspended': 'fas fa-pause-circle'
    }
    return icons[status?.toLowerCase()] || 'fas fa-check-circle'
}

function getStatusText(status) {
    const texts = {
        'active': 'Active',
        'inactive': 'Inactive',
        'seasonal': 'Seasonal',
        'suspended': 'Suspended'
    }
    return texts[status?.toLowerCase()] || 'Active'
}

function getTypePercentage(count) {
    if (lineServices.value.length === 0) return 0
    return Math.round((count / lineServices.value.length) * 100)
}

function getUniqueDestinations() {
    const destinations = new Set()
    lineServices.value.forEach(service => {
        if (service.origin) destinations.add(service.origin)
        if (service.destination) destinations.add(service.destination)
    })
    return destinations.size
}

function getAverageFrequency() {
    const frequencies = lineServices.value
        .map(service => service.frequency)
        .filter(freq => freq && typeof freq === 'string')
        .map(freq => {
            const match = freq.match(/(\d+)/)
            return match ? parseInt(match[1]) : 0
        })
        .filter(num => num > 0)
    
    if (frequencies.length === 0) return '-'
    
    const average = frequencies.reduce((sum, freq) => sum + freq, 0) / frequencies.length
    return Math.round(average)
}

function getAverageTransitTime() {
    const transitTimes = lineServices.value
        .map(service => service.transit_time)
        .filter(time => time && typeof time === 'string')
        .map(time => {
            const match = time.match(/(\d+)/)
            return match ? parseInt(match[1]) : 0
        })
        .filter(num => num > 0)
    
    if (transitTimes.length === 0) return '-'
    
    const average = transitTimes.reduce((sum, time) => sum + time, 0) / transitTimes.length
    return `${Math.round(average)} days`
}
</script>

<style scoped>
.line-service-card {
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

.service-stats {
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

.services-overview,
.service-types,
.coverage-map {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.services-overview h4,
.service-types h4,
.coverage-map h4 {
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

.services-overview h4 i {
    color: #8b5cf6;
}

.service-types h4 i {
    color: #f59e0b;
}

.coverage-map h4 i {
    color: #10b981;
}

.services-grid {
    display: flex;
    flex-direction: column;
    gap: 24px;
    padding: 24px;
}

.service-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.service-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.service-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.service-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.service-info {
    flex: 1;
}

.service-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.service-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.service-status {
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

.status-badge.inactive {
    background: #fef2f2;
    color: #991b1b;
}

.status-badge.seasonal {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.suspended {
    background: #e0e7ff;
    color: #3730a3;
}

.route-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.route-info {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 20px;
}

.route-path {
    display: flex;
    align-items: center;
    gap: 16px;
}

.route-point {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.point-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.route-point.origin .point-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.route-point.destination .point-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.point-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.point-info span {
    color: #1f2937;
    font-weight: 600;
    font-size: 1rem;
}

.route-connector {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    min-width: 60px;
}

.connector-line {
    width: 40px;
    height: 2px;
    background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
    border-radius: 1px;
}

.connector-icon {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

.service-specs {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.spec-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.spec-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.schedule-info {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.schedule-info h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.schedule-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.schedule-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.schedule-item i {
    color: #8b5cf6;
    width: 16px;
    text-align: center;
}

.service-features {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.service-features h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.feature-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.pricing-info {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-radius: 8px;
    border: 1px solid #bae6fd;
    padding: 16px;
}

.pricing-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #0c4a6e;
    font-size: 0.875rem;
}

.pricing-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.pricing-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #bae6fd;
}

.pricing-item label {
    font-weight: 500;
    color: #0369a1;
    font-size: 0.875rem;
}

.pricing-item span {
    font-weight: 600;
    color: #0c4a6e;
    font-size: 0.875rem;
}

.price-value {
    font-weight: 700 !important;
    color: #dc2626 !important;
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

.coverage-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.coverage-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 8px;
}

.coverage-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.coverage-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.coverage-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.coverage-label {
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
    .line-service-card {
        padding: 16px;
    }
    
    .services-grid,
    .types-grid,
    .coverage-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .service-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .route-path {
        flex-direction: column;
        gap: 20px;
    }
    
    .route-connector {
        transform: rotate(90deg);
    }
    
    .specs-grid,
    .pricing-details {
        grid-template-columns: 1fr;
    }
}
</style>