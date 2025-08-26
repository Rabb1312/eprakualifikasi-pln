<template>
    <div class="subcontracting-card">
        <div v-if="hasData" class="subcontracting-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="header-info">
                    <h3>Subcontracting</h3>
                    <p>Informasi subkontrak dan kemitraan manufacturer</p>
                </div>
                <!-- <div class="subcontracting-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ subcontractingPartners.length }}</span>
                        <span class="stat-label">Partners</span>
                    </div>
                </div> -->
            </div>

            <div class="content-sections">
                <!-- Main Subcontracting Info -->
                <div class="subcontracting-overview">
                    <h4>
                        <i class="fas fa-info-circle"></i>
                        Subcontracting Overview
                    </h4>
                    <div class="overview-content">
                        <div class="overview-text">
                            <p>{{ data.subcontracting }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subcontracting Partners (if parsed from text) -->
                <div v-if="subcontractingPartners.length > 0" class="partners-section">
                    <h4>
                        <i class="fas fa-users"></i>
                        Subcontracting Partners
                    </h4>
                    <div class="partners-grid">
                        <div 
                            v-for="(partner, index) in subcontractingPartners"
                            :key="index"
                            class="partner-item"
                        >
                            <div class="partner-header">
                                <div class="partner-icon">
                                    <i :class="getPartnerIcon(partner.type)"></i>
                                </div>
                                <div class="partner-info">
                                    <h5>{{ partner.name || `Partner ${index + 1}` }}</h5>
                                    <span class="partner-type">{{ partner.type || 'Subcontractor' }}</span>
                                    <div class="partner-location" v-if="partner.location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ partner.location }}</span>
                                    </div>
                                </div>
                                <div class="partner-status">
                                    <span :class="['status-badge', getPartnerStatus(partner.status)]">
                                        <i :class="getStatusIcon(partner.status)"></i>
                                        {{ getStatusText(partner.status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="partner-details">
                                <div class="partner-services" v-if="partner.services">
                                    <h6>Services Provided:</h6>
                                    <div class="services-list">
                                        <div 
                                            v-for="(service, serviceIndex) in getServices(partner.services)"
                                            :key="serviceIndex"
                                            class="service-item"
                                        >
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ service }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="partner-specialties" v-if="partner.specialties">
                                    <h6>Specialties:</h6>
                                    <div class="specialties-tags">
                                        <span 
                                            v-for="(specialty, specialtyIndex) in getSpecialties(partner.specialties)"
                                            :key="specialtyIndex"
                                            class="specialty-tag"
                                        >
                                            {{ specialty }}
                                        </span>
                                    </div>
                                </div>

                                <div class="partner-capacity" v-if="partner.capacity">
                                    <h6>Capacity:</h6>
                                    <div class="capacity-info">
                                        <i class="fas fa-industry"></i>
                                        <span>{{ partner.capacity }}</span>
                                    </div>
                                </div>

                                <div class="partner-duration" v-if="partner.duration">
                                    <h6>Partnership Duration:</h6>
                                    <div class="duration-info">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ partner.duration }}</span>
                                    </div>
                                </div>

                                <div class="partner-contact" v-if="partner.contact">
                                    <h6>Contact Information:</h6>
                                    <div class="contact-list">
                                        <div v-if="partner.contact.phone" class="contact-item">
                                            <i class="fas fa-phone"></i>
                                            <span>{{ partner.contact.phone }}</span>
                                        </div>
                                        <div v-if="partner.contact.email" class="contact-item">
                                            <i class="fas fa-envelope"></i>
                                            <span>{{ partner.contact.email }}</span>
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
                <i class="fas fa-handshake"></i>
                <h4>Belum Ada Data Subcontracting</h4>
                <p>Data informasi subkontracting belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.subcontracting)
})

// Parse potential partner information from subcontracting text
const subcontractingPartners = computed(() => {
    if (!props.data.subcontracting) return []
    
    const text = props.data.subcontracting
    const partners = []
    
    // Try to extract partner information if the text contains structured data
    // This is a basic parser - in real implementation, you might have structured JSON
    const lines = text.split('\n').filter(line => line.trim())
    
    lines.forEach((line, index) => {
        // Look for patterns that might indicate partner information
        if (line.toLowerCase().includes('partner') || 
            line.toLowerCase().includes('subcontractor') ||
            line.toLowerCase().includes('vendor')) {
            partners.push({
                name: line.trim(),
                type: 'Subcontractor',
                status: 'active'
            })
        }
    })
    
    // If no structured data found, return empty array
    return partners.length > 0 ? partners : []
})

function getPartnerIcon(type) {
    const typeLower = (type || '').toLowerCase()
    const icons = {
        'manufacturing': 'fas fa-industry',
        'service': 'fas fa-wrench',
        'supplier': 'fas fa-truck',
        'technology': 'fas fa-laptop-code',
        'logistics': 'fas fa-shipping-fast'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (typeLower.includes(key)) return icon
    }
    return 'fas fa-handshake'
}

function getPartnerStatus(status) {
    return (status || 'active').toLowerCase()
}

function getStatusIcon(status) {
    const statusLower = (status || 'active').toLowerCase()
    const icons = {
        'active': 'fas fa-check-circle',
        'inactive': 'fas fa-pause-circle',
        'pending': 'fas fa-clock',
        'terminated': 'fas fa-times-circle'
    }
    return icons[statusLower] || 'fas fa-check-circle'
}

function getStatusText(status) {
    const statusLower = (status || 'active').toLowerCase()
    const texts = {
        'active': 'Active',
        'inactive': 'Inactive',
        'pending': 'Pending',
        'terminated': 'Terminated'
    }
    return texts[statusLower] || 'Active'
}

function getServices(services) {
    if (Array.isArray(services)) return services
    if (typeof services === 'string') return services.split(',').map(s => s.trim())
    return []
}

function getSpecialties(specialties) {
    if (Array.isArray(specialties)) return specialties
    if (typeof specialties === 'string') return specialties.split(',').map(s => s.trim())
    return []
}
</script>

<style scoped>
.subcontracting-card {
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
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
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

.subcontracting-stats {
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

.subcontracting-overview,
.partners-section,
.subcontracting-types,
.subcontracting-analysis {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.subcontracting-overview h4,
.partners-section h4,
.subcontracting-types h4,
.subcontracting-analysis h4 {
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

.subcontracting-overview h4 i {
    color: #06b6d4;
}

.partners-section h4 i {
    color: #10b981;
}

.subcontracting-types h4 i {
    color: #f59e0b;
}

.subcontracting-analysis h4 i {
    color: #8b5cf6;
}

.overview-content {
    padding: 24px;
}

.overview-text {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.overview-text p {
    margin: 0;
    color: #374151;
    line-height: 1.8;
    font-size: 1rem;
    text-align: justify;
}

.partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    padding: 24px;
}

.partner-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.partner-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.partner-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.partner-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #22d3ee 0%, #06b6d4 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.partner-info {
    flex: 1;
}

.partner-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.partner-type {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-bottom: 8px;
}

.partner-location {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
}

.partner-location i {
    font-size: 0.75rem;
}

.partner-status {
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

.status-badge.inactive {
    background: #f3f4f6;
    color: #6b7280;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.terminated {
    background: #fef2f2;
    color: #991b1b;
}

.partner-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.partner-services,
.partner-specialties,
.partner-capacity,
.partner-duration,
.partner-contact {
    background: white;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.partner-services h6,
.partner-specialties h6,
.partner-capacity h6,
.partner-duration h6,
.partner-contact h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.services-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.service-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.service-item i {
    color: #06b6d4;
    font-size: 0.75rem;
}

.specialties-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.specialty-tag {
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    color: #0369a1;
    padding: 2px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.capacity-info,
.duration-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 600;
}

.capacity-info i,
.duration-info i {
    color: #06b6d4;
}

.contact-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.contact-item i {
    color: #06b6d4;
    font-size: 0.875rem;
    width: 16px;
    text-align: center;
}

.types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    padding: 24px;
}

.type-item {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.type-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.type-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 16px;
}

.type-info h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.type-info p {
    margin: 0 0 12px 0;
    color: #6b7280;
    line-height: 1.5;
    font-size: 0.875rem;
}

.type-benefits {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.benefit-tag {
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
    color: #92400e;
    padding: 2px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

.analysis-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    padding: 24px;
}

.analysis-item {
    background: #f8fafc;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    display: flex;
    gap: 16px;
}

.analysis-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.analysis-info {
    flex: 1;
}

.analysis-info h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.analysis-info p {
    margin: 0 0 12px 0;
    color: #6b7280;
    line-height: 1.5;
    font-size: 0.875rem;
}

.analysis-metrics {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.metric {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
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
    .subcontracting-card {
        padding: 16px;
    }
    
    .partners-grid,
    .types-grid,
    .analysis-content {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .partner-header {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .analysis-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>