<template>
    <div class="plants-card">
        <div v-if="hasData" class="plants-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-industry"></i>
                </div>
                <div class="header-info">
                    <h3>Other Manufacturing Plants</h3>
                    <p>Pabrik manufaktur lainnya di seluruh dunia yang dimiliki perusahaan</p>
                </div>
                <div class="plants-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ plantsList.length }}</span>
                        <span class="stat-label">Plants</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Plants Grid -->
                <div class="plants-grid">
                    <div 
                        v-for="(plant, index) in plantsList"
                        :key="index"
                        class="plant-item"
                    >
                        <div class="plant-header">
                            <div class="plant-icon">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="plant-info">
                                <h5>{{ plant.company_name || `Plant ${index + 1}` }}</h5>
                                <span class="plant-type">Manufacturing Plant</span>
                                <div class="plant-number">
                                    <i class="fas fa-hashtag"></i>
                                    <span>Plant #{{ index + 1 }}</span>
                                </div>
                            </div>
                            <div class="plant-status">
                                <span class="status-badge operational">
                                    <i class="fas fa-play-circle"></i>
                                    Operational
                                </span>
                            </div>
                        </div>

                        <div class="plant-details">
                            <!-- Company Information -->
                            <div class="plant-company">
                                <h6>Company Information:</h6>
                                <div class="company-info">
                                    <div class="info-row">
                                        <label>Company Name:</label>
                                        <span>{{ plant.company_name || 'Not specified' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Products Manufactured -->
                            <div class="plant-products" v-if="plant.products_manufactured">
                                <h6>Products Manufactured:</h6>
                                <div class="products-list">
                                    <div 
                                        v-for="(product, productIndex) in getProductsList(plant.products_manufactured)"
                                        :key="productIndex"
                                        class="product-item"
                                    >
                                        <i class="fas fa-cog"></i>
                                        <span>{{ product }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Plant Summary -->
                            <!-- <div class="plant-summary">
                                <div class="summary-grid">
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Company</span>
                                            <span class="summary-value">{{ plant.company_name ? 'Named' : 'Unnamed' }}</span>
                                        </div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Products</span>
                                            <span class="summary-value">{{ getProductsCount(plant.products_manufactured) }}</span>
                                        </div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Status</span>
                                            <span class="summary-value">Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>

                <!-- Plants Overview Summary -->
                <!-- <div class="plants-overview">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Plants Overview
                    </h4>
                    <div class="overview-grid">
                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="overview-info">
                                <span class="overview-number">{{ plantsList.length }}</span>
                                <span class="overview-label">Total Manufacturing Plants</span>
                            </div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="overview-info">
                                <span class="overview-number">{{ getNamedPlantsCount() }}</span>
                                <span class="overview-label">Named Companies</span>
                            </div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="overview-info">
                                <span class="overview-number">{{ getTotalProductTypes() }}</span>
                                <span class="overview-label">Product Types</span>
                            </div>
                        </div>
                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="overview-info">
                                <span class="overview-number">{{ plantsList.length > 0 ? 'Global' : 'None' }}</span>
                                <span class="overview-label">Geographic Reach</span>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-industry"></i>
                <h4>Belum Ada Data Pabrik</h4>
                <p>Data pabrik manufaktur lainnya belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.plants && Array.isArray(props.data.plants) && props.data.plants.length > 0)
})

const plantsList = computed(() => {
    if (!props.data.plants) return []
    
    // Handle JSON field dari database - sesuai dengan ManufacturePlants.vue
    if (Array.isArray(props.data.plants)) {
        return props.data.plants.filter(plant => 
            plant && (plant.company_name || plant.products_manufactured)
        )
    }
    
    if (typeof props.data.plants === 'string') {
        try {
            const parsed = JSON.parse(props.data.plants)
            return Array.isArray(parsed) ? parsed.filter(plant => 
                plant && (plant.company_name || plant.products_manufactured)
            ) : []
        } catch {
            return []
        }
    }
    
    return []
})

// Helper functions sesuai dengan struktur data ManufacturePlants
function getProductsList(productsString) {
    if (!productsString) return []
    
    // Split by comma and clean up
    return productsString
        .split(',')
        .map(product => product.trim())
        .filter(product => product.length > 0)
}

function getProductsCount(productsString) {
    const products = getProductsList(productsString)
    return products.length > 0 ? `${products.length} types` : 'Not specified'
}

function getNamedPlantsCount() {
    return plantsList.value.filter(plant => plant.company_name && plant.company_name.trim()).length
}

function getTotalProductTypes() {
    const allProducts = new Set()
    
    plantsList.value.forEach(plant => {
        if (plant.products_manufactured) {
            getProductsList(plant.products_manufactured).forEach(product => {
                allProducts.add(product.toLowerCase())
            })
        }
    })
    
    return allProducts.size
}
</script>

<style scoped>
.plants-card {
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

.plants-stats {
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

.plants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 24px;
}

.plant-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.plant-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.plant-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.plant-icon {
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

.plant-info {
    flex: 1;
}

.plant-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.plant-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.plant-number {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
}

.plant-number i {
    font-size: 0.75rem;
}

.plant-status {
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

.plant-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.plant-company,
.plant-products,
.plant-summary {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.plant-company h6,
.plant-products h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.company-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.info-row label {
    color: #6b7280;
    font-weight: 500;
}

.info-row span {
    color: #1f2937;
    font-weight: 600;
}

.products-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.product-item {
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

.product-item i {
    color: #7c3aed;
    font-size: 0.75rem;
}

.plant-summary {
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

.plants-overview {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.plants-overview h4 {
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

.plants-overview h4 i {
    color: #7c3aed;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.overview-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    color: white;
    border-radius: 8px;
}

.overview-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.overview-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.overview-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.overview-label {
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
    .plants-card {
        padding: 16px;
    }
    
    .plants-grid,
    .overview-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .plant-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
}
</style>