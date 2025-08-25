<template>
    <div class="insurance-card">
        <div v-if="hasData" class="insurance-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="header-info">
                    <h3>Insurance</h3>
                    <p>Layanan asuransi dan perlindungan barang yang disediakan forwarder</p>
                </div>
                <div class="insurance-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ insuranceTypes.length }}</span>
                        <span class="stat-label">Types</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Insurance Types -->
                <div class="insurance-overview">
                    <h4>
                        <i class="fas fa-umbrella"></i>
                        Insurance Coverage Types
                    </h4>
                    <div class="insurance-grid">
                        <div 
                            v-for="(insurance, index) in insuranceTypes"
                            :key="index"
                            class="insurance-item"
                        >
                            <div class="insurance-header">
                                <div class="insurance-icon">
                                    <i :class="getInsuranceIcon(insurance.type)"></i>
                                </div>
                                <div class="insurance-info">
                                    <h5>{{ insurance.name || insurance.type || 'Insurance Coverage' }}</h5>
                                    <span class="insurance-category">{{ insurance.category || 'General Coverage' }}</span>
                                </div>
                                <div class="insurance-status">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Available
                                    </span>
                                </div>
                            </div>

                            <div class="insurance-details">
                                <div class="coverage-info">
                                    <div class="coverage-grid">
                                        <div class="coverage-item" v-if="insurance.coverage_limit">
                                            <label>Coverage Limit:</label>
                                            <span class="coverage-value">{{ insurance.coverage_limit }}</span>
                                        </div>
                                        <div class="coverage-item" v-if="insurance.premium_rate">
                                            <label>Premium Rate:</label>
                                            <span class="premium-value">{{ insurance.premium_rate }}</span>
                                        </div>
                                        <div class="coverage-item" v-if="insurance.deductible">
                                            <label>Deductible:</label>
                                            <span>{{ insurance.deductible }}</span>
                                        </div>
                                        <div class="coverage-item" v-if="insurance.validity_period">
                                            <label>Validity Period:</label>
                                            <span>{{ insurance.validity_period }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="insurance-description" v-if="insurance.description">
                                    <p>{{ insurance.description }}</p>
                                </div>

                                <div class="covered-risks" v-if="insurance.covered_risks && insurance.covered_risks.length > 0">
                                    <h6>Covered Risks:</h6>
                                    <div class="risks-list">
                                        <div 
                                            v-for="(risk, riskIndex) in insurance.covered_risks"
                                            :key="riskIndex"
                                            class="risk-item"
                                        >
                                            <i class="fas fa-shield-check"></i>
                                            <span>{{ risk }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="exclusions" v-if="insurance.exclusions && insurance.exclusions.length > 0">
                                    <h6>Exclusions:</h6>
                                    <div class="exclusions-list">
                                        <div 
                                            v-for="(exclusion, exclusionIndex) in insurance.exclusions"
                                            :key="exclusionIndex"
                                            class="exclusion-item"
                                        >
                                            <i class="fas fa-times-circle"></i>
                                            <span>{{ exclusion }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insurance Partners -->
                <div class="insurance-partners" v-if="insurancePartners.length > 0">
                    <h4>
                        <i class="fas fa-handshake"></i>
                        Insurance Partners
                    </h4>
                    <div class="partners-grid">
                        <div 
                            v-for="(partner, index) in insurancePartners"
                            :key="index"
                            class="partner-item"
                        >
                            <div class="partner-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="partner-info">
                                <h6>{{ partner.name || 'Insurance Company' }}</h6>
                                <span class="partner-type">{{ partner.type || 'Insurance Provider' }}</span>
                                <div class="partner-rating" v-if="partner.rating">
                                    <div class="rating-stars">
                                        <i 
                                            v-for="star in 5"
                                            :key="star"
                                            :class="['fas fa-star', { active: star <= (partner.rating || 0) }]"
                                        ></i>
                                    </div>
                                    <span class="rating-text">{{ partner.rating }}/5</span>
                                </div>
                            </div>
                            <div class="partner-status" v-if="partner.status">
                                <span :class="['status-indicator', partner.status.toLowerCase()]">
                                    {{ partner.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Claims Process -->
                <div class="claims-process" v-if="claimsProcess">
                    <h4>
                        <i class="fas fa-clipboard-list"></i>
                        Claims Process
                    </h4>
                    <div class="process-content">
                        <div class="process-steps">
                            <div 
                                v-for="(step, index) in claimsSteps"
                                :key="index"
                                class="process-step"
                            >
                                <div class="step-number">
                                    <span>{{ index + 1 }}</span>
                                </div>
                                <div class="step-content">
                                    <h6>{{ step.title }}</h6>
                                    <p>{{ step.description }}</p>
                                    <div class="step-duration" v-if="step.duration">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ step.duration }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="claims-contact" v-if="claimsProcess.contact">
                            <div class="contact-header">
                                <h6>Claims Contact Information</h6>
                            </div>
                            <div class="contact-details">
                                <div class="contact-item" v-if="claimsProcess.contact.phone">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ claimsProcess.contact.phone }}</span>
                                </div>
                                <div class="contact-item" v-if="claimsProcess.contact.email">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ claimsProcess.contact.email }}</span>
                                </div>
                                <div class="contact-item" v-if="claimsProcess.contact.hours">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ claimsProcess.contact.hours }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insurance Statistics -->
                <div class="insurance-statistics">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Insurance Statistics
                    </h4>
                    <div class="statistics-grid">
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ insuranceTypes.length }}</span>
                                <span class="statistic-label">Coverage Types</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ insurancePartners.length }}</span>
                                <span class="statistic-label">Insurance Partners</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getMaxCoverage() }}</span>
                                <span class="statistic-label">Max Coverage</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getAveragePremium() }}</span>
                                <span class="statistic-label">Avg. Premium Rate</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-shield-alt"></i>
                <h4>Belum Ada Data Insurance</h4>
                <p>Data layanan asuransi belum dilengkapi untuk forwarder ini</p>
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
    return !!(props.data.insurance)
})

const insuranceTypes = computed(() => {
    if (!props.data.insurance?.types) return []
    return Array.isArray(props.data.insurance.types) 
        ? props.data.insurance.types 
        : []
})

const insurancePartners = computed(() => {
    if (!props.data.insurance?.partners) return []
    return Array.isArray(props.data.insurance.partners) 
        ? props.data.insurance.partners 
        : []
})

const claimsProcess = computed(() => {
    return props.data.insurance?.claims_process || null
})

const claimsSteps = computed(() => {
    if (!claimsProcess.value?.steps) return []
    return Array.isArray(claimsProcess.value.steps) 
        ? claimsProcess.value.steps 
        : []
})

function getInsuranceIcon(type) {
    const icons = {
        'cargo': 'fas fa-boxes',
        'marine': 'fas fa-ship',
        'transit': 'fas fa-truck',
        'warehouse': 'fas fa-warehouse',
        'liability': 'fas fa-balance-scale',
        'comprehensive': 'fas fa-shield-alt',
        'theft': 'fas fa-user-secret',
        'damage': 'fas fa-hammer'
    }
    return icons[type?.toLowerCase()] || 'fas fa-shield-alt'
}

function getMaxCoverage() {
    if (insuranceTypes.value.length === 0) return '-'
    
    const coverages = insuranceTypes.value
        .map(insurance => insurance.coverage_limit)
        .filter(coverage => coverage)
        .map(coverage => {
            const match = coverage.match(/[\d,]+/)
            return match ? parseFloat(match[0].replace(/,/g, '')) : 0
        })
        .filter(num => num > 0)
    
    if (coverages.length === 0) return '-'
    
    const max = Math.max(...coverages)
    if (max >= 1000000000) {
        return `${(max / 1000000000).toFixed(1)}B`
    } else if (max >= 1000000) {
        return `${(max / 1000000).toFixed(1)}M`
    } else if (max >= 1000) {
        return `${(max / 1000).toFixed(1)}K`
    }
    return max.toString()
}

function getAveragePremium() {
    if (insuranceTypes.value.length === 0) return '-'
    
    const premiums = insuranceTypes.value
        .map(insurance => insurance.premium_rate)
        .filter(premium => premium)
        .map(premium => {
            const match = premium.match(/[\d.]+/)
            return match ? parseFloat(match[0]) : 0
        })
        .filter(num => num > 0)
    
    if (premiums.length === 0) return '-'
    
    const average = premiums.reduce((sum, premium) => sum + premium, 0) / premiums.length
    return `${average.toFixed(2)}%`
}
</script>

<style scoped>
.insurance-card {
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
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

.insurance-stats {
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

.insurance-overview,
.insurance-partners,
.claims-process,
.insurance-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.insurance-overview h4,
.insurance-partners h4,
.claims-process h4,
.insurance-statistics h4 {
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

.insurance-overview h4 i {
    color: #f59e0b;
}

.insurance-partners h4 i {
    color: #10b981;
}

.claims-process h4 i {
    color: #3b82f6;
}

.insurance-statistics h4 i {
    color: #8b5cf6;
}

.insurance-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 24px;
}

.insurance-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.insurance-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.insurance-icon {
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

.insurance-info {
    flex: 1;
}

.insurance-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.insurance-category {
    font-size: 0.875rem;
    color: #6b7280;
}

.insurance-status {
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

.insurance-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.coverage-info {
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.coverage-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.coverage-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
}

.coverage-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.coverage-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.coverage-value {
    color: #059669 !important;
    font-weight: 700 !important;
}

.premium-value {
    color: #dc2626 !important;
    font-weight: 700 !important;
}

.insurance-description {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.insurance-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.covered-risks,
.exclusions {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.covered-risks h6,
.exclusions h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.risks-list,
.exclusions-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.risk-item,
.exclusion-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.risk-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.exclusion-item i {
    color: #ef4444;
    font-size: 0.75rem;
}

.partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    padding: 24px;
}

.partner-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.partner-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.partner-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.partner-info {
    flex: 1;
}

.partner-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.partner-type {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.partner-rating {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-stars i {
    color: #d1d5db;
    font-size: 0.875rem;
}

.rating-stars i.active {
    color: #fbbf24;
}

.rating-text {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 600;
}

.partner-status {
    flex-shrink: 0;
}

.status-indicator {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-indicator.active {
    background: #d1fae5;
    color: #065f46;
}

.status-indicator.preferred {
    background: #fef3c7;
    color: #92400e;
}

.process-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.process-steps {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.process-step {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.step-number {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content h6 {
    margin: 0 0 8px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.step-content p {
    margin: 0 0 8px 0;
    color: #6b7280;
    line-height: 1.5;
    font-size: 0.875rem;
}

.step-duration {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #3b82f6;
    font-weight: 600;
}

.claims-contact {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.contact-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
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
    font-size: 0.875rem;
    color: #374151;
}

.contact-item i {
    color: #3b82f6;
    width: 16px;
    text-align: center;
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
    .insurance-card {
        padding: 16px;
    }
    
    .insurance-grid,
    .partners-grid,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .insurance-header,
    .partner-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .coverage-grid {
        grid-template-columns: 1fr;
    }
    
    .process-step {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>