<template>
    <div class="holding-company-card">
        <div v-if="hasData" class="holding-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="header-info">
                    <h3>Holding Company Information</h3>
                    <p>Informasi lengkap perusahaan induk subcontractor</p>
                </div>
            </div>

            <div class="content-sections">
                <!-- Company Basic Info -->
                <div class="info-section">
                    <h4>
                        <i class="fas fa-info-circle"></i>
                        Company Information
                    </h4>
                    <div class="info-grid">
                        <div class="info-item" v-if="data.holding_nama_perusahaan">
                            <label>Nama Perusahaan:</label>
                            <span>{{ data.holding_nama_perusahaan }}</span>
                        </div>
                        <div class="info-item" v-if="data.holding_tanggal_berdiri">
                            <label>Tanggal Berdiri:</label>
                            <span>{{ formatDate(data.holding_tanggal_berdiri) }}</span>
                        </div>
                        <div class="info-item full-width" v-if="data.holding_alamat">
                            <label>Alamat:</label>
                            <span>{{ data.holding_alamat }}</span>
                        </div>
                        <div class="info-item" v-if="data.holding_phone">
                            <label>Telepon:</label>
                            <span>{{ data.holding_phone }}</span>
                        </div>
                        <div class="info-item" v-if="data.holding_nama_direktur">
                            <label>Nama Direktur:</label>
                            <span>{{ data.holding_nama_direktur }}</span>
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="info-section" v-if="hasFinancialData">
                    <h4>
                        <i class="fas fa-money-bill-wave"></i>
                        Financial Information
                    </h4>
                    <div class="financial-grid">
                        <div class="financial-item" v-if="data.holding_modal_dasar">
                            <label>Modal Dasar:</label>
                            <span class="amount">{{ formatCurrency(data.holding_modal_dasar) }}</span>
                        </div>
                        <div class="financial-item" v-if="data.holding_modal_dikeluarkan">
                            <label>Modal Dikeluarkan:</label>
                            <span class="amount">{{ formatCurrency(data.holding_modal_dikeluarkan) }}</span>
                        </div>
                        <div class="financial-item full-width" v-if="data.holding_pemegang_saham">
                            <label>Pemegang Saham:</label>
                            <span>{{ data.holding_pemegang_saham }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Person -->
                <div class="info-section" v-if="data.holding_contact_person">
                    <h4>
                        <i class="fas fa-address-book"></i>
                        Contact Person
                    </h4>
                    <div class="contact-list">
                        <div 
                            v-for="(contact, index) in contactPersons" 
                            :key="index"
                            class="contact-item"
                        >
                            <div class="contact-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="contact-info">
                                <div class="contact-name">{{ contact.nama || 'Tidak disebutkan' }}</div>
                                <div class="contact-details">
                                    <span v-if="contact.jabatan" class="contact-position">
                                        <i class="fas fa-briefcase"></i>
                                        {{ contact.jabatan }}
                                    </span>
                                    <span v-if="contact.telepon" class="contact-phone">
                                        <i class="fas fa-phone"></i>
                                        {{ contact.telepon }}
                                    </span>
                                    <span v-if="contact.email" class="contact-email">
                                        <i class="fas fa-envelope"></i>
                                        {{ contact.email }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-building-slash"></i>
                <h4>Belum Ada Data Holding Company</h4>
                <p>Informasi holding company belum dilengkapi untuk subcontractor ini</p>
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
        props.data.holding_nama_perusahaan ||
        props.data.holding_tanggal_berdiri ||
        props.data.holding_alamat ||
        props.data.holding_phone ||
        props.data.holding_modal_dasar ||
        props.data.holding_modal_dikeluarkan ||
        props.data.holding_pemegang_saham ||
        props.data.holding_contact_person ||
        props.data.holding_nama_direktur
    )
})

const hasFinancialData = computed(() => {
    return !!(
        props.data.holding_modal_dasar ||
        props.data.holding_modal_dikeluarkan ||
        props.data.holding_pemegang_saham
    )
})

const contactPersons = computed(() => {
    if (Array.isArray(props.data.holding_contact_person)) {
        return props.data.holding_contact_person
    }
    return []
})

function formatDate(dateString) {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}

function formatCurrency(amount) {
    if (!amount) return '-'
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount)
}
</script>

<style scoped>
.holding-company-card {
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
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
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
    gap: 24px;
}

.info-section {
    background: #f8fafc;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e5e7eb;
}

.info-section h4 {
    margin: 0 0 20px 0;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-section h4 i {
    color: #3b82f6;
}

.info-grid,
.financial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.info-item,
.financial-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-item.full-width,
.financial-item.full-width {
    grid-column: 1 / -1;
}

.info-item label,
.financial-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.info-item span,
.financial-item span {
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.5;
}

.financial-item .amount {
    font-weight: 700;
    color: #059669;
    font-size: 1rem;
}

.contact-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.contact-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.contact-info {
    flex: 1;
}

.contact-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
    margin-bottom: 8px;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.contact-details span {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #6b7280;
}

.contact-details i {
    width: 16px;
    text-align: center;
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
    .holding-company-card {
        padding: 16px;
    }
    
    .info-grid,
    .financial-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>