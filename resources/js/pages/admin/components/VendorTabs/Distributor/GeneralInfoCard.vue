<template>
    <div class="general-info-card">
        <div v-if="hasData" class="general-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="header-info">
                    <h3>General Information</h3>
                    <p>Informasi umum dan profil lengkap distributor</p>
                </div>
            </div>

            <div class="content-sections">
                <!-- Company Profile -->
                <div class="info-section" v-if="data.company_profile">
                    <h4>
                        <i class="fas fa-building"></i>
                        Company Profile
                    </h4>
                    <div class="profile-content">
                        <p>{{ data.company_profile }}</p>
                    </div>
                </div>

                <!-- Company Overview -->
                <div class="info-section" v-if="data.company_overview">
                    <h4>
                        <i class="fas fa-eye"></i>
                        Company Overview
                    </h4>
                    <div class="overview-content">
                        <p>{{ data.company_overview }}</p>
                    </div>
                </div>

                <!-- Services Offered -->
                <div class="info-section" v-if="servicesOffered.length > 0">
                    <h4>
                        <i class="fas fa-concierge-bell"></i>
                        Services Offered
                    </h4>
                    <div class="services-grid">
                        <div 
                            v-for="(service, index) in servicesOffered"
                            :key="index"
                            class="service-item"
                        >
                            <div class="service-icon">
                                <i :class="getServiceIcon(service)"></i>
                            </div>
                            <div class="service-info">
                                <h5>{{ service.name || service }}</h5>
                                <p v-if="service.description">{{ service.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target Markets -->
                <div class="info-section" v-if="targetMarkets.length > 0">
                    <h4>
                        <i class="fas fa-bullseye"></i>
                        Target Markets
                    </h4>
                    <div class="markets-grid">
                        <div 
                            v-for="(market, index) in targetMarkets"
                            :key="index"
                            class="market-item"
                        >
                            <div class="market-icon">
                                <i :class="getMarketIcon(market)"></i>
                            </div>
                            <span>{{ market.name || market }}</span>
                        </div>
                    </div>
                </div>

                <!-- Key Personnel -->
                <div class="info-section" v-if="keyPersonnel.length > 0">
                    <h4>
                        <i class="fas fa-users"></i>
                        Key Personnel
                    </h4>
                    <div class="personnel-list">
                        <div 
                            v-for="(person, index) in keyPersonnel"
                            :key="index"
                            class="personnel-item"
                        >
                            <div class="personnel-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="personnel-info">
                                <h5>{{ person.name || 'Unknown' }}</h5>
                                <span class="personnel-position">{{ person.position || 'Position not specified' }}</span>
                                <div class="personnel-contact" v-if="person.email || person.phone">
                                    <span v-if="person.email" class="contact-info">
                                        <i class="fas fa-envelope"></i>
                                        {{ person.email }}
                                    </span>
                                    <span v-if="person.phone" class="contact-info">
                                        <i class="fas fa-phone"></i>
                                        {{ person.phone }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Statistics -->
                <div class="info-section">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Business Statistics
                    </h4>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ getBusinessAge() }}</span>
                                <span class="stat-label">Years in Business</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ servicesOffered.length }}</span>
                                <span class="stat-label">Services</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ targetMarkets.length }}</span>
                                <span class="stat-label">Markets</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ keyPersonnel.length }}</span>
                                <span class="stat-label">Key Personnel</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-info-circle"></i>
                <h4>Belum Ada Data General Information</h4>
                <p>Informasi umum distributor belum dilengkapi</p>
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
    return !!(
        props.data.company_profile ||
        props.data.company_overview ||
        props.data.services_offered ||
        props.data.target_markets ||
        props.data.key_personnel
    )
})

const servicesOffered = computed(() => {
    if (!props.data.services_offered) return []
    if (Array.isArray(props.data.services_offered)) {
        return props.data.services_offered
    }
    if (typeof props.data.services_offered === 'string') {
        return props.data.services_offered.split(',').map(s => ({ name: s.trim() }))
    }
    return []
})

const targetMarkets = computed(() => {
    if (!props.data.target_markets) return []
    if (Array.isArray(props.data.target_markets)) {
        return props.data.target_markets
    }
    if (typeof props.data.target_markets === 'string') {
        return props.data.target_markets.split(',').map(s => ({ name: s.trim() }))
    }
    return []
})

const keyPersonnel = computed(() => {
    if (!props.data.key_personnel) return []
    if (Array.isArray(props.data.key_personnel)) {
        return props.data.key_personnel
    }
    return []
})

function getServiceIcon(service) {
    const serviceName = (service.name || service || '').toLowerCase()
    const icons = {
        'distribution': 'fas fa-truck',
        'logistics': 'fas fa-shipping-fast',
        'warehousing': 'fas fa-warehouse',
        'supply chain': 'fas fa-link',
        'procurement': 'fas fa-shopping-cart',
        'inventory': 'fas fa-boxes',
        'consulting': 'fas fa-comments',
        'technical support': 'fas fa-tools',
        'maintenance': 'fas fa-wrench',
        'training': 'fas fa-graduation-cap'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (serviceName.includes(key)) return icon
    }
    return 'fas fa-cog'
}

function getMarketIcon(market) {
    const marketName = (market.name || market || '').toLowerCase()
    const icons = {
        'oil': 'fas fa-oil-can',
        'gas': 'fas fa-fire',
        'petrochemical': 'fas fa-industry',
        'manufacturing': 'fas fa-cogs',
        'automotive': 'fas fa-car',
        'construction': 'fas fa-hammer',
        'mining': 'fas fa-mountain',
        'power': 'fas fa-bolt',
        'marine': 'fas fa-ship',
        'aerospace': 'fas fa-plane'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (marketName.includes(key)) return icon
    }
    return 'fas fa-industry'
}

function getBusinessAge() {
    if (props.vendor.tanggal_berdiri) {
        const foundedYear = new Date(props.vendor.tanggal_berdiri).getFullYear()
        const currentYear = new Date().getFullYear()
        return currentYear - foundedYear
    }
    return '-'
}
</script>

<style scoped>
.general-info-card {
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
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
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

.content-sections {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.info-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.info-section h4 {
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

.info-section h4 i {
    color: #2563eb;
}

.profile-content,
.overview-content {
    padding: 24px;
}

.profile-content p,
.overview-content p {
    margin: 0;
    color: #374151;
    line-height: 1.8;
    font-size: 1rem;
    text-align: justify;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    padding: 24px;
}

.service-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.service-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.service-icon {
    width: 40px;
    height: 40px;
    background: #2563eb;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.service-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.service-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.4;
}

.markets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    padding: 24px;
}

.market-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-radius: 8px;
    border: 1px solid #93c5fd;
    transition: all 0.3s ease;
}

.market-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
}

.market-icon {
    width: 32px;
    height: 32px;
    background: #2563eb;
    color: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.market-item span {
    font-weight: 600;
    color: #1e40af;
    font-size: 0.875rem;
}

.personnel-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 24px;
}

.personnel-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.personnel-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.personnel-info {
    flex: 1;
}

.personnel-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.personnel-position {
    display: block;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 8px;
    font-style: italic;
}

.personnel-contact {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.contact-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #6b7280;
}

.contact-info i {
    width: 14px;
    text-align: center;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    border-radius: 8px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-label {
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
    .general-info-card {
        padding: 16px;
    }
    
    .services-grid,
    .markets-grid,
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .personnel-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>