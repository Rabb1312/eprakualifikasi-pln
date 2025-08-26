<template>
    <div class="manpower-support-card">
        <div v-if="hasData" class="manpower-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="header-info">
                    <h3>Manpower Support</h3>
                    <p>Dukungan tenaga kerja dan sertifikasi yang dimiliki forwarder</p>
                </div>
                <div class="manpower-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ getTotalManpower() }}</span>
                        <span class="stat-label">Total Staff</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Manpower Support Info -->
                <div v-if="data.manpower_support" class="support-overview">
                    <h4>
                        <i class="fas fa-user-tie"></i>
                        Manpower Support Information
                    </h4>
                    <div class="support-content">
                        <div class="support-item">
                            <div class="support-header">
                                <div class="support-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="support-info">
                                    <h5>Support Details</h5>
                                    <span class="support-type">{{ data.manpower_support }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manpower Certifications -->
                <div v-if="manpowerCertifications.length > 0" class="certifications-section">
                    <h4>
                        <i class="fas fa-certificate"></i>
                        Staff Certifications & Qualifications
                    </h4>
                    <div class="certifications-grid">
                        <div 
                            v-for="(cert, index) in manpowerCertifications"
                            :key="index"
                            class="certification-item"
                        >
                            <div class="certification-header">
                                <div class="certification-icon">
                                    <i :class="getCertificationIcon(cert.title)"></i>
                                </div>
                                <div class="certification-info">
                                    <h5>{{ cert.title || 'Staff Category' }}</h5>
                                    <span class="certification-category">Professional Certification</span>
                                </div>
                                <div class="certification-status">
                                    <span :class="['status-badge', getStaffStatus(cert)]">
                                        <i class="fas fa-check-circle"></i>
                                        {{ getStaffStatusText(cert) }}
                                    </span>
                                </div>
                            </div>

                            <div class="certification-details">
                                <div class="staff-numbers">
                                    <div class="staff-grid">
                                        <div class="staff-count">
                                            <div class="count-icon">
                                                <i class="fas fa-user-clock"></i>
                                            </div>
                                            <div class="count-info">
                                                <span class="count-number">{{ cert.number_full_time || 0 }}</span>
                                                <span class="count-label">Full Time</span>
                                            </div>
                                        </div>
                                        <div class="staff-count">
                                            <div class="count-icon">
                                                <i class="fas fa-user-friends"></i>
                                            </div>
                                            <div class="count-info">
                                                <span class="count-number">{{ cert.number_part_time || 0 }}</span>
                                                <span class="count-label">Part Time</span>
                                            </div>
                                        </div>
                                        <div class="staff-count total">
                                            <div class="count-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div class="count-info">
                                                <span class="count-number">{{ getTotalStaff(cert) }}</span>
                                                <span class="count-label">Total Staff</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="cert.applicable_certification" class="certification-details-info">
                                    <h6>Applicable Certifications:</h6>
                                    <div class="certification-text">
                                        <p>{{ cert.applicable_certification }}</p>
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
                <i class="fas fa-users"></i>
                <h4>Belum Ada Data Manpower Support</h4>
                <p>Data dukungan tenaga kerja belum dilengkapi untuk forwarder ini</p>
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

const manpowerCertifications = computed(() => {
    if (!props.data.manpower_certifications) return []
    return Array.isArray(props.data.manpower_certifications) 
        ? props.data.manpower_certifications 
        : []
})

const hasData = computed(() => {
    return !!(
        props.data.manpower_support ||
        manpowerCertifications.value.length > 0
    )
})

function getCertificationIcon(title) {
    const titleLower = (title || '').toLowerCase()
    const icons = {
        'staff': 'fas fa-user',
        'custom clearance': 'fas fa-file-invoice',
        'ppjk': 'fas fa-file-invoice',
        'dangerous goods': 'fas fa-exclamation-triangle',
        'cranide code': 'fas fa-skull-crossbones',
        'hse': 'fas fa-hard-hat',
        'advisors': 'fas fa-user-graduate',
        'others': 'fas fa-plus-circle'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (titleLower.includes(key)) return icon
    }
    return 'fas fa-certificate'
}

function getTotalStaff(cert) {
    return (parseInt(cert.number_full_time) || 0) + (parseInt(cert.number_part_time) || 0)
}

function getStaffStatus(cert) {
    const total = getTotalStaff(cert)
    if (total > 0) return 'active'
    return 'inactive'
}

function getStaffStatusText(cert) {
    const total = getTotalStaff(cert)
    if (total > 0) return 'Available'
    return 'No Staff'
}

function getTotalManpower() {
    return manpowerCertifications.value.reduce((total, cert) => {
        return total + getTotalStaff(cert)
    }, 0)
}

function getTotalFullTime() {
    return manpowerCertifications.value.reduce((total, cert) => {
        return total + (parseInt(cert.number_full_time) || 0)
    }, 0)
}

function getTotalPartTime() {
    return manpowerCertifications.value.reduce((total, cert) => {
        return total + (parseInt(cert.number_part_time) || 0)
    }, 0)
}

function getCertifiedStaffCount() {
    return manpowerCertifications.value.filter(cert => {
        return cert.applicable_certification && cert.applicable_certification.trim().length > 0
    }).reduce((total, cert) => {
        return total + getTotalStaff(cert)
    }, 0)
}

function getStaffCategories() {
    return manpowerCertifications.value.map(cert => ({
        name: cert.title || 'Unknown',
        count: getTotalStaff(cert),
        fullTime: parseInt(cert.number_full_time) || 0,
        partTime: parseInt(cert.number_part_time) || 0
    })).filter(cat => cat.count > 0)
}

function getCategoryPercentage(count) {
    const total = getTotalManpower()
    if (total === 0) return 0
    return Math.round((count / total) * 100)
}
</script>

<style scoped>
.manpower-support-card {
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
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

.manpower-stats {
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

.support-overview,
.certifications-section,
.categories-summary,
.manpower-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.support-overview h4,
.certifications-section h4,
.categories-summary h4,
.manpower-statistics h4 {
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

.support-overview h4 i {
    color: #10b981;
}

.certifications-section h4 i {
    color: #3b82f6;
}

.categories-summary h4 i {
    color: #f59e0b;
}

.manpower-statistics h4 i {
    color: #8b5cf6;
}

.support-content {
    padding: 24px;
}

.support-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
}

.support-header {
    display: flex;
    align-items: center;
    gap: 16px;
}

.support-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.support-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.support-type {
    font-size: 0.875rem;
    color: #6b7280;
}

.certifications-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 24px;
}

.certification-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.certification-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.certification-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.certification-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.certification-info {
    flex: 1;
}

.certification-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.certification-category {
    font-size: 0.875rem;
    color: #6b7280;
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

.status-badge.inactive {
    background: #fef2f2;
    color: #991b1b;
}

.certification-details {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.staff-count {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
}

.staff-count.total {
    border-color: #10b981;
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
}

.count-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    color: #6b7280;
}

.staff-count.total .count-icon {
    color: #10b981;
}

.count-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.count-number {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}

.staff-count.total .count-number {
    color: #10b981;
}

.count-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.certification-details-info {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    padding: 16px;
}

.certification-details-info h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.certification-text p {
    margin: 0;
    color: #6b7280;
    line-height: 1.5;
    font-size: 0.875rem;
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
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
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
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 4px;
}

.category-breakdown {
    display: flex;
    gap: 8px;
}

.breakdown-item {
    font-size: 0.75rem;
    color: #f59e0b;
    font-weight: 600;
}

.category-percentage {
    flex-shrink: 0;
}

.percentage-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.percentage-value {
    font-size: 0.875rem;
    font-weight: 700;
    color: white;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
}

.statistic-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
    .manpower-support-card {
        padding: 16px;
    }
    
    .certifications-grid,
    .categories-grid,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .certification-header,
    .category-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .staff-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>