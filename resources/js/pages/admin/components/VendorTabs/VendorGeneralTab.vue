<template>
    <div class="vendor-general-tab">
        <div class="tab-content-wrapper">
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-building"></i>
                    Informasi Perusahaan
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nomor Vendor</label>
                        <span class="vendor-number">{{ vendor.nomor_vendor }}</span>
                    </div>
                    <div class="info-item">
                        <label>Nama Perusahaan</label>
                        <span>{{ vendor.nama_perusahaan }}</span>
                    </div>
                    <div class="info-item">
                        <label>Tipe Perusahaan</label>
                        <span :class="['type-badge', vendor.tipe_perusahaan?.toLowerCase()]">
                            {{ getTypeName(vendor.tipe_perusahaan) }}
                        </span>
                    </div>
                    <div class="info-item" v-if="vendor.npwp">
                        <label>NPWP</label>
                        <span>{{ vendor.npwp }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.tanggal_berdiri">
                        <label>Tanggal Berdiri</label>
                        <span>{{ formatDate(vendor.tanggal_berdiri) }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.tanggal_beroperasi">
                        <label>Tanggal Beroperasi</label>
                        <span>{{ formatDate(vendor.tanggal_beroperasi) }}</span>
                    </div>
                </div>
            </div>

            <div class="info-section" v-if="hasContactInfo">
                <h3 class="section-title"><i class="fas fa-phone"></i>Informasi Kontak</h3>
                <div class="info-grid">
                    <div class="info-item" v-if="vendor.phone">
                        <label>Telepon</label>
                        <span>{{ vendor.phone }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.phone_pusat">
                        <label>Telepon Pusat</label>
                        <span>{{ vendor.phone_pusat }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.website">
                        <label>Website</label>
                        <a :href="vendor.website" target="_blank" class="website-link">
                            {{ vendor.website }} <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="info-section" v-if="hasAddressInfo">
                <h3 class="section-title"><i class="fas fa-map-marker-alt"></i>Informasi Alamat</h3>
                <div class="address-grid">
                    <div class="address-item" v-if="vendor.alamat">
                        <label>Alamat Utama</label>
                        <span class="address-text">{{ vendor.alamat }}</span>
                    </div>
                    <div class="address-item" v-if="vendor.alamat_kantor_pusat">
                        <label>Alamat Kantor Pusat</label>
                        <span class="address-text">{{ vendor.alamat_kantor_pusat }}</span>
                    </div>
                    <div class="address-item" v-if="vendor.alamat_kantor_operasional">
                        <label>Alamat Kantor Operasional</label>
                        <span class="address-text">{{ vendor.alamat_kantor_operasional }}</span>
                    </div>
                    <div class="address-item" v-if="vendor.alamat_perusahaan_induk">
                        <label>Alamat Perusahaan Induk</label>
                        <span class="address-text">{{ vendor.alamat_perusahaan_induk }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.kode_pos">
                        <label>Kode Pos</label>
                        <span>{{ vendor.kode_pos }}</span>
                    </div>
                </div>
            </div>

            <div class="info-section" v-if="vendor.contact_person">
                <h3 class="section-title"><i class="fas fa-user-tie"></i>Contact Person</h3>
                <ContactPersonCard :contacts="vendor.contact_person" />
            </div>

            <div class="management-section" v-if="hasManagementInfo">
                <h3 class="section-title"><i class="fas fa-users"></i>Struktur Manajemen</h3>
                <div class="management-grid">
                    <ManagementCard
                        v-if="vendor.top_level"
                        title="Top Level Management"
                        :personnel="vendor.top_level"
                        icon="fas fa-crown"
                        color="gold"
                    />
                    <ManagementCard
                        v-if="vendor.mid_level"
                        title="Mid Level Management"
                        :personnel="vendor.mid_level"
                        icon="fas fa-user-cog"
                        color="blue"
                    />
                    <ManagementCard
                        v-if="vendor.sales_marketing"
                        title="Sales & Marketing"
                        :personnel="vendor.sales_marketing"
                        icon="fas fa-chart-line"
                        color="green"
                    />
                </div>
            </div>

            <div class="info-section" v-if="hasAdditionalInfo">
                <h3 class="section-title"><i class="fas fa-info-circle"></i>Informasi Tambahan</h3>
                <div class="info-grid">
                    <div class="info-item" v-if="vendor.nama_perusahaan_induk">
                        <label>Nama Perusahaan Induk</label>
                        <span>{{ vendor.nama_perusahaan_induk }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.sumber_informasi">
                        <label>Sumber Informasi</label>
                        <span class="capitalize">{{ vendor.sumber_informasi }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.rekomendasi_dari">
                        <label>Rekomendasi Dari</label>
                        <span>{{ vendor.rekomendasi_dari }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.lainnya">
                        <label>Lainnya</label>
                        <span>{{ vendor.lainnya }}</span>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h3 class="section-title"><i class="fas fa-chart-pie"></i>Status Vendor</h3>
                <div class="status-grid">
                    <div class="status-card">
                        <div class="status-header">
                            <i class="fas fa-percentage"></i>
                            <span>Kelengkapan Profil</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div 
                                    class="progress-fill"
                                    :style="{ width: vendor.completion_percentage + '%' }"
                                ></div>
                            </div>
                            <span class="progress-text">{{ vendor.completion_percentage }}%</span>
                        </div>
                    </div>
                    <div class="status-card">
                        <div class="status-header">
                            <i class="fas fa-check-circle"></i>
                            <span>Status Verifikasi</span>
                        </div>
                        <div class="verification-status">
                            <span v-if="vendor.verified_at" class="verified">
                                <i class="fas fa-check-circle"></i>
                                Terverifikasi
                            </span>
                            <span v-else class="unverified">
                                <i class="fas fa-clock"></i>
                                Belum Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import ContactPersonCard from '../shared/ContactPersonCard.vue'
import ManagementCard from '../shared/ManagementCard.vue'

const props = defineProps({ vendor: { type: Object, required: true } })

const hasContactInfo = computed(() =>
    props.vendor.phone || props.vendor.phone_pusat || props.vendor.website
)
const hasAddressInfo = computed(() =>
    props.vendor.alamat || props.vendor.alamat_kantor_pusat || props.vendor.alamat_kantor_operasional ||
    props.vendor.alamat_perusahaan_induk || props.vendor.kode_pos
)
const hasManagementInfo = computed(() =>
    props.vendor.top_level || props.vendor.mid_level || props.vendor.sales_marketing
)
const hasAdditionalInfo = computed(() =>
    props.vendor.nama_perusahaan_induk || props.vendor.sumber_informasi ||
    props.vendor.rekomendasi_dari || props.vendor.lainnya
)

function getTypeName(type) {
    const types = { DS: "Distributor", SC: "Subcontractor", MF: "Manufacturer", FW: "Forwarder" }
    return types[type] || type
}
function formatDate(dateString) {
    if (!dateString) return "-"
    const date = new Date(dateString)
    return date.toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" })
}
</script>

<style scoped>
.vendor-general-tab {
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

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.info-item span {
    font-weight: 500;
    color: #1f2937;
}

.vendor-number {
    font-family: 'JetBrains Mono', monospace;
    background: #1f2937;
    color: white;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-block;
}

.website-link {
    color: #2563eb;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 0.3s ease;
}

.website-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

.address-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.address-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.address-text {
    background: white;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    line-height: 1.5;
}

.management-section {
    background: #f9fafb;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
}

.management-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.status-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.status-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    font-weight: 600;
    color: #374151;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress-bar {
    flex: 1;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #059669, #10b981);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.progress-text {
    font-weight: 700;
    color: #059669;
    min-width: 45px;
    text-align: right;
}

.verification-status {
    display: flex;
    align-items: center;
    gap: 8px;
}

.verified {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #059669;
    font-weight: 600;
}

.unverified {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #d97706;
    font-weight: 600;
}

.capitalize {
    text-transform: capitalize;
}

/* Type badges */
.type-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    display: inline-block;
}

.type-badge.ds {
    background: #e0e7ff;
    color: #3730a3;
}

.type-badge.sc {
    background: #fef3c7;
    color: #92400e;
}

.type-badge.mf {
    background: #d1fae5;
    color: #065f46;
}

.type-badge.fw {
    background: #fed7d7;
    color: #991b1b;
}

@media (max-width: 768px) {
    .vendor-general-tab {
        padding: 16px;
    }
    
    .tab-content-wrapper {
        gap: 24px;
    }
    
    .info-section {
        padding: 20px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .management-grid {
        grid-template-columns: 1fr;
    }
}
</style>