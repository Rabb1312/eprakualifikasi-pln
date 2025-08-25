<template>
    <div class="engineering-card">
        <div v-if="hasData" class="engineering-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="header-info">
                    <h3>Engineering Capabilities</h3>
                    <p>Kemampuan teknis dan engineering yang dimiliki distributor</p>
                </div>
            </div>

            <!-- Gunakan konten yang sama seperti EngineeringDesignCard.vue sebelumnya -->
            <div class="content-sections">
                <!-- Design Capabilities -->
                <div class="capabilities-section">
                    <h4>
                        <i class="fas fa-drafting-compass"></i>
                        Engineering Capabilities
                    </h4>
                    <div class="capabilities-grid">
                        <div 
                            v-for="(capability, index) in engineeringCapabilities"
                            :key="index"
                            class="capability-item"
                        >
                            <div class="capability-header">
                                <div class="capability-icon">
                                    <i :class="getCapabilityIcon(capability.type)"></i>
                                </div>
                                <div class="capability-info">
                                    <h5>{{ capability.name || capability }}</h5>
                                    <span class="capability-type">{{ capability.type || 'Engineering Service' }}</span>
                                </div>
                                <div class="capability-level" v-if="capability.level">
                                    <span :class="['level-badge', capability.level.toLowerCase()]">
                                        {{ capability.level }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="capability-details" v-if="capability.description">
                                <p>{{ capability.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Standards -->
                <div class="standards-section" v-if="technicalStandards.length > 0">
                    <h4>
                        <i class="fas fa-award"></i>
                        Technical Standards & Certifications
                    </h4>
                    <div class="standards-grid">
                        <div 
                            v-for="(standard, index) in technicalStandards"
                            :key="index"
                            class="standard-item"
                        >
                            <div class="standard-badge">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="standard-info">
                                <h6>{{ standard.name || standard }}</h6>
                                <span class="standard-type">{{ standard.type || 'Technical Standard' }}</span>
                                <div class="standard-details" v-if="standard.description">
                                    <p>{{ standard.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Engineering Team -->
                <div class="team-section" v-if="engineeringTeam.length > 0">
                    <h4>
                        <i class="fas fa-users"></i>
                        Engineering Team
                    </h4>
                    <div class="team-stats">
                        <div class="team-summary">
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="summary-info">
                                    <span class="summary-number">{{ getTotalEngineers() }}</span>
                                    <span class="summary-label">Total Engineers</span>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="summary-info">
                                    <span class="summary-number">{{ getAverageExperience() }}</span>
                                    <span class="summary-label">Avg. Experience (Years)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="team-grid">
                        <div 
                            v-for="(member, index) in engineeringTeam"
                            :key="index"
                            class="team-member"
                        >
                            <div class="member-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="member-info">
                                <h6>{{ member.name || 'Engineer' }}</h6>
                                <span class="member-role">{{ member.role || 'Engineer' }}</span>
                                <div class="member-expertise" v-if="member.expertise">
                                    <span class="expertise-label">Expertise:</span>
                                    <span class="expertise-value">{{ member.expertise }}</span>
                                </div>
                                <div class="member-experience" v-if="member.experience">
                                    <span class="experience-label">Experience:</span>
                                    <span class="experience-value">{{ member.experience }} years</span>
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
                <h4>Belum Ada Data Engineering</h4>
                <p>Data kemampuan engineering belum dilengkapi untuk distributor ini</p>
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
    return !!(props.data.engineering)
})

const engineeringCapabilities = computed(() => {
    if (!props.data.engineering?.capabilities) return []
    return Array.isArray(props.data.engineering.capabilities) 
        ? props.data.engineering.capabilities 
        : []
})

const technicalStandards = computed(() => {
    if (!props.data.engineering?.standards) return []
    return Array.isArray(props.data.engineering.standards) 
        ? props.data.engineering.standards 
        : []
})

const engineeringTeam = computed(() => {
    if (!props.data.engineering?.team) return []
    return Array.isArray(props.data.engineering.team) 
        ? props.data.engineering.team 
        : []
})

function getCapabilityIcon(type) {
    const icons = {
        'design': 'fas fa-drafting-compass',
        'analysis': 'fas fa-microscope',
        'simulation': 'fas fa-chart-line',
        'consultation': 'fas fa-comments',
        'installation': 'fas fa-tools',
        'maintenance': 'fas fa-wrench',
        'testing': 'fas fa-vial',
        'quality_control': 'fas fa-check-double'
    }
    return icons[type?.toLowerCase()] || 'fas fa-cogs'
}

function getTotalEngineers() {
    return engineeringTeam.value.length
}

function getAverageExperience() {
    if (engineeringTeam.value.length === 0) return 0
    
    const totalExperience = engineeringTeam.value.reduce((sum, member) => {
        return sum + (parseInt(member.experience) || 0)
    }, 0)
    
    return Math.round(totalExperience / engineeringTeam.value.length)
}
</script>

<style scoped>
/* Gunakan style yang sama seperti EngineeringDesignCard.vue dengan perubahan warna header */
.engineering-card {
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

/* Sisa styling sama seperti sebelumnya... */
.content-sections {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.capabilities-section,
.standards-section,
.team-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.capabilities-section h4,
.standards-section h4,
.team-section h4 {
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

.capabilities-section h4 i {
    color: #059669;
}

.standards-section h4 i {
    color: #d97706;
}

.team-section h4 i {
    color: #dc2626;
}

/* Continue with the rest of the styling... */
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
</style>