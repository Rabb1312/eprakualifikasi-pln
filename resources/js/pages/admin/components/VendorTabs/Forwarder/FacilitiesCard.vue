<template>
    <div class="facilities-card">
        <div v-if="hasData" class="facilities-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="header-info">
                    <h3>Facilities & Capabilities</h3>
                    <p>Fasilitas dan kemampuan teknis yang dimiliki forwarder</p>
                </div>
            </div>

            <div class="content-sections">
                <!-- Heavy Lift Capabilities -->
                <div v-if="data.heavy_lift_capability || data.heavy_lift_cooperation_companies" class="capability-section">
                    <h4>
                        <i class="fas fa-weight-hanging"></i>
                        Heavy Lift Capabilities
                    </h4>
                    <div class="capability-content">
                        <div v-if="data.heavy_lift_capability" class="capability-item">
                            <div class="capability-header">
                                <div class="capability-icon">
                                    <i class="fas fa-truck-pickup"></i>
                                </div>
                                <div class="capability-info">
                                    <h5>Heavy Lift Capability</h5>
                                    <span class="capability-value">{{ data.heavy_lift_capability }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="data.heavy_lift_cooperation_companies" class="cooperation-companies">
                            <h6>Cooperation Companies:</h6>
                            <div class="companies-text">
                                <p>{{ data.heavy_lift_cooperation_companies }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monitoring & Management -->
                <div v-if="data.monitoring_management || data.monitoring_coordination_description" class="monitoring-section">
                    <h4>
                        <i class="fas fa-desktop"></i>
                        Monitoring & Management
                    </h4>
                    <div class="monitoring-content">
                        <div v-if="data.monitoring_management" class="monitoring-item">
                            <div class="monitoring-header">
                                <div class="monitoring-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="monitoring-info">
                                    <h5>Monitoring Management System</h5>
                                    <span class="monitoring-type">{{ data.monitoring_management }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="data.monitoring_coordination_description" class="coordination-desc">
                            <h6>Coordination Description:</h6>
                            <div class="description-text">
                                <p>{{ data.monitoring_coordination_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Software Systems -->
                <div class="software-section">
                    <h4>
                        <i class="fas fa-laptop"></i>
                        Software Systems
                    </h4>
                    <div class="software-grid">
                        <div class="software-item" :class="{ active: !data.software_none }">
                            <div class="software-header">
                                <div class="software-icon">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="software-info">
                                    <h5>Software Availability</h5>
                                    <span class="software-status">{{ data.software_none ? 'No Software' : 'Software Available' }}</span>
                                </div>
                                <div class="software-indicator">
                                    <span :class="['status-badge', data.software_none ? 'unavailable' : 'available']">
                                        <i :class="data.software_none ? 'fas fa-times-circle' : 'fas fa-check-circle'"></i>
                                        {{ data.software_none ? 'None' : 'Available' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="data.software_existing" class="software-item active">
                            <div class="software-header">
                                <div class="software-icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="software-info">
                                    <h5>Existing Software</h5>
                                    <span class="software-status">In Use</span>
                                </div>
                                <div class="software-indicator">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="data.software_shipment_tracking" class="software-item active">
                            <div class="software-header">
                                <div class="software-icon">
                                    <i class="fas fa-search-location"></i>
                                </div>
                                <div class="software-info">
                                    <h5>Shipment Tracking</h5>
                                    <span class="software-status">Real-time Tracking</span>
                                </div>
                                <div class="software-indicator">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div v-if="data.software_shipment_tracking_description" class="software-description">
                                <p>{{ data.software_shipment_tracking_description }}</p>
                            </div>
                        </div>

                        <div v-if="data.software_others && data.software_others_description" class="software-item active">
                            <div class="software-header">
                                <div class="software-icon">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <div class="software-info">
                                    <h5>Other Software</h5>
                                    <span class="software-status">Custom Solutions</span>
                                </div>
                                <div class="software-indicator">
                                    <span class="status-badge available">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                </div>
                            </div>
                            <div class="software-description">
                                <p>{{ data.software_others_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Communication System -->
                <div v-if="data.communication_system || data.communication_system_description" class="communication-section">
                    <h4>
                        <i class="fas fa-broadcast-tower"></i>
                        Communication System
                    </h4>
                    <div class="communication-content">
                        <div v-if="data.communication_system" class="communication-item">
                            <div class="communication-header">
                                <div class="communication-icon">
                                    <i class="fas fa-wifi"></i>
                                </div>
                                <div class="communication-info">
                                    <h5>Communication System Type</h5>
                                    <span class="communication-type">{{ data.communication_system }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="data.communication_system_description" class="communication-desc">
                            <h6>System Description:</h6>
                            <div class="description-text">
                                <p>{{ data.communication_system_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Execution -->
                <div v-if="data.project_execution_procedure || data.project_execution_sop_list" class="execution-section">
                    <h4>
                        <i class="fas fa-clipboard-list"></i>
                        Project Execution
                    </h4>
                    <div class="execution-content">
                        <div v-if="data.project_execution_procedure" class="execution-item">
                            <div class="execution-header">
                                <div class="execution-icon">
                                    <i class="fas fa-list-check"></i>
                                </div>
                                <div class="execution-info">
                                    <h5>Execution Procedure</h5>
                                    <span class="execution-type">{{ data.project_execution_procedure }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="data.project_execution_sop_list" class="sop-list">
                            <h6>SOP List:</h6>
                            <div class="sop-text">
                                <p>{{ data.project_execution_sop_list }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-warehouse"></i>
                <h4>Belum Ada Data Facilities</h4>
                <p>Data fasilitas dan kemampuan belum dilengkapi untuk forwarder ini</p>
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
        props.data.heavy_lift_capability ||
        props.data.heavy_lift_cooperation_companies ||
        props.data.monitoring_management ||
        props.data.monitoring_coordination_description ||
        props.data.software_none !== undefined ||
        props.data.software_existing ||
        props.data.software_shipment_tracking ||
        props.data.software_others ||
        props.data.communication_system ||
        props.data.communication_system_description ||
        props.data.project_execution_procedure ||
        props.data.project_execution_sop_list
    )
})
</script>

<style scoped>
.facilities-card {
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

.content-sections {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.capability-section,
.monitoring-section,
.software-section,
.communication-section,
.execution-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.capability-section h4,
.monitoring-section h4,
.software-section h4,
.communication-section h4,
.execution-section h4 {
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

.capability-section h4 i { color: #f59e0b; }
.monitoring-section h4 i { color: #3b82f6; }
.software-section h4 i { color: #8b5cf6; }
.communication-section h4 i { color: #10b981; }
.execution-section h4 i { color: #ef4444; }

.capability-content,
.monitoring-content,
.communication-content,
.execution-content {
    padding: 24px;
}

.capability-item,
.monitoring-item,
.communication-item,
.execution-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 16px;
}

.capability-header,
.monitoring-header,
.communication-header,
.execution-header {
    display: flex;
    align-items: center;
    gap: 16px;
}

.capability-icon,
.monitoring-icon,
.communication-icon,
.execution-icon {
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

.capability-info h5,
.monitoring-info h5,
.communication-info h5,
.execution-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.capability-value,
.monitoring-type,
.communication-type,
.execution-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.software-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 24px;
}

.software-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.software-item.active {
    opacity: 1;
    border-color: #8b5cf6;
}

.software-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.software-icon {
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

.software-info {
    flex: 1;
}

.software-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.software-status {
    font-size: 0.875rem;
    color: #6b7280;
}

.software-indicator {
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

.software-description {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.8);
}

.software-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.cooperation-companies,
.coordination-desc,
.communication-desc,
.sop-list {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.cooperation-companies h6,
.coordination-desc h6,
.communication-desc h6,
.sop-list h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.companies-text,
.description-text,
.sop-text {
    background: #f8fafc;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.companies-text p,
.description-text p,
.sop-text p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
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
    .facilities-card {
        padding: 16px;
    }
    
    .software-grid {
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .software-header,
    .capability-header,
    .monitoring-header,
    .communication-header,
    .execution-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>