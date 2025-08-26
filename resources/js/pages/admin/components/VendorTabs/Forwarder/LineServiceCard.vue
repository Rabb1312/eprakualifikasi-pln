<template>
    <div class="line-service-card">
        <div v-if="hasData" class="line-service-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="header-info">
                    <h3>Line Service Management</h3>
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
                <!-- Shipping Lines Section -->
                <div v-if="hasShippingLines" class="shipping-section">
                    <h4>
                        <i class="fas fa-ship"></i>
                        Shipping Lines
                    </h4>
                    <div class="shipping-content">
                        <!-- Shipping Line Relation -->
                        <div v-if="lineServiceData.shipping_line_relation" class="relation-info">
                            <div class="relation-header">
                                <div class="relation-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="relation-details">
                                    <h5>Shipping Line Relation</h5>
                                    <span class="relation-type">{{ lineServiceData.shipping_line_relation }}</span>
                                </div>
                                <div class="relation-status">
                                    <span class="status-badge configured">
                                        <i class="fas fa-check-circle"></i>
                                        Configured
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Lines List -->
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
                                        <h6>{{ line.shipping_line_name || `Shipping Line ${index + 1}` }}</h6>
                                        <span class="line-type">Sea Freight Service</span>
                                    </div>
                                    <div class="line-status">
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </span>
                                    </div>
                                </div>

                                <div class="line-details">
                                    <!-- Service Types -->
                                    <div class="service-types">
                                        <h6>Service Types:</h6>
                                        <div class="service-tags">
                                            <span v-if="line.domestic" class="service-tag domestic">
                                                <i class="fas fa-flag"></i>
                                                Domestik
                                            </span>
                                            <span v-if="line.foreign" class="service-tag foreign">
                                                <i class="fas fa-globe"></i>
                                                Luar Negeri
                                            </span>
                                            <span v-if="line.containerized" class="service-tag containerized">
                                                <i class="fas fa-cube"></i>
                                                Kontainer
                                            </span>
                                            <span v-if="line.break_bulk" class="service-tag break-bulk">
                                                <i class="fas fa-boxes"></i>
                                                Break Bulk
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Airlines Section -->
                <div v-if="hasAirlines" class="airlines-section">
                    <h4>
                        <i class="fas fa-plane"></i>
                        Airlines
                    </h4>
                    <div class="airlines-content">
                        <!-- Airlines Relation -->
                        <div v-if="lineServiceData.airlines_relation" class="relation-info">
                            <div class="relation-header">
                                <div class="relation-icon airline">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="relation-details">
                                    <h5>Airlines Relation</h5>
                                    <span class="relation-type">{{ lineServiceData.airlines_relation }}</span>
                                </div>
                                <div class="relation-status">
                                    <span class="status-badge configured">
                                        <i class="fas fa-check-circle"></i>
                                        Configured
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Airlines List -->
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
                                        <h6>{{ airline.airline_name || `Airline ${index + 1}` }}</h6>
                                        <span class="line-type">Air Freight Service</span>
                                    </div>
                                    <div class="line-status">
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle"></i>
                                            Active
                                        </span>
                                    </div>
                                </div>

                                <div class="line-details">
                                    <!-- Airline Information -->
                                    <div class="airline-info">
                                        <h6>Airline Information:</h6>
                                        <div class="airline-details">
                                            <div class="airline-item">
                                                <i class="fas fa-plane"></i>
                                                <span>{{ airline.airline_name || 'Airline name not specified' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-shipping-fast"></i>
                <h4>Belum Ada Data Line Service</h4>
                <p>Data hubungan dengan shipping lines dan airlines belum dikonfigurasi</p>
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
    return !!(Object.keys(props.data).length > 0)
})

const lineServiceData = computed(() => {
    return props.data || {}
})

// Computed properties sesuai dengan ForwarderLineService.vue
const shippingLines = computed(() => {
    if (!lineServiceData.value.shipping_lines) return []
    return Array.isArray(lineServiceData.value.shipping_lines) 
        ? lineServiceData.value.shipping_lines.filter(line => line && (line.shipping_line_name || line.domestic || line.foreign || line.containerized || line.break_bulk))
        : []
})

const airlines = computed(() => {
    if (!lineServiceData.value.airlines) return []
    return Array.isArray(lineServiceData.value.airlines) 
        ? lineServiceData.value.airlines.filter(airline => airline && airline.airline_name)
        : []
})

const hasShippingLines = computed(() => {
    return !!(lineServiceData.value.shipping_line_relation || shippingLines.value.length > 0)
})

const hasAirlines = computed(() => {
    return !!(lineServiceData.value.airlines_relation || airlines.value.length > 0)
})

// Helper functions sesuai dengan struktur data ForwarderLineService
function getTotalLines() {
    return shippingLines.value.length + airlines.value.length
}

function getServiceCount(line) {
    let count = 0
    if (line.domestic) count++
    if (line.foreign) count++
    if (line.containerized) count++
    if (line.break_bulk) count++
    return count
}

function getServiceCoverage(line) {
    const services = []
    if (line.domestic) services.push('Domestic')
    if (line.foreign) services.push('International')
    if (line.containerized) services.push('Container')
    if (line.break_bulk) services.push('Break Bulk')
    
    if (services.length === 0) return 'No services specified'
    if (services.length <= 2) return services.join(' & ')
    return `${services.length} service types`
}

function getTotalServiceTypes() {
    let totalTypes = 0
    
    shippingLines.value.forEach(line => {
        totalTypes += getServiceCount(line)
    })
    
    // Airlines count as 1 service type each
    totalTypes += airlines.value.length
    
    return totalTypes
}

function getCompletionPercentage() {
    let totalFields = 0
    let filledFields = 0
    
    // Check shipping line relation
    totalFields++
    if (lineServiceData.value.shipping_line_relation) filledFields++
    
    // Check airlines relation
    totalFields++
    if (lineServiceData.value.airlines_relation) filledFields++
    
    // Check shipping lines
    shippingLines.value.forEach(line => {
        totalFields += 5 // shipping_line_name + 4 service types
        if (line.shipping_line_name) filledFields++
        if (line.domestic) filledFields++
        if (line.foreign) filledFields++
        if (line.containerized) filledFields++
        if (line.break_bulk) filledFields++
    })
    
    // Check airlines
    airlines.value.forEach(airline => {
        totalFields++ // airline_name
        if (airline.airline_name) filledFields++
    })
    
    return totalFields > 0 ? Math.round((filledFields / totalFields) * 100) : 0
}

function getDistributionPercentage(type) {
    const total = getTotalLines()
    if (total === 0) return 0
    
    if (type === 'shipping') {
        return Math.round((shippingLines.value.length / total) * 100)
    } else if (type === 'airlines') {
        return Math.round((airlines.value.length / total) * 100)
    }
    return 0
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
.service-overview {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.shipping-section h4,
.airlines-section h4,
.service-overview h4 {
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

.service-overview h4 i {
    color: #7c3aed;
}

.shipping-content,
.airlines-content,
.overview-content {
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
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.relation-icon.airline {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.relation-details {
    flex: 1;
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

.relation-status {
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

.status-badge.configured {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.lines-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
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

.line-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.service-types,
.airline-info,
.service-summary {
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    padding: 12px;
}

.service-types h6,
.airline-info h6,
.service-summary h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.service-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.service-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.service-tag.domestic {
    background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
    color: #991b1b;
}

.service-tag.foreign {
    background: linear-gradient(135deg, #ecfdf5 0%, #a7f3d0 100%);
    color: #065f46;
}

.service-tag.containerized {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.service-tag.break-bulk {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.airline-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.airline-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.airline-item i {
    color: #ef4444;
    font-size: 0.875rem;
}

.summary-items {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 8px;
    background: #f8fafc;
    border-radius: 4px;
    border: 1px solid #e5e7eb;
}

.summary-label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.summary-value {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.overview-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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

.service-distribution {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.distribution-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.distribution-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.distribution-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.distribution-label {
    flex: 0 0 120px;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.distribution-bar {
    flex: 1;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.distribution-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.distribution-fill.airlines {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}

.distribution-value {
    flex: 0 0 80px;
    text-align: right;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
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
    .statistics-grid {
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
    
    .distribution-item {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    
    .distribution-label {
        flex: none;
        text-align: center;
    }
    
    .distribution-value {
        flex: none;
        text-align: center;
    }
}
</style>