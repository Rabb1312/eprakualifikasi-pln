<template>
    <div class="general-info-card">
        <div v-if="hasData" class="general-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="header-info">
                    <h3>General Information</h3>
                    <p>Informasi umum dan profil lengkap manufacturer</p>
                </div>
            </div>

            <div class="content-sections">
                <!-- Company Basic Info -->
                <div class="info-section">
                    <h4>
                        <i class="fas fa-building"></i>
                        Company Information
                    </h4>
                    <div class="company-info">
                        <div class="info-item">
                            <label>Company Name:</label>
                            <span>{{ vendor?.nama_perusahaan || 'N/A' }}</span>
                        </div>
                        <div class="info-item" v-if="vendor?.alamat">
                            <label>Address:</label>
                            <span>{{ vendor.alamat }}</span>
                        </div>
                        <div class="info-item" v-if="vendor?.tanggal_berdiri">
                            <label>Established:</label>
                            <span>{{ formatEstablishedYear(vendor.tanggal_berdiri) }}</span>
                        </div>
                        <div class="info-item" v-if="vendor?.telepon">
                            <label>Phone:</label>
                            <span>{{ vendor.telepon }}</span>
                        </div>
                        <div class="info-item" v-if="vendor?.email">
                            <label>Email:</label>
                            <span>{{ vendor.email }}</span>
                        </div>
                        <div class="info-item" v-if="vendor?.website">
                            <label>Website:</label>
                            <span>{{ vendor.website }}</span>
                        </div>
                        <div class="info-item" v-if="vendor?.status">
                            <label>Status:</label>
                            <span :class="['status-indicator', getStatusClass(vendor.status)]">
                                {{ vendor.status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Manufacturing Specialties -->
                <div class="info-section" v-if="manufacturingSpecialties && manufacturingSpecialties.length > 0">
                    <h4>
                        <i class="fas fa-industry"></i>
                        Manufacturing Specialties
                    </h4>
                    <div class="specialties-grid">
                        <div 
                            v-for="(specialty, index) in manufacturingSpecialties"
                            :key="`specialty-${index}`"
                            class="specialty-item"
                        >
                            <div class="specialty-icon">
                                <i :class="getSpecialtyIcon(specialty)"></i>
                            </div>
                            <div class="specialty-info">
                                <h5>{{ getSpecialtyName(specialty, index) }}</h5>
                                <p v-if="getSpecialtyDescription(specialty)">{{ getSpecialtyDescription(specialty) }}</p>
                                <div class="specialty-experience" v-if="specialty?.years_experience">
                                    <span class="experience-badge">{{ specialty.years_experience }} years experience</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target Markets -->
                <div class="info-section" v-if="targetMarkets && targetMarkets.length > 0">
                    <h4>
                        <i class="fas fa-target"></i>
                        Target Markets
                    </h4>
                    <div class="markets-grid">
                        <div 
                            v-for="(market, index) in targetMarkets"
                            :key="`market-${index}`"
                            class="market-item"
                        >
                            <div class="market-icon">
                                <i :class="getMarketIcon(market)"></i>
                            </div>
                            <span>{{ getMarketName(market, index) }}</span>
                            <div class="market-share" v-if="market?.market_share">
                                <span class="share-badge">{{ market.market_share }}% share</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Personnel -->
                <div class="info-section" v-if="keyPersonnel && keyPersonnel.length > 0">
                    <h4>
                        <i class="fas fa-users"></i>
                        Key Personnel
                    </h4>
                    <div class="personnel-list">
                        <div 
                            v-for="(person, index) in keyPersonnel"
                            :key="`person-${index}`"
                            class="personnel-item"
                        >
                            <div class="personnel-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="personnel-info">
                                <h5>{{ getPersonName(person) }}</h5>
                                <span class="personnel-position">{{ getPersonPosition(person) }}</span>
                                <div class="personnel-department" v-if="person?.department">
                                    <span class="department-tag">{{ person.department }}</span>
                                </div>
                                <div class="personnel-contact" v-if="person?.email || person?.phone">
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

                <!-- Company Overview/Profile (if exists) -->
                <div class="info-section" v-if="data?.company_profile || data?.company_overview">
                    <h4>
                        <i class="fas fa-eye"></i>
                        Company Overview
                    </h4>
                    <div class="overview-content">
                        <div class="overview-text">
                            <p>{{ data.company_profile || data.company_overview }}</p>
                        </div>
                    </div>
                </div>

                <!-- Manufacturing Statistics -->
                <div class="info-section">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Manufacturing Statistics
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
                                <i class="fas fa-industry"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ manufacturingSpecialties?.length || 0 }}</span>
                                <span class="stat-label">Product Types</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ targetMarkets?.length || 0 }}</span>
                                <span class="stat-label">Markets</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ keyPersonnel?.length || 0 }}</span>
                                <span class="stat-label">Key Personnel</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ getTotalPlants() }}</span>
                                <span class="stat-label">Plants</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ getTotalServices() }}</span>
                                <span class="stat-label">Services</span>
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
                <p>Informasi umum manufacturer belum dilengkapi</p>
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

// Helper Functions untuk safe string conversion
const safeString = (value) => {
    if (value === null || value === undefined) return ''
    if (typeof value === 'string') return value
    if (typeof value === 'object' && value.name) return String(value.name)
    if (typeof value === 'object' && value.type) return String(value.type)
    return String(value)
}

const formatEstablishedYear = (dateString) => {
    if (!dateString) return 'N/A'
    try {
        return new Date(dateString).getFullYear()
    } catch (error) {
        console.error('Error parsing date:', error)
        return 'N/A'
    }
}

const getStatusClass = (status) => {
    const statusLower = safeString(status).toLowerCase()
    if (statusLower.includes('active') || statusLower.includes('approved')) return 'active'
    if (statusLower.includes('pending')) return 'pending'
    if (statusLower.includes('inactive') || statusLower.includes('suspended')) return 'inactive'
    return 'unknown'
}

// FIX: Robust specialty icon function
const getSpecialtyIcon = (specialty) => {
    try {
        let specialtyName = ''
        
        if (specialty === null || specialty === undefined) {
            specialtyName = ''
        } else if (typeof specialty === 'string') {
            specialtyName = specialty
        } else if (typeof specialty === 'object') {
            specialtyName = specialty.name || specialty.type || String(specialty)
        } else {
            specialtyName = String(specialty)
        }
        
        const name = specialtyName.toLowerCase()
        
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
            'components': 'fas fa-puzzle-piece',
            'medical': 'fas fa-heartbeat',
            'aerospace': 'fas fa-plane',
            'energy': 'fas fa-bolt'
        }
        
        for (const [key, icon] of Object.entries(icons)) {
            if (name.includes(key)) return icon
        }
        
        return 'fas fa-industry'
    } catch (error) {
        console.error('Error in getSpecialtyIcon:', error, specialty)
        return 'fas fa-industry'
    }
}

// FIX: Safe specialty name function
const getSpecialtyName = (specialty, index) => {
    try {
        if (specialty === null || specialty === undefined) {
            return `Specialty ${index + 1}`
        }
        if (typeof specialty === 'string') {
            return specialty
        }
        if (typeof specialty === 'object') {
            return specialty.name || specialty.type || `Specialty ${index + 1}`
        }
        return String(specialty)
    } catch (error) {
        console.error('Error in getSpecialtyName:', error, specialty)
        return `Specialty ${index + 1}`
    }
}

// FIX: Safe specialty description function
const getSpecialtyDescription = (specialty) => {
    try {
        if (specialty && typeof specialty === 'object' && specialty.description) {
            return specialty.description
        }
        return null
    } catch (error) {
        console.error('Error in getSpecialtyDescription:', error, specialty)
        return null
    }
}

const getMarketIcon = (market) => {
    try {
        let marketName = ''
        
        if (market === null || market === undefined) {
            marketName = ''
        } else if (typeof market === 'string') {
            marketName = market
        } else if (typeof market === 'object') {
            marketName = market.name || String(market)
        } else {
            marketName = String(market)
        }
        
        const name = marketName.toLowerCase()
        
        const icons = {
            'domestic': 'fas fa-home',
            'international': 'fas fa-globe',
            'export': 'fas fa-shipping-fast',
            'local': 'fas fa-map-marker-alt',
            'regional': 'fas fa-map',
            'global': 'fas fa-globe-americas',
            'national': 'fas fa-flag',
            'asian': 'fas fa-globe-asia',
            'european': 'fas fa-globe-europe',
            'american': 'fas fa-globe-americas'
        }
        
        for (const [key, icon] of Object.entries(icons)) {
            if (name.includes(key)) return icon
        }
        
        return 'fas fa-target'
    } catch (error) {
        console.error('Error in getMarketIcon:', error, market)
        return 'fas fa-target'
    }
}

const getMarketName = (market, index) => {
    try {
        if (market === null || market === undefined) {
            return `Market ${index + 1}`
        }
        if (typeof market === 'string') {
            return market
        }
        if (typeof market === 'object') {
            return market.name || `Market ${index + 1}`
        }
        return String(market)
    } catch (error) {
        console.error('Error in getMarketName:', error, market)
        return `Market ${index + 1}`
    }
}

const getPersonName = (person) => {
    try {
        if (person === null || person === undefined) {
            return 'Unknown'
        }
        if (typeof person === 'string') {
            return person
        }
        if (typeof person === 'object') {
            return person.name || 'Unknown'
        }
        return String(person)
    } catch (error) {
        console.error('Error in getPersonName:', error, person)
        return 'Unknown'
    }
}

const getPersonPosition = (person) => {
    try {
        if (person === null || person === undefined) {
            return 'Position not specified'
        }
        if (typeof person === 'object') {
            return person.position || person.role || 'Position not specified'
        }
        return 'Position not specified'
    } catch (error) {
        console.error('Error in getPersonPosition:', error, person)
        return 'Position not specified'
    }
}

const getBusinessAge = () => {
    try {
        if (props.vendor?.tanggal_berdiri) {
            const foundedYear = new Date(props.vendor.tanggal_berdiri).getFullYear()
            const currentYear = new Date().getFullYear()
            return currentYear - foundedYear
        }
        return '-'
    } catch (error) {
        console.error('Error calculating business age:', error)
        return '-'
    }
}

const getTotalPlants = () => {
    try {
        if (!props.data?.plants) return 0
        if (Array.isArray(props.data.plants)) return props.data.plants.length
        if (typeof props.data.plants === 'string') {
            const parsed = JSON.parse(props.data.plants)
            return Array.isArray(parsed) ? parsed.length : 1
        }
        if (typeof props.data.plants === 'object') {
            return Object.keys(props.data.plants).length
        }
    } catch (error) {
        console.error('Error calculating total plants:', error)
    }
    return 0
}

const getTotalServices = () => {
    try {
        if (!props.data?.after_sales) return 0
        if (Array.isArray(props.data.after_sales)) return props.data.after_sales.length
        if (typeof props.data.after_sales === 'string') {
            const parsed = JSON.parse(props.data.after_sales)
            return Array.isArray(parsed) ? parsed.length : 1
        }
        if (typeof props.data.after_sales === 'object') {
            return Object.keys(props.data.after_sales).length
        }
    } catch (error) {
        console.error('Error calculating total services:', error)
    }
    return 0
}

// Computed Properties
const hasData = computed(() => {
    return !!(
        props.vendor?.nama_perusahaan ||
        props.data?.product_types ||
        props.data?.personnel ||
        props.data?.plants ||
        props.data?.company_profile ||
        props.data?.company_overview
    )
})

const manufacturingSpecialties = computed(() => {
    if (!props.data?.product_types) return []
    
    try {
        // Handle JSON field
        if (Array.isArray(props.data.product_types)) {
            return props.data.product_types
        }
        if (typeof props.data.product_types === 'string') {
            try {
                const parsed = JSON.parse(props.data.product_types)
                return Array.isArray(parsed) ? parsed : [parsed]
            } catch {
                return props.data.product_types.split(',').map(s => ({ name: s.trim() }))
            }
        }
        if (typeof props.data.product_types === 'object') {
            return Object.entries(props.data.product_types).map(([key, value]) => ({
                name: key,
                description: typeof value === 'string' ? value : JSON.stringify(value)
            }))
        }
    } catch (error) {
        console.error('Error processing manufacturing specialties:', error)
    }
    
    return []
})

const targetMarkets = computed(() => {
    try {
        // Check if vendor has target_markets field
        if (props.vendor?.target_markets) {
            return Array.isArray(props.vendor.target_markets) ? props.vendor.target_markets : []
        }
        
        // Create default market based on location
        if (props.vendor?.alamat) {
            const markets = [{ name: 'Domestic Market', location: props.vendor.alamat }]
            
            // Add international if company seems established
            const businessAge = getBusinessAge()
            if (businessAge > 5) {
                markets.push({ name: 'International Market' })
            }
            
            return markets
        }
        
        return []
    } catch (error) {
        console.error('Error processing target markets:', error)
        return []
    }
})

const keyPersonnel = computed(() => {
    if (!props.data?.personnel) return []
    
    try {
        // Handle JSON field
        if (Array.isArray(props.data.personnel)) {
            return props.data.personnel.slice(0, 5) // Show max 5 key personnel
        }
        if (typeof props.data.personnel === 'string') {
            try {
                const parsed = JSON.parse(props.data.personnel)
                return Array.isArray(parsed) ? parsed.slice(0, 5) : [parsed]
            } catch {
                return []
            }
        }
        if (typeof props.data.personnel === 'object') {
            return Object.entries(props.data.personnel).slice(0, 5).map(([key, value]) => ({
                position: key,
                name: typeof value === 'string' ? value : value?.name || 'Unknown',
                ...value
            }))
        }
    } catch (error) {
        console.error('Error processing key personnel:', error)
    }
    
    return []
})
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
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
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
    color: #7c3aed;
}

.company-info {
    padding: 24px;
    background: #f8fafc;
    border-radius: 0;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.875rem;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    color: #6b7280;
    font-weight: 600;
    min-width: 120px;
}

.info-item span {
    color: #1f2937;
    font-weight: 500;
    text-align: right;
}

.status-indicator {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.status-indicator.active {
    background: #d1fae5;
    color: #065f46;
}

.status-indicator.pending {
    background: #fef3c7;
    color: #92400e;
}

.status-indicator.inactive {
    background: #fef2f2;
    color: #991b1b;
}

.status-indicator.unknown {
    background: #f3f4f6;
    color: #6b7280;
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

.specialties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    padding: 24px;
}

.specialty-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.specialty-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.specialty-icon {
    width: 48px;
    height: 48px;
    background: #7c3aed;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.specialty-info h5 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.specialty-info p {
    margin: 0 0 8px 0;
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.4;
}

.specialty-experience {
    margin-top: 8px;
}

.experience-badge {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
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
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 8px;
    border: 1px solid #d1d5db;
    transition: all 0.3s ease;
    position: relative;
}

.market-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.15);
}

.market-icon {
    width: 32px;
    height: 32px;
    background: #7c3aed;
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
    color: #374151;
    font-size: 0.875rem;
    flex: 1;
}

.market-share {
    flex-shrink: 0;
}

.share-badge {
    background: #7c3aed;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
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

.personnel-department {
    margin-bottom: 8px;
}

.department-tag {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    padding: 2px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
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
    
    .specialties-grid,
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
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
    
    .info-item label {
        min-width: auto;
    }
    
    .info-item span {
        text-align: left;
    }
}
</style>