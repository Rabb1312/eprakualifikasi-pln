<template>
    <div class="certifications-card">
        <div v-if="hasCertifications" class="certifications-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="header-info">
                    <h3>Certifications</h3>
                    <p>Sertifikasi dan standar yang dimiliki oleh manufacturer</p>
                </div>
                <div class="certifications-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ certifications.length }}</span>
                        <span class="stat-label">Total</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ activeCertifications }}</span>
                        <span class="stat-label">Active</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ expiringCertifications }}</span>
                        <span class="stat-label">Expiring</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Certifications Filter -->
                <div class="certifications-filter">
                    <div class="filter-buttons">
                        <button
                            v-for="filter in filterOptions"
                            :key="filter.key"
                            @click="activeFilter = filter.key"
                            :class="['filter-btn', { active: activeFilter === filter.key }]"
                        >
                            <i :class="filter.icon"></i>
                            {{ filter.label }}
                            <span class="filter-count">{{ getFilterCount(filter.key) }}</span>
                        </button>
                    </div>
                </div>

                <!-- Certifications Grid -->
                <div class="certifications-overview">
                    <h4>
                        <i class="fas fa-award"></i>
                        Certification Details
                        <span class="cert-count">({{ filteredCertifications.length }} certifications)</span>
                    </h4>
                    <div class="certifications-grid">
                        <div 
                            v-for="(cert, index) in filteredCertifications"
                            :key="index"
                            class="certification-item"
                        >
                            <div class="certification-header">
                                <div class="certification-badge">
                                    <i :class="getCertificationIcon(cert.type)"></i>
                                </div>
                                <div class="certification-info">
                                    <h5>{{ cert.name || cert.certification_name }}</h5>
                                    <span class="certification-type">{{ cert.type || 'Standard Certification' }}</span>
                                    <div class="certification-number" v-if="cert.certificate_number">
                                        <span class="cert-number">{{ cert.certificate_number }}</span>
                                    </div>
                                </div>
                                <div class="certification-status">
                                    <span :class="['status-badge', getCertificationStatus(cert)]">
                                        <i :class="getStatusIcon(cert)"></i>
                                        {{ getStatusText(cert) }}
                                    </span>
                                </div>
                            </div>

                            <div class="certification-details">
                                <div class="cert-metadata">
                                    <div class="metadata-grid">
                                        <div class="metadata-item" v-if="cert.issuing_body">
                                            <label>Issuing Body:</label>
                                            <span>{{ cert.issuing_body }}</span>
                                        </div>
                                        <div class="metadata-item" v-if="cert.issue_date">
                                            <label>Issue Date:</label>
                                            <span>{{ formatDate(cert.issue_date) }}</span>
                                        </div>
                                        <div class="metadata-item" v-if="cert.expiry_date">
                                            <label>Expiry Date:</label>
                                            <span :class="{ 'expired': isExpired(cert.expiry_date), 'expiring': isExpiring(cert.expiry_date) }">
                                                {{ formatDate(cert.expiry_date) }}
                                            </span>
                                        </div>
                                        <div class="metadata-item" v-if="cert.scope">
                                            <label>Scope:</label>
                                            <span>{{ cert.scope }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="cert-description" v-if="cert.description">
                                    <p>{{ cert.description }}</p>
                                </div>

                                <div class="cert-requirements" v-if="cert.requirements && cert.requirements.length > 0">
                                    <h6>Key Requirements:</h6>
                                    <div class="requirements-list">
                                        <div 
                                            v-for="(requirement, reqIndex) in cert.requirements"
                                            :key="reqIndex"
                                            class="requirement-item"
                                        >
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ requirement }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="cert-benefits" v-if="cert.benefits && cert.benefits.length > 0">
                                    <h6>Benefits:</h6>
                                    <div class="benefits-tags">
                                        <span 
                                            v-for="(benefit, benefitIndex) in cert.benefits"
                                            :key="benefitIndex"
                                            class="benefit-tag"
                                        >
                                            {{ benefit }}
                                        </span>
                                    </div>
                                </div>

                                <div class="cert-validity" v-if="cert.expiry_date">
                                    <div class="validity-bar">
                                        <div class="validity-info">
                                            <span>Validity Period</span>
                                            <span class="validity-percentage">{{ getValidityPercentage(cert) }}% remaining</span>
                                        </div>
                                        <div class="validity-progress">
                                            <div 
                                                class="validity-fill"
                                                :style="{ width: getValidityPercentage(cert) + '%' }"
                                                :class="getValidityClass(cert)"
                                            ></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cert-actions" v-if="cert.certificate_file || cert.audit_report">
                                    <button 
                                        v-if="cert.certificate_file"
                                        class="action-btn primary"
                                        @click="viewCertificate(cert)"
                                    >
                                        <i class="fas fa-eye"></i>
                                        View Certificate
                                    </button>
                                    <button 
                                        v-if="cert.audit_report"
                                        class="action-btn secondary"
                                        @click="viewAuditReport(cert)"
                                    >
                                        <i class="fas fa-file-alt"></i>
                                        Audit Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State for Filter -->
                    <div v-if="filteredCertifications.length === 0" class="empty-filter">
                        <div class="empty-filter-illustration">
                            <i class="fas fa-filter"></i>
                            <h4>No Certifications Found</h4>
                            <p>No certifications match the selected filter criteria</p>
                        </div>
                    </div>
                </div>

                <!-- Certification Types Summary -->
                <div class="certification-types">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Certification Types
                    </h4>
                    <div class="types-grid">
                        <div 
                            v-for="(type, index) in certificationTypes"
                            :key="index"
                            class="type-item"
                        >
                            <div class="type-icon">
                                <i :class="getCertificationIcon(type.name)"></i>
                            </div>
                            <div class="type-info">
                                <h6>{{ type.name }}</h6>
                                <span class="type-count">{{ type.count }} certifications</span>
                            </div>
                            <div class="type-percentage">
                                <span class="percentage-value">{{ getTypePercentage(type.count) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certification Timeline -->
                <div class="certification-timeline">
                    <h4>
                        <i class="fas fa-calendar-alt"></i>
                        Certification Timeline
                    </h4>
                    <div class="timeline-content">
                        <div class="timeline-stats">
                            <div class="timeline-stat">
                                <span class="stat-label">Next Expiry:</span>
                                <span class="stat-value">{{ getNextExpiry() }}</span>
                            </div>
                            <div class="timeline-stat">
                                <span class="stat-label">Avg. Validity:</span>
                                <span class="stat-value">{{ getAverageValidity() }} years</span>
                            </div>
                            <div class="timeline-stat">
                                <span class="stat-label">Renewal Rate:</span>
                                <span class="stat-value">{{ getRenewalRate() }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-certificate"></i>
                <h4>Belum Ada Data Certifications</h4>
                <p>Data sertifikasi belum dilengkapi untuk manufacturer ini</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

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

const emit = defineEmits(['view-certificate', 'view-audit-report'])

const activeFilter = ref('all')

const filterOptions = [
    { key: 'all', label: 'All', icon: 'fas fa-list' },
    { key: 'active', label: 'Active', icon: 'fas fa-check' },
    { key: 'expiring', label: 'Expiring Soon', icon: 'fas fa-exclamation-triangle' },
    { key: 'expired', label: 'Expired', icon: 'fas fa-times' }
]

const hasCertifications = computed(() => {
    return !!(props.data.certifications && Array.isArray(props.data.certifications) && props.data.certifications.length > 0)
})

const certifications = computed(() => {
    if (!props.data.certifications) return []
    return Array.isArray(props.data.certifications) ? props.data.certifications : []
})

const filteredCertifications = computed(() => {
    if (activeFilter.value === 'all') {
        return certifications.value
    }
    
    return certifications.value.filter(cert => {
        switch (activeFilter.value) {
            case 'active':
                return !isExpired(cert.expiry_date)
            case 'expiring':
                return isExpiring(cert.expiry_date)
            case 'expired':
                return isExpired(cert.expiry_date)
            default:
                return true
        }
    })
})

const activeCertifications = computed(() => {
    return certifications.value.filter(cert => !isExpired(cert.expiry_date)).length
})

const expiringCertifications = computed(() => {
    return certifications.value.filter(cert => isExpiring(cert.expiry_date)).length
})

const certificationTypes = computed(() => {
    if (certifications.value.length === 0) return []
    
    const types = {}
    certifications.value.forEach(cert => {
        const type = cert.type || 'General'
        if (!types[type]) {
            types[type] = { name: type, count: 0 }
        }
        types[type].count++
    })
    
    return Object.values(types).sort((a, b) => b.count - a.count)
})

function getFilterCount(filterKey) {
    if (filterKey === 'all') return certifications.value.length
    if (filterKey === 'active') return activeCertifications.value
    if (filterKey === 'expiring') return expiringCertifications.value
    if (filterKey === 'expired') return certifications.value.filter(cert => isExpired(cert.expiry_date)).length
    return 0
}

function getCertificationIcon(type) {
    const icons = {
        'iso': 'fas fa-globe',
        'quality': 'fas fa-check-double',
        'environmental': 'fas fa-leaf',
        'safety': 'fas fa-shield-alt',
        'security': 'fas fa-lock',
        'industry_specific': 'fas fa-industry',
        'product_certification': 'fas fa-box',
        'management_system': 'fas fa-cogs'
    }
    return icons[type?.toLowerCase()] || 'fas fa-certificate'
}

function getCertificationStatus(cert) {
    if (isExpired(cert.expiry_date)) return 'expired'
    if (isExpiring(cert.expiry_date)) return 'expiring'
    return 'active'
}

function getStatusIcon(cert) {
    const status = getCertificationStatus(cert)
    const icons = {
        'active': 'fas fa-check-circle',
        'expiring': 'fas fa-exclamation-triangle',
        'expired': 'fas fa-times-circle'
    }
    return icons[status] || 'fas fa-check-circle'
}

function getStatusText(cert) {
    const status = getCertificationStatus(cert)
    const texts = {
        'active': 'Active',
        'expiring': 'Expiring Soon',
        'expired': 'Expired'
    }
    return texts[status] || 'Active'
}

function isExpired(dateString) {
    if (!dateString) return false
    return new Date(dateString) < new Date()
}

function isExpiring(dateString) {
    if (!dateString) return false
    const expiryDate = new Date(dateString)
    const now = new Date()
    const sixMonthsFromNow = new Date()
    sixMonthsFromNow.setMonth(now.getMonth() + 6)
    
    return expiryDate > now && expiryDate <= sixMonthsFromNow
}

function getValidityPercentage(cert) {
    if (!cert.issue_date || !cert.expiry_date) return 100
    
    const issueDate = new Date(cert.issue_date)
    const expiryDate = new Date(cert.expiry_date)
    const now = new Date()
    
    const totalDuration = expiryDate.getTime() - issueDate.getTime()
    const remainingDuration = expiryDate.getTime() - now.getTime()
    
    if (remainingDuration <= 0) return 0
    
    const percentage = (remainingDuration / totalDuration) * 100
    return Math.max(0, Math.min(100, Math.round(percentage)))
}

function getValidityClass(cert) {
    const percentage = getValidityPercentage(cert)
    if (percentage <= 20) return 'critical'
    if (percentage <= 50) return 'warning'
    return 'good'
}

function getTypePercentage(count) {
    if (certifications.value.length === 0) return 0
    return Math.round((count / certifications.value.length) * 100)
}

function getNextExpiry() {
    const activeCerts = certifications.value.filter(cert => !isExpired(cert.expiry_date))
    if (activeCerts.length === 0) return 'N/A'
    
    const nextExpiry = activeCerts
        .map(cert => new Date(cert.expiry_date))
        .sort((a, b) => a - b)[0]
    
    return formatDate(nextExpiry.toISOString())
}

function getAverageValidity() {
    const validityPeriods = certifications.value
        .filter(cert => cert.issue_date && cert.expiry_date)
        .map(cert => {
            const issue = new Date(cert.issue_date)
            const expiry = new Date(cert.expiry_date)
            return (expiry.getTime() - issue.getTime()) / (1000 * 60 * 60 * 24 * 365)
        })
    
    if (validityPeriods.length === 0) return 0
    
    const average = validityPeriods.reduce((sum, period) => sum + period, 0) / validityPeriods.length
    return Math.round(average)
}

function getRenewalRate() {
    // Mock calculation - in real app, this would be based on historical data
    return 95
}

function formatDate(dateString) {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    })
}

function viewCertificate(cert) {
    emit('view-certificate', cert)
}

function viewAuditReport(cert) {
    emit('view-audit-report', cert)
}
</script>

<style scoped>
.certifications-card {
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

.certifications-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.content-sections {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.certifications-filter {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px 24px;
}

.filter-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-btn {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.filter-btn.active {
    background: #1f2937;
    color: white;
    border-color: #1f2937;
}

.filter-count {
    background: rgba(0, 0, 0, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.filter-btn.active .filter-count {
    background: rgba(255, 255, 255, 0.2);
}

.certifications-overview,
.certification-types,
.certification-timeline {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.certifications-overview h4,
.certification-types h4,
.certification-timeline h4 {
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

.certifications-overview h4 i {
    color: #3b82f6;
}

.certification-types h4 i {
    color: #8b5cf6;
}

.certification-timeline h4 i {
    color: #10b981;
}

.cert-count {
    margin-left: auto;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.certifications-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    padding: 24px;
}

.certification-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.certification-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.certification-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.certification-badge {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.certification-info {
    flex: 1;
    min-width: 0;
}

.certification-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
    line-height: 1.3;
}

.certification-type {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-bottom: 8px;
}

.certification-number {
    margin-top: 4px;
}

.cert-number {
    background: #e5e7eb;
    color: #374151;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    font-family: monospace;
}

.certification-status {
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

.status-badge.expiring {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.expired {
    background: #fef2f2;
    color: #991b1b;
}

.certification-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cert-metadata {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.metadata-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.metadata-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
}

.metadata-item:last-child {
    border-bottom: none;
}

.metadata-item label {
    color: #6b7280;
    font-weight: 500;
}

.metadata-item span {
    color: #1f2937;
    font-weight: 600;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

.metadata-item span.expired {
    color: #dc2626;
    font-weight: 700;
}

.metadata-item span.expiring {
    color: #f59e0b;
    font-weight: 700;
}

.cert-description {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.cert-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.cert-requirements {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.cert-requirements h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.requirements-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.requirement-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.requirement-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.cert-benefits {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.cert-benefits h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.benefits-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.benefit-tag {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.cert-validity {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.validity-bar {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.validity-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.validity-info span:first-child {
    color: #374151;
    font-weight: 500;
}

.validity-percentage {
    color: #6b7280;
    font-weight: 600;
}

.validity-progress {
    width: 100%;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.validity-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
}

.validity-fill.good {
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}

.validity-fill.warning {
    background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
}

.validity-fill.critical {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}

.cert-actions {
    display: flex;
    gap: 12px;
}

.action-btn {
    flex: 1;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.action-btn.primary {
    background: #3b82f6;
    color: white;
}

.action-btn.secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.action-btn.primary:hover {
    background: #2563eb;
}

.action-btn.secondary:hover {
    background: #e5e7eb;
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
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
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
    color: #8b5cf6;
    font-size: 1.125rem;
}

.timeline-content {
    padding: 24px;
}

.timeline-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.timeline-stat {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 16px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 8px;
    text-align: center;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.8;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
}

.empty-filter,
.no-data {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 300px;
    text-align: center;
}

.empty-filter-illustration,
.no-data-illustration {
    max-width: 400px;
}

.empty-filter-illustration i,
.no-data-illustration i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-filter-illustration h4,
.no-data-illustration h4 {
    margin: 0 0 12px 0;
    color: #6b7280;
    font-size: 1.25rem;
    font-weight: 600;
}

.empty-filter-illustration p,
.no-data-illustration p {
    margin: 0;
    color: #9ca3af;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .certifications-card {
        padding: 16px;
    }
    
    .certifications-grid,
    .types-grid,
    .timeline-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .certifications-stats {
        justify-content: center;
    }
    
    .filter-buttons {
        justify-content: center;
    }
    
    .certification-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .metadata-grid {
        grid-template-columns: 1fr;
    }
    
    .cert-actions {
        flex-direction: column;
    }
}
</style>