<template>
    <div class="supply-chain-card">
        <div v-if="hasData" class="supply-chain-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <div class="header-info">
                    <h3>Supply Chain</h3>
                    <p>Rantai pasok dan manajemen logistik manufacturer</p>
                </div>
                <div class="supply-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ suppliers.length }}</span>
                        <span class="stat-label">Suppliers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ distributors.length }}</span>
                        <span class="stat-label">Distributors</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Supply Chain Overview -->
                <div class="supply-chain-overview">
                    <h4>
                        <i class="fas fa-sitemap"></i>
                        Supply Chain Network
                    </h4>
                    <div class="chain-visualization">
                        <div class="chain-level suppliers-level">
                            <h5>
                                <i class="fas fa-industry"></i>
                                Suppliers
                                <span class="level-count">{{ suppliers.length }}</span>
                            </h5>
                            <div class="chain-nodes">
                                <div 
                                    v-for="(supplier, index) in suppliers.slice(0, 5)"
                                    :key="index"
                                    class="chain-node supplier-node"
                                    :title="supplier.name"
                                >
                                    <i :class="getSupplierIcon(supplier.type)"></i>
                                    <span class="node-label">{{ supplier.name || `Supplier ${index + 1}` }}</span>
                                </div>
                                <div v-if="suppliers.length > 5" class="chain-node more-node">
                                    <i class="fas fa-plus"></i>
                                    <span class="node-label">+{{ suppliers.length - 5 }} more</span>
                                </div>
                            </div>
                        </div>

                        <div class="chain-flow">
                            <i class="fas fa-arrow-down"></i>
                        </div>

                        <div class="chain-level manufacturer-level">
                            <h5>
                                <i class="fas fa-cogs"></i>
                                Manufacturing
                                <span class="level-count">1</span>
                            </h5>
                            <div class="chain-nodes">
                                <div class="chain-node manufacturer-node">
                                    <i class="fas fa-industry"></i>
                                    <span class="node-label">{{ vendor.nama_perusahaan || 'Manufacturer' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="chain-flow">
                            <i class="fas fa-arrow-down"></i>
                        </div>

                        <div class="chain-level distributors-level">
                            <h5>
                                <i class="fas fa-truck"></i>
                                Distributors
                                <span class="level-count">{{ distributors.length }}</span>
                            </h5>
                            <div class="chain-nodes">
                                <div 
                                    v-for="(distributor, index) in distributors.slice(0, 5)"
                                    :key="index"
                                    class="chain-node distributor-node"
                                    :title="distributor.name"
                                >
                                    <i :class="getDistributorIcon(distributor.type)"></i>
                                    <span class="node-label">{{ distributor.name || `Distributor ${index + 1}` }}</span>
                                </div>
                                <div v-if="distributors.length > 5" class="chain-node more-node">
                                    <i class="fas fa-plus"></i>
                                    <span class="node-label">+{{ distributors.length - 5 }} more</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suppliers Details -->
                <div class="suppliers-details" v-if="suppliers.length > 0">
                    <h4>
                        <i class="fas fa-industry"></i>
                        Key Suppliers
                    </h4>
                    <div class="suppliers-grid">
                        <div 
                            v-for="(supplier, index) in suppliers"
                            :key="index"
                            class="supplier-item"
                        >
                            <div class="supplier-header">
                                <div class="supplier-icon">
                                    <i :class="getSupplierIcon(supplier.type)"></i>
                                </div>
                                <div class="supplier-info">
                                    <h5>{{ supplier.name || `Supplier ${index + 1}` }}</h5>
                                    <span class="supplier-type">{{ supplier.type || 'Raw Material Supplier' }}</span>
                                    <div class="supplier-location" v-if="supplier.location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ supplier.location }}</span>
                                    </div>
                                </div>
                                <div class="supplier-rating" v-if="supplier.rating">
                                    <div class="rating-stars">
                                        <i 
                                            v-for="star in 5"
                                            :key="star"
                                            :class="['fas fa-star', { active: star <= supplier.rating }]"
                                        ></i>
                                    </div>
                                    <span class="rating-text">{{ supplier.rating }}/5</span>
                                </div>
                            </div>

                            <div class="supplier-details">
                                <div class="supplier-specs">
                                    <div class="spec-item" v-if="supplier.products_supplied">
                                        <label>Products:</label>
                                        <span>{{ supplier.products_supplied }}</span>
                                    </div>
                                    <div class="spec-item" v-if="supplier.lead_time">
                                        <label>Lead Time:</label>
                                        <span>{{ supplier.lead_time }}</span>
                                    </div>
                                    <div class="spec-item" v-if="supplier.minimum_order">
                                        <label>MOQ:</label>
                                        <span>{{ supplier.minimum_order }}</span>
                                    </div>
                                    <div class="spec-item" v-if="supplier.payment_terms">
                                        <label>Payment:</label>
                                        <span>{{ supplier.payment_terms }}</span>
                                    </div>
                                </div>

                                <div class="supplier-relationship" v-if="supplier.relationship_duration">
                                    <div class="relationship-info">
                                        <i class="fas fa-handshake"></i>
                                        <span>Partnership since {{ supplier.relationship_duration }}</span>
                                    </div>
                                </div>

                                <div class="supplier-certifications" v-if="supplier.certifications && supplier.certifications.length > 0">
                                    <h6>Certifications:</h6>
                                    <div class="certifications-tags">
                                        <span 
                                            v-for="(cert, certIndex) in supplier.certifications"
                                            :key="certIndex"
                                            class="certification-tag"
                                        >
                                            {{ cert }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribution Network -->
                <div class="distribution-network" v-if="distributors.length > 0">
                    <h4>
                        <i class="fas fa-truck"></i>
                        Distribution Network
                    </h4>
                    <div class="distributors-grid">
                        <div 
                            v-for="(distributor, index) in distributors"
                            :key="index"
                            class="distributor-item"
                        >
                            <div class="distributor-header">
                                <div class="distributor-icon">
                                    <i :class="getDistributorIcon(distributor.type)"></i>
                                </div>
                                <div class="distributor-info">
                                    <h5>{{ distributor.name || `Distributor ${index + 1}` }}</h5>
                                    <span class="distributor-type">{{ distributor.type || 'General Distributor' }}</span>
                                    <div class="distributor-coverage" v-if="distributor.coverage_area">
                                        <i class="fas fa-map"></i>
                                        <span>{{ distributor.coverage_area }}</span>
                                    </div>
                                </div>
                                <div class="distributor-performance" v-if="distributor.performance_score">
                                    <div class="performance-circle">
                                        <span class="performance-score">{{ distributor.performance_score }}%</span>
                                    </div>
                                    <span class="performance-label">Performance</span>
                                </div>
                            </div>

                            <div class="distributor-details">
                                <div class="distributor-specs">
                                    <div class="spec-item" v-if="distributor.channel_type">
                                        <label>Channel:</label>
                                        <span>{{ distributor.channel_type }}</span>
                                    </div>
                                    <div class="spec-item" v-if="distributor.volume_capacity">
                                        <label>Capacity:</label>
                                        <span>{{ distributor.volume_capacity }}</span>
                                    </div>
                                    <div class="spec-item" v-if="distributor.market_share">
                                        <label>Market Share:</label>
                                        <span>{{ distributor.market_share }}</span>
                                    </div>
                                    <div class="spec-item" v-if="distributor.contract_period">
                                        <label>Contract:</label>
                                        <span>{{ distributor.contract_period }}</span>
                                    </div>
                                </div>

                                <div class="distributor-markets" v-if="distributor.target_markets && distributor.target_markets.length > 0">
                                    <h6>Target Markets:</h6>
                                    <div class="markets-tags">
                                        <span 
                                            v-for="(market, marketIndex) in distributor.target_markets"
                                            :key="marketIndex"
                                            class="market-tag"
                                        >
                                            {{ market }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logistics Management -->
                <div class="logistics-management" v-if="data.supply_chain.logistics">
                    <h4>
                        <i class="fas fa-route"></i>
                        Logistics Management
                    </h4>
                    <div class="logistics-content">
                        <div class="logistics-grid">
                            <div class="logistics-item">
                                <div class="logistics-icon">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                                <div class="logistics-info">
                                    <h6>Warehousing</h6>
                                    <span class="logistics-detail">{{ data.supply_chain.logistics.warehousing || 'Multiple facilities' }}</span>
                                </div>
                            </div>
                            <div class="logistics-item">
                                <div class="logistics-icon">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="logistics-info">
                                    <h6>Transportation</h6>
                                    <span class="logistics-detail">{{ data.supply_chain.logistics.transportation || 'Multi-modal transport' }}</span>
                                </div>
                            </div>
                            <div class="logistics-item">
                                <div class="logistics-icon">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="logistics-info">
                                    <h6>Inventory Management</h6>
                                    <span class="logistics-detail">{{ data.supply_chain.logistics.inventory_management || 'Just-in-time system' }}</span>
                                </div>
                            </div>
                            <div class="logistics-item">
                                <div class="logistics-icon">
                                    <i class="fas fa-search-location"></i>
                                </div>
                                <div class="logistics-info">
                                    <h6>Tracking & Monitoring</h6>
                                    <span class="logistics-detail">{{ data.supply_chain.logistics.tracking || 'Real-time tracking' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supply Chain Metrics -->
                <div class="supply-chain-metrics">
                    <h4>
                        <i class="fas fa-chart-line"></i>
                        Supply Chain Metrics
                    </h4>
                    <div class="metrics-dashboard">
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ suppliers.length }}</span>
                                <span class="metric-label">Active Suppliers</span>
                            </div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ distributors.length }}</span>
                                <span class="metric-label">Distribution Partners</span>
                            </div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ getAverageLeadTime() }}</span>
                                <span class="metric-label">Avg. Lead Time</span>
                            </div>
                        </div>
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="metric-info">
                                <span class="metric-number">{{ getAverageRating() }}/5</span>
                                <span class="metric-label">Avg. Supplier Rating</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-shipping-fast"></i>
                <h4>Belum Ada Data Supply Chain</h4>
                <p>Data rantai pasok belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.supply_chain)
})

const suppliers = computed(() => {
    if (!props.data.supply_chain?.suppliers) return []
    return Array.isArray(props.data.supply_chain.suppliers) 
        ? props.data.supply_chain.suppliers 
        : []
})

const distributors = computed(() => {
    if (!props.data.supply_chain?.distributors) return []
    return Array.isArray(props.data.supply_chain.distributors) 
        ? props.data.supply_chain.distributors 
        : []
})

function getSupplierIcon(type) {
    const icons = {
        'raw_material': 'fas fa-cube',
        'component': 'fas fa-puzzle-piece',
        'chemical': 'fas fa-flask',
        'metal': 'fas fa-hammer',
        'electronic': 'fas fa-microchip',
        'packaging': 'fas fa-box',
        'equipment': 'fas fa-tools'
    }
    return icons[type?.toLowerCase()] || 'fas fa-industry'
}

function getDistributorIcon(type) {
    const icons = {
        'wholesale': 'fas fa-warehouse',
        'retail': 'fas fa-store',
        'online': 'fas fa-laptop',
        'export': 'fas fa-ship',
        'regional': 'fas fa-map'
    }
    return icons[type?.toLowerCase()] || 'fas fa-truck'
}

function getAverageLeadTime() {
    const leadTimes = suppliers.value
        .map(supplier => supplier.lead_time)
        .filter(time => time)
        .map(time => {
            const match = time.match(/(\d+)/)
            return match ? parseInt(match[1]) : 0
        })
        .filter(time => time > 0)
    
    if (leadTimes.length === 0) return '-'
    
    const average = leadTimes.reduce((sum, time) => sum + time, 0) / leadTimes.length
    return `${Math.round(average)} days`
}

function getAverageRating() {
    const ratings = suppliers.value
        .map(supplier => supplier.rating)
        .filter(rating => rating && !isNaN(rating))
    
    if (ratings.length === 0) return '-'
    
    const average = ratings.reduce((sum, rating) => sum + rating, 0) / ratings.length
    return average.toFixed(1)
}
</script>

<style scoped>
.supply-chain-card {
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
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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

.supply-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.content-sections {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.supply-chain-overview,
.suppliers-details,
.distribution-network,
.logistics-management,
.supply-chain-metrics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.supply-chain-overview h4,
.suppliers-details h4,
.distribution-network h4,
.logistics-management h4,
.supply-chain-metrics h4 {
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

.supply-chain-overview h4 i {
    color: #ef4444;
}

.suppliers-details h4 i {
    color: #3b82f6;
}

.distribution-network h4 i {
    color: #10b981;
}

.logistics-management h4 i {
    color: #f59e0b;
}

.supply-chain-metrics h4 i {
    color: #8b5cf6;
}

.chain-visualization {
    padding: 32px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 24px;
}

.chain-level {
    width: 100%;
    max-width: 800px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.chain-level h5 {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #374151;
    font-size: 1rem;
}

.level-count {
    background: #e5e7eb;
    color: #374151;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 700;
}

.chain-nodes {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
}

.chain-node {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 12px;
    border-radius: 8px;
    min-width: 120px;
    text-align: center;
    transition: all 0.3s ease;
}

.supplier-node {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 2px solid #3b82f6;
}

.manufacturer-node {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 2px solid #f59e0b;
}

.distributor-node {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 2px solid #10b981;
}

.more-node {
    background: #f3f4f6;
    color: #6b7280;
    border: 2px dashed #d1d5db;
}

.chain-node:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.chain-node i {
    font-size: 1.25rem;
}

.node-label {
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1.2;
}

.chain-flow {
    color: #6b7280;
    font-size: 1.5rem;
}

.suppliers-grid,
.distributors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    padding: 24px;
}

.supplier-item,
.distributor-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.supplier-item:hover,
.distributor-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.supplier-header,
.distributor-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.supplier-icon,
.distributor-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.distributor-icon {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
}

.supplier-info,
.distributor-info {
    flex: 1;
}

.supplier-info h5,
.distributor-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.supplier-type,
.distributor-type {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-bottom: 8px;
}

.supplier-location,
.distributor-coverage {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
}

.supplier-location i,
.distributor-coverage i {
    font-size: 0.75rem;
}

.supplier-rating {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-stars i {
    color: #d1d5db;
    font-size: 0.875rem;
}

.rating-stars i.active {
    color: #fbbf24;
}

.rating-text {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 600;
}

.distributor-performance {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.performance-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.performance-score {
    color: white;
    font-size: 0.875rem;
    font-weight: 700;
}

.performance-label {
    font-size: 0.75rem;
    color: #6b7280;
    font-weight: 600;
}

.supplier-details,
.distributor-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.supplier-specs,
.distributor-specs {
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

.supplier-relationship {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.relationship-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.relationship-info i {
    color: #10b981;
}

.supplier-certifications,
.distributor-markets {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.supplier-certifications h6,
.distributor-markets h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.certifications-tags,
.markets-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.certification-tag {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 2px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.market-tag {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    padding: 2px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.logistics-content {
    padding: 24px;
}

.logistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.logistics-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.logistics-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.logistics-icon {
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

.logistics-info {
    flex: 1;
}

.logistics-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.logistics-detail {
    font-size: 0.875rem;
    color: #6b7280;
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
    .supply-chain-card {
        padding: 16px;
    }
    
    .suppliers-grid,
    .distributors-grid,
    .logistics-grid,
    .metrics-dashboard {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .supply-stats {
        justify-content: center;
    }
    
    .chain-visualization {
        padding: 20px 16px;
    }
    
    .chain-nodes {
        flex-direction: column;
        align-items: center;
    }
    
    .supplier-header,
    .distributor-header {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .supplier-specs,
    .distributor-specs {
        grid-template-columns: 1fr;
    }
}
</style>