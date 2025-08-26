<template>
    <div class="forwarder-general-info">
        <div class="section-header">
            <div class="header-icon">
                <i class="fas fa-ship"></i>
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
                        <span class="type-badge fwd">Forwarder</span>
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

            <!-- Fleet Information -->
            <div class="info-section" v-if="vendor.kepemilikan || vendor.kepemilikan_armada">
                <h4>
                    <i class="fas fa-truck"></i>
                    Fleet Information / Informasi Armada
                </h4>
                <div class="fleet-info">
                    <div class="fleet-item" v-if="vendor.kepemilikan">
                        <div class="fleet-label">
                            <i class="fas fa-info-circle"></i>
                            In possession / Kepemilikan:
                        </div>
                        <div class="fleet-value">
                            <span class="fleet-badge" :class="getFleetPossessionClass(vendor.kepemilikan)">
                                <i :class="getFleetPossessionIcon(vendor.kepemilikan)"></i>
                                {{ getFleetPossessionText(vendor.kepemilikan) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="fleet-item" v-if="vendor.kepemilikan_armada && vendor.kepemilikan !== 'tidak'">
                        <div class="fleet-label">
                            <i class="fas fa-handshake"></i>
                            Fleet Ownership status / Status kepemilikan armada:
                        </div>
                        <div class="fleet-value">
                            <span class="fleet-badge" :class="getFleetOwnershipClass(vendor.kepemilikan_armada)">
                                <i :class="getFleetOwnershipIcon(vendor.kepemilikan_armada)"></i>
                                {{ getFleetOwnershipText(vendor.kepemilikan_armada) }}
                            </span>
                        </div>
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

            <!-- Management Summary -->
            <div class="info-section" v-if="hasManagementData">
                <h4>
                    <i class="fas fa-chart-bar"></i>
                    Ringkasan Tim
                </h4>
                <div class="stats-grid">
                    <div class="stat-card" v-if="contactPersonCount > 0">
                        <div class="stat-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ contactPersonCount }}</span>
                            <span class="stat-label">Contact Person</span>
                        </div>
                    </div>
                    <div class="stat-card" v-if="topLevelCount > 0">
                        <div class="stat-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ topLevelCount }}</span>
                            <span class="stat-label">Top Level</span>
                        </div>
                    </div>
                    <div class="stat-card" v-if="midLevelCount > 0">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ midLevelCount }}</span>
                            <span class="stat-label">Middle Level</span>
                        </div>
                    </div>
                    <div class="stat-card" v-if="salesMarketingCount > 0">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ salesMarketingCount }}</span>
                            <span class="stat-label">Sales/Marketing</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forwarder Specific Info (if data exists) -->
            <div class="info-section" v-if="hasForwarderData">
                <h4>
                    <i class="fas fa-ship"></i>
                    Informasi Forwarder
                </h4>
                <div class="info-grid">
                    <div class="info-item full-width" v-if="data.heavy_lift_capability">
                        <label>Heavy Lift Capability:</label>
                        <span>{{ data.heavy_lift_capability }}</span>
                    </div>
                    <div class="info-item full-width" v-if="data.monitoring_management">
                        <label>Monitoring Management:</label>
                        <span>{{ data.monitoring_management }}</span>
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

const hasForwarderData = computed(() => {
    return !!(
        props.data.heavy_lift_capability ||
        props.data.monitoring_management ||
        props.data.communication_system ||
        props.data.project_execution_procedure
    )
})

// Fleet Information Functions
function getFleetPossessionText(value) {
    const mapping = {
        'tidak': 'None / Tidak',
        'satu': 'One / Satu',
        'lebih dari satu': 'More than one / Lebih dari satu'
    }
    return mapping[value] || value
}

function getFleetPossessionClass(value) {
    const mapping = {
        'tidak': 'fleet-none',
        'satu': 'fleet-one',
        'lebih dari satu': 'fleet-multiple'
    }
    return mapping[value] || 'fleet-default'
}

function getFleetPossessionIcon(value) {
    const mapping = {
        'tidak': 'fas fa-times-circle',
        'satu': 'fas fa-truck',
        'lebih dari satu': 'fas fa-shipping-fast'
    }
    return mapping[value] || 'fas fa-truck'
}

function getFleetOwnershipText(value) {
    const mapping = {
        'sewa': 'On rent / Sewa',
        'miliki pribadi': 'Own property / Miliki pribadi'
    }
    return mapping[value] || value
}

function getFleetOwnershipClass(value) {
    const mapping = {
        'sewa': 'fleet-rent',
        'miliki pribadi': 'fleet-own'
    }
    return mapping[value] || 'fleet-default'
}

function getFleetOwnershipIcon(value) {
    const mapping = {
        'sewa': 'fas fa-handshake',
        'miliki pribadi': 'fas fa-home'
    }
    return mapping[value] || 'fas fa-truck'
}

function formatDate(dateString) {
    if (!dateString) return "-"
    const date = new Date(dateString)
    return date.toLocaleDateString("id-ID", { 
        day: "2-digit", 
        month: "long", 
        year: "numeric" 
    })
}
</script>

<style scoped>
.forwarder-general-info {
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
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
    background: #fef2f2;
    color: #991b1b;
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

.type-badge.fwd {
    background: #fef2f2;
    color: #991b1b;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.875rem;
    display: inline-block;
    text-transform: uppercase;
}

/* Fleet Information Styles */
.fleet-info {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-top: 8px;
}

.fleet-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
}

.fleet-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.fleet-value {
    margin-left: 24px;
}

.fleet-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
}

.fleet-badge.fleet-none {
    background: #fef2f2;
    color: #991b1b;
}

.fleet-badge.fleet-one {
    background: #fff7ed;
    color: #c2410c;
}

.fleet-badge.fleet-multiple {
    background: #ecfdf5;
    color: #065f46;
}

.fleet-badge.fleet-rent {
    background: #eff6ff;
    color: #1e40af;
}

.fleet-badge.fleet-own {
    background: #f0fdf4;
    color: #166534;
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
    border-color: #dc2626;
}

.management-card {
    border-left: 4px solid #dc2626;
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
    color: #dc2626;
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
    background: linear-gradient(90deg, #dc2626, #ef4444);
    border-radius: 5px;
    transition: width 0.5s ease;
}

.progress-text {
    font-weight: 700;
    color: #dc2626;
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
    .forwarder-general-info {
        padding: 16px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .contacts-list {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .status-grid {
        grid-template-columns: 1fr;
    }
    
    .fleet-info {
        gap: 12px;
    }
}
</style>