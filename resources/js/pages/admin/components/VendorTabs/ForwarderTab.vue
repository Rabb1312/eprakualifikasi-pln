<template>
    <div class="forwarder-tab">
        <div class="tab-content-wrapper">
            <!-- Other Services -->
            <div class="info-section" v-if="hasOtherServices">
                <h3 class="section-title">
                    <i class="fas fa-concierge-bell"></i>
                    Layanan Tambahan
                </h3>
                <div class="services-grid">
                    <ServiceCard
                        title="Expediting Services"
                        :available="data?.able_to_conduct_expediting"
                        description="Kemampuan melakukan expediting untuk mempercepat proses"
                        icon="fas fa-shipping-fast"
                    />
                    
                    <ServiceCard
                        title="Status Reporting"
                        :available="data?.able_to_submit_regular_status_report"
                        description="Penyediaan laporan status reguler untuk tracking"
                        icon="fas fa-chart-bar"
                    />
                    
                    <ServiceCard
                        title="Electrical Supervision"
                        :available="data?.able_to_have_scope_electrical_supervision"
                        description="Supervisi elektrikal dalam lingkup pekerjaan"
                        icon="fas fa-bolt"
                    />
                    
                    <ServiceCard
                        title="Temporary Jetty"
                        :available="data?.having_ability_make_temporary_jetty"
                        description="Kemampuan membuat jetty sementara"
                        icon="fas fa-anchor"
                    />
                    
                    <ServiceCard
                        title="Custom Services"
                        :available="data?.other_services_others"
                        :description="data?.other_services_others_description || 'Layanan khusus lainnya'"
                        icon="fas fa-tools"
                    />
                </div>
            </div>

            <!-- Heavy Lift Capability -->
            <div class="info-section" v-if="hasHeavyLiftInfo">
                <h3 class="section-title">
                    <i class="fas fa-weight-hanging"></i>
                    Kemampuan Heavy Lift
                </h3>
                <div class="heavy-lift-card">
                    <div class="capability-info">
                        <div class="info-item" v-if="data?.heavy_lift_capability">
                            <label>Kapasitas Heavy Lift</label>
                            <span class="capability-value">{{ data.heavy_lift_capability }}</span>
                        </div>
                        
                        <div class="cooperation-section" v-if="data?.heavy_lift_cooperation_companies">
                            <label>Perusahaan Kerjasama</label>
                            <div class="cooperation-text">{{ data.heavy_lift_cooperation_companies }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monitoring & Management -->
            <div class="info-section" v-if="hasMonitoringInfo">
                <h3 class="section-title">
                    <i class="fas fa-monitor-heart-rate"></i>
                    Monitoring & Manajemen
                </h3>
                <div class="monitoring-card">
                    <div class="monitoring-type" v-if="data?.monitoring_management">
                        <label>Tipe Monitoring</label>
                        <span class="monitoring-value">{{ data.monitoring_management }}</span>
                    </div>
                    
                    <div class="monitoring-description" v-if="data?.monitoring_coordination_description">
                        <label>Deskripsi Koordinasi</label>
                        <div class="description-text">{{ data.monitoring_coordination_description }}</div>
                    </div>
                </div>
            </div>

            <!-- Software Systems -->
            <div class="info-section" v-if="hasSoftwareInfo">
                <h3 class="section-title">
                    <i class="fas fa-laptop-code"></i>
                    Sistem Software
                </h3>
                <div class="software-grid">
                    <div class="software-card">
                        <div class="software-header">
                            <i class="fas fa-desktop"></i>
                            <span>Existing Software</span>
                        </div>
                        <div class="software-status">
                            <span :class="['status-badge', data?.software_existing ? 'available' : 'not-available']">
                                {{ data?.software_existing ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                    </div>

                    <div class="software-card">
                        <div class="software-header">
                            <i class="fas fa-truck"></i>
                            <span>Shipment Tracking</span>
                        </div>
                        <div class="software-status">
                            <span :class="['status-badge', data?.software_shipment_tracking ? 'available' : 'not-available']">
                                {{ data?.software_shipment_tracking ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                        <div v-if="data?.software_shipment_tracking_description" class="software-description">
                            {{ data.software_shipment_tracking_description }}
                        </div>
                    </div>

                    <div class="software-card" v-if="data?.software_others">
                        <div class="software-header">
                            <i class="fas fa-cogs"></i>
                            <span>Software Lainnya</span>
                        </div>
                        <div class="software-status">
                            <span class="status-badge available">Tersedia</span>
                        </div>
                        <div v-if="data?.software_others_description" class="software-description">
                            {{ data.software_others_description }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Communication System -->
            <div class="info-section" v-if="hasCommunicationInfo">
                <h3 class="section-title">
                    <i class="fas fa-satellite-dish"></i>
                    Sistem Komunikasi
                </h3>
                <div class="communication-card">
                    <div class="communication-type" v-if="data?.communication_system">
                        <label>Tipe Sistem</label>
                        <span class="system-value">{{ data.communication_system }}</span>
                    </div>
                    
                    <div class="communication-description" v-if="data?.communication_system_description">
                        <label>Deskripsi Sistem</label>
                        <div class="description-text">{{ data.communication_system_description }}</div>
                    </div>
                </div>
            </div>

            <!-- Branch Offices -->
            <div class="info-section" v-if="hasBranchOffices">
                <h3 class="section-title">
                    <i class="fas fa-map-marked-alt"></i>
                    Kantor Cabang
                </h3>
                <div class="branch-offices-grid">
                    <div class="office-card" v-if="data?.indonesian_branch_offices">
                        <div class="office-header">
                            <i class="fas fa-flag"></i>
                            <span>Kantor Cabang Indonesia</span>
                        </div>
                        <BranchOfficesList :offices="data.indonesian_branch_offices" />
                    </div>

                    <div class="office-card" v-if="data?.overseas_branch_offices">
                        <div class="office-header">
                            <i class="fas fa-globe"></i>
                            <span>Kantor Cabang Luar Negeri</span>
                        </div>
                        <BranchOfficesList :offices="data.overseas_branch_offices" />
                    </div>
                </div>
            </div>

            <!-- Transportation Partners -->
            <div class="info-section" v-if="hasTransportationInfo">
                <h3 class="section-title">
                    <i class="fas fa-handshake"></i>
                    Partner Transportasi
                </h3>
                <div class="transportation-grid">
                    <div class="transport-card" v-if="hasShippingInfo">
                        <div class="transport-header">
                            <i class="fas fa-ship"></i>
                            <span>Shipping Lines</span>
                        </div>
                        <div class="relation-info">
                            <span class="relation-type">{{ data.shipping_line_relation }}</span>
                        </div>
                        <PartnersList v-if="data?.shipping_lines" :partners="data.shipping_lines" />
                    </div>

                    <div class="transport-card" v-if="hasAirlineInfo">
                        <div class="transport-header">
                            <i class="fas fa-plane"></i>
                            <span>Airlines</span>
                        </div>
                        <div class="relation-info">
                            <span class="relation-type">{{ data.airlines_relation }}</span>
                        </div>
                        <PartnersList v-if="data?.airlines" :partners="data.airlines" />
                    </div>
                </div>
            </div>

            <!-- Insurance -->
            <div class="info-section" v-if="hasInsuranceInfo">
                <h3 class="section-title">
                    <i class="fas fa-shield-alt"></i>
                    Asuransi
                </h3>
                <div class="insurance-grid">
                    <InsuranceCard
                        title="All Risk Coverage"
                        :available="data?.insurance_all_risk_covered_by_company"
                        description="Perusahaan menanggung semua risiko"
                        icon="fas fa-umbrella"
                    />
                    
                    <InsuranceCard
                        title="Liability Insurance"
                        :available="data?.insurance_liability_with_insurance_company"
                        description="Asuransi tanggung jawab dengan perusahaan asuransi"
                        icon="fas fa-handshake"
                    />
                    
                    <InsuranceCard
                        title="Negotiable Insurance"
                        :available="data?.insurance_negotiable_with_without_insurance"
                        description="Asuransi dapat dinegosiasikan"
                        icon="fas fa-comments"
                    />
                </div>
                
                <div class="insurance-details" v-if="data?.insurance_claim_arrangement_description">
                    <label>Pengaturan Klaim Asuransi</label>
                    <div class="details-text">{{ data.insurance_claim_arrangement_description }}</div>
                </div>
            </div>

            <!-- Manpower Support -->
            <div class="info-section" v-if="hasManpowerInfo">
                <h3 class="section-title">
                    <i class="fas fa-users"></i>
                    Dukungan Tenaga Kerja
                </h3>
                <div class="manpower-card">
                    <div class="manpower-support" v-if="data?.manpower_support">
                        <label>Tipe Dukungan</label>
                        <span class="support-value">{{ data.manpower_support }}</span>
                    </div>
                    
                    <div class="certifications" v-if="data?.manpower_certifications">
                        <label>Sertifikasi Tenaga Kerja</label>
                        <CertificationsList :certifications="data.manpower_certifications" />
                    </div>
                </div>
            </div>

            <!-- No Data State -->
            <div v-if="!data" class="no-data-state">
                <i class="fas fa-info-circle"></i>
                <h4>Belum Ada Data Forwarder</h4>
                <p>Data khusus forwarder belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import ServiceCard from '../shared/ServiceCard.vue'
import BranchOfficesList from '../shared/BranchOfficesList.vue'
import PartnersList from '../shared/PartnersList.vue'
import InsuranceCard from '../shared/InsuranceCard.vue'
import CertificationsList from '../shared/CertificationsList.vue'

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

const hasOtherServices = computed(() => {
    return props.data?.able_to_conduct_expediting ||
           props.data?.able_to_submit_regular_status_report ||
           props.data?.able_to_have_scope_electrical_supervision ||
           props.data?.having_ability_make_temporary_jetty ||
           props.data?.other_services_others
})

const hasHeavyLiftInfo = computed(() => {
    return props.data?.heavy_lift_capability || props.data?.heavy_lift_cooperation_companies
})

const hasMonitoringInfo = computed(() => {
    return props.data?.monitoring_management || props.data?.monitoring_coordination_description
})

const hasSoftwareInfo = computed(() => {
    return props.data?.software_existing || props.data?.software_shipment_tracking || props.data?.software_others
})

const hasCommunicationInfo = computed(() => {
    return props.data?.communication_system || props.data?.communication_system_description
})

const hasBranchOffices = computed(() => {
    return props.data?.indonesian_branch_offices || props.data?.overseas_branch_offices
})

const hasTransportationInfo = computed(() => {
    return hasShippingInfo.value || hasAirlineInfo.value
})

const hasShippingInfo = computed(() => {
    return props.data?.shipping_line_relation || props.data?.shipping_lines
})

const hasAirlineInfo = computed(() => {
    return props.data?.airlines_relation || props.data?.airlines
})

const hasInsuranceInfo = computed(() => {
    return props.data?.insurance_all_risk_covered_by_company ||
           props.data?.insurance_liability_with_insurance_company ||
           props.data?.insurance_negotiable_with_without_insurance ||
           props.data?.insurance_claim_arrangement_description
})

const hasManpowerInfo = computed(() => {
    return props.data?.manpower_support || props.data?.manpower_certifications
})
</script>

<style scoped>
.forwarder-tab {
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

.services-grid,
.software-grid,
.branch-offices-grid,
.transportation-grid,
.insurance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.heavy-lift-card,
.monitoring-card,
.communication-card,
.manpower-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.capability-value,
.monitoring-value,
.system-value,
.support-value {
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.cooperation-section,
.monitoring-description,
.communication-description,
.insurance-details {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.cooperation-text,
.description-text,
.details-text {
    background: #f9fafb;
    padding: 12px 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    line-height: 1.5;
    color: #374151;
    margin-top: 8px;
}

.software-card,
.office-card,
.transport-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.software-header,
.office-header,
.transport-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    font-weight: 600;
    color: #1f2937;
}

.software-header i,
.office-header i,
.transport-header i {
    color: #6366f1;
}

.software-status {
    margin-bottom: 12px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-badge.available {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.not-available {
    background: #fef2f2;
    color: #991b1b;
}

.software-description {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.5;
    margin-top: 8px;
}

.relation-info {
    margin-bottom: 16px;
}

.relation-type {
    display: inline-block;
    background: #e0e7ff;
    color: #3730a3;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
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
    .forwarder-tab {
        padding: 16px;
    }
    
    .tab-content-wrapper {
        gap: 24px;
    }
    
    .info-section {
        padding: 20px;
    }
    
    .services-grid,
    .software-grid,
    .branch-offices-grid,
    .transportation-grid,
    .insurance-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .heavy-lift-card,
    .monitoring-card,
    .communication-card,
    .manpower-card {
        padding: 20px;
    }
}
</style>