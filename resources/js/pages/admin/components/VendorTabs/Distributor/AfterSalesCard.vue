<template>
    <div class="after-sales-card">
        <div v-if="hasData" class="after-sales-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="header-info">
                    <h3>After Sales Service</h3>
                    <p>Layanan purna jual yang disediakan oleh distributor</p>
                </div>
                <div class="service-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ afterSalesServices.length }}</span>
                        <span class="stat-label">Services</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Services Overview -->
                <div class="services-overview">
                    <h4>
                        <i class="fas fa-concierge-bell"></i>
                        Available Services
                    </h4>
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
                                    <h5>{{ service.name || service.type || 'After Sales Service' }}</h5>
                                    <span class="service-category">{{ service.category || 'Support Service' }}</span>
                                </div>
                                <div class="service-status">
                                    <span :class="['status-badge', getServiceStatus(service)]">
                                        <i class="fas fa-check-circle"></i>
                                        Available
                                    </span>
                                </div>
                            </div>

                            <div class="service-details">
                                <div class="details-grid">
                                    <div class="detail-item" v-if="service.response_time">
                                        <label>Response Time:</label>
                                        <span>{{ service.response_time }}</span>
                                    </div>
                                    <div class="detail-item" v-if="service.coverage">
                                        <label>Coverage:</label>
                                        <span>{{ service.coverage }}</span>
                                    </div>
                                    <div class="detail-item" v-if="service.warranty_period">
                                        <label>Warranty:</label>
                                        <span>{{ service.warranty_period }}</span>
                                    </div>
                                    <div class="detail-item" v-if="service.availability">
                                        <label>Availability:</label>
                                        <span>{{ service.availability }}</span>
                                    </div>
                                </div>

                                <div class="service-description" v-if="service.description">
                                    <p>{{ service.description }}</p>
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
                        </div>
                    </div>
                </div>

                <!-- Support Channels -->
                <div class="support-channels" v-if="supportChannels.length > 0">
                    <h4>
                        <i class="fas fa-phone"></i>
                        Support Channels
                    </h4>
                    <div class="channels-grid">
                        <div 
                            v-for="(channel, index) in supportChannels"
                            :key="index"
                            class="channel-item"
                        >
                            <div class="channel-icon">
                                <i :class="getChannelIcon(channel.type)"></i>
                            </div>
                            <div class="channel-info">
                                <h6>{{ channel.name || channel.type }}</h6>
                                <span class="channel-detail">{{ channel.detail || channel.contact }}</span>
                                <div class="channel-hours" v-if="channel.hours">
                                    <i class="fas fa-clock"></i>
                                    {{ channel.hours }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Level Agreement -->
                <div class="sla-section" v-if="serviceLevelAgreement">
                    <h4>
                        <i class="fas fa-clipboard-check"></i>
                        Service Level Agreement (SLA)
                    </h4>
                    <div class="sla-content">
                        <div class="sla-metrics">
                            <div 
                                v-for="(metric, index) in slaMetrics"
                                :key="index"
                                class="sla-metric"
                            >
                                <div class="metric-icon">
                                    <i :class="metric.icon"></i>
                                </div>
                                <div class="metric-info">
                                    <span class="metric-value">{{ metric.value }}</span>
                                    <span class="metric-label">{{ metric.label }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sla-description" v-if="serviceLevelAgreement.description">
                            <p>{{ serviceLevelAgreement.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Training & Support -->
                <div class="training-section" v-if="trainingSupport">
                    <h4>
                        <i class="fas fa-graduation-cap"></i>
                        Training & Support
                    </h4>
                    <div class="training-content">
                        <div class="training-types">
                            <div 
                                v-for="(training, index) in trainingPrograms"
                                :key="index"
                                class="training-item"
                            >
                                <div class="training-badge">
                                    <i :class="getTrainingIcon(training.type)"></i>
                                </div>
                                <div class="training-details">
                                    <h6>{{ training.name || training.type }}</h6>
                                    <span class="training-format">{{ training.format || 'On-site Training' }}</span>
                                    <div class="training-duration" v-if="training.duration">
                                        <i class="fas fa-clock"></i>
                                        {{ training.duration }}
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
                <i class="fas fa-headset"></i>
                <h4>Belum Ada Data After Sales Service</h4>
                <p>Data layanan purna jual belum dilengkapi untuk distributor ini</p>
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
    return !!(props.data.after_sales_service)
})

const afterSalesServices = computed(() => {
    if (!props.data.after_sales_service?.services) return []
    return Array.isArray(props.data.after_sales_service.services) 
        ? props.data.after_sales_service.services 
        : []
})

const supportChannels = computed(() => {
    if (!props.data.after_sales_service?.support_channels) return []
    return Array.isArray(props.data.after_sales_service.support_channels) 
        ? props.data.after_sales_service.support_channels 
        : []
})

const serviceLevelAgreement = computed(() => {
    return props.data.after_sales_service?.sla || null
})

const trainingSupport = computed(() => {
    return props.data.after_sales_service?.training || null
})

const trainingPrograms = computed(() => {
    if (!trainingSupport.value?.programs) return []
    return Array.isArray(trainingSupport.value.programs) 
        ? trainingSupport.value.programs 
        : []
})

const slaMetrics = computed(() => {
    if (!serviceLevelAgreement.value?.metrics) return []
    
    const metrics = serviceLevelAgreement.value.metrics
    return [
        {
            icon: 'fas fa-tachometer-alt',
            value: metrics.response_time || 'N/A',
            label: 'Response Time'
        },
        {
            icon: 'fas fa-tools',
            value: metrics.resolution_time || 'N/A',
            label: 'Resolution Time'
        },
        {
            icon: 'fas fa-percentage',
            value: metrics.uptime || 'N/A',
            label: 'Uptime Guarantee'
        },
        {
            icon: 'fas fa-star',
            value: metrics.satisfaction || 'N/A',
            label: 'Customer Satisfaction'
        }
    ].filter(metric => metric.value !== 'N/A')
})

function getServiceIcon(type) {
    const icons = {
        'maintenance': 'fas fa-tools',
        'repair': 'fas fa-wrench',
        'installation': 'fas fa-cogs',
        'technical_support': 'fas fa-headset',
        'training': 'fas fa-graduation-cap',
        'warranty': 'fas fa-shield-alt',
        'consultation': 'fas fa-comments',
        'spare_parts': 'fas fa-boxes'
    }
    return icons[type?.toLowerCase()] || 'fas fa-concierge-bell'
}

function getServiceStatus(service) {
    return service.status?.toLowerCase() || 'available'
}

function getChannelIcon(type) {
    const icons = {
        'phone': 'fas fa-phone',
        'email': 'fas fa-envelope',
        'chat': 'fas fa-comments',
        'whatsapp': 'fab fa-whatsapp',
        'telegram': 'fab fa-telegram',
        'website': 'fas fa-globe',
        'office': 'fas fa-building'
    }
    return icons[type?.toLowerCase()] || 'fas fa-phone'
}

function getTrainingIcon(type) {
    const icons = {
        'on_site': 'fas fa-building',
        'online': 'fas fa-laptop',
        'workshop': 'fas fa-tools',
        'certification': 'fas fa-certificate',
        'webinar': 'fas fa-video'
    }
    return icons[type?.toLowerCase()] || 'fas fa-graduation-cap'
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

.service-stats {
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
.support-channels,
.sla-section,
.training-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.services-overview h4,
.support-channels h4,
.sla-section h4,
.training-section h4 {
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

.support-channels h4 i {
    color: #2563eb;
}

.sla-section h4 i {
    color: #d97706;
}

.training-section h4 i {
    color: #dc2626;
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
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.detail-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.detail-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.service-description {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    margin-top: 12px;
}

.service-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
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

.channels-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    padding: 24px;
}

.channel-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.channel-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.channel-icon {
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

.channel-info {
    flex: 1;
}

.channel-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.channel-detail {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.channel-hours {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #374151;
}

.channel-hours i {
    color: #6b7280;
}

.sla-content {
    padding: 24px;
}

.sla-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.sla-metric {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border-radius: 8px;
}

.metric-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

.metric-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.metric-value {
    font-size: 1.25rem;
    font-weight: 700;
}

.metric-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

.sla-description {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.sla-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.training-content {
    padding: 24px;
}

.training-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.training-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.training-badge {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.training-details {
    flex: 1;
}

.training-details h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.training-format {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.training-duration {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #374151;
}

.training-duration i {
    color: #6b7280;
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
    .channels-grid,
    .sla-metrics,
    .training-types {
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
    
    .details-grid {
        grid-template-columns: 1fr;
    }
}
</style>