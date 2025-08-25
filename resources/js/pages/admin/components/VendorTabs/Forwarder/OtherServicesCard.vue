<template>
    <div class="other-services-card">
        <div v-if="hasData" class="services-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="header-info">
                    <h3>Other Services</h3>
                    <p>Layanan tambahan yang disediakan oleh forwarder</p>
                </div>
                <div class="services-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ otherServices.length }}</span>
                        <span class="stat-label">Services</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Services List -->
                <div class="services-overview">
                    <h4>
                        <i class="fas fa-list"></i>
                        Available Services
                    </h4>
                    <div class="services-grid">
                        <div 
                            v-for="(service, index) in otherServices"
                            :key="index"
                            class="service-item"
                        >
                            <div class="service-header">
                                <div class="service-icon">
                                    <i :class="getServiceIcon(service.type)"></i>
                                </div>
                                <div class="service-info">
                                    <h5>{{ service.name || service.type || 'Additional Service' }}</h5>
                                    <span class="service-category">{{ service.category || 'Support Service' }}</span>
                                </div>
                                <div class="service-status">
                                    <span :class="['status-badge', getServiceStatus(service)]">
                                        <i class="fas fa-check-circle"></i>
                                        Available
                                    </span>
                                </div>
                            </div>

                            <div class="service-details" v-if="service.description">
                                <p>{{ service.description }}</p>
                            </div>

                            <div class="service-specs" v-if="service.specifications">
                                <div class="specs-grid">
                                    <div 
                                        v-for="(spec, specIndex) in service.specifications"
                                        :key="specIndex"
                                        class="spec-item"
                                    >
                                        <label>{{ spec.name }}:</label>
                                        <span>{{ spec.value }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="service-features" v-if="service.features && service.features.length > 0">
                                <h6>Key Features:</h6>
                                <div class="features-list">
                                    <div 
                                        v-for="(feature, featureIndex) in service.features"
                                        :key="featureIndex"
                                        class="feature-item"
                                    >
                                        <i class="fas fa-check"></i>
                                        <span>{{ feature }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="service-pricing" v-if="service.pricing">
                                <div class="pricing-info">
                                    <span class="pricing-label">{{ service.pricing.type || 'Price' }}:</span>
                                    <span class="pricing-value">{{ service.pricing.value }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Categories -->
                <div class="service-categories" v-if="serviceCategories.length > 0">
                    <h4>
                        <i class="fas fa-tags"></i>
                        Service Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in serviceCategories"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getCategoryIcon(category.name)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} services</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Coverage -->
                <div class="coverage-section" v-if="serviceCoverage">
                    <h4>
                        <i class="fas fa-map-marked-alt"></i>
                        Service Coverage
                    </h4>
                    <div class="coverage-content">
                        <div class="coverage-areas">
                            <div 
                                v-for="(area, index) in coverageAreas"
                                :key="index"
                                class="coverage-area"
                            >
                                <div class="area-badge">
                                    <i :class="getAreaIcon(area.type)"></i>
                                </div>
                                <div class="area-details">
                                    <h6>{{ area.name }}</h6>
                                    <span class="area-description">{{ area.description }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-concierge-bell"></i>
                <h4>Belum Ada Data Other Services</h4>
                <p>Data layanan tambahan belum dilengkapi untuk forwarder ini</p>
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
    return !!(props.data.other_services)
})

const otherServices = computed(() => {
    if (!props.data.other_services?.services) return []
    return Array.isArray(props.data.other_services.services) 
        ? props.data.other_services.services 
        : []
})

const serviceCoverage = computed(() => {
    return props.data.other_services?.coverage || null
})

const coverageAreas = computed(() => {
    if (!serviceCoverage.value?.areas) return []
    return Array.isArray(serviceCoverage.value.areas) 
        ? serviceCoverage.value.areas 
        : []
})

const serviceCategories = computed(() => {
    if (otherServices.value.length === 0) return []
    
    const categories = {}
    otherServices.value.forEach(service => {
        const category = service.category || 'General'
        if (!categories[category]) {
            categories[category] = { name: category, count: 0 }
        }
        categories[category].count++
    })
    
    return Object.values(categories)
})

function getServiceIcon(type) {
    const icons = {
        'documentation': 'fas fa-file-alt',
        'customs_clearance': 'fas fa-file-invoice',
        'packaging': 'fas fa-box',
        'storage': 'fas fa-warehouse',
        'tracking': 'fas fa-search-location',
        'consulting': 'fas fa-comments',
        'cargo_survey': 'fas fa-clipboard-check',
        'door_to_door': 'fas fa-home',
        'pick_up_delivery': 'fas fa-truck',
        'container_stuffing': 'fas fa-boxes'
    }
    return icons[type?.toLowerCase()] || 'fas fa-cog'
}

function getServiceStatus(service) {
    return service.status?.toLowerCase() || 'available'
}

function getCategoryIcon(categoryName) {
    const name = categoryName?.toLowerCase() || ''
    const icons = {
        'documentation': 'fas fa-file-alt',
        'logistics': 'fas fa-route',
        'customs': 'fas fa-file-invoice',
        'warehouse': 'fas fa-warehouse',
        'transport': 'fas fa-truck',
        'consulting': 'fas fa-comments',
        'general': 'fas fa-cog'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (name.includes(key)) return icon
    }
    return 'fas fa-tag'
}

function getAreaIcon(type) {
    const icons = {
        'domestic': 'fas fa-flag',
        'international': 'fas fa-globe',
        'regional': 'fas fa-map',
        'local': 'fas fa-map-marker-alt'
    }
    return icons[type?.toLowerCase()] || 'fas fa-map-marked-alt'
}
</script>

<style scoped>
.other-services-card {
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

.services-stats {
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

.services-overview,
.service-categories,
.coverage-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.services-overview h4,
.service-categories h4,
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

.services-overview h4 i {
    color: #059669;
}

.service-categories h4 i {
    color: #f59e0b;
}

.coverage-section h4 i {
    color: #3b82f6;
}

.services-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 24px;
}

.service-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.service-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.service-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.service-info {
    flex: 1;
}

.service-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.service-category {
    font-size: 0.875rem;
    color: #6b7280;
}

.service-status {
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

.service-details {
    padding: 16px 20px;
    background: white;
}

.service-details p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.service-specs {
    padding: 16px 20px;
    border-top: 1px solid #f3f4f6;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.spec-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.spec-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.service-features {
    padding: 16px 20px;
    border-top: 1px solid #f3f4f6;
}

.service-features h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.feature-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.service-pricing {
    padding: 16px 20px;
    border-top: 1px solid #f3f4f6;
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
}

.pricing-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pricing-label {
    font-weight: 600;
    color: #065f46;
    font-size: 0.875rem;
}

.pricing-value {
    font-weight: 700;
    color: #059669;
    font-size: 1rem;
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
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
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

.coverage-content {
    padding: 24px;
}

.coverage-areas {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.coverage-area {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.area-badge {
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

.area-details h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.area-description {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.4;
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
    .other-services-card {
        padding: 16px;
    }
    
    .services-grid,
    .categories-grid,
    .coverage-areas {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .service-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .specs-grid {
        grid-template-columns: 1fr;
    }
}
</style>