<template>
    <div class="product-catalog-card">
        <div v-if="hasData" class="catalog-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="header-info">
                    <h3>Product Catalog</h3>
                    <p>Katalog produk yang diproduksi oleh manufacturer</p>
                </div>
                <div class="catalog-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ products.length }}</span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ productCategories.length }}</span>
                        <span class="stat-label">Categories</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Product Filter -->
                <div class="product-filter">
                    <div class="filter-controls">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search products..."
                                class="search-input"
                            >
                        </div>
                        <div class="category-filter">
                            <select v-model="selectedCategory" class="category-select">
                                <option value="">All Categories</option>
                                <option 
                                    v-for="category in productCategories"
                                    :key="category.name"
                                    :value="category.name"
                                >
                                    {{ category.name }} ({{ category.count }})
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-overview">
                    <h4>
                        <i class="fas fa-cube"></i>
                        Product Listings
                        <span class="product-count">({{ filteredProducts.length }} products)</span>
                    </h4>
                    <div class="products-grid">
                        <div 
                            v-for="(product, index) in filteredProducts"
                            :key="index"
                            class="product-item"
                        >
                            <div class="product-header">
                                <div class="product-image">
                                    <i v-if="!product.image" :class="getProductIcon(product.category)"></i>
                                    <img v-else :src="product.image" :alt="product.name" />
                                </div>
                                <div class="product-info">
                                    <h5>{{ product.name || product.product_name }}</h5>
                                    <span class="product-category">{{ product.category || 'General' }}</span>
                                    <div class="product-code" v-if="product.product_code">
                                        <span class="code-badge">{{ product.product_code }}</span>
                                    </div>
                                </div>
                                <div class="product-status">
                                    <span :class="['status-badge', getProductStatus(product)]">
                                        <i :class="getStatusIcon(product.availability)"></i>
                                        {{ getStatusText(product.availability) }}
                                    </span>
                                </div>
                            </div>

                            <div class="product-details">
                                <div class="product-description" v-if="product.description">
                                    <p>{{ product.description }}</p>
                                </div>

                                <div class="product-specs" v-if="product.specifications">
                                    <h6>Specifications:</h6>
                                    <div class="specs-grid">
                                        <div 
                                            v-for="(spec, specIndex) in getProductSpecs(product.specifications)"
                                            :key="specIndex"
                                            class="spec-item"
                                        >
                                            <label>{{ spec.name }}:</label>
                                            <span>{{ spec.value }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-variants" v-if="product.variants && product.variants.length > 0">
                                    <h6>Available Variants:</h6>
                                    <div class="variants-list">
                                        <div 
                                            v-for="(variant, variantIndex) in product.variants"
                                            :key="variantIndex"
                                            class="variant-item"
                                        >
                                            <span class="variant-name">{{ variant.name || variant }}</span>
                                            <span class="variant-code" v-if="variant.code">{{ variant.code }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-pricing" v-if="product.pricing">
                                    <div class="pricing-info">
                                        <div class="price-item" v-if="product.pricing.unit_price">
                                            <label>Unit Price:</label>
                                            <span class="price-value">{{ product.pricing.unit_price }}</span>
                                        </div>
                                        <div class="price-item" v-if="product.pricing.minimum_order">
                                            <label>MOQ:</label>
                                            <span>{{ product.pricing.minimum_order }}</span>
                                        </div>
                                        <div class="price-item" v-if="product.pricing.lead_time">
                                            <label>Lead Time:</label>
                                            <span>{{ product.pricing.lead_time }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="product-applications" v-if="product.applications && product.applications.length > 0">
                                    <h6>Applications:</h6>
                                    <div class="applications-tags">
                                        <span 
                                            v-for="(application, appIndex) in product.applications"
                                            :key="appIndex"
                                            class="application-tag"
                                        >
                                            {{ application }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State for Filter -->
                    <div v-if="filteredProducts.length === 0" class="empty-filter">
                        <div class="empty-filter-illustration">
                            <i class="fas fa-search"></i>
                            <h4>No Products Found</h4>
                            <p>No products match your search or filter criteria</p>
                        </div>
                    </div>
                </div>

                <!-- Categories Summary -->
                <div class="categories-summary">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Product Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in productCategories"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getProductIcon(category.name)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} products</span>
                                <div class="category-percentage">
                                    <span class="percentage-value">{{ getCategoryPercentage(category.count) }}%</span>
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
                <h4>Belum Ada Data Product Catalog</h4>
                <p>Katalog produk belum dilengkapi untuk manufacturer ini</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

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

const searchQuery = ref('')
const selectedCategory = ref('')

const hasData = computed(() => {
    return !!(props.data.product_catalog)
})

const products = computed(() => {
    if (!props.data.product_catalog?.products) return []
    return Array.isArray(props.data.product_catalog.products) 
        ? props.data.product_catalog.products 
        : []
})

const productCategories = computed(() => {
    if (products.value.length === 0) return []
    
    const categories = {}
    products.value.forEach(product => {
        const category = product.category || 'General'
        if (!categories[category]) {
            categories[category] = { name: category, count: 0 }
        }
        categories[category].count++
    })
    
    return Object.values(categories).sort((a, b) => b.count - a.count)
})

const filteredProducts = computed(() => {
    let filtered = products.value
    
    // Filter by search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(product => 
            (product.name || product.product_name || '').toLowerCase().includes(query) ||
            (product.description || '').toLowerCase().includes(query) ||
            (product.category || '').toLowerCase().includes(query)
        )
    }
    
    // Filter by category
    if (selectedCategory.value) {
        filtered = filtered.filter(product => product.category === selectedCategory.value)
    }
    
    return filtered
})

function getProductIcon(category) {
    const icons = {
        'automotive': 'fas fa-car',
        'electronics': 'fas fa-microchip',
        'machinery': 'fas fa-cogs',
        'tools': 'fas fa-tools',
        'components': 'fas fa-puzzle-piece',
        'materials': 'fas fa-layer-group',
        'equipment': 'fas fa-industry',
        'parts': 'fas fa-wrench',
        'hardware': 'fas fa-hammer',
        'software': 'fas fa-laptop'
    }
    return icons[category?.toLowerCase()] || 'fas fa-cube'
}

function getProductStatus(product) {
    return product.availability?.toLowerCase() || 'available'
}

function getStatusIcon(availability) {
    const icons = {
        'available': 'fas fa-check-circle',
        'limited': 'fas fa-exclamation-circle',
        'out_of_stock': 'fas fa-times-circle',
        'discontinued': 'fas fa-ban'
    }
    return icons[availability?.toLowerCase()] || 'fas fa-check-circle'
}

function getStatusText(availability) {
    const texts = {
        'available': 'Available',
        'limited': 'Limited Stock',
        'out_of_stock': 'Out of Stock',
        'discontinued': 'Discontinued'
    }
    return texts[availability?.toLowerCase()] || 'Available'
}

function getProductSpecs(specifications) {
    if (Array.isArray(specifications)) {
        return specifications
    }
    if (typeof specifications === 'object') {
        return Object.entries(specifications).map(([name, value]) => ({ name, value }))
    }
    return []
}

function getCategoryPercentage(count) {
    if (products.value.length === 0) return 0
    return Math.round((count / products.value.length) * 100)
}
</script>

<style scoped>
.product-catalog-card {
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

.catalog-stats {
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

.product-filter {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px 24px;
}

.filter-controls {
    display: flex;
    gap: 16px;
    align-items: center;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.search-input {
    width: 100%;
    padding: 10px 12px 10px 40px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.category-filter {
    flex-shrink: 0;
}

.category-select {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
    min-width: 200px;
}

.products-overview,
.categories-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.products-overview h4,
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

.products-overview h4 i {
    color: #f59e0b;
}

.categories-summary h4 i {
    color: #8b5cf6;
}

.product-count {
    margin-left: auto;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    padding: 24px;
}

.product-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.product-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.product-image {
    width: 48px;
    height: 48px;
    background: #f59e0b;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    flex: 1;
    min-width: 0;
}

.product-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.3;
}

.product-category {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    display: block;
    margin-bottom: 4px;
}

.product-code {
    margin-top: 4px;
}

.code-badge {
    background: #e5e7eb;
    color: #374151;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    font-family: monospace;
}

.product-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.available {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.limited {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.out_of_stock {
    background: #fef2f2;
    color: #991b1b;
}

.status-badge.discontinued {
    background: #f3f4f6;
    color: #6b7280;
}

.product-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.product-description {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.product-description p {
    margin: 0;
    color: #374151;
    font-size: 0.875rem;
    line-height: 1.5;
}

.product-specs {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.product-specs h6 {
    margin: 0 0 8px 0;
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
    background: #f8fafc;
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

.product-variants {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.product-variants h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.variants-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.variant-item {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    background: #f8fafc;
    border-radius: 4px;
    border: 1px solid #e5e7eb;
}

.variant-name {
    font-size: 0.75rem;
    color: #374151;
    font-weight: 500;
}

.variant-code {
    font-size: 0.75rem;
    color: #6b7280;
    font-family: monospace;
}

.product-pricing {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #fbbf24;
}

.pricing-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 8px;
}

.price-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
}

.price-item label {
    color: #92400e;
    font-weight: 500;
}

.price-item span {
    color: #78350f;
    font-weight: 600;
}

.price-value {
    color: #dc2626 !important;
    font-weight: 700 !important;
    font-size: 0.875rem !important;
}

.product-applications {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.product-applications h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.applications-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.application-tag {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
    margin-top: 4px;
}

.percentage-value {
    font-weight: 700;
    color: #8b5cf6;
    font-size: 0.875rem;
}

.empty-filter,
.no-data {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 300px;
    text-align: center;
}

.empty-filter-illustration,
.no-data-illustration {
    max-width: 400px;
}

.empty-filter-illustration i,
.no-data-illustration i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-filter-illustration h4,
.no-data-illustration h4 {
    margin: 0 0 12px 0;
    color: #6b7280;
    font-size: 1.25rem;
    font-weight: 600;
}

.empty-filter-illustration p,
.no-data-illustration p {
    margin: 0;
    color: #9ca3af;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .product-catalog-card {
        padding: 16px;
    }
    
    .products-grid,
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .catalog-stats {
        justify-content: center;
    }
    
    .filter-controls {
        flex-direction: column;
        gap: 12px;
    }
    
    .search-box {
        max-width: none;
    }
    
    .category-select {
        min-width: auto;
        width: 100%;
    }
    
    .product-header {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .specs-grid,
    .pricing-info {
        grid-template-columns: 1fr;
    }
}
</style>