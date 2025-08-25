<template>
    <div class="subcontractor-tab">
        <div v-if="loading" class="loading-state">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat data subcontractor...</p>
            </div>
        </div>

        <div v-else-if="error" class="error-state">
            <div class="error-content">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Gagal Memuat Data</h3>
                <p>{{ error }}</p>
                <button @click="loadData" class="btn-retry">
                    <i class="fas fa-redo"></i>
                    Coba Lagi
                </button>
            </div>
        </div>

        <div v-else-if="!specificData" class="empty-state">
            <div class="empty-content">
                <i class="fas fa-info-circle"></i>
                <h3>Belum Ada Data Subcontractor</h3>
                <p>Data khusus subcontractor belum dilengkapi untuk vendor ini.</p>
            </div>
        </div>

        <div v-else class="subcontractor-content">
            <!-- Holding Company Information -->
            <div class="section-card" v-if="hasHoldingCompanyData">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3>Holding Company Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-item" v-if="specificData.holding_nama_perusahaan">
                            <label>Nama Perusahaan:</label>
                            <span>{{ specificData.holding_nama_perusahaan }}</span>
                        </div>
                        <div class="info-item" v-if="specificData.holding_tanggal_berdiri">
                            <label>Tanggal Berdiri:</label>
                            <span>{{ formatDate(specificData.holding_tanggal_berdiri) }}</span>
                        </div>
                        <div class="info-item" v-if="specificData.holding_alamat">
                            <label>Alamat:</label>
                            <span>{{ specificData.holding_alamat }}</span>
                        </div>
                        <div class="info-item" v-if="specificData.holding_phone">
                            <label>Telepon:</label>
                            <span>{{ specificData.holding_phone }}</span>
                        </div>
                        <div class="info-item" v-if="specificData.holding_modal_dasar">
                            <label>Modal Dasar:</label>
                            <span>{{ formatCurrency(specificData.holding_modal_dasar) }}</span>
                        </div>
                        <div class="info-item" v-if="specificData.holding_modal_dikeluarkan">
                            <label>Modal Dikeluarkan:</label>
                            <span>{{ formatCurrency(specificData.holding_modal_dikeluarkan) }}</span>
                        </div>
                        <div class="info-item full-width" v-if="specificData.holding_pemegang_saham">
                            <label>Pemegang Saham:</label>
                            <span>{{ specificData.holding_pemegang_saham }}</span>
                        </div>
                        <div class="info-item" v-if="specificData.holding_nama_direktur">
                            <label>Nama Direktur:</label>
                            <span>{{ specificData.holding_nama_direktur }}</span>
                        </div>
                    </div>
                    
                    <!-- Holding Contact Person -->
                    <ContactPersonCard 
                        v-if="specificData.holding_contact_person"
                        :contacts="specificData.holding_contact_person"
                        title="Contact Person Holding Company"
                    />
                </div>
            </div>

            <!-- Facilities -->
            <div class="section-card" v-if="specificData.facilities">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Facilities Company</h3>
                </div>
                <div class="section-content">
                    <FacilitiesCard :facilities="parseFacilities(specificData.facilities)" />
                </div>
            </div>

            <!-- Other Services -->
            <div class="section-card" v-if="specificData.other_services">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3>Other Services</h3>
                </div>
                <div class="section-content">
                    <div class="service-description">
                        <p>{{ specificData.other_services }}</p>
                    </div>
                </div>
            </div>

            <!-- Employee Classification -->
            <div class="section-card" v-if="specificData.employees">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Total Permanent Construction Employees</h3>
                </div>
                <div class="section-content">
                    <EmployeeClassificationTable :employees="specificData.employees" />
                </div>
            </div>

            <!-- Scope of Work -->
            <div class="section-card" v-if="specificData.scope_of_work">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h3>Scope of Work</h3>
                </div>
                <div class="section-content">
                    <div class="scope-description">
                        <p>{{ specificData.scope_of_work }}</p>
                    </div>
                </div>
            </div>

            <!-- Major Projects -->
            <div class="section-card" v-if="specificData.major_projects">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3>Major Projects (3-5 Years)</h3>
                </div>
                <div class="section-content">
                    <ProjectsCard :projects="specificData.major_projects" />
                </div>
            </div>

            <!-- Knowledge -->
            <div class="section-card" v-if="specificData.local_regulation_knowledge">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Knowledge of Local Regulations</h3>
                </div>
                <div class="section-content">
                    <div class="knowledge-content">
                        <div class="knowledge-status">
                            <span :class="['status-badge', specificData.local_regulation_knowledge]">
                                <i :class="specificData.local_regulation_knowledge === 'yes' ? 'fas fa-check-circle' : 'fas fa-times-circle'"></i>
                                {{ specificData.local_regulation_knowledge === 'yes' ? 'Ya, mengetahui' : 'Tidak mengetahui' }}
                            </span>
                        </div>
                        <div v-if="specificData.regulation_knowledge_details" class="knowledge-details">
                            <label>Detail Pengetahuan:</label>
                            <p>{{ specificData.regulation_knowledge_details }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import ContactPersonCard from '../shared/ContactPersonCard.vue'
import FacilitiesCard from '../shared/FacilitiesCard.vue'
import ProjectsCard from '../shared/ProjectsCard.vue'
import EmployeeClassificationTable from './EmployeeClassificationTable.vue'

const props = defineProps({
    vendor: {
        type: Object,
        required: true
    },
    data: {
        type: Object,
        default: () => ({})
    }
})

const loading = ref(false)
const error = ref(null)
const specificData = ref(null)

const hasHoldingCompanyData = computed(() => {
    if (!specificData.value) return false
    
    return !!(
        specificData.value.holding_nama_perusahaan ||
        specificData.value.holding_tanggal_berdiri ||
        specificData.value.holding_alamat ||
        specificData.value.holding_phone ||
        specificData.value.holding_modal_dasar ||
        specificData.value.holding_modal_dikeluarkan ||
        specificData.value.holding_pemegang_saham ||
        specificData.value.holding_contact_person ||
        specificData.value.holding_nama_direktur
    )
})

function parseFacilities(facilities) {
    if (Array.isArray(facilities)) {
        return facilities.map(facility => {
            if (typeof facility === 'string') {
                return {
                    name: getFacilityName(facility),
                    type: facility,
                    status: 'Available'
                }
            }
            return facility
        })
    }
    return []
}

function getFacilityName(facilityCode) {
    const facilityNames = {
        'mechanical_erection_power': 'Mechanical Erection Power',
        'civil_construction': 'Civil Construction',
        'electrical_installation': 'Electrical Installation',
        'piping_installation': 'Piping Installation',
        'instrumentation': 'Instrumentation',
        'welding': 'Welding',
        'painting': 'Painting',
        'insulation': 'Insulation'
    }
    return facilityNames[facilityCode] || facilityCode
}

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

function loadData() {
    loading.value = true
    error.value = null
    
    try {
        // Set data from props
        specificData.value = props.data
        console.log('Subcontractor data loaded:', specificData.value)
    } catch (err) {
        error.value = err.message
        console.error('Error loading subcontractor data:', err)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    loadData()
})
</script>

<style scoped>
.subcontractor-tab {
    padding: 24px;
    min-height: 400px;
}

.loading-state,
.error-state,
.empty-state {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    text-align: center;
}

.loading-spinner i {
    font-size: 2rem;
    color: #3b82f6;
    margin-bottom: 16px;
}

.error-content,
.empty-content {
    max-width: 400px;
}

.error-content i,
.empty-content i {
    font-size: 3rem;
    color: #ef4444;
    margin-bottom: 16px;
}

.empty-content i {
    color: #6b7280;
}

.error-content h3,
.empty-content h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 1.25rem;
}

.error-content p,
.empty-content p {
    margin: 0 0 20px 0;
    color: #6b7280;
    line-height: 1.5;
}

.btn-retry {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.btn-retry:hover {
    background: #2563eb;
}

.subcontractor-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.section-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.section-header {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    padding: 20px 24px;
    color: white;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.section-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
}

.section-content {
    padding: 24px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.info-item span {
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.5;
}

.service-description,
.scope-description {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
    border-left: 4px solid #3b82f6;
}

.service-description p,
.scope-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
}

.knowledge-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.knowledge-status {
    display: flex;
    align-items: center;
    gap: 12px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
}

.status-badge.yes {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.no {
    background: #fef2f2;
    color: #991b1b;
}

.knowledge-details {
    background: #f8fafc;
    padding: 20px;
    border-radius: 12px;
}

.knowledge-details label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 0.875rem;
}

.knowledge-details p {
    margin: 0;
    color: #6b7280;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .subcontractor-tab {
        padding: 16px;
    }
    
    .section-content {
        padding: 16px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        padding: 16px;
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
}
</style>