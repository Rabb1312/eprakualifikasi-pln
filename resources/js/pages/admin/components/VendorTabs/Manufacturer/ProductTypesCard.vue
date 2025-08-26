<template>
    <div class="product-types-card">
        <div v-if="hasData" class="product-types-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="header-info">
                    <h3>Types of Product Manufactured</h3>
                    <p>Jenis-jenis produk yang diproduksi oleh manufacturer beserta spesifikasinya</p>
                </div>
                <div class="types-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ productTypes.length }}</span>
                        <span class="stat-label">Product Types</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Product Types Grid -->
                <div class="product-types-grid">
                    <div 
                        v-for="(productType, index) in productTypes"
                        :key="index"
                        class="product-type-item"
                    >
                        <div class="product-type-header">
                            <div class="product-type-icon">
                                <i :class="getProductTypeIcon(productType.type)"></i>
                            </div>
                            <div class="product-type-info">
                                <h5>{{ productType.type || `Product Type ${index + 1}` }}</h5>
                                <span class="product-category">Manufacturing Product</span>
                                <div class="product-number">
                                    <i class="fas fa-hashtag"></i>
                                    <span>Product #{{ index + 1 }}</span>
                                </div>
                            </div>
                            <div class="product-status">
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i>
                                    Active
                                </span>
                            </div>
                        </div>

                        <div class="product-type-details">
                            <!-- Product Type -->
                            <div class="product-info-section">
                                <h6>Product Information:</h6>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label>Jenis Produk:</label>
                                        <span>{{ productType.type || 'Not specified' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Size Range -->
                            <div class="product-size-section" v-if="productType.size_range">
                                <h6>Size Range / Rentang Ukuran:</h6>
                                <div class="size-display">
                                    <div class="size-item">
                                        <i class="fas fa-ruler"></i>
                                        <span>{{ productType.size_range }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Speciality -->
                            <div class="product-speciality-section" v-if="productType.speciality">
                                <h6>Speciality / Spesialisasi:</h6>
                                <div class="speciality-display">
                                    <div class="speciality-item">
                                        <i class="fas fa-star"></i>
                                        <span>{{ productType.speciality }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Summary -->
                            <div class="product-summary">
                                <div class="summary-grid">
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Type</span>
                                            <span class="summary-value">{{ productType.type ? 'Specified' : 'Not specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-ruler"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Size Range</span>
                                            <span class="summary-value">{{ productType.size_range ? 'Defined' : 'Not defined' }}</span>
                                        </div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Speciality</span>
                                            <span class="summary-value">{{ productType.speciality ? 'Available' : 'Not specified' }}</span>
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
                <i class="fas fa-cube"></i>
                <h4>Belum Ada Data Product Types</h4>
                <p>Data jenis produk yang diproduksi belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.product_types && Array.isArray(props.data.product_types) && props.data.product_types.length > 0)
})

const productTypes = computed(() => {
    if (!props.data.product_types) return []
    
    // Handle JSON field dari database - sesuai dengan ManufactureProductTypes.vue
    if (Array.isArray(props.data.product_types)) {
        return props.data.product_types.filter(product => 
            product && (product.type || product.size_range || product.speciality)
        )
    }
    
    if (typeof props.data.product_types === 'string') {
        try {
            const parsed = JSON.parse(props.data.product_types)
            return Array.isArray(parsed) ? parsed.filter(product => 
                product && (product.type || product.size_range || product.speciality)
            ) : []
        } catch {
            return []
        }
    }
    
    return []
})

// Helper functions sesuai dengan struktur data ManufactureProductTypes
function getProductTypeIcon(type) {
    if (!type) return 'fas fa-cube'
    
    const typeLower = type.toLowerCase()
    const icons = {
        'steel': 'fas fa-hammer',
        'pipe': 'fas fa-tint',
        'machinery': 'fas fa-cogs',
        'automotive': 'fas fa-car',
        'electronics': 'fas fa-microchip',
        'textiles': 'fas fa-tshirt',
        'chemicals': 'fas fa-flask',
        'metals': 'fas fa-hammer',
        'plastics': 'fas fa-cube',
        'food': 'fas fa-utensils',
        'pharmaceuticals': 'fas fa-pills',
        'construction': 'fas fa-hard-hat',
        'equipment': 'fas fa-tools',
        'components': 'fas fa-puzzle-piece',
        'parts': 'fas fa-cog'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (typeLower.includes(key)) return icon
    }
    return 'fas fa-cube'
}

function getSpecifiedTypesCount() {
    return productTypes.value.filter(product => product.type && product.type.trim()).length
}

function getSizedProductsCount() {
    return productTypes.value.filter(product => product.size_range && product.size_range.trim()).length
}

function getSpecializedProductsCount() {
    return productTypes.value.filter(product => product.speciality && product.speciality.trim()).length
}

function getCompletedProductsCount() {
    return productTypes.value.filter(product => 
        product.type && product.type.trim() && 
        product.size_range && product.size_range.trim() && 
        product.speciality && product.speciality.trim()
    ).length
}

function getSpecializedPercentage() {
    if (productTypes.value.length === 0) return 0
    return Math.round((getSpecializedProductsCount() / productTypes.value.length) * 100)
}

function getSizedPercentage() {
    if (productTypes.value.length === 0) return 0
    return Math.round((getSizedProductsCount() / productTypes.value.length) * 100)
}
</script>

<style scoped>
.product-types-card {
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

.types-stats {
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

.product-types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 24px;
}

.product-type-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-type-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.product-type-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.product-type-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.product-type-info {
    flex: 1;
}

.product-type-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.product-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.product-number {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
}

.product-number i {
    font-size: 0.75rem;
}

.product-status {
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

.product-type-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.product-info-section,
.product-size-section,
.product-speciality-section,
.product-summary {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.product-info-section h6,
.product-size-section h6,
.product-speciality-section h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.info-item label {
    color: #6b7280;
    font-weight: 500;
}

.info-item span {
    color: #1f2937;
    font-weight: 600;
}

.size-display,
.speciality-display {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.size-item,
.speciality-item {
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

.size-item i {
    color: #7c3aed;
    font-size: 0.75rem;
}

.speciality-item i {
    color: #f59e0b;
    font-size: 0.75rem;
}

.product-summary {
    margin-top: 8px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.summary-icon {
    width: 24px;
    height: 24px;
    background: #7c3aed;
    color: white;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.summary-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.summary-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.summary-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

.product-overview {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.product-overview h4 {
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

.product-overview h4 i {
    color: #7c3aed;
}

.overview-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.product-categories {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.category-group h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.category-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.category-summary {
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-summary.specified {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
}

.category-summary.sized {
    background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
    color: #4338ca;
}

.category-summary.specialized {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.category-label {
    font-weight: 600;
    font-size: 0.875rem;
}

.category-count {
    font-weight: 700;
    font-size: 0.875rem;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
    .product-types-card {
        padding: 16px;
    }
    
    .product-types-grid,
    .product-categories,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .product-type-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
}
</style>