<template>
    <div class="after-sales-card">
        <div v-if="hasData" class="after-sales-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="header-info">
                    <h3>After Sales Support</h3>
                    <p>Layanan purna jual dan dukungan teknis yang tersedia untuk pelanggan</p>
                </div>
                <div class="services-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ serviceScore }}%</span>
                        <span class="stat-label">Coverage</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Service Options Grid -->
                <div class="services-grid">
                    <!-- Priority Treatment -->
                    <div class="service-item">
                        <div class="service-header">
                            <div class="service-icon priority">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="service-info">
                                <h5>Priority Treatment</h5>
                                <span class="service-category">Issue Resolution Service</span>
                                <div class="service-description">
                                    <p>Priority treatment for over/short/damaged requests</p>
                                </div>
                            </div>
                            <div class="service-availability">
                                <span :class="['availability-badge', getAvailabilityClass(afterSalesData.priority_treatment)]">
                                    <i :class="getAvailabilityIcon(afterSalesData.priority_treatment)"></i>
                                    {{ getAvailabilityText(afterSalesData.priority_treatment) }}
                                </span>
                            </div>
                        </div>

                        <div class="service-details">
                            <div class="service-specs">
                                <h6>Service Information:</h6>
                                <div class="specs-grid">
                                    <div class="spec-item">
                                        <label>Service Type:</label>
                                        <span>Priority Issue Handling</span>
                                    </div>
                                    <div class="spec-item">
                                        <label>Coverage:</label>
                                        <span>Over/Short/Damaged Requests</span>
                                    </div>
                                    <div class="spec-item">
                                        <label>Availability:</label>
                                        <span>{{ afterSalesData.priority_treatment === 'yes' ? 'Available' : 'Not Available' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Returned Goods Procedure -->
                    <div class="service-item">
                        <div class="service-header">
                            <div class="service-icon returns">
                                <i class="fas fa-undo"></i>
                            </div>
                            <div class="service-info">
                                <h5>Returned Goods Procedure</h5>
                                <span class="service-category">Returns Management</span>
                                <div class="service-description">
                                    <p>Standardized procedure for returned goods processing</p>
                                </div>
                            </div>
                            <div class="service-availability">
                                <span :class="['availability-badge', getAvailabilityClass(afterSalesData.procedure_returned_goods)]">
                                    <i :class="getAvailabilityIcon(afterSalesData.procedure_returned_goods)"></i>
                                    {{ getReturnsProcedureText(afterSalesData.procedure_returned_goods) }}
                                </span>
                            </div>
                        </div>

                        <div class="service-details">
                            <div class="service-specs">
                                <h6>Procedure Information:</h6>
                                <div class="specs-grid">
                                    <div class="spec-item">
                                        <label>Service Type:</label>
                                        <span>Returns Processing</span>
                                    </div>
                                    <div class="spec-item">
                                        <label>Process:</label>
                                        <span>{{ afterSalesData.procedure_returned_goods === 'yes' ? 'Standardized Procedure' : 'No Procedure' }}</span>
                                    </div>
                                    <div class="spec-item">
                                        <label>Status:</label>
                                        <span>{{ afterSalesData.procedure_returned_goods === 'yes' ? 'Available' : 'Not Available' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Centre -->
                    <div class="service-item">
                        <div class="service-header">
                            <div class="service-icon contact">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="service-info">
                                <h5>Contact Centre Service</h5>
                                <span class="service-category">Customer Support</span>
                                <div class="service-description">
                                    <p>Dedicated contact centre for customer service</p>
                                </div>
                            </div>
                            <div class="service-availability">
                                <span :class="['availability-badge', getAvailabilityClass(afterSalesData.contact_centre)]">
                                    <i :class="getAvailabilityIcon(afterSalesData.contact_centre)"></i>
                                    {{ getContactCentreText(afterSalesData.contact_centre) }}
                                </span>
                            </div>
                        </div>

                        <div class="service-details">
                            <div class="service-specs">
                                <h6>Contact Information:</h6>
                                <div class="specs-grid">
                                    <div class="spec-item">
                                        <label>Service Type:</label>
                                        <span>Customer Support Centre</span>
                                    </div>
                                    <div class="spec-item">
                                        <label>Channel:</label>
                                        <span>Phone Support</span>
                                    </div>
                                    <div class="spec-item">
                                        <label>Availability:</label>
                                        <span>{{ afterSalesData.contact_centre === 'yes' ? 'Available' : 'Not Available' }}</span>
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
                <i class="fas fa-headset"></i>
                <h4>Belum Ada Data After Sales</h4>
                <p>Data layanan purna jual belum dikonfigurasi untuk manufacturer ini</p>
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
    return !!(props.data.after_sales && Object.keys(props.data.after_sales).length > 0)
})

const afterSalesData = computed(() => {
    if (!props.data.after_sales) return {}
    
    // Handle JSON field dari database
    if (typeof props.data.after_sales === 'string') {
        try {
            return JSON.parse(props.data.after_sales)
        } catch {
            return {}
        }
    }
    
    return props.data.after_sales || {}
})

// Helper functions sesuai dengan ManufactureAfterSales.vue
const serviceScore = computed(() => {
    const options = [
        afterSalesData.value.priority_treatment,
        afterSalesData.value.procedure_returned_goods,
        afterSalesData.value.contact_centre
    ]
    
    const totalOptions = options.filter(option => option && option !== "").length
    const yesOptions = options.filter(option => option === "yes").length
    
    if (totalOptions === 0) return 0
    return Math.round((yesOptions / totalOptions) * 100)
})

function getAvailabilityClass(availability) {
    if (availability === 'yes') return 'available'
    if (availability === 'no') return 'unavailable'
    return 'unknown'
}

function getAvailabilityIcon(availability) {
    if (availability === 'yes') return 'fas fa-check-circle'
    if (availability === 'no') return 'fas fa-times-circle'
    return 'fas fa-question-circle'
}

function getAvailabilityText(availability) {
    if (availability === 'yes') return 'Tersedia'
    if (availability === 'no') return 'Tidak Tersedia'
    return 'Tidak Ditentukan'
}

function getReturnsProcedureText(availability) {
    if (availability === 'yes') return 'Ada Prosedur'
    if (availability === 'no') return 'Tidak Ada Prosedur'
    return 'Tidak Ditentukan'
}

function getContactCentreText(availability) {
    if (availability === 'yes') return 'Tersedia'
    if (availability === 'no') return 'Tidak Tersedia'
    return 'Tidak Ditentukan'
}

function getAvailableServicesCount() {
    const services = [
        afterSalesData.value.priority_treatment,
        afterSalesData.value.procedure_returned_goods,
        afterSalesData.value.contact_centre
    ]
    return services.filter(service => service === 'yes').length
}

function getTotalServicesCount() {
    const services = [
        afterSalesData.value.priority_treatment,
        afterSalesData.value.procedure_returned_goods,
        afterSalesData.value.contact_centre
    ]
    return services.filter(service => service && service !== '').length
}
</script>

<style scoped>
.after-sales-card {
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

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 24px;
}

.service-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.service-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.service-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.service-icon {
    width: 56px;
    height: 56px;
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.service-icon.priority {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.service-icon.returns {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.service-icon.contact {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.service-info {
    flex: 1;
}

.service-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.service-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.service-description {
    margin-top: 8px;
}

.service-description p {
    margin: 0;
    color: #374151;
    font-size: 0.875rem;
    line-height: 1.4;
}

.service-availability {
    flex-shrink: 0;
}

.availability-badge {
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

.availability-badge.available {
    background: #d1fae5;
    color: #065f46;
}

.availability-badge.unavailable {
    background: #fef2f2;
    color: #991b1b;
}

.availability-badge.unknown {
    background: #f3f4f6;
    color: #6b7280;
}

.service-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.service-specs,
.service-features {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.service-specs h6,
.service-features h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.spec-item label {
    color: #6b7280;
    font-weight: 500;
}

.spec-item span {
    color: #1f2937;
    font-weight: 600;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.feature-item i {
    color: #7c3aed;
    font-size: 0.75rem;
}

.service-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.service-summary h4 {
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

.service-summary h4 i {
    color: #7c3aed;
}

.summary-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.coverage-analysis {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.coverage-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.coverage-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.coverage-item {
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.coverage-item.available {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
}

.coverage-item.total {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.coverage-item.score {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
}

.coverage-label {
    font-weight: 600;
    font-size: 0.875rem;
}

.coverage-count {
    font-weight: 700;
    font-size: 0.875rem;
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
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
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
    .after-sales-card {
        padding: 16px;
    }
    
    .services-grid,
    .coverage-analysis,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 16px;
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
    
    .specs-grid {
        grid-template-columns: 1fr;
    }
}
</style>