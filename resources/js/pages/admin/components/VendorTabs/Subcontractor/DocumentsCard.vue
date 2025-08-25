<template>
    <div class="documents-card">
        <div class="section-header">
            <div class="header-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="header-info">
                <h3>Documents & Attachments</h3>
                <p>Dokumen dan lampiran yang diperlukan untuk subcontractor</p>
            </div>
            <div class="documents-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ totalDocuments }}</span>
                    <span class="stat-label">Total</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ uploadedDocuments }}</span>
                    <span class="stat-label">Uploaded</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ approvedDocuments }}</span>
                    <span class="stat-label">Approved</span>
                </div>
            </div>
        </div>

        <div v-if="hasDocuments" class="documents-content">
            <!-- Documents Filter -->
            <div class="documents-filter">
                <div class="filter-buttons">
                    <button
                        v-for="filter in filterOptions"
                        :key="filter.key"
                        @click="activeFilter = filter.key"
                        :class="['filter-btn', { active: activeFilter === filter.key }]"
                    >
                        <i :class="filter.icon"></i>
                        {{ filter.label }}
                        <span class="filter-count">{{ getFilterCount(filter.key) }}</span>
                    </button>
                </div>
            </div>

            <!-- Documents Grid -->
            <div class="documents-grid">
                <div 
                    v-for="doc in filteredDocuments" 
                    :key="doc.id || doc.document_type"
                    class="document-item"
                >
                    <div class="document-header">
                        <div class="document-icon">
                            <i :class="getDocumentIcon(doc)"></i>
                        </div>
                        <div class="document-info">
                            <h5>{{ doc.document_name || doc.name }}</h5>
                            <span class="document-type">{{ doc.document_type }}</span>
                        </div>
                        <div class="document-status">
                            <span :class="['status-badge', getStatusClass(doc.status)]">
                                <i :class="getStatusIcon(doc.status)"></i>
                                {{ getStatusText(doc.status) }}
                            </span>
                        </div>
                    </div>

                    <div class="document-details">
                        <div class="detail-row" v-if="doc.file_name">
                            <label>File:</label>
                            <span>{{ doc.file_name }}</span>
                        </div>
                        <div class="detail-row" v-if="doc.file_size">
                            <label>Size:</label>
                            <span>{{ formatFileSize(doc.file_size) }}</span>
                        </div>
                        <div class="detail-row" v-if="doc.uploaded_at">
                            <label>Uploaded:</label>
                            <span>{{ formatDate(doc.uploaded_at) }}</span>
                        </div>
                        <div class="detail-row" v-if="doc.expiry_date">
                            <label>Expires:</label>
                            <span :class="{ 'expired': isExpired(doc.expiry_date) }">
                                {{ formatDate(doc.expiry_date) }}
                            </span>
                        </div>
                    </div>

                    <div class="document-actions">
                        <button 
                            v-if="doc.file_path" 
                            @click="viewDocument(doc)"
                            class="action-btn view"
                            title="View Document"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                        <button 
                            v-if="doc.file_path" 
                            @click="downloadDocument(doc)"
                            class="action-btn download"
                            title="Download"
                        >
                            <i class="fas fa-download"></i>
                        </button>
                        <button 
                            v-if="canReview(doc)" 
                            @click="reviewDocument(doc)"
                            class="action-btn review"
                            title="Review"
                        >
                            <i class="fas fa-clipboard-check"></i>
                        </button>
                    </div>

                    <div v-if="doc.admin_notes" class="document-notes">
                        <label>Admin Notes:</label>
                        <p>{{ doc.admin_notes }}</p>
                    </div>

                    <div v-if="doc.rejection_reason" class="rejection-reason">
                        <label>Rejection Reason:</label>
                        <p>{{ doc.rejection_reason }}</p>
                    </div>
                </div>
            </div>

            <!-- Empty State for Filter -->
            <div v-if="filteredDocuments.length === 0" class="empty-filter">
                <div class="empty-filter-illustration">
                    <i class="fas fa-filter"></i>
                    <h4>No Documents Found</h4>
                    <p>No documents match the selected filter criteria</p>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-file-alt"></i>
                <h4>Belum Ada Dokumen</h4>
                <p>Dokumen dan lampiran belum diupload untuk subcontractor ini</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

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

const emit = defineEmits(['view', 'download', 'review'])

const activeFilter = ref('all')

const filterOptions = [
    { key: 'all', label: 'All', icon: 'fas fa-list' },
    { key: 'uploaded', label: 'Uploaded', icon: 'fas fa-upload' },
    { key: 'approved', label: 'Approved', icon: 'fas fa-check' },
    { key: 'rejected', label: 'Rejected', icon: 'fas fa-times' },
    { key: 'pending', label: 'Pending', icon: 'fas fa-clock' }
]

const documentsList = computed(() => {
    // Get documents from vendor or create mock documents for demo
    if (props.vendor.documents && Array.isArray(props.vendor.documents)) {
        return props.vendor.documents
    }
    
    // Return mock documents for subcontractor
    return [
        {
            id: 1,
            document_type: 'company_profile',
            document_name: 'Company Profile',
            status: 'approved',
            file_name: 'company_profile.pdf',
            file_size: '2048000',
            uploaded_at: '2025-08-20T10:30:00Z',
            file_path: '/documents/company_profile.pdf'
        },
        {
            id: 2,
            document_type: 'financial_statement',
            document_name: 'Financial Statement',
            status: 'pending',
            file_name: 'financial_2024.pdf',
            file_size: '1024000',
            uploaded_at: '2025-08-22T14:15:00Z',
            file_path: '/documents/financial_2024.pdf'
        },
        {
            id: 3,
            document_type: 'safety_certificate',
            document_name: 'Safety Certificate',
            status: 'rejected',
            file_name: 'safety_cert.pdf',
            file_size: '512000',
            uploaded_at: '2025-08-21T09:45:00Z',
            file_path: '/documents/safety_cert.pdf',
            rejection_reason: 'Certificate has expired'
        }
    ]
})

const hasDocuments = computed(() => {
    return documentsList.value.length > 0
})

const filteredDocuments = computed(() => {
    if (activeFilter.value === 'all') {
        return documentsList.value
    }
    
    return documentsList.value.filter(doc => {
        switch (activeFilter.value) {
            case 'uploaded':
                return doc.status !== 'not_uploaded'
            case 'approved':
                return doc.status === 'approved'
            case 'rejected':
                return doc.status === 'rejected'
            case 'pending':
                return ['uploaded', 'under_review'].includes(doc.status)
            default:
                return true
        }
    })
})

const totalDocuments = computed(() => documentsList.value.length)
const uploadedDocuments = computed(() => documentsList.value.filter(doc => doc.status !== 'not_uploaded').length)
const approvedDocuments = computed(() => documentsList.value.filter(doc => doc.status === 'approved').length)

function getFilterCount(filterKey) {
    if (filterKey === 'all') return totalDocuments.value
    if (filterKey === 'uploaded') return uploadedDocuments.value
    if (filterKey === 'approved') return approvedDocuments.value
    if (filterKey === 'rejected') return documentsList.value.filter(doc => doc.status === 'rejected').length
    if (filterKey === 'pending') return documentsList.value.filter(doc => ['uploaded', 'under_review'].includes(doc.status)).length
    return 0
}

function getDocumentIcon(doc) {
    const type = doc.file_type?.toLowerCase() || doc.document_type?.toLowerCase() || ''
    if (type.includes('pdf')) return 'fas fa-file-pdf'
    if (type.includes('image')) return 'fas fa-file-image'
    if (type.includes('word')) return 'fas fa-file-word'
    if (type.includes('excel')) return 'fas fa-file-excel'
    return 'fas fa-file-alt'
}

function getStatusClass(status) {
    return status?.toLowerCase() || 'unknown'
}

function getStatusIcon(status) {
    switch (status) {
        case 'approved': return 'fas fa-check-circle'
        case 'rejected': return 'fas fa-times-circle'
        case 'under_review': return 'fas fa-clock'
        case 'uploaded': return 'fas fa-upload'
        case 'pending': return 'fas fa-clock'
        default: return 'fas fa-question-circle'
    }
}

function getStatusText(status) {
    switch (status) {
        case 'approved': return 'Approved'
        case 'rejected': return 'Rejected'
        case 'under_review': return 'Under Review'
        case 'uploaded': return 'Uploaded'
        case 'pending': return 'Pending'
        case 'not_uploaded': return 'Not Uploaded'
        default: return 'Unknown'
    }
}

function canReview(doc) {
    return ['uploaded', 'under_review'].includes(doc.status)
}

function isExpired(dateString) {
    if (!dateString) return false
    return new Date(dateString) < new Date()
}

function formatFileSize(size) {
    if (!size) return '-'
    const units = ['B', 'KB', 'MB', 'GB']
    let unitIndex = 0
    let fileSize = parseInt(size)
    
    while (fileSize >= 1024 && unitIndex < units.length - 1) {
        fileSize /= 1024
        unitIndex++
    }
    
    return `${fileSize.toFixed(1)} ${units[unitIndex]}`
}

function formatDate(dateString) {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    })
}

function viewDocument(doc) {
    emit('view', doc)
}

function downloadDocument(doc) {
    emit('download', doc)
}

function reviewDocument(doc) {
    emit('review', doc)
}
</script>

<style scoped>
.documents-card {
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
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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

.documents-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.documents-filter {
    margin-bottom: 24px;
}

.filter-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-btn {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.filter-btn.active {
    background: #1f2937;
    color: white;
    border-color: #1f2937;
}

.filter-count {
    background: rgba(0, 0, 0, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.filter-btn.active .filter-count {
    background: rgba(255, 255, 255, 0.2);
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
}

.document-item {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.document-item:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.document-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
}

.document-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    background: #f3f4f6;
    color: #6b7280;
}

.document-info {
    flex: 1;
    min-width: 0;
}

.document-info h5 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.3;
}

.document-type {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.document-status {
    flex-shrink: 0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.approved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.rejected {
    background: #fef2f2;
    color: #991b1b;
}

.status-badge.pending,
.status-badge.uploaded,
.status-badge.under_review {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.not_uploaded {
    background: #f3f4f6;
    color: #6b7280;
}

.document-details {
    margin-bottom: 16px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    font-size: 0.875rem;
}

.detail-row label {
    color: #6b7280;
    font-weight: 500;
}

.detail-row span {
    color: #1f2937;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

.detail-row span.expired {
    color: #dc2626;
    font-weight: 600;
}

.document-actions {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
}

.action-btn {
    flex: 1;
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.action-btn.view {
    background: #dbeafe;
    color: #1e40af;
}

.action-btn.download {
    background: #d1fae5;
    color: #065f46;
}

.action-btn.review {
    background: #fef3c7;
    color: #92400e;
}

.action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.document-notes,
.rejection-reason {
    padding-top: 16px;
    border-top: 1px solid #f3f4f6;
}

.document-notes label,
.rejection-reason label {
    display: block;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 8px;
}

.document-notes p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.5;
}

.rejection-reason p {
    margin: 0;
    color: #dc2626;
    font-size: 0.875rem;
    line-height: 1.5;
    font-weight: 500;
}

.empty-filter,
.no-data {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 300px;
    text-align: center;
}

.empty-filter-illustration,
.no-data-illustration {
    max-width: 400px;
}

.empty-filter-illustration i,
.no-data-illustration i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 20px;
}

.empty-filter-illustration h4,
.no-data-illustration h4 {
    margin: 0 0 12px 0;
    color: #6b7280;
    font-size: 1.25rem;
    font-weight: 600;
}

.empty-filter-illustration p,
.no-data-illustration p {
    margin: 0;
    color: #9ca3af;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .documents-card {
        padding: 16px;
    }
    
    .documents-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .documents-stats {
        justify-content: center;
    }
    
    .filter-buttons {
        justify-content: center;
    }
    
    .document-header {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
}
</style>