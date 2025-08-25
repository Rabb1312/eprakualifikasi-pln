<template>
    <div class="other-services-card">
        <div v-if="hasData" class="services-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <div class="header-info">
                    <h3>Other Services</h3>
                    <p>Layanan tambahan yang disediakan oleh forwarder</p>
                </div>
                <div class="services-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ getServicesCount() }}</span>
                        <span class="stat-label">Services</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Main Services -->
                <div class="services-overview">
                    <h4>
                        <i class="fas fa-list"></i>
                        Available Services
                    </h4>
                    <div class="services-grid">
                        <div class="service-item" :class="{ active: data.able_to_conduct_expediting }">
                            <div class="service-header">
                                <div class="service-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="service-info">
                                    <h5>Expediting Services</h5>
                                    <span class="service-category">Project Management</span>
                                </div>
                                <div class="service-status">
                                    <span :class="['status-badge', data.able_to_conduct_expediting ? 'available' : 'unavailable']">
                                        <i :class="data.able_to_conduct_expediting ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                        {{ data.able_to_conduct_expediting ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                            <div class="service-description">
                                <p>Kemampuan untuk melakukan expediting dan monitoring kemajuan proyek secara real-time</p>
                            </div>
                        </div>

                        <div class="service-item" :class="{ active: data.able_to_submit_regular_status_report }">
                            <div class="service-header">
                                <div class="service-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="service-info">
                                    <h5>Regular Status Reports</h5>
                                    <span class="service-category">Reporting</span>
                                </div>
                                <div class="service-status">
                                    <span :class="['status-badge', data.able_to_submit_regular_status_report ? 'available' : 'unavailable']">
                                        <i :class="data.able_to_submit_regular_status_report ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                        {{ data.able_to_submit_regular_status_report ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                            <div class="service-description">
                                <p>Penyediaan laporan status reguler mengenai kemajuan pengiriman dan proyek</p>
                            </div>
                        </div>

                        <div class="service-item" :class="{ active: data.able_to_have_scope_electrical_supervision }">
                            <div class="service-header">
                                <div class="service-icon">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="service-info">
                                    <h5>Electrical Supervision</h5>
                                    <span class="service-category">Technical Services</span>
                                </div>
                                <div class="service-status">
                                    <span :class="['status-badge', data.able_to_have_scope_electrical_supervision ? 'available' : 'unavailable']">
                                        <i :class="data.able_to_have_scope_electrical_supervision ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                        {{ data.able_to_have_scope_electrical_supervision ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                            <div class="service-description">
                                <p>Kemampuan supervisi teknis untuk peralatan elektrikal dan instalasi</p>
                            </div>
                        </div>

                        <div class="service-item" :class="{ active: data.having_ability_make_temporary_jetty }">
                            <div class="service-header">
                                <div class="service-icon">
                                    <i class="fas fa-anchor"></i>
                                </div>
                                <div class="service-info">
                                    <h5>Temporary Jetty Construction</h5>
                                    <span class="service-category">Marine Services</span>
                                </div>
                                <div class="service-status">
                                    <span :class="['status-badge', data.having_ability_make_temporary_jetty ? 'available' : 'unavailable']">
                                        <i :class="data.having_ability_make_temporary_jetty ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                        {{ data.having_ability_make_temporary_jetty ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                            <div class="service-description">
                                <p>Kemampuan membangun dermaga sementara untuk kebutuhan proyek khusus</p>
                            </div>
                        </div>

                        <div v-if="data.other_services_others && data.other_services_others_description" class="service-item active">
                            <div class="service-header">
                                <div class="service-icon">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="service-info">
                                    <h5>Other Services</h5>
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
                                <p>{{ data.other_services_others_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Statistics -->
                <div class="service-statistics">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Service Statistics
                    </h4>
                    <div class="statistics-grid">
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getAvailableServicesCount() }}</span>
                                <span class="statistic-label">Available Services</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getUnavailableServicesCount() }}</span>
                                <span class="statistic-label">Not Available</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getAvailabilityPercentage() }}%</span>
                                <span class="statistic-label">Availability Rate</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getServiceRating() }}</span>
                                <span class="statistic-label">Service Rating</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-concierge-bell"></i>
                <h4>Belum Ada Data Other Services</h4>
                <p>Data layanan tambahan belum dilengkapi untuk forwarder ini</p>
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
        props.data.other_services_none !== undefined ||
        props.data.able_to_conduct_expediting !== undefined ||
        props.data.able_to_submit_regular_status_report !== undefined ||
        props.data.able_to_have_scope_electrical_supervision !== undefined ||
        props.data.having_ability_make_temporary_jetty !== undefined ||
        props.data.other_services_others !== undefined ||
        props.data.other_services_others_description
    )
})

function getServicesCount() {
    let count = 4 // Base services
    if (props.data.other_services_others && props.data.other_services_others_description) {
        count += 1
    }
    return count
}

function getAvailableServicesCount() {
    let count = 0
    if (props.data.able_to_conduct_expediting) count++
    if (props.data.able_to_submit_regular_status_report) count++
    if (props.data.able_to_have_scope_electrical_supervision) count++
    if (props.data.having_ability_make_temporary_jetty) count++
    if (props.data.other_services_others && props.data.other_services_others_description) count++
    return count
}

function getUnavailableServicesCount() {
    return getServicesCount() - getAvailableServicesCount()
}

function getAvailabilityPercentage() {
    const total = getServicesCount()
    const available = getAvailableServicesCount()
    return total > 0 ? Math.round((available / total) * 100) : 0
}

function getServiceRating() {
    const percentage = getAvailabilityPercentage()
    if (percentage >= 90) return '★★★★★'
    if (percentage >= 70) return '★★★★☆'
    if (percentage >= 50) return '★★★☆☆'
    if (percentage >= 30) return '★★☆☆☆'
    return '★☆☆☆☆'
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
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
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
.service-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.services-overview h4,
.service-statistics h4 {
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
    color: #059669;
}

.service-statistics h4 i {
    color: #f59e0b;
}

.services-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 24px;
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
    border-color: #059669;
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
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
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
    .statistics-grid {
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
}
</style>