<template>
    <div class="facilities-card">
        <div v-if="hasFacilities" class="facilities-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="header-info">
                    <h3>Facilities Company</h3>
                    <p>Fasilitas yang dimiliki oleh subcontractor</p>
                </div>
            </div>

            <div class="facilities-grid">
                <div 
                    v-for="(facility, index) in facilitiesList" 
                    :key="index"
                    class="facility-item"
                >
                    <div class="facility-header">
                        <div class="facility-icon">
                            <i :class="getFacilityIcon(facility.type)"></i>
                        </div>
                        <div class="facility-info">
                            <h5>{{ facility.name }}</h5>
                            <span class="facility-category">{{ facility.category || 'General Facility' }}</span>
                        </div>
                        <div class="facility-status">
                            <span class="status-badge available">
                                <i class="fas fa-check-circle"></i>
                                Available
                            </span>
                        </div>
                    </div>
                    
                    <div class="facility-description" v-if="facility.description">
                        <p>{{ facility.description }}</p>
                    </div>
                </div>
            </div>

            <div class="facilities-summary">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="summary-info">
                        <h4>{{ facilitiesList.length }}</h4>
                        <span>Total Facilities Available</span>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-tools"></i>
                <h4>Belum Ada Data Fasilitas</h4>
                <p>Data fasilitas perusahaan belum dilengkapi untuk subcontractor ini</p>
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

const hasFacilities = computed(() => {
    return !!(props.data.facilities && Array.isArray(props.data.facilities) && props.data.facilities.length > 0)
})

const facilitiesList = computed(() => {
    if (!props.data.facilities) return []
    
    return props.data.facilities.map(facility => {
        if (typeof facility === 'string') {
            return {
                name: getFacilityName(facility),
                type: facility,
                category: getFacilityCategory(facility),
                description: getFacilityDescription(facility)
            }
        }
        return facility
    })
})

function getFacilityName(facilityCode) {
    const facilityNames = {
        'mechanical_erection_power': 'Mechanical Erection Power',
        'civil_construction': 'Civil Construction',
        'electrical_installation': 'Electrical Installation',
        'piping_installation': 'Piping Installation',
        'instrumentation': 'Instrumentation',
        'welding': 'Welding',
        'painting': 'Painting',
        'insulation': 'Insulation',
        'scaffolding': 'Scaffolding',
        'crane_operation': 'Crane Operation',
        'heavy_lifting': 'Heavy Lifting Equipment'
    }
    return facilityNames[facilityCode] || facilityCode.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

function getFacilityCategory(facilityCode) {
    const categories = {
        'mechanical_erection_power': 'Mechanical',
        'civil_construction': 'Civil',
        'electrical_installation': 'Electrical',
        'piping_installation': 'Piping',
        'instrumentation': 'Instrumentation',
        'welding': 'Welding',
        'painting': 'Finishing',
        'insulation': 'Insulation',
        'scaffolding': 'Support',
        'crane_operation': 'Heavy Equipment',
        'heavy_lifting': 'Heavy Equipment'
    }
    return categories[facilityCode] || 'General'
}

function getFacilityDescription(facilityCode) {
    const descriptions = {
        'mechanical_erection_power': 'Fasilitas untuk pemasangan dan erection equipment mechanical dan power',
        'civil_construction': 'Fasilitas untuk pekerjaan konstruksi sipil dan infrastruktur',
        'electrical_installation': 'Fasilitas untuk instalasi sistem kelistrikan dan kontrol',
        'piping_installation': 'Fasilitas untuk instalasi sistem perpipaan dan plumbing',
        'instrumentation': 'Fasilitas untuk instalasi dan kalibrasi instrumentation',
        'welding': 'Fasilitas untuk pekerjaan pengelasan dan fabrikasi logam',
        'painting': 'Fasilitas untuk pekerjaan pengecatan dan finishing',
        'insulation': 'Fasilitas untuk pekerjaan insulasi thermal dan acoustic'
    }
    return descriptions[facilityCode] || 'Fasilitas untuk mendukung operasional perusahaan'
}

function getFacilityIcon(facilityType) {
    const icons = {
        'mechanical_erection_power': 'fas fa-cogs',
        'civil_construction': 'fas fa-hammer',
        'electrical_installation': 'fas fa-bolt',
        'piping_installation': 'fas fa-pipe',
        'instrumentation': 'fas fa-gauge-high',
        'welding': 'fas fa-fire',
        'painting': 'fas fa-paint-brush',
        'insulation': 'fas fa-shield-alt',
        'scaffolding': 'fas fa-building',
        'crane_operation': 'fas fa-truck-pickup',
        'heavy_lifting': 'fas fa-weight-hanging'
    }
    return icons[facilityType] || 'fas fa-tools'
}
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
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

.facilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.facility-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.facility-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.facility-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
}

.facility-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
}

.facility-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #fef3c7 0%, #fcd34d 100%);
    color: #d97706;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.facility-info {
    flex: 1;
    min-width: 0;
}

.facility-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
    line-height: 1.3;
}

.facility-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.facility-status {
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

.facility-description {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid #f59e0b;
}

.facility-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.facilities-summary {
    display: flex;
    justify-content: center;
}

.summary-card {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    padding: 24px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.summary-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.summary-info h4 {
    margin: 0 0 4px 0;
    font-size: 2rem;
    font-weight: 700;
}

.summary-info span {
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
    .facilities-card {
        padding: 16px;
    }
    
    .facilities-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .facility-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .summary-card {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
}
</style>