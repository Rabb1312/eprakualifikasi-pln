<template>
    <div class="personnel-card">
        <div v-if="hasData" class="personnel-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="header-info">
                    <h3>Personnel Management</h3>
                    <p>Struktur organisasi dan jumlah karyawan tetap berdasarkan kategori departemen</p>
                </div>
                <div class="personnel-stats">
                    <div class="stat-badge">
                        <span class="stat-number">{{ personnelData.total_workforce || 0 }}</span>
                        <span class="stat-label">Total Staff</span>
                    </div>
                </div>
            </div>

            <div class="content-sections">
                <!-- Personnel Categories Grid -->
                <div class="categories-grid">
                    <div 
                        v-for="(category, index) in personnelCategories"
                        :key="category.key"
                        class="category-item"
                    >
                        <div class="category-header">
                            <div class="category-icon">
                                <i :class="getCategoryIcon(category.key)"></i>
                            </div>
                            <div class="category-info">
                                <h5>{{ category.label }}</h5>
                                <span class="category-type">{{ getCategoryType(category.key) }}</span>
                            </div>
                            <div class="category-count">
                                <span class="count-badge">
                                    <span class="count-number">{{ getPersonnelCount(category.key) }}</span>
                                    <span class="count-label">orang</span>
                                </span>
                            </div>
                        </div>

                        <div class="category-details">
                            <div class="personnel-summary">
                                <h6>Category Summary:</h6>
                                <div class="summary-content">
                                    <div class="summary-item">
                                        <label>Jumlah Karyawan:</label>
                                        <span>{{ getPersonnelCount(category.key) }} orang</span>
                                    </div>
                                    <div class="summary-item">
                                        <label>Persentase:</label>
                                        <span>{{ getCategoryPercentage(category.key) }}% dari total</span>
                                    </div>
                                </div>
                            </div>

                            <div class="category-description">
                                <h6>Description:</h6>
                                <div class="description-content">
                                    <p>{{ getCategoryDescription(category.key) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organization Chart Section -->
                <div class="organization-chart" v-if="personnelData.org_chart_name">
                    <h4>
                        <i class="fas fa-sitemap"></i>
                        Organization Chart
                    </h4>
                    <div class="chart-content">
                        <div class="chart-info">
                            <div class="chart-file">
                                <i class="fas fa-file-alt"></i>
                                <div class="file-details">
                                    <span class="file-name">{{ personnelData.org_chart_name }}</span>
                                    <small class="file-type">Organization Structure Document</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personnel Overview -->
                <div class="personnel-overview">
                    <h4>
                        <i class="fas fa-chart-pie"></i>
                        Personnel Distribution Overview
                    </h4>
                    <div class="overview-content">
                        <!-- Department Distribution -->
                        <div class="distribution-summary">
                            <div class="summary-header">
                                <h6>Department Distribution</h6>
                            </div>
                            <div class="distribution-items">
                                <div 
                                    v-for="category in getTopCategories()"
                                    :key="category.key"
                                    class="distribution-item"
                                >
                                    <span class="distribution-label">{{ category.label }}</span>
                                    <div class="distribution-bar">
                                        <div 
                                            class="distribution-fill"
                                            :style="{ width: category.percentage + '%' }"
                                        ></div>
                                    </div>
                                    <span class="distribution-value">{{ category.count }} ({{ category.percentage }}%)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Personnel Statistics -->
                        <div class="personnel-statistics">
                            <div class="statistics-grid">
                                <div class="statistic-item">
                                    <div class="statistic-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="statistic-info">
                                        <span class="statistic-number">{{ personnelData.total_workforce || 0 }}</span>
                                        <span class="statistic-label">Total Workforce</span>
                                    </div>
                                </div>
                                <div class="statistic-item">
                                    <div class="statistic-icon">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="statistic-info">
                                        <span class="statistic-number">{{ getPersonnelCount('management') }}</span>
                                        <span class="statistic-label">Management</span>
                                    </div>
                                </div>
                                <div class="statistic-item">
                                    <div class="statistic-icon">
                                        <i class="fas fa-industry"></i>
                                    </div>
                                    <div class="statistic-info">
                                        <span class="statistic-number">{{ getPersonnelCount('production') }}</span>
                                        <span class="statistic-label">Production</span>
                                    </div>
                                </div>
                                <div class="statistic-item">
                                    <div class="statistic-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="statistic-info">
                                        <span class="statistic-number">{{ getActiveCategories() }}</span>
                                        <span class="statistic-label">Active Departments</span>
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
                <i class="fas fa-users"></i>
                <h4>Belum Ada Data Personnel</h4>
                <p>Data struktur organisasi dan personel belum dilengkapi untuk manufacturer ini</p>
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
    return !!(props.data.personnel && Object.keys(props.data.personnel).length > 0)
})

const personnelData = computed(() => {
    if (!props.data.personnel) return {}
    
    // Handle JSON field dari database
    if (typeof props.data.personnel === 'string') {
        try {
            return JSON.parse(props.data.personnel)
        } catch {
            return {}
        }
    }
    
    return props.data.personnel || {}
})

// Categories sesuai dengan ManufacturePersonnel.vue
const personnelCategories = [
    { key: "management", label: "Management" },
    { key: "admin_finance", label: "Admin/Finance" },
    { key: "engineering", label: "Engineering" },
    { key: "procurement", label: "Procurement" },
    { key: "warehousing", label: "Warehousing" },
    { key: "sales_marketing", label: "Sales/Marketing" },
    { key: "production", label: "Production" },
    { key: "qa_qc", label: "QA/QC" },
    { key: "service", label: "Service" }
]

// Helper functions sesuai dengan ManufacturePersonnel.vue
function getCategoryIcon(key) {
    const icons = {
        'management': 'fas fa-user-tie',
        'admin_finance': 'fas fa-calculator',
        'engineering': 'fas fa-cogs',
        'procurement': 'fas fa-shopping-cart',
        'warehousing': 'fas fa-warehouse',
        'sales_marketing': 'fas fa-chart-line',
        'production': 'fas fa-industry',
        'qa_qc': 'fas fa-check-double',
        'service': 'fas fa-tools'
    }
    return icons[key] || 'fas fa-user'
}

function getCategoryType(key) {
    const types = {
        'management': 'Executive Level',
        'admin_finance': 'Administrative Support',
        'engineering': 'Technical Division',
        'procurement': 'Supply Chain',
        'warehousing': 'Logistics Division',
        'sales_marketing': 'Commercial Division',
        'production': 'Manufacturing Division',
        'qa_qc': 'Quality Assurance',
        'service': 'Customer Support'
    }
    return types[key] || 'Department'
}

function getCategoryDescription(key) {
    const descriptions = {
        'management': 'Tim manajemen yang bertanggung jawab atas pengambilan keputusan strategis dan operasional perusahaan.',
        'admin_finance': 'Departemen yang menangani administrasi umum, keuangan, akuntansi, dan dukungan operasional.',
        'engineering': 'Tim teknik yang bertanggung jawab atas desain, pengembangan produk, dan inovasi teknologi.',
        'procurement': 'Departemen pengadaan yang mengelola pembelian bahan baku, komponen, dan layanan eksternal.',
        'warehousing': 'Tim gudang yang mengelola penyimpanan, inventori, dan distribusi produk.',
        'sales_marketing': 'Departemen penjualan dan pemasaran yang bertanggung jawab atas strategi komersial dan hubungan pelanggan.',
        'production': 'Divisi produksi yang bertanggung jawab atas proses manufaktur dan operasi pabrik.',
        'qa_qc': 'Tim quality assurance dan quality control yang memastikan standar kualitas produk.',
        'service': 'Departemen layanan yang menangani dukungan pelanggan dan layanan purna jual.'
    }
    return descriptions[key] || 'Departemen dalam struktur organisasi perusahaan.'
}

function getCategoryResponsibilities(key) {
    const responsibilities = {
        'management': [
            'Strategic planning and decision making',
            'Policy development and implementation',
            'Resource allocation and budgeting',
            'Performance monitoring and evaluation'
        ],
        'admin_finance': [
            'Financial planning and analysis',
            'Accounting and bookkeeping',
            'Human resources management',
            'Administrative support services'
        ],
        'engineering': [
            'Product design and development',
            'Technical specifications preparation',
            'Process improvement initiatives',
            'Technology innovation and research'
        ],
        'procurement': [
            'Supplier evaluation and selection',
            'Purchase order management',
            'Contract negotiation',
            'Vendor relationship management'
        ],
        'warehousing': [
            'Inventory management and control',
            'Storage optimization',
            'Shipping and receiving operations',
            'Distribution planning'
        ],
        'sales_marketing': [
            'Market analysis and research',
            'Customer relationship management',
            'Sales target achievement',
            'Marketing campaign execution'
        ],
        'production': [
            'Manufacturing operations management',
            'Production scheduling and planning',
            'Equipment maintenance and operation',
            'Safety protocol implementation'
        ],
        'qa_qc': [
            'Quality standards implementation',
            'Product testing and inspection',
            'Quality documentation management',
            'Continuous improvement processes'
        ],
        'service': [
            'Customer support and assistance',
            'After-sales service delivery',
            'Technical support provision',
            'Customer satisfaction monitoring'
        ]
    }
    return responsibilities[key] || []
}

function getPersonnelCount(key) {
    return parseInt(personnelData.value[key]) || 0
}

function getCategoryPercentage(key) {
    const total = personnelData.value.total_workforce || 0
    const count = getPersonnelCount(key)
    return total > 0 ? Math.round((count / total) * 100) : 0
}

function getTopCategories() {
    return personnelCategories
        .map(category => ({
            ...category,
            count: getPersonnelCount(category.key),
            percentage: getCategoryPercentage(category.key)
        }))
        .filter(category => category.count > 0)
        .sort((a, b) => b.count - a.count)
}

function getActiveCategories() {
    return personnelCategories.filter(category => getPersonnelCount(category.key) > 0).length
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

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.category-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.category-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.category-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}

.category-icon {
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

.category-info {
    flex: 1;
}

.category-info h5 {
    margin: 0 0 4px 0;
    font-weight: 700;
    color: #1f2937;
    font-size: 1.125rem;
}

.category-type {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.category-count {
    flex-shrink: 0;
}

.count-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px 12px;
    background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
    color: #6d28d9;
    border-radius: 8px;
}

.count-number {
    font-size: 1.25rem;
    font-weight: 700;
}

.count-label {
    font-size: 0.75rem;
    opacity: 0.8;
}

.category-details {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.personnel-summary,
.category-description,
.category-responsibilities {
    background: #f8fafc;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.personnel-summary h6,
.category-description h6,
.category-responsibilities h6 {
    margin: 0 0 12px 0;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.summary-content {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 0.875rem;
}

.summary-item label {
    color: #6b7280;
    font-weight: 500;
}

.summary-item span {
    color: #1f2937;
    font-weight: 600;
}

.description-content p {
    margin: 0;
    color: #374151;
    line-height: 1.6;
    font-size: 0.875rem;
    text-align: justify;
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
    color: #7c3aed;
    font-size: 0.75rem;
}

.organization-chart,
.personnel-overview {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.organization-chart h4,
.personnel-overview h4 {
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

.organization-chart h4 i {
    color: #10b981;
}

.personnel-overview h4 i {
    color: #7c3aed;
}

.chart-content,
.overview-content {
    padding: 24px;
}

.chart-info {
    display: flex;
    justify-content: center;
}

.chart-file {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    max-width: 400px;
}

.chart-file i {
    color: #10b981;
    font-size: 1.5rem;
}

.file-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.file-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.file-type {
    color: #6b7280;
    font-size: 0.75rem;
}

.overview-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.distribution-summary {
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

.distribution-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.distribution-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.distribution-label {
    flex: 0 0 120px;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.distribution-bar {
    flex: 1;
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.distribution-fill {
    height: 100%;
    background: linear-gradient(90deg, #7c3aed 0%, #a855f7 100%);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.distribution-value {
    flex: 0 0 80px;
    text-align: right;
    font-weight: 600;
    color: #7c3aed;
    font-size: 0.875rem;
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
    
    .categories-grid,
    .distribution-summary,
    .statistics-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .category-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .distribution-item {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    
    .distribution-label {
        flex: none;
        text-align: center;
    }
    
    .distribution-value {
        flex: none;
        text-align: center;
    }
}
</style>