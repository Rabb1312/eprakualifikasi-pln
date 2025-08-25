<template>
    <div class="manpower-support-card">
        <div v-if="hasData" class="manpower-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="header-info">
                    <h3>Manpower Support</h3>
                    <p>Dukungan tenaga kerja dan sumber daya manusia yang disediakan forwarder</p>
                </div>
                <div class="manpower-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ getTotalManpower() }}</span>
                        <span class="stat-label">Total Staff</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Staff Categories -->
                <div class="staff-overview">
                    <h4>
                        <i class="fas fa-user-tie"></i>
                        Staff Categories
                    </h4>
                    <div class="staff-grid">
                        <div 
                            v-for="(category, index) in staffCategories"
                            :key="index"
                            class="staff-item"
                        >
                            <div class="staff-header">
                                <div class="staff-icon">
                                    <i :class="getStaffIcon(category.type)"></i>
                                </div>
                                <div class="staff-info">
                                    <h5>{{ category.name || category.type || 'Staff Category' }}</h5>
                                    <span class="staff-department">{{ category.department || 'General Department' }}</span>
                                </div>
                                <div class="staff-count">
                                    <span class="count-badge">
                                        {{ category.count || 0 }} people
                                    </span>
                                </div>
                            </div>

                            <div class="staff-details">
                                <div class="details-grid">
                                    <div class="detail-item" v-if="category.experience_level">
                                        <label>Experience Level:</label>
                                        <span>{{ category.experience_level }}</span>
                                    </div>
                                    <div class="detail-item" v-if="category.qualifications">
                                        <label>Qualifications:</label>
                                        <span>{{ category.qualifications }}</span>
                                    </div>
                                    <div class="detail-item" v-if="category.working_hours">
                                        <label>Working Hours:</label>
                                        <span>{{ category.working_hours }}</span>
                                    </div>
                                    <div class="detail-item" v-if="category.availability">
                                        <label>Availability:</label>
                                        <span>{{ category.availability }}</span>
                                    </div>
                                </div>

                                <div class="staff-description" v-if="category.description">
                                    <p>{{ category.description }}</p>
                                </div>

                                <div class="staff-responsibilities" v-if="category.responsibilities && category.responsibilities.length > 0">
                                    <h6>Key Responsibilities:</h6>
                                    <div class="responsibilities-list">
                                        <div 
                                            v-for="(responsibility, respIndex) in category.responsibilities"
                                            :key="respIndex"
                                            class="responsibility-item"
                                        >
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ responsibility }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="staff-skills" v-if="category.skills && category.skills.length > 0">
                                    <h6>Required Skills:</h6>
                                    <div class="skills-tags">
                                        <span 
                                            v-for="(skill, skillIndex) in category.skills"
                                            :key="skillIndex"
                                            class="skill-tag"
                                        >
                                            {{ skill }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Training Programs -->
                <div class="training-programs" v-if="trainingPrograms.length > 0">
                    <h4>
                        <i class="fas fa-graduation-cap"></i>
                        Training Programs
                    </h4>
                    <div class="training-grid">
                        <div 
                            v-for="(program, index) in trainingPrograms"
                            :key="index"
                            class="training-item"
                        >
                            <div class="training-header">
                                <div class="training-icon">
                                    <i :class="getTrainingIcon(program.type)"></i>
                                </div>
                                <div class="training-info">
                                    <h6>{{ program.name || program.type || 'Training Program' }}</h6>
                                    <span class="training-category">{{ program.category || 'Professional Development' }}</span>
                                </div>
                                <div class="training-status">
                                    <span :class="['status-badge', getTrainingStatus(program)]">
                                        <i class="fas fa-check-circle"></i>
                                        Available
                                    </span>
                                </div>
                            </div>

                            <div class="training-details">
                                <div class="training-specs">
                                    <div class="spec-item" v-if="program.duration">
                                        <label>Duration:</label>
                                        <span>{{ program.duration }}</span>
                                    </div>
                                    <div class="spec-item" v-if="program.frequency">
                                        <label>Frequency:</label>
                                        <span>{{ program.frequency }}</span>
                                    </div>
                                    <div class="spec-item" v-if="program.method">
                                        <label>Method:</label>
                                        <span>{{ program.method }}</span>
                                    </div>
                                    <div class="spec-item" v-if="program.certification">
                                        <label>Certification:</label>
                                        <span>{{ program.certification }}</span>
                                    </div>
                                </div>

                                <div class="training-description" v-if="program.description">
                                    <p>{{ program.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organizational Structure -->
                <div class="organizational-structure">
                    <h4>
                        <i class="fas fa-sitemap"></i>
                        Organizational Structure
                    </h4>
                    <div class="structure-content">
                        <div class="departments-overview">
                            <div 
                                v-for="(department, index) in departmentSummary"
                                :key="index"
                                class="department-item"
                            >
                                <div class="department-icon">
                                    <i :class="getDepartmentIcon(department.name)"></i>
                                </div>
                                <div class="department-info">
                                    <h6>{{ department.name }}</h6>
                                    <span class="department-count">{{ department.count }} staff members</span>
                                    <div class="department-percentage">
                                        <div class="percentage-bar">
                                            <div 
                                                class="percentage-fill"
                                                :style="{ width: getDepartmentPercentage(department.count) + '%' }"
                                            ></div>
                                        </div>
                                        <span class="percentage-text">{{ getDepartmentPercentage(department.count) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HR Statistics -->
                <div class="hr-statistics">
                    <h4>
                        <i class="fas fa-chart-line"></i>
                        HR Statistics
                    </h4>
                    <div class="statistics-grid">
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getTotalManpower() }}</span>
                                <span class="statistic-label">Total Employees</span>
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
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ trainingPrograms.length }}</span>
                                <span class="statistic-label">Training Programs</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getAverageExperience() }}</span>
                                <span class="statistic-label">Avg. Experience</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-users"></i>
                <h4>Belum Ada Data Manpower Support</h4>
                <p>Data dukungan tenaga kerja belum dilengkapi untuk forwarder ini</p>
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
    return !!(props.data.manpower_support)
})

const staffCategories = computed(() => {
    if (!props.data.manpower_support?.staff_categories) return []
    return Array.isArray(props.data.manpower_support.staff_categories) 
        ? props.data.manpower_support.staff_categories 
        : []
})

const trainingPrograms = computed(() => {
    if (!props.data.manpower_support?.training_programs) return []
    return Array.isArray(props.data.manpower_support.training_programs) 
        ? props.data.manpower_support.training_programs 
        : []
})

const departmentSummary = computed(() => {
    if (staffCategories.value.length === 0) return []
    
    const departments = {}
    staffCategories.value.forEach(category => {
        const dept = category.department || 'General'
        if (!departments[dept]) {
            departments[dept] = { name: dept, count: 0 }
        }
        departments[dept].count += parseInt(category.count) || 0
    })
    
    return Object.values(departments).sort((a, b) => b.count - a.count)
})

function getStaffIcon(type) {
    const icons = {
        'management': 'fas fa-user-tie',
        'operations': 'fas fa-cogs',
        'logistics': 'fas fa-truck',
        'administration': 'fas fa-clipboard',
        'customer_service': 'fas fa-headset',
        'finance': 'fas fa-calculator',
        'warehouse': 'fas fa-warehouse',
        'documentation': 'fas fa-file-alt',
        'customs': 'fas fa-file-invoice',
        'technical': 'fas fa-tools'
    }
    return icons[type?.toLowerCase()] || 'fas fa-user'
}

function getTrainingIcon(type) {
    const icons = {
        'technical': 'fas fa-tools',
        'safety': 'fas fa-hard-hat',
        'customer_service': 'fas fa-smile',
        'leadership': 'fas fa-crown',
        'software': 'fas fa-laptop',
        'language': 'fas fa-language',
        'certification': 'fas fa-certificate',
        'orientation': 'fas fa-compass'
    }
    return icons[type?.toLowerCase()] || 'fas fa-graduation-cap'
}

function getDepartmentIcon(department) {
    const icons = {
        'management': 'fas fa-user-tie',
        'operations': 'fas fa-cogs',
        'logistics': 'fas fa-route',
        'administration': 'fas fa-building',
        'finance': 'fas fa-chart-line',
        'hr': 'fas fa-users',
        'it': 'fas fa-laptop',
        'general': 'fas fa-briefcase'
    }
    return icons[department?.toLowerCase()] || 'fas fa-building'
}

function getTrainingStatus(program) {
    return program.status?.toLowerCase() || 'available'
}

function getTotalManpower() {
    return staffCategories.value.reduce((total, category) => {
        return total + (parseInt(category.count) || 0)
    }, 0)
}

function getDepartmentCount() {
    return departmentSummary.value.length
}

function getDepartmentPercentage(count) {
    const total = getTotalManpower()
    if (total === 0) return 0
    return Math.round((count / total) * 100)
}

function getAverageExperience() {
    const experiences = staffCategories.value
        .map(category => category.experience_level)
        .filter(exp => exp && typeof exp === 'string')
        .map(exp => {
            const match = exp.match(/(\d+)/)
            return match ? parseInt(match[1]) : 0
        })
        .filter(num => num > 0)
    
    if (experiences.length === 0) return '-'
    
    const average = experiences.reduce((sum, exp) => sum + exp, 0) / experiences.length
    return `${Math.round(average)} years`
}
</script>

<style scoped>
.manpower-support-card {
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

.manpower-stats {
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

.staff-overview,
.training-programs,
.organizational-structure,
.hr-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.staff-overview h4,
.training-programs h4,
.organizational-structure h4,
.hr-statistics h4 {
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

.staff-overview h4 i {
    color: #10b981;
}

.training-programs h4 i {
    color: #3b82f6;
}

.organizational-structure h4 i {
    color: #8b5cf6;
}

.hr-statistics h4 i {
    color: #f59e0b;
}

.staff-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 24px;
}

.staff-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

.staff-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.staff-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.staff-info {
    flex: 1;
}

.staff-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.staff-department {
    font-size: 0.875rem;
    color: #6b7280;
}

.staff-count {
    flex-shrink: 0;
}

.count-badge {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.staff-details {
    padding: 16px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.detail-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.detail-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.staff-description {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.staff-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.staff-responsibilities,
.staff-skills {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.staff-responsibilities h6,
.staff-skills h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.responsibilities-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.responsibility-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.responsibility-item i {
    color: #10b981;
    font-size: 0.75rem;
}

.skills-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.skill-tag {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.training-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    padding: 24px;
}

.training-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.training-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.training-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.training-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.training-info {
    flex: 1;
}

.training-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.training-category {
    font-size: 0.875rem;
    color: #6b7280;
}

.training-status {
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

.training-details {
    padding: 16px 20px;
}

.training-specs {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.spec-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 6px;
}

.spec-item label {
    font-weight: 500;
    color: #6b7280;
    font-size: 0.875rem;
}

.spec-item span {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.training-description {
    background: white;
    padding: 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.training-description p {
    margin: 0;
    color: #374151;
    line-height: 1.5;
    font-size: 0.875rem;
}

.structure-content {
    padding: 24px;
}

.departments-overview {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.department-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.department-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.department-info {
    flex: 1;
}

.department-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.department-count {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 8px;
}

.department-percentage {
    display: flex;
    align-items: center;
    gap: 12px;
}

.percentage-bar {
    flex: 1;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.percentage-fill {
    height: 100%;
    background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.percentage-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: #8b5cf6;
    min-width: 40px;
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
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
    .manpower-support-card {
        padding: 16px;
    }
    
    .staff-grid,
    .training-grid,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .staff-header,
    .training-header,
    .department-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .details-grid,
    .training-specs {
        grid-template-columns: 1fr;
    }
    
    .department-percentage {
        flex-direction: column;
        gap: 8px;
    }
}
</style>