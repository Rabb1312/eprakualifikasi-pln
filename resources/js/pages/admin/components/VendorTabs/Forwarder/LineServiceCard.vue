<template>
    <div class="line-service-card">
        <div v-if="hasData" class="line-service-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-route"></i>
                </div>
                <div class="header-info">
                    <h3>Line Service</h3>
                    <p>Hubungan dengan shipping lines dan airlines untuk layanan reguler</p>
                </div>
                <div class="service-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ getTotalLines() }}</span>
                        <span class="stat-label">Total Lines</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Shipping Lines -->
                <div v-if="hasShippingLines" class="shipping-section">
                    <h4>
                        <i class="fas fa-ship"></i>
                        Shipping Lines
                    </h4>
                    <div class="shipping-content">
                        <div v-if="data.shipping_line_relation" class="relation-info">
                            <div class="relation-header">
                                <div class="relation-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="relation-details">
                                    <h5>Shipping Line Relation</h5>
                                    <span class="relation-type">{{ data.shipping_line_relation }}</span>
                                </div>
                            </div>
                        </div>

                        <div v-if="shippingLines.length > 0" class="lines-grid">
                            <div 
                                v-for="(line, index) in shippingLines"
                                :key="`ship-${index}`"
                                class="line-item"
                            >
                                <div class="line-header">
                                    <div class="line-icon">
                                        <i class="fas fa-anchor"></i>
                                    </div>
                                    <div class="line-info">
                                        <h6>{{ line.name || `Shipping Line ${index + 1}` }}</h6>
                                        <span class="line-type">Sea Freight</span>
                                    </div>
                                    <div class="line-status">
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </span>
                                    </div>
                                </div>

                                <div class="line-details">
                                    <div class="detail-grid">
                                        <div class="detail-item" v-if="line.routes">
                                            <label>Routes:</label>
                                            <span>{{ line.routes }}</span>
                                        </div>
                                        <div class="detail-item" v-if="line.frequency">
                                            <label>Frequency:</label>
                                            <span>{{ line.frequency }}</span>
                                        </div>
                                        <div class="detail-item" v-if="line.vessel_type">
                                            <label>Vessel Type:</label>
                                            <span>{{ line.vessel_type }}</span>
                                        </div>
                                        <div class="detail-item" v-if="line.contact">
                                            <label>Contact:</label>
                                            <span>{{ line.contact }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Airlines -->
                <div v-if="hasAirlines" class="airlines-section">
                    <h4>
                        <i class="fas fa-plane"></i>
                        Airlines
                    </h4>
                    <div class="airlines-content">
                        <div v-if="data.airlines_relation" class="relation-info">
                            <div class="relation-header">
                                <div class="relation-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="relation-details">
                                    <h5>Airlines Relation</h5>
                                    <span class="relation-type">{{ data.airlines_relation }}</span>
                                </div>
                            </div>
                        </div>

                        <div v-if="airlines.length > 0" class="lines-grid">
                            <div 
                                v-for="(airline, index) in airlines"
                                :key="`air-${index}`"
                                class="line-item"
                            >
                                <div class="line-header">
                                    <div class="line-icon airline">
                                        <i class="fas fa-plane-departure"></i>
                                    </div>
                                    <div class="line-info">
                                        <h6>{{ airline.name || `Airline ${index + 1}` }}</h6>
                                        <span class="line-type">Air Freight</span>
                                    </div>
                                    <div class="line-status">
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </span>
                                    </div>
                                </div>

                                <div class="line-details">
                                    <div class="detail-grid">
                                        <div class="detail-item" v-if="airline.destinations">
                                            <label>Destinations:</label>
                                            <span>{{ airline.destinations }}</span>
                                        </div>
                                        <div class="detail-item" v-if="airline.frequency">
                                            <label>Frequency:</label>
                                            <span>{{ airline.frequency }}</span>
                                        </div>
                                        <div class="detail-item" v-if="airline.aircraft_type">
                                            <label>Aircraft Type:</label>
                                            <span>{{ airline.aircraft_type }}</span>
                                        </div>
                                        <div class="detail-item" v-if="airline.cargo_capacity">
                                            <label>Cargo Capacity:</label>
                                            <span>{{ airline.cargo_capacity }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Coverage -->
                <div class="coverage-section">
                    <h4>
                        <i class="fas fa-globe"></i>
                        Service Coverage
                    </h4>
                    <div class="coverage-stats">
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-ship"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ shippingLines.length }}</span>
                                <span class="coverage-label">Shipping Lines</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ airlines.length }}</span>
                                <span class="coverage-label">Airlines</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getTotalRoutes() }}</span>
                                <span class="coverage-label">Total Routes</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getAverageFrequency() }}</span>
                                <span class="coverage-label">Avg. Weekly Frequency</span>
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
                <p>Data hubungan dengan shipping lines dan airlines belum dilengkapi</p>
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

const shippingLines = computed(() => {
    if (!props.data.shipping_lines) return []
    return Array.isArray(props.data.shipping_lines) 
        ? props.data.shipping_lines 
        : []
})

const airlines = computed(() => {
    if (!props.data.airlines) return []
    return Array.isArray(props.data.airlines) 
        ? props.data.airlines 
        : []
})

const hasShippingLines = computed(() => {
    return !!(props.data.shipping_line_relation || shippingLines.value.length > 0)
})

const hasAirlines = computed(() => {
    return !!(props.data.airlines_relation || airlines.value.length > 0)
})

const hasData = computed(() => {
    return hasShippingLines.value || hasAirlines.value
})

function getTotalLines() {
    return shippingLines.value.length + airlines.value.length
}

function getTotalRoutes() {
    let routes = 0
    
    shippingLines.value.forEach(line => {
        if (line.routes) {
            // Estimate routes based on description
            routes += line.routes.split(',').length
        } else {
            routes += 1
        }
    })
    
    airlines.value.forEach(airline => {
        if (airline.destinations) {
            routes += airline.destinations.split(',').length
        } else {
            routes += 1
        }
    })
    
    return routes
}

function getAverageFrequency() {
    const frequencies = []
    
    shippingLines.value.forEach(line => {
        if (line.frequency) {
            const match = line.frequency.match(/(\d+)/)
            if (match) frequencies.push(parseInt(match[1]))
        }
    })
    
    airlines.value.forEach(airline => {
        if (airline.frequency) {
            const match = airline.frequency.match(/(\d+)/)
            if (match) frequencies.push(parseInt(match[1]))
        }
    })
    
    if (frequencies.length === 0) return '-'
    
    const average = frequencies.reduce((sum, freq) => sum + freq, 0) / frequencies.length
    return Math.round(average)
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

.shipping-section,
.airlines-section,
.coverage-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.shipping-section h4,
.airlines-section h4,
.coverage-section h4 {
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

.shipping-section h4 i {
    color: #3b82f6;
}

.airlines-section h4 i {
    color: #ef4444;
}

.coverage-section h4 i {
    color: #10b981;
}

.shipping-content,
.airlines-content {
    padding: 24px;
}

.relation-info {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
}

.relation-header {
    display: flex;
    align-items: center;
    gap: 16px;
}

.relation-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.relation-details h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.relation-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.lines-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 16px;
}

.line-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.line-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.line-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.line-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.line-icon.airline {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.line-info {
    flex: 1;
}

.line-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.line-type {
    font-size: 0.875rem;
    color: #6b7280;
}

.line-status {
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

.line-details {
    padding: 16px 20px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
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
    text-align: right;
    max-width: 60%;
    word-break: break-word;
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
    
    .lines-grid,
    .coverage-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .line-header,
    .relation-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>