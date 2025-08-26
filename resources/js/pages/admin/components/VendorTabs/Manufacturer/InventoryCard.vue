<template>
    <div class="inventory-card">
        <div v-if="hasData" class="inventory-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="header-info">
                    <h3>Inventory Management</h3>
                    <p>Konfigurasi ketersediaan barang untuk pembelian langsung</p>
                </div>
                <!-- <div class="inventory-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ inventoryScore }}%</span>
                        <span class="stat-label">Configured</span>
                    </div>
                </div> -->
            </div>

            <div class="content-sections">
                <!-- Direct Purchase Configuration -->
                <div class="inventory-configuration">
                    <!-- Direct Purchase Availability -->
                    <div class="config-item">
                        <div class="config-header">
                            <div class="config-icon purchase">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="config-info">
                                <h5>Direct Purchase Availability</h5>
                                <span class="config-category">Inventory Configuration</span>
                                <div class="config-description">
                                    <p>Ketersediaan barang untuk pembelian langsung dari inventori</p>
                                </div>
                            </div>
                            <div class="config-status">
                                <span :class="['status-badge', getAvailabilityStatusClass()]">
                                    <i :class="getAvailabilityStatusIcon()"></i>
                                    {{ getAvailabilityStatusText() }}
                                </span>
                            </div>
                        </div>

                        <div class="config-details">
                            <div class="availability-info">
                                <h6>Purchase Configuration:</h6>
                                <div class="availability-content">
                                    <div class="availability-item">
                                        <i :class="inventoryData.available_goods_direct_purchase === 'yes' ? 'fas fa-check-circle text-success' : inventoryData.available_goods_direct_purchase === 'no' ? 'fas fa-times-circle text-danger' : 'fas fa-question-circle text-muted'"></i>
                                        <span>{{ getAvailabilityDescription() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information (if not available) -->
                    <div class="config-item" v-if="inventoryData.available_goods_direct_purchase === 'no' && inventoryData.inventory_note">
                        <div class="config-header">
                            <div class="config-icon note">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <div class="config-info">
                                <h5>Additional Information</h5>
                                <span class="config-category">Alternative Options</span>
                                <div class="config-description">
                                    <p>Penjelasan dan alternatif untuk pembelian langsung</p>
                                </div>
                            </div>
                            <div class="config-status">
                                <span class="status-badge info">
                                    <i class="fas fa-info-circle"></i>
                                    Available
                                </span>
                            </div>
                        </div>

                        <div class="config-details">
                            <div class="note-content">
                                <h6>Explanation:</h6>
                                <div class="note-text">
                                    <p>{{ inventoryData.inventory_note }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-boxes"></i>
                <h4>Belum Ada Data Inventory</h4>
                <p>Konfigurasi inventory management belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.inventory && Object.keys(props.data.inventory).length > 0)
})

const inventoryData = computed(() => {
    if (!props.data.inventory) return {}
    
    // Handle JSON field dari database
    if (typeof props.data.inventory === 'string') {
        try {
            return JSON.parse(props.data.inventory)
        } catch {
            return {}
        }
    }
    
    return props.data.inventory || {}
})

// Helper functions sesuai dengan ManufactureInventory.vue
const inventoryScore = computed(() => {
    const items = [
        inventoryData.value.available_goods_direct_purchase,
        // inventory_note is optional, only count if direct purchase is 'no'
        inventoryData.value.available_goods_direct_purchase === 'no' ? inventoryData.value.inventory_note : true
    ]
    
    const configuredItems = items.filter(item => item && item !== '').length
    return Math.round((configuredItems / items.length) * 100)
})

function getAvailabilityStatusClass() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') return 'available'
    if (inventoryData.value.available_goods_direct_purchase === 'no') return 'unavailable'
    return 'not-configured'
}

function getAvailabilityStatusIcon() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') return 'fas fa-check-circle'
    if (inventoryData.value.available_goods_direct_purchase === 'no') return 'fas fa-times-circle'
    return 'fas fa-question-circle'
}

function getAvailabilityStatusText() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') return 'Tersedia'
    if (inventoryData.value.available_goods_direct_purchase === 'no') return 'Tidak Tersedia'
    return 'Belum Dikonfigurasi'
}

function getAvailabilityDescription() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') {
        return 'Barang dapat dibeli langsung dari stok yang tersedia'
    } else if (inventoryData.value.available_goods_direct_purchase === 'no') {
        return 'Barang tidak tersedia untuk pembelian langsung dari inventori'
    }
    return 'Status ketersediaan barang belum ditentukan'
}

function getOverallStatusClass() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') return 'status-available'
    if (inventoryData.value.available_goods_direct_purchase === 'no') return 'status-unavailable'
    return 'status-pending'
}

function getOverallStatusIcon() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') return 'fas fa-check-circle'
    if (inventoryData.value.available_goods_direct_purchase === 'no') return 'fas fa-exclamation-triangle'
    return 'fas fa-question-circle'
}

function getOverallStatusTitle() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') return 'Inventory Tersedia'
    if (inventoryData.value.available_goods_direct_purchase === 'no') return 'Inventory Tidak Tersedia'
    return 'Status Belum Ditentukan'
}

function getOverallStatusDescription() {
    if (inventoryData.value.available_goods_direct_purchase === 'yes') {
        return 'Pelanggan dapat melakukan pembelian langsung dari inventori yang tersedia.'
    } else if (inventoryData.value.available_goods_direct_purchase === 'no') {
        return 'Pembelian langsung tidak tersedia. Pelanggan perlu mengikuti prosedur khusus.'
    }
    return 'Silakan konfigurasi status ketersediaan barang untuk pembelian langsung.'
}

function getConfiguredItemsCount() {
    let count = 0
    if (inventoryData.value.available_goods_direct_purchase) count++
    if (inventoryData.value.available_goods_direct_purchase === 'no' && inventoryData.value.inventory_note) count++
    else if (inventoryData.value.available_goods_direct_purchase === 'yes') count++ // auto-complete for 'yes' case
    return count
}
</script>

<style scoped>
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

.inventory-configuration {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.config-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.config-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.config-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.config-icon {
    width: 56px;
    height: 56px;
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.config-icon.purchase {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.config-icon.note {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.config-info {
    flex: 1;
}

.config-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.config-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.config-description {
    margin-top: 8px;
}

.config-description p {
    margin: 0;
    color: #374151;
    font-size: 0.875rem;
    line-height: 1.4;
}

.config-status {
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

.status-badge.available {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.unavailable {
    background: #fef2f2;
    color: #991b1b;
}

.status-badge.not-configured {
    background: #f3f4f6;
    color: #6b7280;
}

.status-badge.info {
    background: #dbeafe;
    color: #1e40af;
}

.config-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.availability-info,
.note-content,
.purchase-features,
.alternative-options {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.availability-info h6,
.note-content h6,
.purchase-features h6,
.alternative-options h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.availability-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.availability-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.note-text p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
    text-align: justify;
    padding: 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.features-list,
.alternatives-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.feature-item,
.alternative-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.feature-item i {
    color: #3b82f6;
    font-size: 0.75rem;
}

.alternative-item i {
    color: #f59e0b;
    font-size: 0.75rem;
}

.inventory-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.inventory-summary h4 {
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
    color: #7c3aed;
}

.summary-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.status-overview {
    display: flex;
    justify-content: center;
}

.status-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-radius: 12px;
    min-width: 300px;
}

.status-available {
    background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
    border-left: 4px solid #28a745;
}

.status-unavailable {
    background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
    border-left: 4px solid #ffc107;
}

.status-pending {
    background: linear-gradient(135deg, #e2e3e5 0%, #ffffff 100%);
    border-left: 4px solid #6c757d;
}

.status-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.status-available .status-icon {
    background: #28a745;
    color: white;
}

.status-unavailable .status-icon {
    background: #ffc107;
    color: white;
}

.status-pending .status-icon {
    background: #6c757d;
    color: white;
}

.status-details {
    flex: 1;
}

.status-title {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1.125rem;
}

.status-description {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.4;
}

.configuration-summary {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.summary-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.summary-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.summary-item {
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-item.direct-purchase {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.summary-item.note-available {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.summary-item.completion {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
}

.summary-label {
    font-weight: 600;
    font-size: 0.875rem;
}

.summary-value {
    font-weight: 700;
    font-size: 0.875rem;
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

.text-success {
    color: #059669 !important;
}

.text-danger {
    color: #dc2626 !important;
}

.text-muted {
    color: #9ca3af !important;
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
    
    .inventory-configuration,
    .configuration-summary,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .config-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .status-card {
        flex-direction: column;
        text-align: center;
        min-width: auto;
    }
    
    .summary-items {
        grid-template-columns: 1fr;
    }
}
</style>