<template>
    <div class="code-standard-card">
        <div v-if="hasData" class="code-standard-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="header-info">
                    <h3>Code Standard Capability</h3>
                    <p>Standar kode dan sertifikasi yang dimiliki perusahaan untuk manufaktur</p>
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
                                <i :class="getAuthorityIcon(standard.authority)"></i>
                            </div>
                            <div class="standard-info">
                                <h5>{{ standard.authority || `Standard ${index + 1}` }}</h5>
                                <span class="standard-type">{{ getAuthorityType(standard.authority) }}</span>
                                <div class="standard-number">
                                    <i class="fas fa-hashtag"></i>
                                    <span>Standard #{{ index + 1 }}</span>
                                </div>
                            </div>
                            <div class="standard-status">
                                <span :class="['status-badge', getStandardStatus(standard)]">
                                    <i :class="getStandardStatusIcon(standard)"></i>
                                    {{ getStandardStatusText(standard) }}
                                </span>
                            </div>
                        </div>

                        <div class="standard-details">
                            <!-- Authority Information -->
                            <div class="authority-info">
                                <h6>Authority Information:</h6>
                                <div class="authority-content">
                                    <div class="authority-item">
                                        <label>Authority:</label>
                                        <span>{{ standard.authority || 'Not specified' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Details -->
                            <div class="section-details" v-if="standard.section">
                                <h6>Section / Code:</h6>
                                <div class="section-content">
                                    <div class="section-item">
                                        <i class="fas fa-layer-group"></i>
                                        <span>{{ standard.section }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Classes Information -->
                            <div class="classes-info" v-if="standard.classes">
                                <h6>Classes:</h6>
                                <div class="classes-content">
                                    <div class="classes-item">
                                        <i class="fas fa-tags"></i>
                                        <span>{{ standard.classes }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Stamps Held -->
                            <div class="stamps-info" v-if="standard.stamps_held">
                                <h6>Stamps Held:</h6>
                                <div class="stamps-content">
                                    <div class="stamps-tags">
                                        <span 
                                            v-for="(stamp, stampIndex) in getStampsArray(standard.stamps_held)"
                                            :key="stampIndex"
                                            class="stamp-tag"
                                        >
                                            <i class="fas fa-stamp"></i>
                                            {{ stamp }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Standard Summary -->
                            <!-- <div class="standard-summary">
                                <h6>Summary:</h6>
                                <div class="summary-grid">
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Authority</span>
                                            <span class="summary-value">{{ standard.authority ? 'Specified' : 'Not specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Section</span>
                                            <span class="summary-value">{{ standard.section ? 'Defined' : 'Not defined' }}</span>
                                        </div>
                                    </div>
                                    <div class="summary-item">
                                        <div class="summary-icon">
                                            <i class="fas fa-stamp"></i>
                                        </div>
                                        <div class="summary-info">
                                            <span class="summary-label">Stamps</span>
                                            <span class="summary-value">{{ getStampsCount(standard.stamps_held) }} stamps</span>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Authority Description -->
                            <div class="authority-description">
                                <h6>Authority Description:</h6>
                                <div class="description-content">
                                    <p>{{ getAuthorityDescription(standard.authority) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Standards Overview -->
                <div class="standards-overview">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Code Standards Overview
                    </h4>
                    <div class="overview-content">
                        <!-- Authorities Summary -->
                        <div class="authorities-summary">
                            <div class="summary-header">
                                <h6>Authorities Distribution</h6>
                            </div>
                            <div class="authorities-items">
                                <div 
                                    v-for="(authority, index) in getUniqueAuthorities()"
                                    :key="index"
                                    class="authority-summary-item"
                                >
                                    <span class="authority-label">{{ authority.name }}</span>
                                    <span class="authority-count">{{ authority.count }} standard{{ authority.count !== 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stamps Summary -->
                        <div class="stamps-summary" v-if="getAllUniqueStamps().length > 0">
                            <div class="summary-header">
                                <h6>Stamps Held</h6>
                            </div>
                            <div class="stamps-list">
                                <span 
                                    v-for="(stamp, index) in getAllUniqueStamps()"
                                    :key="index"
                                    class="stamp-badge"
                                >
                                    <i class="fas fa-certificate"></i>
                                    {{ stamp }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reference Guide -->
                <div class="reference-guide">
                    <h4>
                        <i class="fas fa-book"></i>
                        Reference Guide
                    </h4>
                    <div class="guide-content">
                        <div class="guide-sections">
                            <div class="guide-section">
                                <h6>Common Authorities:</h6>
                                <div class="guide-items">
                                    <div class="guide-item">
                                        <strong>ASME</strong> - American Society of Mechanical Engineers
                                    </div>
                                    <div class="guide-item">
                                        <strong>API</strong> - American Petroleum Institute
                                    </div>
                                    <div class="guide-item">
                                        <strong>AWS</strong> - American Welding Society
                                    </div>
                                    <div class="guide-item">
                                        <strong>SNI</strong> - Standar Nasional Indonesia
                                    </div>
                                </div>
                            </div>

                            <div class="guide-section">
                                <h6>Example Stamps:</h6>
                                <div class="guide-items">
                                    <div class="guide-item">
                                        <strong>U</strong> - Pressure Vessels
                                    </div>
                                    <div class="guide-item">
                                        <strong>S</strong> - Power Boilers
                                    </div>
                                    <div class="guide-item">
                                        <strong>PP</strong> - Pressure Piping
                                    </div>
                                    <div class="guide-item">
                                        <strong>UM</strong> - Miniature Pressure Vessels
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-certificate"></i>
                <h4>Belum Ada Data Code Standards</h4>
                <p>Data standar kode dan sertifikasi belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.code_standard && Array.isArray(props.data.code_standard) && props.data.code_standard.length > 0)
})

const codeStandards = computed(() => {
    if (!props.data.code_standard) return []
    
    // Handle JSON field dari database - sesuai dengan ManufactureCodeStandard.vue
    if (Array.isArray(props.data.code_standard)) {
        return props.data.code_standard.filter(standard => 
            standard && (standard.authority || standard.section || standard.classes || standard.stamps_held)
        )
    }
    
    if (typeof props.data.code_standard === 'string') {
        try {
            const parsed = JSON.parse(props.data.code_standard)
            return Array.isArray(parsed) ? parsed.filter(standard => 
                standard && (standard.authority || standard.section || standard.classes || standard.stamps_held)
            ) : []
        } catch {
            return []
        }
    }
    
    return []
})

// Helper functions sesuai dengan struktur data ManufactureCodeStandard
function getAuthorityIcon(authority) {
    if (!authority) return 'fas fa-certificate'
    
    const authorityUpper = authority.toUpperCase()
    const icons = {
        'ASME': 'fas fa-cogs',
        'API': 'fas fa-oil-can',
        'AWS': 'fas fa-fire',
        'SNI': 'fas fa-flag',
        'ANSI': 'fas fa-flag-usa',
        'ISO': 'fas fa-globe',
        'ASTM': 'fas fa-vial',
        'DIN': 'fas fa-flag',
        'JIS': 'fas fa-yin-yang'
    }
    
    for (const [key, icon] of Object.entries(icons)) {
        if (authorityUpper.includes(key)) return icon
    }
    return 'fas fa-certificate'
}

function getAuthorityType(authority) {
    if (!authority) return 'Code Standard'
    
    const authorityUpper = authority.toUpperCase()
    const types = {
        'ASME': 'Mechanical Engineering Standard',
        'API': 'Petroleum Industry Standard',
        'AWS': 'Welding Standard',
        'SNI': 'Indonesian National Standard',
        'ANSI': 'American National Standard',
        'ISO': 'International Standard',
        'ASTM': 'Materials Testing Standard',
        'DIN': 'German Industrial Standard',
        'JIS': 'Japanese Industrial Standard'
    }
    
    for (const [key, type] of Object.entries(types)) {
        if (authorityUpper.includes(key)) return type
    }
    return 'Code Standard'
}

function getAuthorityDescription(authority) {
    if (!authority) return 'No authority specified for this standard.'
    
    const authorityUpper = authority.toUpperCase()
    const descriptions = {
        'ASME': 'The American Society of Mechanical Engineers develops codes and standards for mechanical engineering, including pressure vessels, boilers, and piping systems.',
        'API': 'The American Petroleum Institute develops standards for the oil and gas industry, covering equipment, operations, and safety procedures.',
        'AWS': 'The American Welding Society establishes standards for welding processes, procedures, and qualifications.',
        'SNI': 'Standar Nasional Indonesia adalah standar yang ditetapkan oleh Badan Standardisasi Nasional untuk berbagai industri di Indonesia.',
        'ANSI': 'The American National Standards Institute oversees the development of voluntary consensus standards for products, services, and systems.',
        'ISO': 'The International Organization for Standardization develops and publishes international standards for various industries and applications.',
        'ASTM': 'ASTM International develops technical standards for materials, products, systems, and services.',
        'DIN': 'Deutsches Institut für Normung develops German and European standards for various industries.',
        'JIS': 'Japanese Industrial Standards cover a wide range of industrial activities in Japan.'
    }
    
    for (const [key, description] of Object.entries(descriptions)) {
        if (authorityUpper.includes(key)) return description
    }
    return `${authority} is a recognized standards authority in the manufacturing industry.`
}

function getStandardStatus(standard) {
    const completeness = getStandardCompleteness(standard)
    if (completeness >= 75) return 'complete'
    if (completeness >= 50) return 'partial'
    return 'incomplete'
}

function getStandardStatusIcon(standard) {
    const status = getStandardStatus(standard)
    const icons = {
        'complete': 'fas fa-check-circle',
        'partial': 'fas fa-clock',
        'incomplete': 'fas fa-exclamation-circle'
    }
    return icons[status] || 'fas fa-question-circle'
}

function getStandardStatusText(standard) {
    const completeness = getStandardCompleteness(standard)
    return `${completeness}% Complete`
}

function getStandardCompleteness(standard) {
    const fields = ['authority', 'section', 'classes', 'stamps_held']
    const filledFields = fields.filter(field => standard[field] && standard[field].trim()).length
    return Math.round((filledFields / fields.length) * 100)
}

function getStampsArray(stampsHeld) {
    if (!stampsHeld) return []
    return stampsHeld.split(',').map(stamp => stamp.trim()).filter(stamp => stamp)
}

function getStampsCount(stampsHeld) {
    return getStampsArray(stampsHeld).length
}

function getUniqueAuthorities() {
    const authorities = {}
    codeStandards.value.forEach(standard => {
        if (standard.authority && standard.authority.trim()) {
            const auth = standard.authority.trim()
            if (!authorities[auth]) {
                authorities[auth] = { name: auth, count: 0 }
            }
            authorities[auth].count++
        }
    })
    return Object.values(authorities).sort((a, b) => b.count - a.count)
}

function getAllUniqueStamps() {
    const stamps = new Set()
    codeStandards.value.forEach(standard => {
        if (standard.stamps_held) {
            getStampsArray(standard.stamps_held).forEach(stamp => {
                stamps.add(stamp.toUpperCase())
            })
        }
    })
    return Array.from(stamps).sort()
}

function getCompleteStandards() {
    return codeStandards.value.filter(standard => getStandardCompleteness(standard) === 100).length
}
</script>

<style scoped>
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
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
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

.standard-number {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #6b7280;
}

.standard-number i {
    font-size: 0.75rem;
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

.status-badge.complete {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.partial {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.incomplete {
    background: #fef2f2;
    color: #991b1b;
}

.standard-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.authority-info,
.section-details,
.classes-info,
.stamps-info,
.standard-summary,
.authority-description {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.authority-info h6,
.section-details h6,
.classes-info h6,
.stamps-info h6,
.standard-summary h6,
.authority-description h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.authority-content,
.section-content,
.classes-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.authority-item,
.section-item,
.classes-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #374151;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.authority-item {
    justify-content: space-between;
}

.authority-item label {
    color: #6b7280;
    font-weight: 500;
}

.authority-item span {
    color: #1f2937;
    font-weight: 600;
}

.section-item i,
.classes-item i {
    color: #7c3aed;
    font-size: 0.75rem;
}

.stamps-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.stamps-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.stamp-tag {
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

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.summary-icon {
    width: 24px;
    height: 24px;
    background: #7c3aed;
    color: white;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.summary-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.summary-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.summary-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
}

.authority-description .description-content p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
    text-align: justify;
    padding: 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.standards-overview,
.reference-guide {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.standards-overview h4,
.reference-guide h4 {
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

.standards-overview h4 i {
    color: #7c3aed;
}

.reference-guide h4 i {
    color: #10b981;
}

.overview-content,
.guide-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.authorities-summary,
.stamps-summary {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.summary-header h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.authorities-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.authority-summary-item {
    padding: 12px 16px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
}

.authority-label {
    font-weight: 600;
    font-size: 0.875rem;
}

.authority-count {
    font-weight: 700;
    font-size: 0.875rem;
}

.stamps-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.stamp-badge {
    background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
    color: #7c3aed;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.statistic-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
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

.guide-sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.guide-section h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.guide-items {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.guide-item {
    padding: 8px 12px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.875rem;
    color: #374151;
    line-height: 1.4;
}

.guide-item strong {
    color: #7c3aed;
    font-weight: 600;
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
    .authorities-summary,
    .statistics-grid,
    .guide-sections {
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
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
}
</style>