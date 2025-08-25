<template>
    <div class="personnel-card">
        <div v-if="hasData" class="personnel-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="header-info">
                    <h3>Personnel</h3>
                    <p>Struktur personel dan tenaga kerja manufacturer</p>
                </div>
                <div class="personnel-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ personnelList.length }}</span>
                        <span class="stat-label">Personnel</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Personnel Grid -->
                <div class="personnel-grid">
                    <div 
                        v-for="(person, index) in personnelList"
                        :key="`person-${index}`"
                        class="personnel-item"
                    >
                        <div class="personnel-header">
                            <div class="personnel-avatar">
                                <i :class="getPersonnelIcon(person?.position || person?.role)"></i>
                            </div>
                            <div class="personnel-info">
                                <h5>{{ getPersonName(person, index) }}</h5>
                                <span class="personnel-position">{{ getPersonPosition(person) }}</span>
                                <div class="personnel-department" v-if="person?.department">
                                    <i class="fas fa-building"></i>
                                    <span>{{ person.department }}</span>
                                </div>
                            </div>
                            <div class="personnel-level" v-if="person?.level">
                                <span :class="['level-badge', getLevelClass(person.level)]">
                                    {{ person.level }}
                                </span>
                            </div>
                        </div>

                        <div class="personnel-details">
                            <div class="personnel-qualifications" v-if="person?.qualifications">
                                <h6>Qualifications:</h6>
                                <div class="qualifications-list">
                                    <div 
                                        v-for="(qualification, qualIndex) in getQualifications(person.qualifications)"
                                        :key="qualIndex"
                                        class="qualification-item"
                                    >
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>{{ qualification }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="personnel-experience" v-if="person?.experience">
                                <h6>Experience:</h6>
                                <div class="experience-info">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ person.experience }}</span>
                                </div>
                            </div>

                            <div class="personnel-responsibilities" v-if="person?.responsibilities">
                                <h6>Responsibilities:</h6>
                                <div class="responsibilities-list">
                                    <div 
                                        v-for="(responsibility, respIndex) in getResponsibilities(person.responsibilities)"
                                        :key="respIndex"
                                        class="responsibility-item"
                                    >
                                        <i class="fas fa-check"></i>
                                        <span>{{ responsibility }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="personnel-certifications" v-if="person?.certifications">
                                <h6>Certifications:</h6>
                                <div class="certifications-tags">
                                    <span 
                                        v-for="(cert, certIndex) in getCertifications(person.certifications)"
                                        :key="certIndex"
                                        class="certification-tag"
                                    >
                                        <i class="fas fa-certificate"></i>
                                        {{ cert }}
                                    </span>
                                </div>
                            </div>

                            <div class="personnel-contact" v-if="person?.email || person?.phone">
                                <h6>Contact Information:</h6>
                                <div class="contact-list">
                                    <div v-if="person.email" class="contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <span>{{ person.email }}</span>
                                    </div>
                                    <div v-if="person.phone" class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ person.phone }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personnel Statistics -->
                <div class="personnel-statistics">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Personnel Statistics
                    </h4>
                    <div class="statistics-grid">
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ personnelList.length }}</span>
                                <span class="statistic-label">Total Personnel</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getManagementCount() }}</span>
                                <span class="statistic-label">Management Level</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getCertifiedPersonnelCount() }}</span>
                                <span class="statistic-label">Certified Personnel</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getDepartmentCount() }}</span>
                                <span class="statistic-label">Departments</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-users"></i>
                <h4>Belum Ada Data Personnel</h4>
                <p>Data personel belum dilengkapi untuk manufacturer ini</p>
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

// Helper Functions
const safeString = (value) => {
    if (value === null || value === undefined) return ''
    if (typeof value === 'string') return value
    return String(value)
}

const isValidPersonObject = (person) => {
    return person !== null && person !== undefined && typeof person === 'object'
}

const getPersonName = (person, index) => {
    try {
        if (person === null || person === undefined) {
            return `Personnel ${index + 1}`
        }
        if (typeof person === 'string') {
            return person
        }
        if (typeof person === 'object') {
            return person.name || person.position || person.role || `Personnel ${index + 1}`
        }
        return `Personnel ${index + 1}`
    } catch (error) {
        console.error('Error getting person name:', error, person)
        return `Personnel ${index + 1}`
    }
}

const getPersonPosition = (person) => {
    try {
        if (!isValidPersonObject(person)) {
            return 'Staff'
        }
        return person.position || person.role || 'Staff'
    } catch (error) {
        console.error('Error getting person position:', error, person)
        return 'Staff'
    }
}

const hasData = computed(() => {
    return !!(props.data?.personnel)
})

const personnelList = computed(() => {
    if (!props.data?.personnel) return []
    
    try {
        // Handle JSON field dari database
        if (Array.isArray(props.data.personnel)) {
            return props.data.personnel.filter(person => person !== null && person !== undefined)
        }
        
        if (typeof props.data.personnel === 'string') {
            try {
                const parsed = JSON.parse(props.data.personnel)
                if (Array.isArray(parsed)) {
                    return parsed.filter(person => person !== null && person !== undefined)
                }
                return parsed ? [parsed] : []
            } catch (parseError) {
                console.error('Error parsing personnel JSON:', parseError)
                return []
            }
        }
        
        if (typeof props.data.personnel === 'object' && props.data.personnel !== null) {
            return Object.entries(props.data.personnel)
                .filter(([key, value]) => key && value !== null && value !== undefined)
                .map(([key, value]) => {
                    try {
                        if (typeof value === 'string') {
                            return {
                                position: key,
                                name: value
                            }
                        }
                        if (typeof value === 'object' && value !== null) {
                            return {
                                position: key,
                                name: value.name || key,
                                ...value
                            }
                        }
                        return {
                            position: key,
                            name: String(value)
                        }
                    } catch (entryError) {
                        console.error('Error processing personnel entry:', entryError, key, value)
                        return {
                            position: key,
                            name: key
                        }
                    }
                })
        }
    } catch (error) {
        console.error('Error processing personnel list:', error, props.data.personnel)
    }
    
    return []
})

function getPersonnelIcon(position) {
    try {
        const positionLower = safeString(position).toLowerCase()
        const icons = {
            'manager': 'fas fa-user-tie',
            'director': 'fas fa-crown',
            'supervisor': 'fas fa-user-check',
            'engineer': 'fas fa-drafting-compass',
            'technician': 'fas fa-wrench',
            'operator': 'fas fa-cogs',
            'quality': 'fas fa-check-double',
            'safety': 'fas fa-hard-hat',
            'admin': 'fas fa-clipboard',
            'hr': 'fas fa-user-friends'
        }
        
        for (const [key, icon] of Object.entries(icons)) {
            if (positionLower.includes(key)) return icon
        }
        return 'fas fa-user'
    } catch (error) {
        console.error('Error getting personnel icon:', error, position)
        return 'fas fa-user'
    }
}

function getLevelClass(level) {
    try {
        const levelLower = safeString(level).toLowerCase()
        if (levelLower.includes('senior') || levelLower.includes('manager') || levelLower.includes('director')) {
            return 'senior'
        }
        if (levelLower.includes('junior') || levelLower.includes('assistant')) {
            return 'junior'
        }
        return 'regular'
    } catch (error) {
        console.error('Error getting level class:', error, level)
        return 'regular'
    }
}

function getQualifications(qualifications) {
    try {
        if (!qualifications) return []
        if (Array.isArray(qualifications)) return qualifications.filter(q => q)
        if (typeof qualifications === 'string') {
            return qualifications.split(',').map(q => q.trim()).filter(q => q)
        }
        return []
    } catch (error) {
        console.error('Error getting qualifications:', error, qualifications)
        return []
    }
}

function getResponsibilities(responsibilities) {
    try {
        if (!responsibilities) return []
        if (Array.isArray(responsibilities)) return responsibilities.filter(r => r)
        if (typeof responsibilities === 'string') {
            return responsibilities.split(',').map(r => r.trim()).filter(r => r)
        }
        return []
    } catch (error) {
        console.error('Error getting responsibilities:', error, responsibilities)
        return []
    }
}

function getCertifications(certifications) {
    try {
        if (!certifications) return []
        if (Array.isArray(certifications)) return certifications.filter(c => c)
        if (typeof certifications === 'string') {
            return certifications.split(',').map(c => c.trim()).filter(c => c)
        }
        return []
    } catch (error) {
        console.error('Error getting certifications:', error, certifications)
        return []
    }
}

function getManagementCount() {
    try {
        return personnelList.value.filter(person => {
            if (!isValidPersonObject(person)) return false
            const position = safeString(person.position || person.role).toLowerCase()
            return position.includes('manager') || position.includes('director') || position.includes('supervisor')
        }).length
    } catch (error) {
        console.error('Error getting management count:', error)
        return 0
    }
}

function getCertifiedPersonnelCount() {
    try {
        return personnelList.value.filter(person => {
            return isValidPersonObject(person) && person.certifications
        }).length
    } catch (error) {
        console.error('Error getting certified personnel count:', error)
        return 0
    }
}

function getDepartmentCount() {
    try {
        const departments = new Set()
        personnelList.value.forEach(person => {
            if (isValidPersonObject(person) && person.department) {
                departments.add(person.department)
            }
        })
        return departments.size
    } catch (error) {
        console.error('Error getting department count:', error)
        return 0
    }
}
</script>

<style scoped>
.personnel-card {
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
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

.personnel-stats {
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

.personnel-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.personnel-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.personnel-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.personnel-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.personnel-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.personnel-info {
    flex: 1;
}

.personnel-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.personnel-position {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.personnel-department {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
}

.personnel-department i {
    font-size: 0.75rem;
}

.personnel-level {
    flex-shrink: 0;
}

.level-badge {
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.level-badge.senior {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.level-badge.regular {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
}

.level-badge.junior {
    background: linear-gradient(135deg, #a7f3d0 0%, #6ee7b7 100%);
    color: #065f46;
}

.personnel-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.personnel-qualifications,
.personnel-experience,
.personnel-responsibilities,
.personnel-certifications,
.personnel-contact {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.personnel-qualifications h6,
.personnel-experience h6,
.personnel-responsibilities h6,
.personnel-certifications h6,
.personnel-contact h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.qualifications-list,
.responsibilities-list,
.contact-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.qualification-item,
.responsibility-item,
.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.qualification-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.responsibility-item i {
    color: #3b82f6;
    font-size: 0.75rem;
}

.contact-item i {
    color: #f59e0b;
    font-size: 0.75rem;
    width: 14px;
    text-align: center;
}

.experience-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 600;
}

.experience-info i {
    color: #10b981;
}

.certifications-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.certification-tag {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.personnel-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.personnel-statistics h4 {
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

.personnel-statistics h4 i {
    color: #8b5cf6;
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
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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
    .personnel-card {
        padding: 16px;
    }
    
    .personnel-grid,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .personnel-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>