<template>
    <div class="product-types-card">
        <div v-if="hasData" class="product-types-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="header-info">
                    <h3>Product Types</h3>
                    <p>Jenis-jenis produk yang diproduksi oleh manufacturer</p>
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
                                <i :class="getProductTypeIcon(productType)"></i>
                            </div>
                            <div class="product-type-info">
                                <h5>{{ productType.name || productType.type || `Product Type ${index + 1}` }}</h5>
                                <span class="product-category">{{ productType.category || 'Manufacturing Product' }}</span>
                            </div>
                            <div class="product-status">
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i>
                                    Active
                                </span>
                            </div>
                        </div>

                        <div class="product-type-details">
                            <div class="product-description" v-if="productType.description">
                                <p>{{ productType.description }}</p>
                            </div>

                            <div class="product-specifications" v-if="productType.specifications">
                                <h6>Specifications:</h6>
                                <div class="specs-grid">
                                    <div 
                                        v-for="(spec, specIndex) in getSpecifications(productType.specifications)"
                                        :key="specIndex"
                                        class="spec-item"
                                    >
                                        <label>{{ spec.name }}:</label>
                                        <span>{{ spec.value }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="product-materials" v-if="productType.materials && productType.materials.length > 0">
                                <h6>Materials Used:</h6>
                                <div class="materials-tags">
                                    <span 
                                        v-for="(material, materialIndex) in productType.materials"
                                        :key="materialIndex"
                                        class="material-tag"
                                    >
                                        {{ material }}
                                    </span>
                                </div>
                            </div>

                            <div class="product-capacity" v-if="productType.production_capacity">
                                <h6>Production Capacity:</h6>
                                <div class="capacity-info">
                                    <div class="capacity-item">
                                        <i class="fas fa-industry"></i>
                                        <span>{{ productType.production_capacity }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="product-applications" v-if="productType.applications && productType.applications.length > 0">
                                <h6>Applications:</h6>
                                <div class="applications-list">
                                    <div 
                                        v-for="(application, appIndex) in productType.applications"
                                        :key="appIndex"
                                        class="application-item"
                                    >
                                        <i class="fas fa-arrow-right"></i>
                                        <span>{{ application }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="product-certifications" v-if="productType.certifications && productType.certifications.length > 0">
                                <h6>Certifications:</h6>
                                <div class="certifications-tags">
                                    <span 
                                        v-for="(cert, certIndex) in productType.certifications"
                                        :key="certIndex"
                                        class="certification-tag"
                                    >
                                        <i class="fas fa-certificate"></i>
                                        {{ cert }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Categories Summary -->
                <div class="categories-summary">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Product Categories Distribution
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in getProductCategories()"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getProductTypeIcon(category)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} products</span>
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
                <i class="fas fa-boxes"></i>
                <h4>Belum Ada Data Product Types</h4>
                <p>Data jenis produk belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.product_types)
})

const productTypes = computed(() => {
    if (!props.data.product_types) return []
    
    // Handle JSON field dari database
    if (Array.isArray(props.data.product_types)) {
        return props.data.product_types
    }
    
    if (typeof props.data.product_types === 'string') {
        try {
            const parsed = JSON.parse(props.data.product_types)
            return Array.isArray(parsed) ? parsed : [parsed]
        } catch {
            // Fallback jika bukan JSON valid
            return props.data.product_types.split(',').map(s => ({ name: s.trim() }))
        }
    }
    
    if (typeof props.data.product_types === 'object') {
        return Object.entries(props.data.product_types).map(([key, value]) => ({
            name: key,
            description: typeof value === 'string' ? value : JSON.stringify(value)
        }))
    }
    
    return []
})

function getProductTypeIcon(productType) {
    const typeName = (productType.name || productType.type || productType || '').toLowerCase()
    const icons = {
        'automotive': 'fas fa-car',
        'electronics': 'fas fa-microchip',
        'machinery': 'fas fa-cogs',
        'textiles': 'fas fa-tshirt',
        'chemicals': 'fas fa-flask',
        'metals': 'fas fa-hammer',
        'plastics': 'fas fa-cube',
        'food': 'fas fa-utensils',
        'pharmaceuticals': 'fas fa-pills',
        'construction': 'fas fa-hard-hat',
        'equipment': 'fas fa-tools',
        'components': 'fas fa-puzzle-piece'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (typeName.includes(key)) return icon
    }
    return 'fas fa-box'
}

function getSpecifications(specifications) {
    if (Array.isArray(specifications)) {
        return specifications
    }
    if (typeof specifications === 'object') {
        return Object.entries(specifications).map(([name, value]) => ({ name, value }))
    }
    return []
}

function getProductCategories() {
    const categories = {}
    productTypes.value.forEach(product => {
        const category = product.category || 'General'
        if (!categories[category]) {
            categories[category] = { name: category, count: 0 }
        }
        categories[category].count++
    })
    
    return Object.values(categories).sort((a, b) => b.count - a.count)
}

function getCategoryPercentage(count) {
    if (productTypes.value.length === 0) return 0
    return Math.round((count / productTypes.value.length) * 100)
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
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
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

.product-description {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.product-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.product-specifications,
.product-capacity {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.product-specifications h6,
.product-capacity h6,
.product-materials h6,
.product-applications h6,
.product-certifications h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 8px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 8px;
    background: white;
    border-radius: 4px;
    font-size: 0.75rem;
}

.spec-item label {
    color: #6b7280;
    font-weight: 500;
}

.spec-item span {
    color: #1f2937;
    font-weight: 600;
}

.capacity-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.capacity-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #374151;
    font-weight: 600;
}

.capacity-item i {
    color: #f59e0b;
}

.product-materials,
.product-applications,
.product-certifications {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.materials-tags,
.certifications-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.material-tag {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
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

.applications-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.application-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.application-item i {
    color: #f59e0b;
    font-size: 0.75rem;
}

.categories-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.categories-summary h4 {
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

.categories-summary h4 i {
    color: #8b5cf6;
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
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
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
    color: #8b5cf6;
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
    .product-types-card {
        padding: 16px;
    }
    
    .product-types-grid,
    .categories-grid {
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
}
</style>