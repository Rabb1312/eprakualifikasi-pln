<template>
    <div class="branch-offices-card">
        <div v-if="hasBranches" class="branches-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="header-info">
                    <h3>Branch Offices</h3>
                    <p>Kantor cabang forwarder di Indonesia dan luar negeri</p>
                </div>
                <div class="branches-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ totalBranches }}</span>
                        <span class="stat-label">Total Offices</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Indonesian Branch Offices -->
                <div v-if="indonesianBranches.length > 0" class="branches-section">
                    <h4>
                        <i class="fas fa-flag"></i>
                        Indonesian Branch Offices
                    </h4>
                    <div class="branches-grid">
                        <div 
                            v-for="(branch, index) in indonesianBranches"
                            :key="`id-${index}`"
                            class="branch-item"
                        >
                            <div class="branch-header">
                                <div class="branch-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="branch-info">
                                    <h5>{{ branch.name || `Indonesian Branch ${index + 1}` }}</h5>
                                    <span class="branch-location">{{ branch.city || branch.location || 'Indonesia' }}</span>
                                </div>
                                <div class="branch-flag">
                                    <div class="flag-badge indonesia">
                                        <i class="fas fa-flag"></i>
                                        <span>ID</span>
                                    </div>
                                </div>
                            </div>

                            <div class="branch-details">
                                <div class="detail-grid">
                                    <div class="detail-item" v-if="branch.address">
                                        <label>Address:</label>
                                        <span>{{ branch.address }}</span>
                                    </div>
                                    <div class="detail-item" v-if="branch.phone">
                                        <label>Phone:</label>
                                        <span>{{ branch.phone }}</span>
                                    </div>
                                    <div class="detail-item" v-if="branch.email">
                                        <label>Email:</label>
                                        <span>{{ branch.email }}</span>
                                    </div>
                                    <div class="detail-item" v-if="branch.manager">
                                        <label>Manager:</label>
                                        <span>{{ branch.manager }}</span>
                                    </div>
                                </div>

                                <div v-if="branch.services && branch.services.length > 0" class="branch-services">
                                    <h6>Available Services:</h6>
                                    <div class="services-tags">
                                        <span 
                                            v-for="(service, serviceIndex) in branch.services"
                                            :key="serviceIndex"
                                            class="service-tag"
                                        >
                                            {{ service }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overseas Branch Offices -->
                <div v-if="overseasBranches.length > 0" class="branches-section">
                    <h4>
                        <i class="fas fa-globe"></i>
                        Overseas Branch Offices
                    </h4>
                    <div class="branches-grid">
                        <div 
                            v-for="(branch, index) in overseasBranches"
                            :key="`os-${index}`"
                            class="branch-item"
                        >
                            <div class="branch-header">
                                <div class="branch-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="branch-info">
                                    <h5>{{ branch.name || `Overseas Branch ${index + 1}` }}</h5>
                                    <span class="branch-location">{{ branch.country || branch.city || branch.location || 'International' }}</span>
                                </div>
                                <div class="branch-flag">
                                    <div class="flag-badge overseas">
                                        <i class="fas fa-globe"></i>
                                        <span>INT</span>
                                    </div>
                                </div>
                            </div>

                            <div class="branch-details">
                                <div class="detail-grid">
                                    <div class="detail-item" v-if="branch.address">
                                        <label>Address:</label>
                                        <span>{{ branch.address }}</span>
                                    </div>
                                    <div class="detail-item" v-if="branch.phone">
                                        <label>Phone:</label>
                                        <span>{{ branch.phone }}</span>
                                    </div>
                                    <div class="detail-item" v-if="branch.email">
                                        <label>Email:</label>
                                        <span>{{ branch.email }}</span>
                                    </div>
                                    <div class="detail-item" v-if="branch.country">
                                        <label>Country:</label>
                                        <span>{{ branch.country }}</span>
                                    </div>
                                </div>

                                <div v-if="branch.services && branch.services.length > 0" class="branch-services">
                                    <h6>Available Services:</h6>
                                    <div class="services-tags">
                                        <span 
                                            v-for="(service, serviceIndex) in branch.services"
                                            :key="serviceIndex"
                                            class="service-tag overseas"
                                        >
                                            {{ service }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branch Statistics -->
                <div class="branches-statistics">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Branch Statistics
                    </h4>
                    <div class="statistics-grid">
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-flag"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ indonesianBranches.length }}</span>
                                <span class="statistic-label">Indonesian Offices</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ overseasBranches.length }}</span>
                                <span class="statistic-label">Overseas Offices</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ totalBranches }}</span>
                                <span class="statistic-label">Total Branches</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-map"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getUniqueCountries() }}</span>
                                <span class="statistic-label">Countries</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-building"></i>
                <h4>Belum Ada Data Branch Offices</h4>
                <p>Data kantor cabang belum dilengkapi untuk forwarder ini</p>
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

const indonesianBranches = computed(() => {
    if (!props.data.indonesian_branch_offices) return []
    return Array.isArray(props.data.indonesian_branch_offices) 
        ? props.data.indonesian_branch_offices 
        : []
})

const overseasBranches = computed(() => {
    if (!props.data.overseas_branch_offices) return []
    return Array.isArray(props.data.overseas_branch_offices) 
        ? props.data.overseas_branch_offices 
        : []
})

const totalBranches = computed(() => {
    return indonesianBranches.value.length + overseasBranches.value.length
})

const hasBranches = computed(() => {
    return totalBranches.value > 0
})

function getUniqueCountries() {
    const countries = new Set(['Indonesia']) // Indonesia always counted
    
    overseasBranches.value.forEach(branch => {
        if (branch.country) {
            countries.add(branch.country)
        }
    })
    
    return countries.size
}
</script>

<style scoped>
.branch-offices-card {
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
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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

.branches-stats {
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

.branches-section,
.branches-statistics {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.branches-section h4,
.branches-statistics h4 {
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

.branches-section h4 i {
    color: #3b82f6;
}

.branches-statistics h4 i {
    color: #f59e0b;
}

.branches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
    padding: 24px;
}

.branch-item {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.branch-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.branch-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: white;
    border-bottom: 1px solid #f3f4f6;
}

.branch-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.branch-info {
    flex: 1;
}

.branch-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.branch-location {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.branch-flag {
    flex-shrink: 0;
}

.flag-badge {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.flag-badge.indonesia {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
}

.flag-badge.overseas {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
}

.branch-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

.branch-services {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 16px;
}

.branch-services h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.services-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.service-tag {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.service-tag.overseas {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
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
    .branch-offices-card {
        padding: 16px;
    }
    
    .branches-grid,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .branch-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
}
</style>