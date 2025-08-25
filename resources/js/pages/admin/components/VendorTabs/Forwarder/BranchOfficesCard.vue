<template>
    <div class="branch-offices-card">
        <div v-if="hasBranches" class="branches-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="header-info">
                    <h3>Branch Offices</h3>
                    <p>Kantor cabang dan perwakilan forwarder di berbagai lokasi</p>
                </div>
                <div class="branches-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ branchOffices.length }}</span>
                        <span class="stat-label">Branches</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Branches List -->
                <div class="branches-overview">
                    <h4>
                        <i class="fas fa-list"></i>
                        Branch Office Locations
                    </h4>
                    <div class="branches-grid">
                        <div 
                            v-for="(branch, index) in branchOffices"
                            :key="index"
                            class="branch-item"
                        >
                            <div class="branch-header">
                                <div class="branch-icon">
                                    <i :class="getBranchIcon(branch.type)"></i>
                                </div>
                                <div class="branch-info">
                                    <h5>{{ branch.name || `Branch ${index + 1}` }}</h5>
                                    <span class="branch-type">{{ getBranchTypeLabel(branch.type) }}</span>
                                </div>
                                <div class="branch-status">
                                    <span :class="['status-badge', getBranchStatus(branch)]">
                                        <i :class="getStatusIcon(branch.status)"></i>
                                        {{ getStatusText(branch.status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="branch-details">
                                <div class="address-section" v-if="branch.address">
                                    <div class="address-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="address-info">
                                        <h6>Address</h6>
                                        <p>{{ branch.address }}</p>
                                    </div>
                                </div>

                                <div class="contact-section">
                                    <div class="contact-grid">
                                        <div class="contact-item" v-if="branch.phone">
                                            <i class="fas fa-phone"></i>
                                            <span>{{ branch.phone }}</span>
                                        </div>
                                        <div class="contact-item" v-if="branch.email">
                                            <i class="fas fa-envelope"></i>
                                            <span>{{ branch.email }}</span>
                                        </div>
                                        <div class="contact-item" v-if="branch.fax">
                                            <i class="fas fa-fax"></i>
                                            <span>{{ branch.fax }}</span>
                                        </div>
                                        <div class="contact-item" v-if="branch.website">
                                            <i class="fas fa-globe"></i>
                                            <span>{{ branch.website }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="services-section" v-if="branch.services && branch.services.length > 0">
                                    <h6>Available Services:</h6>
                                    <div class="services-tags">
                                        <span 
                                            v-for="(service, serviceIndex) in branch.services"
                                            :key="serviceIndex"
                                            class="service-tag"
                                        >
                                            {{ service }}
                                        </span>
                                    </div>
                                </div>

                                <div class="manager-section" v-if="branch.manager">
                                    <div class="manager-info">
                                        <div class="manager-avatar">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="manager-details">
                                            <h6>Branch Manager</h6>
                                            <span class="manager-name">{{ branch.manager.name || 'Not specified' }}</span>
                                            <div class="manager-contact" v-if="branch.manager.phone || branch.manager.email">
                                                <span v-if="branch.manager.phone" class="contact-info">
                                                    <i class="fas fa-phone"></i>
                                                    {{ branch.manager.phone }}
                                                </span>
                                                <span v-if="branch.manager.email" class="contact-info">
                                                    <i class="fas fa-envelope"></i>
                                                    {{ branch.manager.email }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="operational-info" v-if="branch.operational_hours || branch.established_date">
                                    <div class="operational-grid">
                                        <div class="operational-item" v-if="branch.operational_hours">
                                            <label>Operational Hours:</label>
                                            <span>{{ branch.operational_hours }}</span>
                                        </div>
                                        <div class="operational-item" v-if="branch.established_date">
                                            <label>Established:</label>
                                            <span>{{ formatDate(branch.established_date) }}</span>
                                        </div>
                                        <div class="operational-item" v-if="branch.employee_count">
                                            <label>Employees:</label>
                                            <span>{{ branch.employee_count }} people</span>
                                        </div>
                                        <div class="operational-item" v-if="branch.area_coverage">
                                            <label>Coverage Area:</label>
                                            <span>{{ branch.area_coverage }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branch Types Summary -->
                <div class="branch-types" v-if="branchTypes.length > 0">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Branch Types Distribution
                    </h4>
                    <div class="types-grid">
                        <div 
                            v-for="(type, index) in branchTypes"
                            :key="index"
                            class="type-item"
                        >
                            <div class="type-icon">
                                <i :class="getBranchIcon(type.name)"></i>
                            </div>
                            <div class="type-info">
                                <h6>{{ getBranchTypeLabel(type.name) }}</h6>
                                <span class="type-count">{{ type.count }} branches</span>
                            </div>
                            <div class="type-percentage">
                                <span class="percentage-value">{{ getTypePercentage(type.count) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Geographic Coverage -->
                <div class="geographic-coverage">
                    <h4>
                        <i class="fas fa-globe"></i>
                        Geographic Coverage
                    </h4>
                    <div class="coverage-stats">
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-map"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getUniqueCities() }}</span>
                                <span class="coverage-label">Cities</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getUniqueProvinces() }}</span>
                                <span class="coverage-label">Provinces</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getTotalEmployees() }}</span>
                                <span class="coverage-label">Total Employees</span>
                            </div>
                        </div>
                        <div class="coverage-item">
                            <div class="coverage-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="coverage-info">
                                <span class="coverage-number">{{ getAverageAge() }}</span>
                                <span class="coverage-label">Avg. Age (Years)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-building"></i>
                <h4>Belum Ada Data Branch Offices</h4>
                <p>Data kantor cabang belum dilengkapi untuk forwarder ini</p>
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

const hasBranches = computed(() => {
    return !!(props.data.branch_offices && Array.isArray(props.data.branch_offices) && props.data.branch_offices.length > 0)
})

const branchOffices = computed(() => {
    if (!props.data.branch_offices) return []
    return Array.isArray(props.data.branch_offices) ? props.data.branch_offices : []
})

const branchTypes = computed(() => {
    if (branchOffices.value.length === 0) return []
    
    const types = {}
    branchOffices.value.forEach(branch => {
        const type = branch.type || 'main'
        if (!types[type]) {
            types[type] = { name: type, count: 0 }
        }
        types[type].count++
    })
    
    return Object.values(types).sort((a, b) => b.count - a.count)
})

function getBranchIcon(type) {
    const icons = {
        'main': 'fas fa-building',
        'branch': 'fas fa-store',
        'representative': 'fas fa-handshake',
        'warehouse': 'fas fa-warehouse',
        'office': 'fas fa-city',
        'depot': 'fas fa-industry',
        'terminal': 'fas fa-truck-loading'
    }
    return icons[type?.toLowerCase()] || 'fas fa-building'
}

function getBranchTypeLabel(type) {
    const labels = {
        'main': 'Main Office',
        'branch': 'Branch Office',
        'representative': 'Representative Office',
        'warehouse': 'Warehouse',
        'office': 'Regional Office',
        'depot': 'Depot',
        'terminal': 'Terminal'
    }
    return labels[type?.toLowerCase()] || 'Office'
}

function getBranchStatus(branch) {
    return branch.status?.toLowerCase() || 'active'
}

function getStatusIcon(status) {
    const icons = {
        'active': 'fas fa-check-circle',
        'inactive': 'fas fa-times-circle',
        'under_construction': 'fas fa-hard-hat',
        'planned': 'fas fa-clock'
    }
    return icons[status?.toLowerCase()] || 'fas fa-check-circle'
}

function getStatusText(status) {
    const texts = {
        'active': 'Active',
        'inactive': 'Inactive',
        'under_construction': 'Under Construction',
        'planned': 'Planned'
    }
    return texts[status?.toLowerCase()] || 'Active'
}

function getTypePercentage(count) {
    if (branchOffices.value.length === 0) return 0
    return Math.round((count / branchOffices.value.length) * 100)
}

function getUniqueCities() {
    const cities = new Set()
    branchOffices.value.forEach(branch => {
        if (branch.city) {
            cities.add(branch.city)
        } else if (branch.address) {
            // Try to extract city from address
            const addressParts = branch.address.split(',')
            if (addressParts.length > 1) {
                cities.add(addressParts[1].trim())
            }
        }
    })
    return cities.size || branchOffices.value.length
}

function getUniqueProvinces() {
    const provinces = new Set()
    branchOffices.value.forEach(branch => {
        if (branch.province) {
            provinces.add(branch.province)
        } else if (branch.address) {
            // Try to extract province from address
            const addressParts = branch.address.split(',')
            if (addressParts.length > 2) {
                provinces.add(addressParts[2].trim())
            }
        }
    })
    return provinces.size || Math.ceil(branchOffices.value.length / 2)
}

function getTotalEmployees() {
    return branchOffices.value.reduce((sum, branch) => {
        return sum + (parseInt(branch.employee_count) || 0)
    }, 0)
}

function getAverageAge() {
    const validDates = branchOffices.value.filter(branch => branch.established_date)
    if (validDates.length === 0) return '-'
    
    const currentYear = new Date().getFullYear()
    const totalAge = validDates.reduce((sum, branch) => {
        const establishedYear = new Date(branch.established_date).getFullYear()
        return sum + (currentYear - establishedYear)
    }, 0)
    
    return Math.round(totalAge / validDates.length)
}

function formatDate(dateString) {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
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

.branches-overview,
.branch-types,
.geographic-coverage {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.branches-overview h4,
.branch-types h4,
.geographic-coverage h4 {
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

.branches-overview h4 i {
    color: #3b82f6;
}

.branch-types h4 i {
    color: #f59e0b;
}

.geographic-coverage h4 i {
    color: #10b981;
}

.branches-grid {
    display: flex;
    flex-direction: column;
    gap: 24px;
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
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
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

.branch-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.branch-status {
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
    background: #fef2f2;
    color: #991b1b;
}

.status-badge.under_construction {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.planned {
    background: #e0e7ff;
    color: #3730a3;
}

.branch-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.address-section {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.address-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.address-info h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.address-info p {
    margin: 0;
    color: #6b7280;
    line-height: 1.5;
    font-size: 0.875rem;
}

.contact-section {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
    font-size: 0.875rem;
    color: #374151;
}

.contact-item i {
    color: #3b82f6;
    width: 16px;
    text-align: center;
}

.services-section {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.services-section h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.services-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.service-tag {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.manager-section {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.manager-info {
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.manager-avatar {
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

.manager-details h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.manager-name {
    display: block;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
    margin-bottom: 8px;
}

.manager-contact {
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

.operational-info {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.operational-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.operational-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
}

.operational-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.operational-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.type-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.type-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.type-icon {
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

.type-info {
    flex: 1;
}

.type-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.type-count {
    font-size: 0.875rem;
    color: #6b7280;
}

.type-percentage {
    flex-shrink: 0;
}

.percentage-value {
    font-weight: 700;
    color: #f59e0b;
    font-size: 1.125rem;
}

.coverage-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.coverage-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 8px;
}

.coverage-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.coverage-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.coverage-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.coverage-label {
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
    .branch-offices-card {
        padding: 16px;
    }
    
    .branches-grid,
    .types-grid,
    .coverage-stats {
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
    
    .contact-grid,
    .operational-grid {
        grid-template-columns: 1fr;
    }
    
    .address-section,
    .manager-info {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>