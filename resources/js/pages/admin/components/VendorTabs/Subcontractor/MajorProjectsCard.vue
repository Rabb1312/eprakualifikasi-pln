<template>
    <div class="major-projects-card">
        <div v-if="hasProjects" class="projects-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="header-info">
                    <h3>Major Projects (3-5 Years)</h3>
                    <p>Proyek-proyek besar yang telah dikerjakan dalam 3-5 tahun terakhir</p>
                </div>
                <div class="projects-count">
                    <span class="count-badge">{{ projectsList.length }} Projects</span>
                </div>
            </div>

            <div class="projects-timeline">
                <div 
                    v-for="(project, index) in projectsList" 
                    :key="index"
                    class="project-item"
                >
                    <div class="project-timeline-marker">
                        <div class="timeline-dot">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="timeline-year">{{ project.year_of_execution }}</div>
                    </div>
                    
                    <div class="project-card">
                        <div class="project-header">
                            <div class="project-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="project-title">
                                <h4>{{ project.project_name }}</h4>
                                <span class="project-owner">{{ project.project_owner }}</span>
                            </div>
                            <div v-if="project.value" class="project-value">
                                <span class="value-label">Project Value</span>
                                <span class="value-amount">{{ formatCurrency(project.value) }}</span>
                            </div>
                        </div>

                        <div class="project-details">
                            <div class="details-grid">
                                <div class="detail-item" v-if="project.main_contractor">
                                    <label>Main Contractor:</label>
                                    <span>{{ project.main_contractor }}</span>
                                </div>
                                <div class="detail-item" v-if="project.work_duration">
                                    <label>Duration:</label>
                                    <span>{{ project.work_duration }}</span>
                                </div>
                                <div class="detail-item" v-if="project.client_representative">
                                    <label>Client Representative:</label>
                                    <span>{{ project.client_representative }}</span>
                                </div>
                                <div class="detail-item" v-if="project.phone">
                                    <label>Contact:</label>
                                    <span>{{ project.phone }}</span>
                                </div>
                            </div>
                            
                            <div v-if="project.scope_of_work" class="project-scope">
                                <label>Scope of Work:</label>
                                <p>{{ project.scope_of_work }}</p>
                            </div>
                            
                            <div v-if="project.work_package" class="project-package">
                                <label>Work Package:</label>
                                <p>{{ project.work_package }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Summary -->
            <div class="projects-summary">
                <div class="summary-stats">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ projectsList.length }}</span>
                            <span class="stat-label">Total Projects</span>
                        </div>
                    </div>
                    <div class="stat-item" v-if="totalValue > 0">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ formatShortCurrency(totalValue) }}</span>
                            <span class="stat-label">Total Value</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ yearRange }}</span>
                            <span class="stat-label">Year Range</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-project-diagram"></i>
                <h4>Belum Ada Data Major Projects</h4>
                <p>Data proyek-proyek besar belum dilengkapi untuk subcontractor ini</p>
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

const hasProjects = computed(() => {
    return !!(props.data.major_projects && Array.isArray(props.data.major_projects) && props.data.major_projects.length > 0)
})

const projectsList = computed(() => {
    if (!props.data.major_projects) return []
    
    // Sort by year descending
    return [...props.data.major_projects].sort((a, b) => {
        return parseInt(b.year_of_execution || 0) - parseInt(a.year_of_execution || 0)
    })
})

const totalValue = computed(() => {
    return projectsList.value.reduce((total, project) => {
        return total + (parseFloat(project.value) || 0)
    }, 0)
})

const yearRange = computed(() => {
    if (projectsList.value.length === 0) return '-'
    
    const years = projectsList.value
        .map(p => parseInt(p.year_of_execution || 0))
        .filter(year => year > 0)
        .sort()
    
    if (years.length === 0) return '-'
    if (years.length === 1) return years[0].toString()
    
    return `${years[0]} - ${years[years.length - 1]}`
})

function formatCurrency(amount) {
    if (!amount) return '-'
    const numAmount = typeof amount === 'string' ? parseFloat(amount) : amount
    if (isNaN(numAmount)) return '-'
    
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(numAmount)
}

function formatShortCurrency(amount) {
    if (!amount) return '-'
    const numAmount = typeof amount === 'string' ? parseFloat(amount) : amount
    if (isNaN(numAmount)) return '-'
    
    if (numAmount >= 1000000000000) {
        return `${(numAmount / 1000000000000).toFixed(1)}T`
    } else if (numAmount >= 1000000000) {
        return `${(numAmount / 1000000000).toFixed(1)}B`
    } else if (numAmount >= 1000000) {
        return `${(numAmount / 1000000).toFixed(1)}M`
    } else if (numAmount >= 1000) {
        return `${(numAmount / 1000).toFixed(1)}K`
    }
    
    return formatCurrency(numAmount)
}
</script>

<style scoped>
.major-projects-card {
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

.projects-count {
    flex-shrink: 0;
}

.count-badge {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

.projects-timeline {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 24px;
    margin-bottom: 32px;
}

.projects-timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 40px;
    bottom: 40px;
    width: 2px;
    background: linear-gradient(to bottom, #e5e7eb, #d1d5db);
}

.project-item {
    display: flex;
    gap: 24px;
    position: relative;
}

.project-timeline-marker {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    z-index: 1;
}

.timeline-dot {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.timeline-year {
    background: white;
    border: 2px solid #3b82f6;
    color: #3b82f6;
    padding: 4px 12px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.875rem;
}

.project-card {
    flex: 1;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.project-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.project-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.project-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.project-title {
    flex: 1;
    min-width: 0;
}

.project-title h4 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.25rem;
    line-height: 1.3;
}

.project-owner {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.project-value {
    flex-shrink: 0;
    text-align: right;
}

.value-label {
    display: block;
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    margin-bottom: 4px;
}

.value-amount {
    display: block;
    font-weight: 700;
    color: #059669;
    font-size: 1.125rem;
}

.project-details {
    padding: 24px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.detail-item label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.detail-item span {
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.4;
}

.project-scope,
.project-package {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.project-scope label,
.project-package label {
    display: block;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.project-scope p,
.project-package p {
    margin: 0;
    color: #6b7280;
    line-height: 1.6;
    font-size: 0.875rem;
}

.projects-summary {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    border-radius: 16px;
    padding: 24px;
    color: white;
}

.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-label {
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
    .major-projects-card {
        padding: 16px;
    }
    
    .projects-timeline::before {
        left: 20px;
    }
    
    .timeline-dot {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .project-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .project-value {
        text-align: center;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .summary-stats {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>