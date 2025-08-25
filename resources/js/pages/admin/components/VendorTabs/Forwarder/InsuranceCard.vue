<template>
    <div class="insurance-card">
        <div v-if="hasData" class="insurance-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="header-info">
                    <h3>Insurance Coverage</h3>
                    <p>Layanan asuransi dan perlindungan barang yang disediakan forwarder</p>
                </div>
                <div class="insurance-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ getInsuranceTypesCount() }}</span>
                        <span class="stat-label">Coverage Types</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Insurance Types -->
                <div class="insurance-types">
                    <h4>
                        <i class="fas fa-umbrella"></i>
                        Insurance Types Available
                    </h4>
                    <div class="types-grid">
                        <div class="type-item" :class="{ active: !data.insurance_none }">
                            <div class="type-header">
                                <div class="type-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="type-info">
                                    <h5>Insurance Availability</h5>
                                    <span class="type-status">{{ data.insurance_none ? 'No Insurance' : 'Insurance Available' }}</span>
                                </div>
                                <div class="type-indicator">
                                    <span :class="['status-badge', data.insurance_none ? 'unavailable' : 'available']">
                                        <i :class="data.insurance_none ? 'fas fa-times-circle' : 'fas fa-check-circle'"></i>
                                        {{ data.insurance_none ? 'None' : 'Available' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="data.insurance_all_risk_covered_by_company" class="type-item active">
                            <div class="type-header">
                                <div class="type-icon">
                                    <i class="fas fa-shield-check"></i>
                                </div>
                                <div class="type-info">
                                    <h5>All Risk Coverage</h5>
                                    <span class="type-status">Covered by Company</span>
                                </div>
                                <div class="type-indicator">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div class="type-description">
                                <p>Comprehensive all-risk insurance coverage provided directly by the company for maximum protection.</p>
                            </div>
                        </div>

                        <div v-if="data.insurance_liability_with_insurance_company" class="type-item active">
                            <div class="type-header">
                                <div class="type-icon">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                <div class="type-info">
                                    <h5>Liability Insurance</h5>
                                    <span class="type-status">External Insurance Company</span>
                                </div>
                                <div class="type-indicator">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div class="type-description">
                                <p>Professional liability coverage through partnership with established insurance companies.</p>
                            </div>
                        </div>

                        <div v-if="data.insurance_negotiable_with_without_insurance" class="type-item active">
                            <div class="type-header">
                                <div class="type-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="type-info">
                                    <h5>Negotiable Insurance</h5>
                                    <span class="type-status">Flexible Coverage</span>
                                </div>
                                <div class="type-indicator">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Available
                                    </span>
                                </div>
                            </div>
                            <div class="type-description">
                                <p>Flexible insurance options that can be negotiated with or without additional insurance coverage based on client needs.</p>
                            </div>
                        </div>

                        <div v-if="data.insurance_others && data.insurance_others_description" class="type-item active">
                            <div class="type-header">
                                <div class="type-icon">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="type-info">
                                    <h5>Other Insurance Types</h5>
                                    <span class="type-status">Custom Solutions</span>
                                </div>
                                <div class="type-indicator">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Available
                                    </span>
                                </div>
                            </div>
                            <div class="type-description">
                                <p>{{ data.insurance_others_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Claims Process -->
                <div v-if="data.insurance_claim_arrangement_description" class="claims-section">
                    <h4>
                        <i class="fas fa-clipboard-list"></i>
                        Claims Arrangement Process
                    </h4>
                    <div class="claims-content">
                        <div class="claims-process">
                            <div class="process-header">
                                <div class="process-icon">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div class="process-info">
                                    <h5>Insurance Claims Procedure</h5>
                                    <span class="process-type">Standard Process</span>
                                </div>
                            </div>
                            <div class="process-description">
                                <p>{{ data.insurance_claim_arrangement_description }}</p>
                            </div>
                        </div>

                        <div class="claims-steps">
                            <h6>Standard Claims Process:</h6>
                            <div class="steps-list">
                                <div class="step-item">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <h6>Incident Reporting</h6>
                                        <p>Immediate notification of any damage or loss incidents</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <h6>Documentation</h6>
                                        <p>Complete documentation and evidence gathering</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <h6>Assessment</h6>
                                        <p>Professional damage assessment and evaluation</p>
                                    </div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">4</div>
                                    <div class="step-content">
                                        <h6>Settlement</h6>
                                        <p>Claims processing and compensation settlement</p>
                                    </div>
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
                                <span class="statistic-number">{{ getInsuranceTypesCount() }}</span>
                                <span class="statistic-label">Coverage Types</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getCoveragePercentage() }}%</span>
                                <span class="statistic-label">Coverage Rate</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getInsuranceRating() }}</span>
                                <span class="statistic-label">Insurance Rating</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ hasClaimsProcess() ? 'Yes' : 'No' }}</span>
                                <span class="statistic-label">Claims Process</span>
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
    return !!(
        props.data.insurance_none !== undefined ||
        props.data.insurance_all_risk_covered_by_company ||
        props.data.insurance_liability_with_insurance_company ||
        props.data.insurance_negotiable_with_without_insurance ||
        props.data.insurance_others ||
        props.data.insurance_claim_arrangement_description
    )
})

function getInsuranceTypesCount() {
    let count = 0
    if (props.data.insurance_all_risk_covered_by_company) count++
    if (props.data.insurance_liability_with_insurance_company) count++
    if (props.data.insurance_negotiable_with_without_insurance) count++
    if (props.data.insurance_others && props.data.insurance_others_description) count++
    return count
}

function getCoveragePercentage() {
    if (props.data.insurance_none) return 0
    
    const totalTypes = 4 // Total possible insurance types
    const availableTypes = getInsuranceTypesCount()
    return totalTypes > 0 ? Math.round((availableTypes / totalTypes) * 100) : 0
}

function getInsuranceRating() {
    const percentage = getCoveragePercentage()
    if (percentage >= 90) return '★★★★★'
    if (percentage >= 70) return '★★★★☆'
    if (percentage >= 50) return '★★★☆☆'
    if (percentage >= 30) return '★★☆☆☆'
    return '★☆☆☆☆'
}

function hasClaimsProcess() {
    return !!(props.data.insurance_claim_arrangement_description)
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

.insurance-types,
.claims-section,
.insurance-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.insurance-types h4,
.claims-section h4,
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

.insurance-types h4 i {
    color: #f59e0b;
}

.claims-section h4 i {
    color: #3b82f6;
}

.insurance-statistics h4 i {
    color: #8b5cf6;
}

.types-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 24px;
}

.type-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.type-item.active {
    opacity: 1;
    border-color: #f59e0b;
}

.type-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.type-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
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

.type-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.type-status {
    font-size: 0.875rem;
    color: #6b7280;
}

.type-indicator {
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

.status-badge.unavailable {
    background: #fef2f2;
    color: #991b1b;
}

.type-description {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.8);
}

.type-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.claims-content {
    padding: 24px;
}

.claims-process {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
}

.process-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 12px;
}

.process-icon {
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

.process-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.process-type {
    font-size: 0.875rem;
    color: #6b7280;
}

.process-description {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.process-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.claims-steps h6 {
    margin: 0 0 16px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.steps-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.step-number {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.step-content h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.step-content p {
    margin: 0;
    color: #6b7280;
    line-height: 1.4;
    font-size: 0.8rem;
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
    
    .types-grid,
    .steps-list,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .type-header,
    .process-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .step-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>