<template>
    <div class="code-standard-card">
        <div v-if="hasData" class="code-standard-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="header-info">
                    <h3>Code & Standards</h3>
                    <p>Standar dan kode yang diterapkan dalam manufakturing</p>
                </div>
                <div class="standards-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ codeStandards.length }}</span>
                        <span class="stat-label">Standards</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Code Standards Grid -->
                <div class="standards-grid">
                    <div 
                        v-for="(standard, index) in codeStandards"
                        :key="index"
                        class="standard-item"
                    >
                        <div class="standard-header">
                            <div class="standard-icon">
                                <i :class="getStandardIcon(standard.type || standard.category)"></i>
                            </div>
                            <div class="standard-info">
                                <h5>{{ standard.name || standard.code || `Standard ${index + 1}` }}</h5>
                                <span class="standard-type">{{ standard.type || standard.category || 'Manufacturing Standard' }}</span>
                                <div class="standard-code" v-if="standard.code">
                                    <span class="code-badge">{{ standard.code }}</span>
                                </div>
                            </div>
                            <div class="standard-status">
                                <span :class="['status-badge', getComplianceStatus(standard.compliance)]">
                                    <i :class="getComplianceIcon(standard.compliance)"></i>
                                    {{ getComplianceText(standard.compliance) }}
                                </span>
                            </div>
                        </div>

                        <div class="standard-details">
                            <div class="standard-description" v-if="standard.description">
                                <p>{{ standard.description }}</p>
                            </div>

                            <div class="standard-scope" v-if="standard.scope">
                                <h6>Scope of Application:</h6>
                                <div class="scope-info">
                                    <p>{{ standard.scope }}</p>
                                </div>
                            </div>

                            <div class="standard-requirements" v-if="standard.requirements">
                                <h6>Key Requirements:</h6>
                                <div class="requirements-list">
                                    <div 
                                        v-for="(requirement, reqIndex) in getRequirements(standard.requirements)"
                                        :key="reqIndex"
                                        class="requirement-item"
                                    >
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ requirement }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="standard-implementation" v-if="standard.implementation">
                                <h6>Implementation Details:</h6>
                                <div class="implementation-info">
                                    <div class="implementation-grid">
                                        <div v-if="standard.implementation.date" class="impl-item">
                                            <label>Implementation Date:</label>
                                            <span>{{ formatDate(standard.implementation.date) }}</span>
                                        </div>
                                        <div v-if="standard.implementation.version" class="impl-item">
                                            <label>Version:</label>
                                            <span>{{ standard.implementation.version }}</span>
                                        </div>
                                        <div v-if="standard.implementation.certifying_body" class="impl-item">
                                            <label>Certifying Body:</label>
                                            <span>{{ standard.implementation.certifying_body }}</span>
                                        </div>
                                        <div v-if="standard.implementation.validity" class="impl-item">
                                            <label>Valid Until:</label>
                                            <span>{{ formatDate(standard.implementation.validity) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="standard-benefits" v-if="standard.benefits">
                                <h6>Benefits:</h6>
                                <div class="benefits-tags">
                                    <span 
                                        v-for="(benefit, benefitIndex) in getBenefits(standard.benefits)"
                                        :key="benefitIndex"
                                        class="benefit-tag"
                                    >
                                        {{ benefit }}
                                    </span>
                                </div>
                            </div>

                            <div class="standard-processes" v-if="standard.applicable_processes">
                                <h6>Applicable Processes:</h6>
                                <div class="processes-list">
                                    <div 
                                        v-for="(process, processIndex) in getProcesses(standard.applicable_processes)"
                                        :key="processIndex"
                                        class="process-item"
                                    >
                                        <i :class="getProcessIcon(process)"></i>
                                        <span>{{ process }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="standard-documentation" v-if="standard.documentation">
                                <h6>Documentation:</h6>
                                <div class="documentation-list">
                                    <div 
                                        v-for="(doc, docIndex) in getDocumentation(standard.documentation)"
                                        :key="docIndex"
                                        class="documentation-item"
                                    >
                                        <i class="fas fa-file-alt"></i>
                                        <span>{{ doc.name || doc }}</span>
                                        <span v-if="doc.version" class="doc-version">v{{ doc.version }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="standard-compliance-level" v-if="standard.compliance_level">
                                <h6>Compliance Level:</h6>
                                <div class="compliance-bar">
                                    <div class="compliance-info">
                                        <span>{{ standard.compliance_level }}% Compliant</span>
                                        <span class="compliance-status">{{ getComplianceLevel(standard.compliance_level) }}</span>
                                    </div>
                                    <div class="compliance-progress">
                                        <div 
                                            class="compliance-fill"
                                            :style="{ width: standard.compliance_level + '%' }"
                                            :class="getComplianceLevelClass(standard.compliance_level)"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Standards Categories -->
                <div class="standards-categories">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Standards Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in getStandardCategories()"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getStandardIcon(category.name)"></i>
                            </div>
                            <div class="category-info">
                                <h6>{{ category.name }}</h6>
                                <span class="category-count">{{ category.count }} standards</span>
                            </div>
                            <div class="category-compliance">
                                <span class="compliance-percentage">{{ category.compliance }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compliance Summary -->
                <div class="compliance-summary">
                    <h4>
                        <i class="fas fa-chart-bar"></i>
                        Compliance Summary
                    </h4>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ codeStandards.length }}</span>
                                <span class="summary-label">Total Standards</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getCompliantStandards() }}</span>
                                <span class="summary-label">Fully Compliant</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getAverageCompliance() }}%</span>
                                <span class="summary-label">Avg. Compliance</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="summary-info">
                                <span class="summary-number">{{ getInternationalStandards() }}</span>
                                <span class="summary-label">International Standards</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-certificate"></i>
                <h4>Belum Ada Data Code & Standards</h4>
                <p>Data standar dan kode belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.code_standard)
})

const codeStandards = computed(() => {
    if (!props.data.code_standard) return []
    
    // Handle JSON field dari database
    if (Array.isArray(props.data.code_standard)) {
        return props.data.code_standard
    }
    
    if (typeof props.data.code_standard === 'string') {
        try {
            const parsed = JSON.parse(props.data.code_standard)
            return Array.isArray(parsed) ? parsed : [parsed]
        } catch {
            return []
        }
    }
    
    if (typeof props.data.code_standard === 'object') {
        return Object.entries(props.data.code_standard).map(([key, value]) => ({
            name: key,
            ...value
        }))
    }
    
    return []
})

function getStandardIcon(type) {
    const typeLower = (type || '').toLowerCase()
    const icons = {
        'iso': 'fas fa-globe',
        'astm': 'fas fa-vial',
        'din': 'fas fa-flag',
        'jis': 'fas fa-flag',
        'ansi': 'fas fa-flag-usa',
        'iec': 'fas fa-bolt',
        'ieee': 'fas fa-microchip',
        'quality': 'fas fa-check-double',
        'safety': 'fas fa-shield-alt',
        'environmental': 'fas fa-leaf',
        'security': 'fas fa-lock'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (typeLower.includes(key)) return icon
    }
    return 'fas fa-certificate'
}

function getComplianceStatus(compliance) {
    const complianceLower = (compliance || '').toLowerCase()
    if (complianceLower.includes('full') || complianceLower.includes('complete')) return 'compliant'
    if (complianceLower.includes('partial') || complianceLower.includes('progress')) return 'partial'
    if (complianceLower.includes('non') || complianceLower.includes('not')) return 'non-compliant'
    return 'compliant'
}

function getComplianceIcon(compliance) {
    const status = getComplianceStatus(compliance)
    const icons = {
        'compliant': 'fas fa-check-circle',
        'partial': 'fas fa-clock',
        'non-compliant': 'fas fa-times-circle'
    }
    return icons[status] || 'fas fa-check-circle'
}

function getComplianceText(compliance) {
    return compliance || 'Compliant'
}

function getRequirements(requirements) {
    if (Array.isArray(requirements)) return requirements
    if (typeof requirements === 'string') return requirements.split(',').map(r => r.trim())
    return []
}

function getBenefits(benefits) {
    if (Array.isArray(benefits)) return benefits
    if (typeof benefits === 'string') return benefits.split(',').map(b => b.trim())
    return []
}

function getProcesses(processes) {
    if (Array.isArray(processes)) return processes
    if (typeof processes === 'string') return processes.split(',').map(p => p.trim())
    return []
}

function getProcessIcon(process) {
    const processLower = (process || '').toLowerCase()
    const icons = {
        'manufacturing': 'fas fa-industry',
        'quality': 'fas fa-check-double',
        'design': 'fas fa-drafting-compass',
        'testing': 'fas fa-vial',
        'assembly': 'fas fa-puzzle-piece'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (processLower.includes(key)) return icon
    }
    return 'fas fa-cogs'
}

function getDocumentation(documentation) {
    if (Array.isArray(documentation)) return documentation
    if (typeof documentation === 'string') return documentation.split(',').map(d => ({ name: d.trim() }))
    return []
}

function getComplianceLevel(level) {
    const levelNum = parseInt(level) || 0
    if (levelNum >= 95) return 'Excellent'
    if (levelNum >= 85) return 'Good'
    if (levelNum >= 75) return 'Satisfactory'
    if (levelNum >= 60) return 'Needs Improvement'
    return 'Poor'
}

function getComplianceLevelClass(level) {
    const levelNum = parseInt(level) || 0
    if (levelNum >= 90) return 'excellent'
    if (levelNum >= 75) return 'good'
    if (levelNum >= 60) return 'satisfactory'
    return 'poor'
}

function formatDate(dateString) {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    })
}

function getStandardCategories() {
    const categories = {}
    codeStandards.value.forEach(standard => {
        const category = standard.type || standard.category || 'General'
        if (!categories[category]) {
            categories[category] = { 
                name: category, 
                count: 0, 
                totalCompliance: 0 
            }
        }
        categories[category].count++
        const compliance = parseInt(standard.compliance_level) || 100
        categories[category].totalCompliance += compliance
    })
    
    return Object.values(categories).map(cat => ({
        ...cat,
        compliance: Math.round(cat.totalCompliance / cat.count)
    })).sort((a, b) => b.count - a.count)
}

function getCompliantStandards() {
    return codeStandards.value.filter(standard => {
        const status = getComplianceStatus(standard.compliance)
        return status === 'compliant'
    }).length
}

function getAverageCompliance() {
    const levels = codeStandards.value
        .map(standard => parseInt(standard.compliance_level) || 100)
        .filter(level => level > 0)
    
    if (levels.length === 0) return 100
    
    const average = levels.reduce((sum, level) => sum + level, 0) / levels.length
    return Math.round(average)
}

function getInternationalStandards() {
    return codeStandards.value.filter(standard => {
        const type = (standard.type || '').toLowerCase()
        return type.includes('iso') || type.includes('iec') || type.includes('ieee') || 
               type.includes('astm') || type.includes('international')
    }).length
}
</script>

<style scoped>
/* Style yang sama dengan komponen sebelumnya, dengan adaptasi warna untuk code standards */
.code-standard-card {
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
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
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

.standards-stats {
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

.standards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 24px;
}

.standard-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.standard-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.standard-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.standard-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.standard-info {
    flex: 1;
}

.standard-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.standard-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
    display: block;
    margin-bottom: 8px;
}

.standard-code {
    margin-top: 4px;
}

.code-badge {
    background: #e5e7eb;
    color: #374151;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    font-family: monospace;
}

.standard-status {
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

.status-badge.compliant {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.partial {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.non-compliant {
    background: #fef2f2;
    color: #991b1b;
}

.standard-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.standard-description,
.standard-scope,
.standard-requirements,
.standard-implementation,
.standard-benefits,
.standard-processes,
.standard-documentation,
.standard-compliance-level {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.standard-description p,
.scope-info p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
}

.standard-scope h6,
.standard-requirements h6,
.standard-implementation h6,
.standard-benefits h6,
.standard-processes h6,
.standard-documentation h6,
.standard-compliance-level h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.requirements-list,
.processes-list,
.documentation-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.requirement-item,
.process-item,
.documentation-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
}

.requirement-item i {
    color: #7c3aed;
    font-size: 0.75rem;
}

.process-item i {
    color: #7c3aed;
    font-size: 0.875rem;
    width: 16px;
    text-align: center;
}

.documentation-item i {
    color: #7c3aed;
    font-size: 0.875rem;
}

.doc-version {
    margin-left: auto;
    font-size: 0.75rem;
    color: #6b7280;
    background: #f3f4f6;
    padding: 2px 6px;
    border-radius: 4px;
}

.implementation-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
}

.impl-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.impl-item label {
    color: #6b7280;
    font-weight: 500;
}

.impl-item span {
    color: #1f2937;
    font-weight: 600;
}

.benefits-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.benefit-tag {
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.compliance-bar {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.compliance-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.compliance-info span:first-child {
    color: #374151;
    font-weight: 600;
}

.compliance-status {
    color: #6b7280;
    font-weight: 500;
}

.compliance-progress {
    width: 100%;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.compliance-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
}

.compliance-fill.excellent {
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
}

.compliance-fill.good {
    background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
}

.compliance-fill.satisfactory {
    background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
}

.compliance-fill.poor {
    background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
}

.standards-categories,
.compliance-summary {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.standards-categories h4,
.compliance-summary h4 {
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

.standards-categories h4 i {
    color: #f59e0b;
}

.compliance-summary h4 i {
    color: #ef4444;
}

.categories-grid,
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    padding: 24px;
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

.category-compliance {
    flex-shrink: 0;
}

.compliance-percentage {
    font-weight: 700;
    color: #f59e0b;
    font-size: 1.125rem;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-radius: 8px;
}

.summary-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.summary-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.summary-number {
    font-size: 1.5rem;
    font-weight: 700;
}

.summary-label {
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
    .code-standard-card {
        padding: 16px;
    }
    
    .standards-grid,
    .categories-grid,
    .summary-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
   .standard-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .implementation-grid {
        grid-template-columns: 1fr;
    }
}
</style>