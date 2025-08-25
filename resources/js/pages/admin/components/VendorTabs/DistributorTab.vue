<template>
    <div class="distributor-tab">
        <div class="tab-content-wrapper">
            <!-- Engineering Activities -->
            <div class="info-section" v-if="data?.engineering_activities">
                <h3 class="section-title">
                    <i class="fas fa-cogs"></i>
                    Aktivitas Engineering
                </h3>
                <div class="engineering-card">
                    <div class="activity-description">
                        {{ data.engineering_activities }}
                    </div>
                </div>
            </div>

            <!-- Service Capabilities -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-tools"></i>
                    Kemampuan Layanan
                </h3>
                <div class="capabilities-grid">
                    <div class="capability-card">
                        <div class="capability-header">
                            <i class="fas fa-star"></i>
                            <span>Priority Treatment</span>
                        </div>
                        <div class="capability-status">
                            <span :class="['status-indicator', data?.priority_treatment || 'unknown']">
                                <i :class="getStatusIcon(data?.priority_treatment)"></i>
                                {{ getStatusText(data?.priority_treatment) }}
                            </span>
                        </div>
                        <div class="capability-description">
                            Kemampuan memberikan treatment prioritas untuk customer
                        </div>
                    </div>

                    <div class="capability-card">
                        <div class="capability-header">
                            <i class="fas fa-handshake"></i>
                            <span>Complaint Agreement</span>
                        </div>
                        <div class="capability-status">
                            <span :class="['status-indicator', data?.complaint_agreement || 'unknown']">
                                <i :class="getStatusIcon(data?.complaint_agreement)"></i>
                                {{ getStatusText(data?.complaint_agreement) }}
                            </span>
                        </div>
                        <div class="capability-description">
                            Kesediaan untuk menangani keluhan customer dengan formal agreement
                        </div>
                    </div>

                    <div class="capability-card">
                        <div class="capability-header">
                            <i class="fas fa-headset"></i>
                            <span>Contact Centre</span>
                        </div>
                        <div class="capability-status">
                            <span :class="['status-indicator', data?.has_contact_centre || 'unknown']">
                                <i :class="getStatusIcon(data?.has_contact_centre)"></i>
                                {{ getStatusText(data?.has_contact_centre) }}
                            </span>
                        </div>
                        <div class="capability-description">
                            Memiliki contact centre untuk layanan customer 24/7
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Quality Metrics -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-chart-line"></i>
                    Metrik Kualitas Layanan
                </h3>
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon customer-service">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Customer Service</div>
                            <div class="metric-value">
                                {{ data?.has_contact_centre === 'yes' ? 'Available' : 'Standard' }}
                            </div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon priority">
                            <i class="fas fa-fast-forward"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Priority Service</div>
                            <div class="metric-value">
                                {{ data?.priority_treatment === 'yes' ? 'Supported' : 'Standard' }}
                            </div>
                        </div>
                    </div>

                    <div class="metric-card">
                        <div class="metric-icon complaint">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Complaint Handling</div>
                            <div class="metric-value">
                                {{ data?.complaint_agreement === 'yes' ? 'Formal Agreement' : 'Standard' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Summary -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-check"></i>
                    Ringkasan Layanan
                </h3>
                <div class="service-summary">
                    <div class="summary-header">
                        <div class="summary-score">
                            <div class="score-circle" :class="getScoreClass()">
                                <span class="score-number">{{ getServiceScore() }}</span>
                                <span class="score-total">/3</span>
                            </div>
                            <div class="score-label">Service Score</div>
                        </div>
                        <div class="summary-text">
                            <h4>{{ getServiceLevel() }}</h4>
                            <p>{{ getServiceDescription() }}</p>
                        </div>
                    </div>
                    
                    <div class="summary-details">
                        <div class="detail-item">
                            <i class="fas fa-check-circle success"></i>
                            <span>Basic distribution services available</span>
                        </div>
                        <div class="detail-item" v-if="data?.priority_treatment === 'yes'">
                            <i class="fas fa-check-circle success"></i>
                            <span>Priority treatment for urgent orders</span>
                        </div>
                        <div class="detail-item" v-if="data?.has_contact_centre === 'yes'">
                            <i class="fas fa-check-circle success"></i>
                            <span>24/7 customer contact centre</span>
                        </div>
                        <div class="detail-item" v-if="data?.complaint_agreement === 'yes'">
                            <i class="fas fa-check-circle success"></i>
                            <span>Formal complaint handling agreement</span>
                        </div>
                        <div class="detail-item" v-if="data?.engineering_activities">
                            <i class="fas fa-check-circle success"></i>
                            <span>Engineering support capabilities</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Data State -->
            <div v-if="!data" class="no-data-state">
                <i class="fas fa-info-circle"></i>
                <h4>Belum Ada Data Distributor</h4>
                <p>Data khusus distributor belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    vendor: {
        type: Object,
        required: true
    },
    data: {
        type: Object,
        default: null
    }
})

function getStatusIcon(status) {
    switch (status) {
        case 'yes':
            return 'fas fa-check-circle'
        case 'no':
            return 'fas fa-times-circle'
        default:
            return 'fas fa-question-circle'
    }
}

function getStatusText(status) {
    switch (status) {
        case 'yes':
            return 'Ya'
        case 'no':
            return 'Tidak'
        default:
            return 'Belum diisi'
    }
}

function getServiceScore() {
    if (!props.data) return 0
    
    let score = 0
    if (props.data.priority_treatment === 'yes') score++
    if (props.data.complaint_agreement === 'yes') score++
    if (props.data.has_contact_centre === 'yes') score++
    
    return score
}

function getScoreClass() {
    const score = getServiceScore()
    if (score === 3) return 'excellent'
    if (score === 2) return 'good'
    if (score === 1) return 'fair'
    return 'basic'
}

function getServiceLevel() {
    const score = getServiceScore()
    if (score === 3) return 'Premium Service'
    if (score === 2) return 'Enhanced Service'
    if (score === 1) return 'Standard Plus'
    return 'Basic Service'
}

function getServiceDescription() {
    const score = getServiceScore()
    if (score === 3) return 'Distributor dengan layanan lengkap dan premium untuk semua kebutuhan customer'
    if (score === 2) return 'Distributor dengan layanan yang baik dan beberapa fitur tambahan'
    if (score === 1) return 'Distributor dengan layanan standar plus beberapa kemampuan tambahan'
    return 'Distributor dengan layanan dasar sesuai standar industri'
}
</script>

<style scoped>
.distributor-tab {
    padding: 24px;
}

.tab-content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.info-section {
    background: #f9fafb;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 20px 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}

.section-title i {
    color: #6b7280;
}

.engineering-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.activity-description {
    line-height: 1.6;
    color: #374151;
    font-size: 1rem;
}

.capabilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.capability-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.capability-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.capability-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.capability-header i {
    color: #6366f1;
    font-size: 1.125rem;
}

.capability-header span {
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.capability-status {
    margin-bottom: 12px;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-indicator.yes {
    background: #d1fae5;
    color: #065f46;
}

.status-indicator.no {
    background: #fef2f2;
    color: #991b1b;
}

.status-indicator.unknown {
    background: #f3f4f6;
    color: #6b7280;
}

.capability-description {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.5;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.metric-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.metric-icon.customer-service {
    background: #dbeafe;
    color: #1e40af;
}

.metric-icon.priority {
    background: #fef3c7;
    color: #92400e;
}

.metric-icon.complaint {
    background: #d1fae5;
    color: #065f46;
}

.metric-content {
    flex: 1;
}

.metric-title {
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
}

.metric-value {
    font-size: 0.875rem;
    color: #6b7280;
}

.service-summary {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.summary-header {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.summary-score {
    text-align: center;
    flex-shrink: 0;
}

.score-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(255, 255, 255, 0.3);
    margin-bottom: 8px;
}

.score-circle.excellent {
    background: rgba(16, 185, 129, 0.2);
    border-color: #10b981;
}

.score-circle.good {
    background: rgba(59, 130, 246, 0.2);
    border-color: #3b82f6;
}

.score-circle.fair {
    background: rgba(245, 158, 11, 0.2);
    border-color: #f59e0b;
}

.score-circle.basic {
    background: rgba(156, 163, 175, 0.2);
    border-color: #9ca3af;
}

.score-number {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.score-total {
    font-size: 1rem;
    opacity: 0.8;
}

.score-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

.summary-text {
    flex: 1;
}

.summary-text h4 {
    margin: 0 0 8px 0;
    font-size: 1.25rem;
    font-weight: 700;
}

.summary-text p {
    margin: 0;
    opacity: 0.9;
    line-height: 1.5;
}

.summary-details {
    padding: 24px;
    background: #f9fafb;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 0.875rem;
    color: #374151;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-item i.success {
    color: #10b981;
}

.no-data-state {
    text-align: center;
    padding: 64px 32px;
    color: #6b7280;
}

.no-data-state i {
    font-size: 3rem;
    margin-bottom: 16px;
    color: #d1d5db;
}

.no-data-state h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 1.125rem;
}

.no-data-state p {
    margin: 0;
    color: #6b7280;
}

@media (max-width: 768px) {
    .distributor-tab {
        padding: 16px;
    }
    
    .tab-content-wrapper {
        gap: 24px;
    }
    
    .info-section {
        padding: 20px;
    }
    
    .capabilities-grid,
    .metrics-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .summary-header {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .capability-card {
        padding: 20px;
    }
}
</style>