<template>
    <div class="inventory-card">
        <div v-if="hasData" class="inventory-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="header-info">
                    <h3>Inventory Management</h3>
                    <p>Sistem manajemen inventori dan gudang manufacturer</p>
                </div>
                <div class="inventory-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ inventoryItems.length }}</span>
                        <span class="stat-label">Items</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Inventory Items Grid -->
                <div class="inventory-grid">
                    <div 
                        v-for="(item, index) in inventoryItems"
                        :key="index"
                        class="inventory-item"
                    >
                        <div class="item-header">
                            <div class="item-icon">
                                <i :class="getItemIcon(item.category || item.type)"></i>
                            </div>
                            <div class="item-info">
                                <h5>{{ item.name || item.type || `Item ${index + 1}` }}</h5>
                                <span class="item-category">{{ item.category || 'Inventory Item' }}</span>
                                <div class="item-code" v-if="item.code">
                                    <span class="code-badge">{{ item.code }}</span>
                                </div>
                            </div>
                            <div class="item-status">
                                <span :class="['status-badge', getStockStatus(item.stock_level)]">
                                    <i :class="getStockIcon(item.stock_level)"></i>
                                    {{ getStockText(item.stock_level) }}
                                </span>
                            </div>
                        </div>

                        <div class="item-details">
                            <div class="item-description" v-if="item.description">
                                <p>{{ item.description }}</p>
                            </div>

                            <div class="stock-information">
                                <h6>Stock Information:</h6>
                                <div class="stock-grid">
                                    <div v-if="item.current_stock" class="stock-item">
                                        <label>Current Stock:</label>
                                        <span class="stock-value">{{ item.current_stock }}</span>
                                    </div>
                                    <div v-if="item.minimum_stock" class="stock-item">
                                        <label>Minimum Stock:</label>
                                        <span class="stock-value">{{ item.minimum_stock }}</span>
                                    </div>
                                    <div v-if="item.maximum_stock" class="stock-item">
                                        <label>Maximum Stock:</label>
                                        <span class="stock-value">{{ item.maximum_stock }}</span>
                                    </div>
                                    <div v-if="item.reorder_point" class="stock-item">
                                        <label>Reorder Point:</label>
                                        <span class="stock-value">{{ item.reorder_point }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="item-specifications" v-if="item.specifications">
                                <h6>Specifications:</h6>
                                <div class="specs-grid">
                                    <div 
                                        v-for="(spec, specIndex) in getSpecifications(item.specifications)"
                                        :key="specIndex"
                                        class="spec-item"
                                    >
                                        <label>{{ spec.name }}:</label>
                                        <span>{{ spec.value }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="item-location" v-if="item.location">
                                <h6>Storage Location:</h6>
                                <div class="location-info">
                                    <div class="location-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ item.location.warehouse || item.location }}</span>
                                    </div>
                                    <div v-if="item.location.rack" class="location-item">
                                        <i class="fas fa-layer-group"></i>
                                        <span>Rack: {{ item.location.rack }}</span>
                                    </div>
                                    <div v-if="item.location.bin" class="location-item">
                                        <i class="fas fa-box"></i>
                                        <span>Bin: {{ item.location.bin }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="item-suppliers" v-if="item.suppliers">
                                <h6>Suppliers:</h6>
                                <div class="suppliers-list">
                                    <div 
                                        v-for="(supplier, supplierIndex) in getSuppliers(item.suppliers)"
                                        :key="supplierIndex"
                                        class="supplier-item"
                                    >
                                        <i class="fas fa-truck"></i>
                                        <span>{{ supplier.name || supplier }}</span>
                                        <span v-if="supplier.lead_time" class="lead-time">{{ supplier.lead_time }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="item-costs" v-if="item.cost_info">
                                <h6>Cost Information:</h6>
                                <div class="costs-grid">
                                    <div v-if="item.cost_info.unit_cost" class="cost-item">
                                        <label>Unit Cost:</label>
                                        <span class="cost-value">{{ item.cost_info.unit_cost }}</span>
                                    </div>
                                    <div v-if="item.cost_info.total_value" class="cost-item">
                                        <label>Total Value:</label>
                                        <span class="cost-value">{{ item.cost_info.total_value }}</span>
                                    </div>
                                    <div v-if="item.cost_info.last_purchase" class="cost-item">
                                        <label>Last Purchase:</label>
                                        <span class="cost-value">{{ item.cost_info.last_purchase }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="item-tracking" v-if="item.tracking">
                                <h6>Tracking Method:</h6>
                                <div class="tracking-info">
                                    <div class="tracking-method">
                                        <i :class="getTrackingIcon(item.tracking.method)"></i>
                                        <span>{{ item.tracking.method || 'Manual Tracking' }}</span>
                                    </div>
                                    <div v-if="item.tracking.system" class="tracking-system">
                                        <i class="fas fa-desktop"></i>
                                        <span>System: {{ item.tracking.system }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Summary -->
                <div class="inventory-summary">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Inventory Summary
                    </h4>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ inventoryItems.length }}</span>
                                <span class="summary-label">Total Items</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getInStockItems() }}</span>
                                <span class="summary-label">In Stock</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getLowStockItems() }}</span>
                                <span class="summary-label">Low Stock</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getOutOfStockItems() }}</span>
                                <span class="summary-label">Out of Stock</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Categories -->
                <div class="inventory-categories">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Inventory Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in getInventoryCategories()"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getItemIcon(category.name)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} items</span>
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
                <i class="fas fa-warehouse"></i>
                <h4>Belum Ada Data Inventory</h4>
                <p>Data manajemen inventori belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.inventory)
})

const inventoryItems = computed(() => {
    if (!props.data.inventory) return []
    
    // Handle JSON field dari database
    if (Array.isArray(props.data.inventory)) {
        return props.data.inventory
    }
    
    if (typeof props.data.inventory === 'string') {
        try {
            const parsed = JSON.parse(props.data.inventory)
            return Array.isArray(parsed) ? parsed : [parsed]
        } catch {
            return []
        }
    }
    
    if (typeof props.data.inventory === 'object') {
        return Object.entries(props.data.inventory).map(([key, value]) => ({
            name: key,
            ...value
        }))
    }
    
    return []
})

function getItemIcon(category) {
    const categoryLower = (category || '').toLowerCase()
    const icons = {
        'raw_material': 'fas fa-cube',
        'component': 'fas fa-puzzle-piece',
        'finished_goods': 'fas fa-box',
        'spare_parts': 'fas fa-wrench',
        'consumables': 'fas fa-shopping-cart',
        'tools': 'fas fa-tools',
        'equipment': 'fas fa-cogs',
        'chemicals': 'fas fa-flask',
        'packaging': 'fas fa-archive'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (categoryLower.includes(key)) return icon
    }
    return 'fas fa-box'
}

function getStockStatus(stockLevel) {
    const levelLower = (stockLevel || '').toLowerCase()
    if (levelLower.includes('out') || levelLower.includes('empty')) return 'out-of-stock'
    if (levelLower.includes('low') || levelLower.includes('critical')) return 'low-stock'
    if (levelLower.includes('high') || levelLower.includes('optimal')) return 'in-stock'
    return 'normal'
}

function getStockIcon(stockLevel) {
    const status = getStockStatus(stockLevel)
    const icons = {
        'in-stock': 'fas fa-check-circle',
        'low-stock': 'fas fa-exclamation-triangle',
        'out-of-stock': 'fas fa-times-circle',
        'normal': 'fas fa-check-circle'
    }
    return icons[status] || 'fas fa-check-circle'
}

function getStockText(stockLevel) {
    return stockLevel || 'Normal'
}

function getSpecifications(specifications) {
    if (Array.isArray(specifications)) return specifications
    if (typeof specifications === 'object') {
        return Object.entries(specifications).map(([name, value]) => ({ name, value }))
    }
    return []
}

function getSuppliers(suppliers) {
    if (Array.isArray(suppliers)) return suppliers
    if (typeof suppliers === 'string') return suppliers.split(',').map(s => ({ name: s.trim() }))
    return []
}

function getTrackingIcon(method) {
    const methodLower = (method || '').toLowerCase()
    const icons = {
        'barcode': 'fas fa-barcode',
        'rfid': 'fas fa-broadcast-tower',
        'qr': 'fas fa-qrcode',
        'manual': 'fas fa-clipboard',
        'digital': 'fas fa-desktop'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (methodLower.includes(key)) return icon
    }
    return 'fas fa-search'
}

function getInStockItems() {
    return inventoryItems.value.filter(item => {
        const status = getStockStatus(item.stock_level)
        return status === 'in-stock' || status === 'normal'
    }).length
}

function getLowStockItems() {
    return inventoryItems.value.filter(item => {
        return getStockStatus(item.stock_level) === 'low-stock'
    }).length
}

function getOutOfStockItems() {
    return inventoryItems.value.filter(item => {
        return getStockStatus(item.stock_level) === 'out-of-stock'
    }).length
}

function getInventoryCategories() {
    const categories = {}
    inventoryItems.value.forEach(item => {
        const category = item.category || item.type || 'General'
        if (!categories[category]) {
            categories[category] = { name: category, count: 0 }
        }
        categories[category].count++
    })
    
    return Object.values(categories).sort((a, b) => b.count - a.count)
}

function getCategoryPercentage(count) {
    if (inventoryItems.value.length === 0) return 0
    return Math.round((count / inventoryItems.value.length) * 100)
}
</script>

<style scoped>
/* Style yang sama dengan komponen sebelumnya, dengan adaptasi warna untuk inventory */
.inventory-card {
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
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

.inventory-stats {
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

.inventory-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 24px;
}

.inventory-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.inventory-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.item-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.item-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.item-info {
    flex: 1;
}

.item-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.item-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.item-code {
    margin-top: 4px;
}

.code-badge {
    background: #e5e7eb;
    color: #374151;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    font-family: monospace;
}

.item-status {
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

.status-badge.in-stock,
.status-badge.normal {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.low-stock {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.out-of-stock {
    background: #fef2f2;
    color: #991b1b;
}

.item-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.item-description,
.stock-information,
.item-specifications,
.item-location,
.item-suppliers,
.item-costs,
.item-tracking {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.item-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.stock-information h6,
.item-specifications h6,
.item-location h6,
.item-suppliers h6,
.item-costs h6,
.item-tracking h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.stock-grid,
.specs-grid,
.costs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.stock-item,
.spec-item,
.cost-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.stock-item label,
.spec-item label,
.cost-item label {
    color: #6b7280;
    font-weight: 500;
}

.stock-value,
.spec-item span,
.cost-value {
    color: #1f2937;
    font-weight: 600;
}

.location-info,
.suppliers-list,
.tracking-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.location-item,
.supplier-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.location-item i,
.supplier-item i {
    color: #f59e0b;
    font-size: 0.875rem;
    width: 16px;
    text-align: center;
}

.lead-time {
    margin-left: auto;
    font-size: 0.75rem;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 6px;
    border-radius: 4px;
}

.tracking-method,
.tracking-system {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.tracking-method i,
.tracking-system i {
    color: #f59e0b;
}

.inventory-summary,
.inventory-categories {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.inventory-summary h4,
.inventory-categories h4 {
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

.inventory-summary h4 i {
    color: #8b5cf6;
}

.inventory-categories h4 i {
    color: #ef4444;
}

.summary-grid,
.categories-grid {
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
    .inventory-card {
        padding: 16px;
    }
    
    .inventory-grid,
    .summary-grid,
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .item-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .stock-grid,
    .specs-grid,
    .costs-grid {
        grid-template-columns: 1fr;
    }
}
</style>