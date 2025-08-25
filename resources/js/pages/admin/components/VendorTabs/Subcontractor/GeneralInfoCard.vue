<template>
    <div class="subcontractor-general-info">
        <div class="section-header">
            <div class="header-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="header-info">
                <h3>Informasi Umum Subcontractor</h3>
                <p>Data profil utama vendor Subcontractor</p>
            </div>
        </div>

        <div class="info-sections">
            <!-- Company Info -->
            <div class="info-section">
                <h4>
                    <i class="fas fa-id-card"></i>
                    Identitas Perusahaan
                </h4>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nomor Vendor:</label>
                        <span>{{ vendor.nomor_vendor }}</span>
                    </div>
                    <div class="info-item">
                        <label>Nama Perusahaan:</label>
                        <span>{{ vendor.nama_perusahaan }}</span>
                    </div>
                    <div class="info-item">
                        <label>Tipe Perusahaan:</label>
                        <span class="type-badge sc">Subcontractor</span>
                    </div>
                    <div class="info-item" v-if="vendor.npwp">
                        <label>NPWP:</label>
                        <span>{{ vendor.npwp }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.tanggal_berdiri">
                        <label>Tanggal Berdiri:</label>
                        <span>{{ formatDate(vendor.tanggal_berdiri) }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="info-section" v-if="vendor.phone || vendor.website || vendor.email">
                <h4>
                    <i class="fas fa-phone"></i>
                    Kontak Perusahaan
                </h4>
                <div class="info-grid">
                    <div class="info-item" v-if="vendor.phone">
                        <label>Telepon:</label>
                        <span>{{ vendor.phone }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.email">
                        <label>Email:</label>
                        <span>{{ vendor.email }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.website">
                        <label>Website:</label>
                        <a :href="vendor.website" target="_blank" class="website-link">
                            {{ vendor.website }} <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Address Info -->
            <div class="info-section" v-if="vendor.alamat || vendor.kode_pos">
                <h4>
                    <i class="fas fa-map-marker-alt"></i>
                    Alamat Perusahaan
                </h4>
                <div class="info-grid">
                    <div class="info-item" v-if="vendor.alamat">
                        <label>Alamat:</label>
                        <span>{{ vendor.alamat }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.kode_pos">
                        <label>Kode Pos:</label>
                        <span>{{ vendor.kode_pos }}</span>
                    </div>
                </div>
            </div>

            <!-- Legal Info -->
            <div class="info-section" v-if="vendor.legal_status || vendor.izin_usaha">
                <h4>
                    <i class="fas fa-file-contract"></i>
                    Legalitas & Izin
                </h4>
                <div class="info-grid">
                    <div class="info-item" v-if="vendor.legal_status">
                        <label>Status Badan Hukum:</label>
                        <span>{{ vendor.legal_status }}</span>
                    </div>
                    <div class="info-item" v-if="vendor.izin_usaha">
                        <label>Izin Usaha:</label>
                        <span>{{ vendor.izin_usaha }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div class="info-section">
                <h4>
                    <i class="fas fa-chart-pie"></i>
                    Status Vendor
                </h4>
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
const props = defineProps({
    vendor: {
        type: Object,
        required: true
    }
})

function formatDate(dateString) {
    if (!dateString) return "-"
    const date = new Date(dateString)
    return date.toLocaleDateString("id-ID", { day: "2-digit", month: "long", year: "numeric" })
}
</script>

<style scoped>
.subcontractor-general-info {
    padding: 24px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    margin-bottom: 24px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 16px;
}

.header-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #fef3c7 0%, #f59e0b 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #92400e;
    font-size: 1.5rem;
}

.header-info h3 {
    margin: 0;
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 700;
}
.header-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.info-sections {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-section h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 12px 0;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 12px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.info-item span,
.info-item a {
    font-weight: 500;
    color: #1f2937;
    font-size: 0.95rem;
    text-decoration: none;
}

.website-link {
    color: #2563eb;
}
.website-link:hover {
    text-decoration: underline;
    color: #1d4ed8;
}

.type-badge.sc {
    background: #fef3c7;
    color: #92400e;
    padding: 4px 10px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.9rem;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-top: 8px;
}

.status-card {
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.status-header {
    display: flex;
    align-items: center;
    gap: 8px;
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
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.progress-text {
    font-weight: 700;
    color: #f59e0b;
    min-width: 40px;
    text-align: right;
}

.verification-status {
    display: flex;
    align-items: center;
    gap: 8px;
}

.verified {
    color: #059669;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

.unverified {
    color: #d97706;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

@media (max-width: 768px) {
    .subcontractor-general-info {
        padding: 16px;
    }
    .info-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
}
</style>