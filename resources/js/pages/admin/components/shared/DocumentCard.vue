<template>
    <div class="document-card">
        <div class="document-header">
            <div class="document-icon" :class="getDocumentIconClass()">
                <i :class="getDocumentIcon()"></i>
            </div>
            <div class="document-info">
                <h5>{{ document.document_name }}</h5>
                <div class="document-meta">
                    <span class="document-type">{{ document.document_type }}</span>
                    <span v-if="document.document_number" class="document-number">
                        No: {{ document.document_number }}
                    </span>
                </div>
            </div>
        </div>

        <div class="document-status">
            <span :class="['status-badge', document.status]">
                <i :class="getStatusIcon()"></i>
                {{ getStatusText() }}
            </span>
            <span v-if="document.expires_soon" class="expiry-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Akan Kadaluarsa
            </span>
        </div>

        <div class="document-details">
            <div class="detail-row" v-if="document.file_name">
                <label>File:</label>
                <span>{{ document.file_name }}</span>
            </div>
            <div class="detail-row" v-if="document.file_size">
                <label>Ukuran:</label>
                <span>{{ formatFileSize(document.file_size) }}</span>
            </div>
            <div class="detail-row" v-if="document.expiry_date">
                <label>Kadaluarsa:</label>
                <span :class="{ 'expired': isExpired() }">
                    {{ formatDate(document.expiry_date) }}
                </span>
            </div>
            <div class="detail-row" v-if="document.uploaded_at">
                <label>Upload:</label>
                <span>{{ formatDate(document.uploaded_at) }}</span>
            </div>
        </div>

        <div class="document-actions">
            <button 
                v-if="document.file_path" 
                @click="$emit('view', document)"
                class="btn-action view"
                title="Lihat Dokumen"
            >
                <i class="fas fa-eye"></i>
            </button>
            <button 
                v-if="document.file_path" 
                @click="$emit('download', document)"
                class="btn-action download"
                title="Download"
            >
                <i class="fas fa-download"></i>
            </button>
            <button 
                v-if="canReview()" 
                @click="$emit('review', document)"
                class="btn-action review"
                title="Review Dokumen"
            >
                <i class="fas fa-clipboard-check"></i>
            </button>
        </div>

        <div v-if="document.admin_notes" class="document-notes">
            <label>Catatan Admin:</label>
            <p>{{ document.admin_notes }}</p>
        </div>

        <div v-if="document.rejection_reason" class="rejection-reason">
            <label>Alasan Ditolak:</label>
            <p>{{ document.rejection_reason }}</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    document: {
        type: Object,
        required: true
    },
    vendor: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['review', 'download', 'view'])

function getDocumentIcon() {
    const type = props.document.file_type?.toLowerCase() || ''
    if (type.includes('pdf')) return 'fas fa-file-pdf'
    if (type.includes('image')) return 'fas fa-file-image'
    if (type.includes('word')) return 'fas fa-file-word'
    if (type.includes('excel')) return 'fas fa-file-excel'
    return 'fas fa-file-alt'
}

function getDocumentIconClass() {
    const type = props.document.file_type?.toLowerCase() || ''
    if (type.includes('pdf')) return 'pdf'
    if (type.includes('image')) return 'image'
    if (type.includes('word')) return 'word'
    if (type.includes('excel')) return 'excel'
    return 'default'
}

function getStatusIcon() {
    switch (props.document.status) {
        case 'approved': return 'fas fa-check-circle'
        case 'rejected': return 'fas fa-times-circle'
        case 'under_review': return 'fas fa-clock'
        case 'uploaded': return 'fas fa-upload'
        case 'expired': return 'fas fa-exclamation-triangle'
        default: return 'fas fa-question-circle'
    }
}

function getStatusText() {
    switch (props.document.status) {
        case 'not_uploaded': return 'Belum Upload'
        case 'uploaded': return 'Uploaded'
        case 'under_review': return 'Under Review'
        case 'approved': return 'Approved'
        case 'rejected': return 'Rejected'
        case 'expired': return 'Expired'
        default: return 'Unknown'
    }
}

function canReview() {
    return ['uploaded', 'under_review'].includes(props.document.status)
}

function isExpired() {
    if (!props.document.expiry_date) return false
    return new Date(props.document.expiry_date) < new Date()
}

function formatDate(dateString) {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('id-ID')
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
</script>

<style scoped>
.document-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.document-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #d1d5db;
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
}

.document-icon.pdf {
    background: #fef2f2;
    color: #dc2626;
}

.document-icon.image {
    background: #f0f9ff;
    color: #0284c7;
}

.document-icon.word {
    background: #eff6ff;
    color: #2563eb;
}

.document-icon.excel {
    background: #f0fdf4;
    color: #16a34a;
}

.document-icon.default {
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

.document-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.document-type,
.document-number {
    font-size: 0.75rem;
    color: #6b7280;
}

.document-status {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
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

.status-badge.not_uploaded {
    background: #f3f4f6;
    color: #6b7280;
}

.status-badge.uploaded {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.under_review {
    background: #fef3c7;
    color: #92400e;
}

.status-badge.approved {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.rejected {
    background: #fef2f2;
    color: #991b1b;
}

.status-badge.expired {
    background: #fef2f2;
    color: #991b1b;
}

.expiry-warning {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    background: #fef3c7;
    color: #92400e;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
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

.btn-action {
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

.btn-action.view {
    background: #dbeafe;
    color: #1e40af;
}

.btn-action.download {
    background: #fef3c7;
    color: #92400e;
}

.btn-action.review {
    background: #d1fae5;
    color: #065f46;
}

.btn-action:hover {
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
</style>