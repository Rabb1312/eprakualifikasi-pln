<template>
    <div class="after-sales-card">
        <div v-if="hasData" class="after-sales-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="header-info">
                    <h3>After Sales Service</h3>
                    <p>Layanan purna jual dan dukungan teknis manufacturer</p>
                </div>
                <div class="services-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ afterSalesServices.length }}</span>
                        <span class="stat-label">Services</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Services Grid -->
                <div class="services-grid">
                    <div 
                        v-for="(service, index) in afterSalesServices"
                        :key="index"
                        class="service-item"
                    >
                        <div class="service-header">
                            <div class="service-icon">
                                <i :class="getServiceIcon(service.type)"></i>
                            </div>
                            <div class="service-info">
                                <h5>{{ service.name || service.type || `Service ${index + 1}` }}</h5>
                                <span class="service-category">{{ service.category || 'After Sales Service' }}</span>
                            </div>
                            <div class="service-availability">
                                <span :class="['availability-badge', getAvailabilityClass(service.availability)]">
                                    <i :class="getAvailabilityIcon(service.availability)"></i>
                                    {{ getAvailabilityText(service.availability) }}
                                </span>
                            </div>
                        </div>

                        <div class="service-details">
                            <div class="service-description" v-if="service.description">
                                <p>{{ service.description }}</p>
                            </div>

                            <div class="service-specs" v-if="service.response_time || service.coverage || service.warranty">
                                <h6>Service Specifications:</h6>
                                <div class="specs-grid">
                                    <div v-if="service.response_time" class="spec-item">
                                        <label>Response Time:</label>
                                        <span>{{ service.response_time }}</span>
                                    </div>
                                    <div v-if="service.coverage" class="spec-item">
                                        <label>Coverage:</label>
                                        <span>{{ service.coverage }}</span>
                                    </div>
                                    <div v-if="service.warranty" class="spec-item">
                                        <label>Warranty:</label>
                                        <span>{{ service.warranty }}</span>
                                    </div>
                                    <div v-if="service.cost" class="spec-item">
                                        <label>Cost:</label>
                                        <span>{{ service.cost }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="service-features" v-if="service.features">
                                <h6>Key Features:</h6>
                                <div class="features-list">
                                    <div 
                                        v-for="(feature, featureIndex) in getFeatures(service.features)"
                                        :key="featureIndex"
                                        class="feature-item"
                                    >
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ feature }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="service-channels" v-if="service.channels">
                                <h6>Service Channels:</h6>
                                <div class="channels-grid">
                                    <div 
                                        v-for="(channel, channelIndex) in getChannels(service.channels)"
                                        :key="channelIndex"
                                        class="channel-item"
                                    >
                                        <i :class="getChannelIcon(channel)"></i>
                                        <span>{{ channel }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="service-locations" v-if="service.locations">
                                <h6>Service Locations:</h6>
                                <div class="locations-tags">
                                    <span 
                                        v-for="(location, locationIndex) in getLocations(service.locations)"
                                        :key="locationIndex"
                                        class="location-tag"
                                    >
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ location }}
                                    </span>
                                </div>
                            </div>

                            <div class="service-contact" v-if="service.contact">
                                <h6>Contact Information:</h6>
                                <div class="contact-info">
                                    <div v-if="service.contact.phone" class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ service.contact.phone }}</span>
                                    </div>
                                    <div v-if="service.contact.email" class="contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ service.contact.email }}</span>
                                    </div>
                                    <div v-if="service.contact.website" class="contact-item">
                                        <i class="fas fa-globe"></i>
                                        <span>{{ service.contact.website }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Categories -->
                <div class="service-categories">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Service Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in getServiceCategories()"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getServiceIcon(category.name)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} services</span>
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
                <i class="fas fa-wrench"></i>
                <h4>Belum Ada Data After Sales</h4>
                <p>Data layanan purna jual belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.after_sales)
})

const afterSalesServices = computed(() => {
    if (!props.data.after_sales) return []
    
    // Handle JSON field dari database
    if (Array.isArray(props.data.after_sales)) {
        return props.data.after_sales
    }
    
    if (typeof props.data.after_sales === 'string') {
        try {
            const parsed = JSON.parse(props.data.after_sales)
            return Array.isArray(parsed) ? parsed : [parsed]
        } catch {
            return []
        }
    }
    
    if (typeof props.data.after_sales === 'object') {
        return Object.entries(props.data.after_sales).map(([key, value]) => ({
            type: key,
            ...value
        }))
    }
    
    return []
})

function getServiceIcon(type) {
    const typeLower = (type || '').toLowerCase()
    const icons = {
        'maintenance': 'fas fa-tools',
        'repair': 'fas fa-wrench',
        'installation': 'fas fa-hammer',
        'training': 'fas fa-graduation-cap',
        'support': 'fas fa-headset',
        'warranty': 'fas fa-shield-alt',
        'consultation': 'fas fa-comments',
        'upgrade': 'fas fa-arrow-up',
        'replacement': 'fas fa-exchange-alt',
        'calibration': 'fas fa-sliders-h'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (typeLower.includes(key)) return icon
    }
    return 'fas fa-cog'
}

function getAvailabilityClass(availability) {
    const availabilityLower = (availability || 'available').toLowerCase()
    if (availabilityLower.includes('24/7') || availabilityLower.includes('always')) return 'always'
    if (availabilityLower.includes('limited') || availabilityLower.includes('business')) return 'limited'
    if (availabilityLower.includes('on-demand') || availabilityLower.includes('request')) return 'on-demand'
    return 'available'
}

function getAvailabilityIcon(availability) {
    const availabilityClass = getAvailabilityClass(availability)
    const icons = {
        'always': 'fas fa-clock',
        'available': 'fas fa-check-circle',
        'limited': 'fas fa-business-time',
        'on-demand': 'fas fa-phone'
    }
    return icons[availabilityClass] || 'fas fa-check-circle'
}

function getAvailabilityText(availability) {
    return availability || 'Available'
}

function getFeatures(features) {
    if (Array.isArray(features)) return features
    if (typeof features === 'string') return features.split(',').map(f => f.trim())
    return []
}

function getChannels(channels) {
    if (Array.isArray(channels)) return channels
    if (typeof channels === 'string') return channels.split(',').map(c => c.trim())
    return []
}

function getChannelIcon(channel) {
    const channelLower = (channel || '').toLowerCase()
    const icons = {
        'phone': 'fas fa-phone',
        'email': 'fas fa-envelope',
        'online': 'fas fa-globe',
        'onsite': 'fas fa-map-marker-alt',
        'remote': 'fas fa-desktop',
        'chat': 'fas fa-comments'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (channelLower.includes(key)) return icon
    }
    return 'fas fa-headset'
}

function getLocations(locations) {
    if (Array.isArray(locations)) return locations
    if (typeof locations === 'string') return locations.split(',').map(l => l.trim())
    return []
}

function getServiceCategories() {
    const categories = {}
    afterSalesServices.value.forEach(service => {
        const category = service.category || service.type || 'General'
        if (!categories[category]) {
            categories[category] = { name: category, count: 0 }
        }
        categories[category].count++
    })
    
    return Object.values(categories).sort((a, b) => b.count - a.count)
}

function getCategoryPercentage(count) {
    if (afterSalesServices.value.length === 0) return 0
    return Math.round((count / afterSalesServices.value.length) * 100)
}
</script>

<style scoped>
.after-sales-card {
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
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
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

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 24px;
}

.service-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.service-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.service-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.service-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.service-info {
    flex: 1;
}

.service-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.service-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.service-availability {
    flex-shrink: 0;
}

.availability-badge {
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

.availability-badge.always {
    background: #d1fae5;
    color: #065f46;
}

.availability-badge.available {
    background: #dbeafe;
    color: #1e40af;
}

.availability-badge.limited {
    background: #fef3c7;
    color: #92400e;
}

.availability-badge.on-demand {
    background: #e0e7ff;
    color: #3730a3;
}

.service-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.service-description,
.service-specs,
.service-features,
.service-channels,
.service-locations,
.service-contact {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.service-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.service-specs h6,
.service-features h6,
.service-channels h6,
.service-locations h6,
.service-contact h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.specs-grid {
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
    color: #3b82f6;
    font-size: 0.75rem;
}

.channels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.channel-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
    color: #374151;
}

.channel-item i {
    color: #3b82f6;
    font-size: 0.875rem;
}

.locations-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.location-tag {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.contact-item i {
    color: #3b82f6;
    font-size: 0.875rem;
    width: 16px;
    text-align: center;
}

.service-categories {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.service-categories h4 {
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

.service-categories h4 i {
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
    .after-sales-card {
        padding: 16px;
    }
    
    .services-grid,
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 16px;
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
    
    .specs-grid,
    .channels-grid {
        grid-template-columns: 1fr;
    }
}
</style>