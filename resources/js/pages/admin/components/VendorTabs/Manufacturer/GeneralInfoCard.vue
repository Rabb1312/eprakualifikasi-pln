<template>
    <div class="manufacture-general-info">
        <div class="section-header">
            <div class="header-icon">
                <i class="fas fa-industry"></i>
            </div>
            <div class="header-info">
                <h3>General Information</h3>
                <p>Basic information about your company from vendor registration</p>
            </div>
        </div>

        <div class="info-sections">
            <!-- Company Identity -->
            <div class="info-section">
                <h4>
                    <i class="fas fa-id-card"></i>
                    Identitas Perusahaan
                </h4>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nomor Vendor:</label>
                        <span class="vendor-number">{{ vendor.nomor_vendor || '-' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Tipe Perusahaan:</label>
                        <span class="type-badge mf">Manufacture</span>
                    </div>
                    <div class="info-item full-width">
                        <label>Nama Perusahaan:</label>
                        <span class="company-name">{{ vendor.nama_perusahaan || '-' }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.npwp">
                        <label>NPWP:</label>
                        <span>{{ vendor.npwp }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.tanggal_berdiri">
                        <label>Tanggal Berdiri:</label>
                        <span>{{ formatDate(vendor.tanggal_berdiri) }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.tanggal_beroperasi">
                        <label>Tanggal Mulai Beroperasi:</label>
                        <span>{{ formatDate(vendor.tanggal_beroperasi) }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="info-section" v-if="vendor.phone || vendor.website">
                <h4>
                    <i class="fas fa-phone"></i>
                    Kontak Perusahaan
                </h4>
                <div class="info-grid">
                    <div class="info-item" v-if="vendor.phone">
                        <label>Nomor Telepon:</label>
                        <span>{{ vendor.phone }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.website">
                        <label>Website:</label>
                        <a :href="vendor.website" target="_blank" class="website-link">
                            {{ vendor.website }} <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="info-section" v-if="vendor.alamat || vendor.alamat_kantor_operasional || vendor.kode_pos">
                <h4>
                    <i class="fas fa-map-marker-alt"></i>
                    Alamat
                </h4>
                <div class="info-grid">
                    <div class="info-item full-width" v-if="vendor.alamat">
                        <label>Alamat:</label>
                        <span class="text-content">{{ vendor.alamat }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.kode_pos">
                        <label>Kode Pos:</label>
                        <span>{{ vendor.kode_pos }}</span>
                    </div>
                    <div class="info-item full-width" v-if="vendor.alamat_kantor_operasional">
                        <label>Alamat Kantor Operasional:</label>
                        <span class="text-content">{{ vendor.alamat_kantor_operasional }}</span>
                    </div>
                </div>
            </div>

            <!-- Parent Corporation -->
            <div class="info-section" v-if="vendor.nama_perusahaan_induk || vendor.alamat_perusahaan_induk">
                <h4>
                    <i class="fas fa-building"></i>
                    Parent Corporation
                </h4>
                <div class="info-grid">
                    <div class="info-item full-width" v-if="vendor.nama_perusahaan_induk">
                        <label>Parent Corporation Name / Nama Perusahaan Induk:</label>
                        <span class="company-name">{{ vendor.nama_perusahaan_induk }}</span>
                    </div>
                    <div class="info-item full-width" v-if="vendor.alamat_perusahaan_induk">
                        <label>Parent Corporation Address / Alamat Perusahaan Induk:</label>
                        <span class="text-content">{{ vendor.alamat_perusahaan_induk }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Person -->
            <div class="info-section" v-if="vendor.contact_person && vendor.contact_person.length > 0">
                <h4>
                    <i class="fas fa-user-tie"></i>
                    Contact Person
                </h4>
                <div class="contacts-list">
                    <div 
                        v-for="(contact, index) in vendor.contact_person" 
                        :key="index"
                        class="contact-card"
                    >
                        <div class="contact-header">
                            <div class="contact-name">{{ contact.nama || 'Nama tidak tersedia' }}</div>
                            <div class="contact-position" v-if="contact.jabatan">{{ contact.jabatan }}</div>
                        </div>
                        <div class="contact-details">
                            <div v-if="contact.telepon" class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ contact.telepon }}</span>
                            </div>
                            <div v-if="contact.email" class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ contact.email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Level Management -->
            <div class="info-section" v-if="vendor.top_level && vendor.top_level.length > 0">
                <h4>
                    <i class="fas fa-users-cog"></i>
                    Top Level Management
                </h4>
                <div class="contacts-list">
                    <div 
                        v-for="(person, index) in vendor.top_level" 
                        :key="index"
                        class="contact-card management-card"
                    >
                        <div class="contact-header">
                            <div class="contact-name">{{ person.nama || 'Nama tidak tersedia' }}</div>
                            <div class="contact-position" v-if="person.jabatan">{{ person.jabatan }}</div>
                        </div>
                        <div class="contact-details">
                            <div v-if="person.telepon" class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ person.telepon }}</span>
                            </div>
                            <div v-if="person.email" class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ person.email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Level Management -->
            <div class="info-section" v-if="vendor.mid_level && vendor.mid_level.length > 0">
                <h4>
                    <i class="fas fa-users"></i>
                    Middle Level Management
                </h4>
                <div class="contacts-list">
                    <div 
                        v-for="(person, index) in vendor.mid_level" 
                        :key="index"
                        class="contact-card management-card"
                    >
                        <div class="contact-header">
                            <div class="contact-name">{{ person.nama || 'Nama tidak tersedia' }}</div>
                            <div class="contact-position" v-if="person.jabatan">{{ person.jabatan }}</div>
                        </div>
                        <div class="contact-details">
                            <div v-if="person.telepon" class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ person.telepon }}</span>
                            </div>
                            <div v-if="person.email" class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ person.email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales/Marketing -->
            <div class="info-section" v-if="vendor.sales_marketing && vendor.sales_marketing.length > 0">
                <h4>
                    <i class="fas fa-chart-line"></i>
                    Sales/Marketing
                </h4>
                <div class="contacts-list">
                    <div 
                        v-for="(person, index) in vendor.sales_marketing" 
                        :key="index"
                        class="contact-card marketing-card"
                    >
                        <div class="contact-header">
                            <div class="contact-name">{{ person.nama || 'Nama tidak tersedia' }}</div>
                            <div class="contact-position" v-if="person.jabatan">{{ person.jabatan }}</div>
                        </div>
                        <div class="contact-details">
                            <div v-if="person.telepon" class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ person.telepon }}</span>
                            </div>
                            <div v-if="person.email" class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ person.email }}</span>
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

            <!-- Status Information -->
            <div class="info-section">
                <h4>
                    <i class="fas fa-chart-pie"></i>
                    Status Vendor
                </h4>
                <div class="status-grid">
                    <div class="status-card">
                        <div class="status-header">
                            <i class="fas fa-percentage"></i>
                            <span>Kelengkapan Profil</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div 
                                    class="progress-fill"
                                    :style="{ width: (vendor.completion_percentage || 0) + '%' }"
                                ></div>
                            </div>
                            <span class="progress-text">{{ vendor.completion_percentage || 0 }}%</span>
                        </div>
                    </div>
                    <div class="status-card">
                        <div class="status-header">
                            <i class="fas fa-check-circle"></i>
                            <span>Status Verifikasi</span>
                        </div>
                        <div class="verification-status">
                            <span v-if="vendor.verified_at" class="verified">
                                <i class="fas fa-check-circle"></i>
                                Terverifikasi
                            </span>
                            <span v-else class="unverified">
                                <i class="fas fa-clock"></i>
                                Belum Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    data: {
        type: Object,
        default: () => ({})
    },
    vendor: {
        type: Object,
        required: true
    }
})

// Management Data Computed
const contactPersonCount = computed(() => {
    return props.vendor.contact_person ? props.vendor.contact_person.length : 0
})

const topLevelCount = computed(() => {
    return props.vendor.top_level ? props.vendor.top_level.length : 0
})

const midLevelCount = computed(() => {
    return props.vendor.mid_level ? props.vendor.mid_level.length : 0
})

const salesMarketingCount = computed(() => {
    return props.vendor.sales_marketing ? props.vendor.sales_marketing.length : 0
})

const hasManagementData = computed(() => {
    return contactPersonCount.value > 0 || topLevelCount.value > 0 || midLevelCount.value > 0 || salesMarketingCount.value > 0
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

// Helper Functions
function formatDate(dateString) {
    if (!dateString) return "-"
    const date = new Date(dateString)
    return date.toLocaleDateString("id-ID", { 
        day: "2-digit", 
        month: "long", 
        year: "numeric" 
    })
}

function getSpecialtyIcon(specialty) {
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

function getSpecialtyName(specialty, index) {
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

function getSpecialtyDescription(specialty) {
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
</script>

<style scoped>
/* Styles sama seperti sebelumnya - tidak ada perubahan dalam CSS */
.manufacture-general-info {
    padding: 24px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    margin-bottom: 24px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 16px;
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
}

.header-info h3 {
    margin: 0;
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 700;
}

.header-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.info-sections {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-section h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 12px 0;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.info-item span,
.info-item a {
    font-weight: 500;
    color: #1f2937;
    font-size: 0.95rem;
    text-decoration: none;
    line-height: 1.4;
}

.vendor-number {
    background: #f3e8ff;
    color: #7c3aed;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 700;
    font-family: monospace;
    display: inline-block;
}

.company-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 1.1rem;
}

.text-content {
    text-align: justify;
    line-height: 1.6;
}

.website-link {
    color: #2563eb;
}

.website-link:hover {
    text-decoration: underline;
    color: #1d4ed8;
}

.type-badge.mf {
    background: #f3e8ff;
    color: #7c3aed;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.875rem;
    display: inline-block;
    text-transform: uppercase;
}

/* Contact Cards */
.contacts-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    margin-top: 8px;
}

.contact-card {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    transition: all 0.2s ease;
}

.contact-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #7c3aed;
}

.management-card {
    border-left: 4px solid #7c3aed;
}

.marketing-card {
    border-left: 4px solid #f59e0b;
}

.contact-header {
    margin-bottom: 12px;
}

.contact-name {
    font-weight: 700;
    color: #1f2937;
    font-size: 1.1rem;
    margin-bottom: 4px;
}

.contact-position {
    color: #7c3aed;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-size: 0.875rem;
}

.contact-item i {
    width: 16px;
    color: #9ca3af;
}

/* Manufacturing Specialties */
.specialties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    margin-top: 8px;
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

.experience-badge {
    background: #f3e8ff;
    color: #7c3aed;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Company Overview */
.overview-content {
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-top: 8px;
}

.stat-card {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    padding: 16px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-label {
    font-size: 0.75rem;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

/* Status Grid */
.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-top: 8px;
}

.status-card {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.status-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #374151;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress-bar {
    flex: 1;
    height: 10px;
    background: #f3f4f6;
    border-radius: 5px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #7c3aed, #a855f7);
    border-radius: 5px;
    transition: width 0.5s ease;
}

.progress-text {
    font-weight: 700;
    color: #7c3aed;
    min-width: 45px;
    text-align: right;
    font-size: 0.875rem;
}

.verification-status {
    display: flex;
    align-items: center;
    gap: 8px;
}

.verified {
    color: #059669;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

.unverified {
    color: #d97706;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

@media (max-width: 768px) {
    .manufacture-general-info {
        padding: 16px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .contacts-list {
        grid-template-columns: 1fr;
    }
    
    .specialties-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
}
</style>