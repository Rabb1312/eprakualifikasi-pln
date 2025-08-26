<template>
    <div class="other-services-card">
        <div v-if="hasData" class="services-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="header-info">
                    <h3>Other Services</h3>
                    <p>Layanan tambahan dan kemampuan khusus yang disediakan oleh forwarder</p>
                </div>
                <div class="services-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ getAvailableServicesCount() }}/{{ getTotalServicesCount() }}</span>
                        <span class="stat-label">Available</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Available Services Overview -->
                <div class="services-overview">
                    <h4>
                        <i class="fas fa-list-check"></i>
                        Available Services
                    </h4>
                    <div class="services-content-area">
                        <!-- None of Services Notice -->
                        <div v-if="otherServicesData.other_services_none" class="none-services-notice">
                            <div class="notice-icon">
                                <i class="fas fa-ban"></i>
                            </div>
                            <div class="notice-content">
                                <h6>No Additional Services</h6>
                                <p>This forwarder does not provide any of the additional services listed below.</p>
                            </div>
                        </div>

                        <!-- Services Grid -->
                        <div v-else class="services-grid">
                            <!-- Expediting Service -->
                            <div class="service-item" :class="{ active: otherServicesData.able_to_conduct_expediting }">
                                <div class="service-header">
                                    <div class="service-icon">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                    <div class="service-info">
                                        <h5>Expediting Services</h5>
                                        <span class="service-category">Project Management</span>
                                    </div>
                                    <div class="service-status">
                                        <span :class="['status-badge', otherServicesData.able_to_conduct_expediting ? 'available' : 'unavailable']">
                                            <i :class="otherServicesData.able_to_conduct_expediting ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                            {{ otherServicesData.able_to_conduct_expediting ? 'Available' : 'Not Available' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="service-description">
                                    <p>Kemampuan untuk melakukan expediting dan mempercepat proses pengiriman serta monitoring kemajuan proyek secara real-time.</p>
                                </div>
                            </div>

                            <!-- Regular Status Report -->
                            <div class="service-item" :class="{ active: otherServicesData.able_to_submit_regular_status_report }">
                                <div class="service-header">
                                    <div class="service-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="service-info">
                                        <h5>Regular Status Reports</h5>
                                        <span class="service-category">Reporting & Documentation</span>
                                    </div>
                                    <div class="service-status">
                                        <span :class="['status-badge', otherServicesData.able_to_submit_regular_status_report ? 'available' : 'unavailable']">
                                            <i :class="otherServicesData.able_to_submit_regular_status_report ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                            {{ otherServicesData.able_to_submit_regular_status_report ? 'Available' : 'Not Available' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="service-description">
                                    <p>Penyediaan laporan status reguler dan detail mengenai kemajuan pengiriman, dokumentasi, dan milestone proyek.</p>
                                </div>
                            </div>

                            <!-- Electrical Supervision -->
                            <div class="service-item" :class="{ active: otherServicesData.able_to_have_scope_electrical_supervision }">
                                <div class="service-header">
                                    <div class="service-icon">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <div class="service-info">
                                        <h5>Electrical Supervision</h5>
                                        <span class="service-category">Technical Services</span>
                                    </div>
                                    <div class="service-status">
                                        <span :class="['status-badge', otherServicesData.able_to_have_scope_electrical_supervision ? 'available' : 'unavailable']">
                                            <i :class="otherServicesData.able_to_have_scope_electrical_supervision ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                            {{ otherServicesData.able_to_have_scope_electrical_supervision ? 'Available' : 'Not Available' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="service-description">
                                    <p>Kemampuan supervisi dan pengawasan teknis untuk peralatan elektrikal, instalasi, dan sistem kelistrikan proyek.</p>
                                </div>
                            </div>

                            <!-- Temporary Jetty Construction -->
                            <div class="service-item" :class="{ active: otherServicesData.having_ability_make_temporary_jetty }">
                                <div class="service-header">
                                    <div class="service-icon">
                                        <i class="fas fa-anchor"></i>
                                    </div>
                                    <div class="service-info">
                                        <h5>Temporary Jetty Construction</h5>
                                        <span class="service-category">Marine Infrastructure</span>
                                    </div>
                                    <div class="service-status">
                                        <span :class="['status-badge', otherServicesData.having_ability_make_temporary_jetty ? 'available' : 'unavailable']">
                                            <i :class="otherServicesData.having_ability_make_temporary_jetty ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                            {{ otherServicesData.having_ability_make_temporary_jetty ? 'Available' : 'Not Available' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="service-description">
                                    <p>Kemampuan membangun dan menyediakan dermaga sementara untuk kebutuhan proyek khusus dan operasi marine.</p>
                                </div>
                            </div>

                            <!-- Other Custom Services -->
                            <div v-if="otherServicesData.other_services_others && otherServicesData.other_services_others_description" 
                                 class="service-item active custom-service">
                                <div class="service-header">
                                    <div class="service-icon custom">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <div class="service-info">
                                        <h5>Other Custom Services</h5>
                                        <span class="service-category">Additional Services</span>
                                    </div>
                                    <div class="service-status">
                                        <span class="status-badge available">
                                            <i class="fas fa-check-circle"></i>
                                            Available
                                        </span>
                                    </div>
                                </div>
                                <div class="service-description">
                                    <p>{{ otherServicesData.other_services_others_description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Heavy Lift Capability Section -->
                <div class="heavy-lift-section">
                    <h4>
                        <i class="fas fa-weight-hanging"></i>
                        Heavy Lift Capability
                    </h4>
                    <div class="heavy-lift-content">
                        <div class="capability-status">
                            <div class="status-card" :class="getHeavyLiftStatusClass()">
                                <div class="status-icon">
                                    <i :class="getHeavyLiftStatusIcon()"></i>
                                </div>
                                <div class="status-details">
                                    <h6>{{ getHeavyLiftStatusTitle() }}</h6>
                                    <p>{{ getHeavyLiftStatusDescription() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cooperation Companies -->
                        <div v-if="otherServicesData.heavy_lift_capability === 'yes' && otherServicesData.heavy_lift_cooperation_companies" 
                             class="cooperation-section">
                            <h6>Cooperation Companies:</h6>
                            <div class="cooperation-content">
                                <div class="cooperation-text">
                                    <i class="fas fa-handshake"></i>
                                    <p>{{ otherServicesData.heavy_lift_cooperation_companies }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-cogs"></i>
                <h4>Belum Ada Data Other Services</h4>
                <p>Data layanan tambahan belum dikonfigurasi untuk forwarder ini</p>
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
    return !!(Object.keys(props.data).length > 0)
})

const otherServicesData = computed(() => {
    return props.data || {}
})

// Helper functions sesuai dengan ForwarderOtherServices.vue
function getTotalServicesCount() {
    let count = 4 // Base services: expediting, status report, electrical supervision, temporary jetty
    if (otherServicesData.value.other_services_others && otherServicesData.value.other_services_others_description) {
        count += 1
    }
    return count
}

function getAvailableServicesCount() {
    if (otherServicesData.value.other_services_none) {
        return 0
    }
    
    let count = 0
    if (otherServicesData.value.able_to_conduct_expediting) count++
    if (otherServicesData.value.able_to_submit_regular_status_report) count++
    if (otherServicesData.value.able_to_have_scope_electrical_supervision) count++
    if (otherServicesData.value.having_ability_make_temporary_jetty) count++
    if (otherServicesData.value.other_services_others && otherServicesData.value.other_services_others_description) count++
    return count
}

function getAvailabilityPercentage() {
    const total = getTotalServicesCount()
    const available = getAvailableServicesCount()
    return total > 0 ? Math.round((available / total) * 100) : 0
}

function getHeavyLiftStatusClass() {
    if (otherServicesData.value.heavy_lift_capability === 'yes') return 'status-available'
    if (otherServicesData.value.heavy_lift_capability === 'no') return 'status-unavailable'
    return 'status-unknown'
}

function getHeavyLiftStatusIcon() {
    if (otherServicesData.value.heavy_lift_capability === 'yes') return 'fas fa-check-circle'
    if (otherServicesData.value.heavy_lift_capability === 'no') return 'fas fa-times-circle'
    return 'fas fa-question-circle'
}

function getHeavyLiftStatusTitle() {
    if (otherServicesData.value.heavy_lift_capability === 'yes') return 'Heavy Lift Capability Available'
    if (otherServicesData.value.heavy_lift_capability === 'no') return 'Heavy Lift Capability Not Available'
    return 'Heavy Lift Capability Status Unknown'
}

function getHeavyLiftStatusDescription() {
    if (otherServicesData.value.heavy_lift_capability === 'yes') return 'This forwarder has heavy lift capability and can handle oversized or heavy cargo operations.'
    if (otherServicesData.value.heavy_lift_capability === 'no') return 'This forwarder does not have heavy lift capability for handling oversized or heavy cargo.'
    return 'Heavy lift capability status has not been specified for this forwarder.'
}
</script>

<style scoped>
.other-services-card {
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

.services-stats {
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

.services-overview,
.heavy-lift-section,
.services-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.services-overview h4,
.heavy-lift-section h4,
.services-summary h4 {
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

.services-overview h4 i {
    color: #7c3aed;
}

.heavy-lift-section h4 i {
    color: #f59e0b;
}

.services-summary h4 i {
    color: #10b981;
}

.services-content-area,
.heavy-lift-content,
.summary-content {
    padding: 24px;
}

.none-services-notice {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: linear-gradient(135deg, #fef2f2 0%, #fefefe 100%);
    border: 1px solid #fecaca;
    border-radius: 8px;
}

.notice-icon {
    width: 48px;
    height: 48px;
    background: #dc2626;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.notice-content h6 {
    margin: 0 0 4px 0;
    color: #991b1b;
    font-weight: 600;
}

.notice-content p {
    margin: 0;
    color: #7f1d1d;
    font-size: 0.875rem;
}

.services-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.service-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.service-item.active {
    opacity: 1;
    border-color: #7c3aed;
}

.service-item.custom-service {
    border-color: #f59e0b;
}

.service-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.service-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.service-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.service-icon.custom {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.service-info {
    flex: 1;
}

.service-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.service-category {
    font-size: 0.875rem;
    color: #6b7280;
}

.service-status {
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

.service-description {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.8);
}

.service-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.capability-status {
    margin-bottom: 20px;
}

.status-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-radius: 8px;
}

.status-available {
    background: linear-gradient(135deg, #d1fae5 0%, #ffffff 100%);
    border-left: 4px solid #10b981;
}

.status-unavailable {
    background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
    border-left: 4px solid #ef4444;
}

.status-unknown {
    background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
    border-left: 4px solid #6b7280;
}

.status-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.status-available .status-icon {
    background: #10b981;
    color: white;
}

.status-unavailable .status-icon {
    background: #ef4444;
    color: white;
}

.status-unknown .status-icon {
    background: #6b7280;
    color: white;
}

.status-details h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
}

.status-details p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.4;
}

.cooperation-section h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.cooperation-content {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.cooperation-text {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.cooperation-text i {
    color: #f59e0b;
    font-size: 1.125rem;
    margin-top: 2px;
    flex-shrink: 0;
}

.cooperation-text p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.statistic-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.category-item {
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-item.project {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
}

.category-item.reporting {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.category-item.technical {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.category-item.marine {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.category-label {
    font-weight: 600;
    font-size: 0.875rem;
}

.category-status {
    font-weight: 700;
    font-size: 0.75rem;
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
    .other-services-card {
        padding: 16px;
    }
    
    .services-grid,
    .statistics-grid,
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .service-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .status-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .cooperation-text {
        flex-direction: column;
        gap: 8px;
    }
}
</style>