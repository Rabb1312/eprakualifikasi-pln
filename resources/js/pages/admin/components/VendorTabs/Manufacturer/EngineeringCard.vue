<template>
    <div class="engineering-card">
        <div v-if="hasData" class="engineering-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-drafting-compass"></i>
                </div>
                <div class="header-info">
                    <h3>Engineering Design</h3>
                    <p>Kemampuan desain dan rekayasa yang dimiliki manufacturer</p>
                </div>
                <div class="engineering-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ engineeringCapabilities.length }}</span>
                        <span class="stat-label">Capabilities</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Engineering Capabilities Grid -->
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
                                <h5>{{ capability.name || capability.type || `Capability ${index + 1}` }}</h5>
                                <span class="capability-category">{{ capability.category || 'Engineering Service' }}</span>
                            </div>
                            <div class="capability-level">
                                <span :class="['level-badge', getLevelClass(capability.level)]">
                                    {{ capability.level || 'Standard' }}
                                </span>
                            </div>
                        </div>

                        <div class="capability-details">
                            <div class="capability-description" v-if="capability.description">
                                <p>{{ capability.description }}</p>
                            </div>

                            <div class="capability-software" v-if="capability.software">
                                <h6>Software & Tools:</h6>
                                <div class="software-tags">
                                    <span 
                                        v-for="(software, softwareIndex) in getSoftware(capability.software)"
                                        :key="softwareIndex"
                                        class="software-tag"
                                    >
                                        <i :class="getSoftwareIcon(software)"></i>
                                        {{ software }}
                                    </span>
                                </div>
                            </div>

                            <div class="capability-standards" v-if="capability.standards">
                                <h6>Standards & Codes:</h6>
                                <div class="standards-list">
                                    <div 
                                        v-for="(standard, standardIndex) in getStandards(capability.standards)"
                                        :key="standardIndex"
                                        class="standard-item"
                                    >
                                        <i class="fas fa-certificate"></i>
                                        <span>{{ standard }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="capability-experience" v-if="capability.experience">
                                <h6>Experience & Projects:</h6>
                                <div class="experience-info">
                                    <div v-if="capability.experience.years" class="experience-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ capability.experience.years }} years experience</span>
                                    </div>
                                    <div v-if="capability.experience.projects" class="experience-item">
                                        <i class="fas fa-project-diagram"></i>
                                        <span>{{ capability.experience.projects }} completed projects</span>
                                    </div>
                                    <div v-if="capability.experience.clients" class="experience-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ capability.experience.clients }} clients served</span>
                                    </div>
                                </div>
                            </div>

                            <div class="capability-deliverables" v-if="capability.deliverables">
                                <h6>Deliverables:</h6>
                                <div class="deliverables-grid">
                                    <div 
                                        v-for="(deliverable, deliverableIndex) in getDeliverables(capability.deliverables)"
                                        :key="deliverableIndex"
                                        class="deliverable-item"
                                    >
                                        <i :class="getDeliverableIcon(deliverable)"></i>
                                        <span>{{ deliverable }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="capability-timeline" v-if="capability.timeline">
                                <h6>Typical Timeline:</h6>
                                <div class="timeline-info">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ capability.timeline }}</span>
                                </div>
                            </div>

                            <div class="capability-team" v-if="capability.team">
                                <h6>Team Composition:</h6>
                                <div class="team-composition">
                                    <div 
                                        v-for="(member, memberIndex) in getTeamMembers(capability.team)"
                                        :key="memberIndex"
                                        class="team-member"
                                    >
                                        <div class="member-role">{{ member.role || member }}</div>
                                        <div class="member-count" v-if="member.count">{{ member.count }} persons</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Engineering Statistics -->
                <div class="engineering-statistics">
                    <h4>
                        <i class="fas fa-chart-line"></i>
                        Engineering Statistics
                    </h4>
                    <div class="statistics-grid">
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-drafting-compass"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ engineeringCapabilities.length }}</span>
                                <span class="statistic-label">Design Capabilities</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getTotalSoftware() }}</span>
                                <span class="statistic-label">Software Tools</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getTotalStandards() }}</span>
                                <span class="statistic-label">Standards Applied</span>
                            </div>
                        </div>
                        <div class="statistic-item">
                            <div class="statistic-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="statistic-info">
                                <span class="statistic-number">{{ getTotalTeamSize() }}</span>
                                <span class="statistic-label">Engineering Team</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Capability Categories -->
                <div class="capability-categories">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Engineering Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in getCapabilityCategories()"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getCapabilityIcon(category.name)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} capabilities</span>
                            </div>
                            <div class="category-percentage">
                                <span class="percentage-value">{{ getCategoryPercentage(category.count) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-drafting-compass"></i>
                <h4>Belum Ada Data Engineering Design</h4>
                <p>Data kemampuan engineering design belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.engineering_design)
})

const engineeringCapabilities = computed(() => {
    if (!props.data.engineering_design) return []
    
    // Handle JSON field dari database
    if (Array.isArray(props.data.engineering_design)) {
        return props.data.engineering_design
    }
    
    if (typeof props.data.engineering_design === 'string') {
        try {
            const parsed = JSON.parse(props.data.engineering_design)
            return Array.isArray(parsed) ? parsed : [parsed]
        } catch {
            return []
        }
    }
    
    if (typeof props.data.engineering_design === 'object') {
        return Object.entries(props.data.engineering_design).map(([key, value]) => ({
            type: key,
            ...value
        }))
    }
    
    return []
})

function getCapabilityIcon(type) {
    const typeLower = (type || '').toLowerCase()
    const icons = {
        'mechanical': 'fas fa-cogs',
        'electrical': 'fas fa-bolt',
        'civil': 'fas fa-building',
        'structural': 'fas fa-home',
        'process': 'fas fa-industry',
        'piping': 'fas fa-pipe',
        'instrumentation': 'fas fa-gauge',
        'control': 'fas fa-sliders-h',
        'automation': 'fas fa-robot',
        'software': 'fas fa-code',
        '3d_modeling': 'fas fa-cube',
        'simulation': 'fas fa-chart-line',
        'analysis': 'fas fa-calculator'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (typeLower.includes(key)) return icon
    }
    return 'fas fa-drafting-compass'
}

function getLevelClass(level) {
    const levelLower = (level || '').toLowerCase()
    if (levelLower.includes('expert') || levelLower.includes('advanced')) return 'expert'
    if (levelLower.includes('intermediate') || levelLower.includes('standard')) return 'intermediate'
    if (levelLower.includes('basic') || levelLower.includes('entry')) return 'basic'
    return 'standard'
}

function getSoftware(software) {
    if (Array.isArray(software)) return software
    if (typeof software === 'string') return software.split(',').map(s => s.trim())
    return []
}

function getSoftwareIcon(software) {
    const softwareLower = (software || '').toLowerCase()
    const icons = {
        'autocad': 'fas fa-drafting-compass',
        'solidworks': 'fas fa-cube',
        'catia': 'fas fa-cube',
        'inventor': 'fas fa-cube',
        'ansys': 'fas fa-calculator',
        'matlab': 'fas fa-chart-line',
        'python': 'fas fa-code',
        'excel': 'fas fa-table'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (softwareLower.includes(key)) return icon
    }
    return 'fas fa-laptop'
}

function getStandards(standards) {
    if (Array.isArray(standards)) return standards
    if (typeof standards === 'string') return standards.split(',').map(s => s.trim())
    return []
}

function getDeliverables(deliverables) {
    if (Array.isArray(deliverables)) return deliverables
    if (typeof deliverables === 'string') return deliverables.split(',').map(d => d.trim())
    return []
}

function getDeliverableIcon(deliverable) {
    const deliverableLower = (deliverable || '').toLowerCase()
    const icons = {
        'drawing': 'fas fa-file-alt',
        'model': 'fas fa-cube',
        'report': 'fas fa-file-pdf',
        'calculation': 'fas fa-calculator',
        'specification': 'fas fa-list-alt',
        'manual': 'fas fa-book'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (deliverableLower.includes(key)) return icon
    }
    return 'fas fa-file'
}

function getTeamMembers(team) {
    if (Array.isArray(team)) return team
    if (typeof team === 'object') {
        return Object.entries(team).map(([role, count]) => ({ role, count }))
    }
    if (typeof team === 'string') {
        return team.split(',').map(t => ({ role: t.trim() }))
    }
    return []
}

function getTotalSoftware() {
    return engineeringCapabilities.value.reduce((total, capability) => {
        const software = getSoftware(capability.software || [])
        return total + software.length
    }, 0)
}

function getTotalStandards() {
    return engineeringCapabilities.value.reduce((total, capability) => {
        const standards = getStandards(capability.standards || [])
        return total + standards.length
    }, 0)
}

function getTotalTeamSize() {
    return engineeringCapabilities.value.reduce((total, capability) => {
        const members = getTeamMembers(capability.team || [])
        return total + members.reduce((sum, member) => sum + (parseInt(member.count) || 1), 0)
    }, 0)
}

function getCapabilityCategories() {
    const categories = {}
    engineeringCapabilities.value.forEach(capability => {
        const category = capability.category || capability.type || 'General'
        if (!categories[category]) {
            categories[category] = { name: category, count: 0 }
        }
        categories[category].count++
    })
    
    return Object.values(categories).sort((a, b) => b.count - a.count)
}

function getCategoryPercentage(count) {
    if (engineeringCapabilities.value.length === 0) return 0
    return Math.round((count / engineeringCapabilities.value.length) * 100)
}
</script>

<style scoped>
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
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
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

.engineering-stats {
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

.capabilities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 24px;
}

.capability-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.capability-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.capability-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.capability-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #a855f7 0%, #8b5cf6 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.capability-info {
    flex: 1;
}

.capability-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.capability-category {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.capability-level {
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

.level-badge.expert {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
}

.level-badge.intermediate {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.level-badge.basic {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.level-badge.standard {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.capability-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.capability-description,
.capability-software,
.capability-standards,
.capability-experience,
.capability-deliverables,
.capability-timeline,
.capability-team {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.capability-description p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.capability-software h6,
.capability-standards h6,
.capability-experience h6,
.capability-deliverables h6,
.capability-timeline h6,
.capability-team h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.software-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.software-tag {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.standards-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.standard-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.standard-item i {
    color: #8b5cf6;
    font-size: 0.75rem;
}

.experience-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.experience-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 600;
}

.experience-item i {
    color: #8b5cf6;
    font-size: 0.875rem;
    width: 16px;
    text-align: center;
}

.deliverables-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.deliverable-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
    color: #374151;
}

.deliverable-item i {
    color: #8b5cf6;
    font-size: 0.875rem;
}

.timeline-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 600;
}

.timeline-info i {
    color: #8b5cf6;
}

.team-composition {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.team-member {
    padding: 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    text-align: center;
}

.member-role {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.member-count {
    font-size: 0.75rem;
    color: #6b7280;
}

.engineering-statistics,
.capability-categories {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.engineering-statistics h4,
.capability-categories h4 {
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

.engineering-statistics h4 i {
    color: #ef4444;
}

.capability-categories h4 i {
    color: #f59e0b;
}

.statistics-grid,
.categories-grid {
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
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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

.category-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.category-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.category-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
    flex-shrink: 0;
}

.category-info {
    flex: 1;
}

.category-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
}

.category-count {
    font-size: 0.875rem;
    color: #6b7280;
}

.category-percentage {
    flex-shrink: 0;
}

.percentage-value {
    font-weight: 700;
    color: #f59e0b;
    font-size: 1.125rem;
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
    .engineering-card {
        padding: 16px;
    }
    
    .capabilities-grid,
    .statistics-grid,
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .capability-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .deliverables-grid,
    .team-composition {
        grid-template-columns: 1fr;
    }
}
</style>