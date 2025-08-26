<template>
    <div class="branch-offices-card">
        <div v-if="hasBranches" class="branches-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="header-info">
                    <h3>Branch Office Management</h3>
                    <p>Kantor cabang forwarder di Indonesia dan luar negeri</p>
                </div>
                <div class="branches-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ totalBranches }}</span>
                        <span class="stat-label">Total Offices</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Indonesian Branch Offices -->
                <div v-if="indonesianBranches.length > 0" class="branches-section">
                    <h4>
                        <i class="fas fa-flag"></i>
                        Indonesian Branch Offices
                    </h4>
                    <div class="branches-grid">
                        <div 
                            v-for="(branch, index) in indonesianBranches"
                            :key="`id-${index}`"
                            class="branch-item"
                        >
                            <div class="branch-header">
                                <div class="branch-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="branch-info">
                                    <h5>{{ getBranchName(branch, index, 'Indonesian') }}</h5>
                                    <span class="branch-location">{{ getBranchLocation(branch) }}</span>
                                </div>
                                <div class="branch-flag">
                                    <div class="flag-badge indonesia">
                                        <i class="fas fa-flag"></i>
                                        <span>ID</span>
                                    </div>
                                </div>
                            </div>

                            <div class="branch-details">
                                <!-- City & Address -->
                                <div class="address-section">
                                    <h6>Address Information:</h6>
                                    <div class="address-content">
                                        <div class="address-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <p>{{ branch.city_address || 'Address not specified' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="contact-section">
                                    <h6>Contact Information:</h6>
                                    <div class="contact-grid">
                                        <div v-if="branch.phone" class="contact-item">
                                            <label>Phone:</label>
                                            <span>{{ branch.phone }}</span>
                                        </div>
                                        <div v-if="branch.fax" class="contact-item">
                                            <label>Fax:</label>
                                            <span>{{ branch.fax }}</span>
                                        </div>
                                        <div v-if="branch.contact" class="contact-item full-width">
                                            <label>Contact Person:</label>
                                            <span>{{ branch.contact }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overseas Branch Offices -->
                <div v-if="overseasBranches.length > 0" class="branches-section">
                    <h4>
                        <i class="fas fa-globe"></i>
                        Overseas Branch Offices
                    </h4>
                    <div class="branches-grid">
                        <div 
                            v-for="(branch, index) in overseasBranches"
                            :key="`os-${index}`"
                            class="branch-item"
                        >
                            <div class="branch-header">
                                <div class="branch-icon overseas">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="branch-info">
                                    <h5>{{ getBranchName(branch, index, 'Overseas') }}</h5>
                                    <span class="branch-location">{{ getBranchLocation(branch) }}</span>
                                </div>
                                <div class="branch-flag">
                                    <div class="flag-badge overseas">
                                        <i class="fas fa-globe"></i>
                                        <span>INT</span>
                                    </div>
                                </div>
                            </div>

                            <div class="branch-details">
                                <!-- City & Address -->
                                <div class="address-section">
                                    <h6>Address Information:</h6>
                                    <div class="address-content">
                                        <div class="address-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <p>{{ branch.city_address || 'Address not specified' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="contact-section">
                                    <h6>Contact Information:</h6>
                                    <div class="contact-grid">
                                        <div v-if="branch.phone" class="contact-item">
                                            <label>Phone:</label>
                                            <span>{{ branch.phone }}</span>
                                        </div>
                                        <div v-if="branch.fax" class="contact-item">
                                            <label>Fax:</label>
                                            <span>{{ branch.fax }}</span>
                                        </div>
                                        <div v-if="branch.contact" class="contact-item full-width">
                                            <label>Contact Person:</label>
                                            <span>{{ branch.contact }}</span>
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
                <i class="fas fa-map-marker-alt"></i>
                <h4>Belum Ada Data Branch Offices</h4>
                <p>Data kantor cabang belum dikonfigurasi untuk forwarder ini</p>
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

// Computed properties sesuai dengan ForwarderBranchOffice.vue
const indonesianBranches = computed(() => {
    if (!props.data.indonesian_branch_offices) return []
    return Array.isArray(props.data.indonesian_branch_offices) 
        ? props.data.indonesian_branch_offices.filter(branch => branch && (branch.city_address || branch.phone || branch.fax || branch.contact))
        : []
})

const overseasBranches = computed(() => {
    if (!props.data.overseas_branch_offices) return []
    return Array.isArray(props.data.overseas_branch_offices) 
        ? props.data.overseas_branch_offices.filter(branch => branch && (branch.city_address || branch.phone || branch.fax || branch.contact))
        : []
})

const totalBranches = computed(() => {
    return indonesianBranches.value.length + overseasBranches.value.length
})

const hasBranches = computed(() => {
    return totalBranches.value > 0
})

// Helper functions sesuai dengan struktur data ForwarderBranchOffice
function getBranchName(branch, index, type) {
    // Coba ekstrak nama dari city_address jika ada
    if (branch.city_address) {
        const addressParts = branch.city_address.split('-')
        if (addressParts.length > 0) {
            const firstPart = addressParts[0].trim()
            if (firstPart) {
                return `${type} Branch - ${firstPart}`
            }
        }
    }
    return `${type} Branch ${index + 1}`
}

function getBranchLocation(branch) {
    if (branch.city_address) {
        // Ekstrak bagian pertama sebelum tanda '-' atau ambil 50 karakter pertama
        const addressParts = branch.city_address.split('-')
        if (addressParts.length > 0) {
            return addressParts[0].trim()
        }
        return branch.city_address.length > 50 
            ? branch.city_address.substring(0, 50) + '...'
            : branch.city_address
    }
    return 'Location not specified'
}

function getCountryFromAddress(cityAddress) {
    if (!cityAddress) return 'International'
    
    // Coba ekstrak negara dari alamat (biasanya di awal atau akhir)
    const addressParts = cityAddress.split('-')
    if (addressParts.length > 0) {
        const firstPart = addressParts[0].trim()
        // Jika bagian pertama terlihat seperti nama negara atau kota
        if (firstPart.length < 30) {
            return firstPart
        }
    }
    return 'International'
}

function getContactMethodsCount(branch) {
    let count = 0
    if (branch.phone) count++
    if (branch.fax) count++
    if (branch.contact) count++
    return count
}

function getCompletionPercentage() {
    if (totalBranches.value === 0) return 0
    
    let totalFields = 0
    let filledFields = 0
    
    // Hitung untuk Indonesian branches
    indonesianBranches.value.forEach(branch => {
        totalFields += 4 // city_address, phone, fax, contact
        if (branch.city_address) filledFields++
        if (branch.phone) filledFields++
        if (branch.fax) filledFields++
        if (branch.contact) filledFields++
    })
    
    // Hitung untuk Overseas branches
    overseasBranches.value.forEach(branch => {
        totalFields += 4 // city_address, phone, fax, contact
        if (branch.city_address) filledFields++
        if (branch.phone) filledFields++
        if (branch.fax) filledFields++
        if (branch.contact) filledFields++
    })
    
    return totalFields > 0 ? Math.round((filledFields / totalFields) * 100) : 0
}

function getDistributionPercentage(type) {
    if (totalBranches.value === 0) return 0
    
    if (type === 'indonesian') {
        return Math.round((indonesianBranches.value.length / totalBranches.value) * 100)
    } else if (type === 'overseas') {
        return Math.round((overseasBranches.value.length / totalBranches.value) * 100)
    }
    return 0
}
</script>

<style scoped>
.branch-offices-card {
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

.branches-stats {
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

.branches-section,
.branches-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.branches-section h4,
.branches-statistics h4 {
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

.branches-section h4 i {
    color: #7c3aed;
}

.branches-statistics h4 i {
    color: #f59e0b;
}

.branches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    padding: 24px;
}

.branch-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.branch-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.branch-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.branch-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.branch-icon.overseas {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.branch-info {
    flex: 1;
}

.branch-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.branch-location {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.branch-flag {
    flex-shrink: 0;
}

.flag-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.flag-badge.indonesia {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
}

.flag-badge.overseas {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
}

.branch-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.address-section,
.contact-section,
.branch-summary {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.address-section h6,
.contact-section h6,
.branch-summary h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.address-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.address-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.address-item i {
    color: #7c3aed;
    font-size: 1.125rem;
    margin-top: 2px;
    flex-shrink: 0;
}

.address-item p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.contact-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.contact-item.full-width {
    grid-column: 1 / -1;
}

.contact-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.contact-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

.summary-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.summary-label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.summary-value {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.statistics-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
}

.statistic-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border-radius: 8px;
}

.statistic-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.statistic-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.statistic-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.statistic-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

.distribution-summary {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.distribution-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.distribution-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.distribution-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.distribution-label {
    flex: 0 0 140px;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.distribution-bar {
    flex: 1;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.distribution-fill {
    height: 100%;
    background: linear-gradient(90deg, #dc2626 0%, #b91c1c 100%);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.distribution-fill.overseas {
    background: linear-gradient(90deg, #059669 0%, #047857 100%);
}

.distribution-value {
    flex: 0 0 80px;
    text-align: right;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
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
    .branch-offices-card {
        padding: 16px;
    }
    
    .branches-grid,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .branch-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
    }
    
    .distribution-item {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    
    .distribution-label {
        flex: none;
        text-align: center;
    }
    
    .distribution-value {
        flex: none;
        text-align: center;
    }
}
</style>