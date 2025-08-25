<template>
    <div class="production-capacity-card">
        <div v-if="hasData" class="production-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="header-info">
                    <h3>Production Capacity</h3>
                    <p>Kapasitas produksi dan kemampuan manufakturing yang dimiliki</p>
                </div>
                <div class="capacity-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ productionLines.length }}</span>
                        <span class="stat-label">Production Lines</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Production Lines Overview -->
                <div class="production-overview">
                    <h4>
                        <i class="fas fa-list"></i>
                        Production Lines
                    </h4>
                    <div class="production-grid">
                        <div 
                            v-for="(line, index) in productionLines"
                            :key="index"
                            class="production-item"
                        >
                            <div class="production-header">
                                <div class="production-icon">
                                    <i :class="getProductionIcon(line.type)"></i>
                                </div>
                                <div class="production-info">
                                    <h5>{{ line.name || `Production Line ${index + 1}` }}</h5>
                                    <span class="production-type">{{ line.type || 'Manufacturing Line' }}</span>
                                </div>
                                <div class="production-status">
                                    <span :class="['status-badge', getStatusClass(line.status)]">
                                        <i :class="getStatusIcon(line.status)"></i>
                                        {{ getStatusText(line.status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="production-details">
                                <div class="capacity-metrics">
                                    <div class="metrics-grid">
                                        <div class="metric-item" v-if="line.daily_capacity">
                                            <label>Daily Capacity:</label>
                                            <span class="capacity-value">{{ line.daily_capacity }}</span>
                                        </div>
                                        <div class="metric-item" v-if="line.monthly_capacity">
                                            <label>Monthly Capacity:</label>
                                            <span class="capacity-value">{{ line.monthly_capacity }}</span>
                                        </div>
                                        <div class="metric-item" v-if="line.annual_capacity">
                                            <label>Annual Capacity:</label>
                                            <span class="capacity-value">{{ line.annual_capacity }}</span>
                                        </div>
                                        <div class="metric-item" v-if="line.efficiency_rate">
                                            <label>Efficiency Rate:</label>
                                            <span class="efficiency-value">{{ line.efficiency_rate }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="equipment-info" v-if="line.equipment && line.equipment.length > 0">
                                    <h6>Equipment & Machinery:</h6>
                                    <div class="equipment-list">
                                        <div 
                                            v-for="(equipment, equipIndex) in line.equipment"
                                            :key="equipIndex"
                                            class="equipment-item"
                                        >
                                            <div class="equipment-icon">
                                                <i :class="getEquipmentIcon(equipment.type)"></i>
                                            </div>
                                            <div class="equipment-details">
                                                <span class="equipment-name">{{ equipment.name || equipment.type }}</span>
                                                <span class="equipment-specs" v-if="equipment.specifications">{{ equipment.specifications }}</span>
                                            </div>
                                            <div class="equipment-count" v-if="equipment.quantity">
                                                <span class="count-badge">{{ equipment.quantity }}x</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="operational-hours" v-if="line.operational_hours">
                                    <div class="hours-info">
                                        <h6>Operational Schedule:</h6>
                                        <div class="schedule-details">
                                            <div class="schedule-item" v-if="line.operational_hours.working_days">
                                                <i class="fas fa-calendar"></i>
                                                <span>{{ line.operational_hours.working_days }} days/week</span>
                                            </div>
                                            <div class="schedule-item" v-if="line.operational_hours.shifts_per_day">
                                                <i class="fas fa-clock"></i>
                                                <span>{{ line.operational_hours.shifts_per_day }} shifts/day</span>
                                            </div>
                                            <div class="schedule-item" v-if="line.operational_hours.hours_per_shift">
                                                <i class="fas fa-hourglass-half"></i>
                                                <span>{{ line.operational_hours.hours_per_shift }} hours/shift</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="capacity-utilization" v-if="line.current_utilization">
                                    <h6>Capacity Utilization:</h6>
                                    <div class="utilization-bar">
                                        <div class="utilization-fill" :style="{ width: line.current_utilization + '%' }"></div>
                                    </div>
                                    <div class="utilization-text">
                                        <span>{{ line.current_utilization }}% utilized</span>
                                        <span class="available-capacity">{{ 100 - line.current_utilization }}% available</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Capacity Summary -->
                <div class="capacity-summary">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Total Production Capacity
                    </h4>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getTotalDailyCapacity() }}</span>
                                <span class="summary-label">Total Daily Capacity</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getTotalMonthlyCapacity() }}</span>
                                <span class="summary-label">Total Monthly Capacity</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getTotalAnnualCapacity() }}</span>
                                <span class="summary-label">Total Annual Capacity</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getAverageUtilization() }}%</span>
                                <span class="summary-label">Average Utilization</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Production Categories -->
                <div class="production-categories" v-if="productionCategories.length > 0">
                    <h4>
                        <i class="fas fa-tags"></i>
                        Production Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in productionCategories"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getCategoryIcon(category.type)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.type }}</h6>
                                <span class="category-lines">{{ category.lines }} lines</span>
                                <div class="category-capacity">
                                    <span class="capacity-text">{{ category.totalCapacity }} total capacity</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-industry"></i>
                <h4>Belum Ada Data Production Capacity</h4>
                <p>Data kapasitas produksi belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.production_capacity)
})

const productionLines = computed(() => {
    if (!props.data.production_capacity?.lines) return []
    return Array.isArray(props.data.production_capacity.lines) 
        ? props.data.production_capacity.lines 
        : []
})

const productionCategories = computed(() => {
    if (productionLines.value.length === 0) return []
    
    const categories = {}
    productionLines.value.forEach(line => {
        const type = line.type || 'General'
        if (!categories[type]) {
            categories[type] = { type, lines: 0, totalCapacity: 0 }
        }
        categories[type].lines++
        const dailyCapacity = parseFloat(line.daily_capacity?.replace(/[^\d.]/g, '')) || 0
        categories[type].totalCapacity += dailyCapacity
    })
    
    return Object.values(categories).map(cat => ({
        ...cat,
        totalCapacity: formatCapacity(cat.totalCapacity)
    }))
})

function getProductionIcon(type) {
    const icons = {
        'automotive': 'fas fa-car',
        'electronics': 'fas fa-microchip',
        'machinery': 'fas fa-cogs',
        'assembly': 'fas fa-puzzle-piece',
        'packaging': 'fas fa-box',
        'testing': 'fas fa-flask',
        'quality_control': 'fas fa-check-double',
        'finishing': 'fas fa-paint-brush'
    }
    return icons[type?.toLowerCase()] || 'fas fa-industry'
}

function getEquipmentIcon(type) {
    const icons = {
        'cnc_machine': 'fas fa-cog',
        'robot': 'fas fa-robot',
        'conveyor': 'fas fa-arrow-right',
        'press': 'fas fa-compress-arrows-alt',
        'welder': 'fas fa-fire',
        'furnace': 'fas fa-fire-alt',
        'printer': 'fas fa-print',
        'scanner': 'fas fa-search'
    }
    return icons[type?.toLowerCase()] || 'fas fa-tools'
}

function getCategoryIcon(type) {
    return getProductionIcon(type)
}

function getStatusClass(status) {
    return status?.toLowerCase() || 'operational'
}

function getStatusIcon(status) {
    const icons = {
        'operational': 'fas fa-play-circle',
        'maintenance': 'fas fa-wrench',
        'idle': 'fas fa-pause-circle',
        'offline': 'fas fa-stop-circle'
    }
    return icons[status?.toLowerCase()] || 'fas fa-play-circle'
}

function getStatusText(status) {
    const texts = {
        'operational': 'Operational',
        'maintenance': 'Maintenance',
        'idle': 'Idle',
        'offline': 'Offline'
    }
    return texts[status?.toLowerCase()] || 'Operational'
}

function getTotalDailyCapacity() {
    const total = productionLines.value.reduce((sum, line) => {
        const capacity = parseFloat(line.daily_capacity?.replace(/[^\d.]/g, '')) || 0
        return sum + capacity
    }, 0)
    return formatCapacity(total)
}

function getTotalMonthlyCapacity() {
    const total = productionLines.value.reduce((sum, line) => {
        const capacity = parseFloat(line.monthly_capacity?.replace(/[^\d.]/g, '')) || 0
        return sum + capacity
    }, 0)
    return formatCapacity(total)
}

function getTotalAnnualCapacity() {
    const total = productionLines.value.reduce((sum, line) => {
        const capacity = parseFloat(line.annual_capacity?.replace(/[^\d.]/g, '')) || 0
        return sum + capacity
    }, 0)
    return formatCapacity(total)
}

function getAverageUtilization() {
    const utilizationRates = productionLines.value
        .map(line => parseFloat(line.current_utilization) || 0)
        .filter(rate => rate > 0)
    
    if (utilizationRates.length === 0) return 0
    
    const average = utilizationRates.reduce((sum, rate) => sum + rate, 0) / utilizationRates.length
    return Math.round(average)
}

function formatCapacity(capacity) {
    if (capacity >= 1000000) {
        return `${(capacity / 1000000).toFixed(1)}M`
    } else if (capacity >= 1000) {
        return `${(capacity / 1000).toFixed(1)}K`
    }
    return capacity.toString()
}
</script>

<style scoped>
.production-capacity-card {
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

.capacity-stats {
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

.production-overview,
.capacity-summary,
.production-categories {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.production-overview h4,
.capacity-summary h4,
.production-categories h4 {
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

.production-overview h4 i {
    color: #059669;
}

.capacity-summary h4 i {
    color: #3b82f6;
}

.production-categories h4 i {
    color: #f59e0b;
}

.production-grid {
    display: flex;
    flex-direction: column;
    gap: 24px;
    padding: 24px;
}

.production-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.production-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.production-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.production-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #34d399 0%, #059669 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.production-info {
    flex: 1;
}

.production-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.production-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.production-status {
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

.status-badge.idle {
    background: #e0e7ff;
    color: #3730a3;
}

.status-badge.offline {
    background: #fef2f2;
    color: #991b1b;
}

.production-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.capacity-metrics {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

.capacity-value {
    font-weight: 700;
    color: #059669;
    font-size: 0.875rem;
}

.efficiency-value {
    font-weight: 700;
    color: #3b82f6;
    font-size: 0.875rem;
}

.equipment-info {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.equipment-info h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.equipment-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.equipment-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.equipment-icon {
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

.equipment-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.equipment-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.equipment-specs {
    font-size: 0.75rem;
    color: #6b7280;
}

.equipment-count {
    flex-shrink: 0;
}

.count-badge {
    background: #1f2937;
    color: white;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.operational-hours {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.hours-info h6 {
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
    color: #059669;
    width: 16px;
    text-align: center;
}

.capacity-utilization {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.capacity-utilization h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.utilization-bar {
    width: 100%;
    height: 12px;
    background: #f3f4f6;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 8px;
}

.utilization-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    border-radius: 6px;
    transition: width 0.5s ease;
}

.utilization-text {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
}

.utilization-text span:first-child {
    color: #059669;
    font-weight: 600;
}

.available-capacity {
    color: #6b7280;
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
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
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

.category-lines {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 4px;
}

.category-capacity {
    font-size: 0.875rem;
}

.capacity-text {
    color: #f59e0b;
    font-weight: 600;
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
    .production-capacity-card {
        padding: 16px;
    }
    
    .production-grid,
    .summary-grid,
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .production-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
}
</style>