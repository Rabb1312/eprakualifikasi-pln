<template>
    <div class="documents-tab">
        <div class="tab-content-wrapper">
            <!-- Documents Header -->
            <div class="documents-header">
                <div class="header-info">
                    <h3>
                        <i class="fas fa-file-alt"></i>
                        Dokumen Vendor
                    </h3>
                    <p>Kelola dan review dokumen yang diunggah oleh vendor</p>
                </div>
                <div class="header-actions">
                    <div class="document-stats">
                        <span class="stat-item">
                            <i class="fas fa-file"></i>
                            {{ getTotalDocuments() }} Total
                        </span>
                        <span class="stat-item approved">
                            <i class="fas fa-check"></i>
                            {{ getApprovedDocuments() }} Approved
                        </span>
                        <span class="stat-item pending">
                            <i class="fas fa-clock"></i>
                            {{ getPendingDocuments() }} Pending
                        </span>
                    </div>
                    <button @click="refreshDocuments" class="btn-refresh" :disabled="loading">
                        <i class="fas fa-sync-alt" :class="{ 'fa-spin': loading }"></i>
                        Refresh
                    </button>
                </div>
            </div>

            <!-- Documents Filter -->
            <div class="documents-filter">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>Status</label>
                        <select v-model="filters.status" @change="filterDocuments">
                            <option value="">Semua Status</option>
                            <option value="not_uploaded">Belum Upload</option>
                            <option value="uploaded">Uploaded</option>
                            <option value="under_review">Under Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Tipe Dokumen</label>
                        <select v-model="filters.type" @change="filterDocuments">
                            <option value="">Semua Tipe</option>
                            <option value="legal">Legal</option>
                            <option value="financial">Financial</option>
                            <option value="technical">Technical</option>
                            <option value="certificate">Certificate</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Kadaluarsa</label>
                        <select v-model="filters.expiry" @change="filterDocuments">
                            <option value="">Semua</option>
                            <option value="expires_soon">Akan Kadaluarsa</option>
                            <option value="expired">Sudah Kadaluarsa</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button @click="resetFilters" class="btn-secondary">
                            <i class="fas fa-undo"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Documents List -->
            <div class="documents-list" v-if="!loading">
                <div v-if="filteredDocuments.length === 0" class="no-documents">
                    <i class="fas fa-file-alt"></i>
                    <h4>Tidak Ada Dokumen</h4>
                    <p v-if="hasActiveFilters">Tidak ada dokumen yang sesuai dengan filter</p>
                    <p v-else>Belum ada dokumen yang diunggah untuk vendor ini</p>
                </div>

                <div v-else class="document-groups">
                    <div 
                        v-for="group in documentGroups" 
                        :key="group.name"
                        class="document-group"
                    >
                        <div class="group-header">
                            <h4>
                                <i :class="getGroupIcon(group.type)"></i>
                                {{ group.name }}
                            </h4>
                            <span class="group-count">{{ group.documents.length }} dokumen</span>
                        </div>
                        
                        <div class="group-documents">
                            <DocumentCard
                                v-for="document in group.documents"
                                :key="document.id"
                                :document="document"
                                :vendor="vendor"
                                @review="reviewDocument"
                                @download="downloadDocument"
                                @view="viewDocument"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat dokumen...</p>
            </div>
        </div>

        <!-- Document Review Modal -->
        <DocumentReviewModal
            v-if="showReviewModal"
            :document="selectedDocument"
            @close="showReviewModal = false"
            @approve="approveDocument"
            @reject="rejectDocument"
        />

        <!-- Document Viewer Modal -->
        <DocumentViewerModal
            v-if="showViewerModal"
            :document="selectedDocument"
            @close="showViewerModal = false"
        />
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
// ✅ PERBAIKI PATH IMPORT - relatif ke folder shared yang sejajar
import DocumentCard from '../shared/DocumentCard.vue'
import DocumentReviewModal from '../shared/DocumentReviewModal.vue'
import DocumentViewerModal from '../shared/DocumentViewerModal.vue'

const props = defineProps({
    vendor: {
        type: Object,
        required: true
    },
    documents: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['refresh'])

const loading = ref(false)
const showReviewModal = ref(false)
const showViewerModal = ref(false)
const selectedDocument = ref(null)

const filters = ref({
    status: '',
    type: '',
    expiry: ''
})

const filteredDocuments = computed(() => {
    let docs = props.documents || []
    
    if (filters.value.status) {
        docs = docs.filter(doc => doc.status === filters.value.status)
    }
    
    if (filters.value.type) {
        docs = docs.filter(doc => doc.document_type?.includes(filters.value.type))
    }
    
    if (filters.value.expiry) {
        if (filters.value.expiry === 'expires_soon') {
            docs = docs.filter(doc => doc.expires_soon)
        } else if (filters.value.expiry === 'expired') {
            docs = docs.filter(doc => doc.status === 'expired')
        }
    }
    
    return docs
})

const documentGroups = computed(() => {
    const groups = {}
    
    filteredDocuments.value.forEach(doc => {
        const groupName = doc.group_name || doc.group || 'Lainnya'
        const groupType = doc.parent_type || 'general'
        
        if (!groups[groupName]) {
            groups[groupName] = {
                name: groupName,
                type: groupType,
                documents: []
            }
        }
        
        groups[groupName].documents.push(doc)
    })
    
    return Object.values(groups).sort((a, b) => a.name.localeCompare(b.name))
})

const hasActiveFilters = computed(() => {
    return filters.value.status || filters.value.type || filters.value.expiry
})

function getTotalDocuments() {
    return props.documents?.length || 0
}

function getApprovedDocuments() {
    return props.documents?.filter(doc => doc.status === 'approved').length || 0
}

function getPendingDocuments() {
    return props.documents?.filter(doc => 
        ['uploaded', 'under_review'].includes(doc.status)
    ).length || 0
}

function getGroupIcon(type) {
    const icons = {
        'legal': 'fas fa-balance-scale',
        'financial': 'fas fa-chart-line',
        'technical': 'fas fa-cogs',
        'certificate': 'fas fa-certificate',
        'general': 'fas fa-folder'
    }
    return icons[type] || 'fas fa-folder'
}

function filterDocuments() {
    // Filter sudah reactive melalui computed
}

function resetFilters() {
    filters.value = {
        status: '',
        type: '',
        expiry: ''
    }
}

async function refreshDocuments() {
    loading.value = true
    try {
        emit('refresh')
    } finally {
        setTimeout(() => {
            loading.value = false
        }, 1000)
    }
}

function reviewDocument(document) {
    selectedDocument.value = document
    showReviewModal.value = true
}

function viewDocument(document) {
    selectedDocument.value = document
    showViewerModal.value = true
}

function downloadDocument(document) {
    if (document.file_path) {
        const link = document.createElement('a')
        link.href = document.file_path
        link.download = document.file_name || 'document'
        link.click()
    }
}

async function approveDocument(document, notes) {
    try {
        // API call to approve document
        await axios.patch(`/api/admin/documents/${document.id}/approve`, { notes })
        showReviewModal.value = false
        refreshDocuments()
        // Show success toast
    } catch (error) {
        console.error('Error approving document:', error)
        // Show error toast
    }
}

async function rejectDocument(document, reason, notes) {
    try {
        // API call to reject document
        await axios.patch(`/api/admin/documents/${document.id}/reject`, { 
            reason, 
            notes 
        })
        showReviewModal.value = false
        refreshDocuments()
        // Show success toast
    } catch (error) {
        console.error('Error rejecting document:', error)
        // Show error toast
    }
}
</script>

<style scoped>
/* Existing styles... */
.documents-tab {
    padding: 24px;
}

.tab-content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.documents-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 24px;
    background: #f9fafb;
    padding: 24px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.header-info h3 {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 0 8px 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
}

.header-info h3 i {
    color: #6b7280;
}

.header-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.header-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
}

.document-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
}

.stat-item.approved {
    color: #059669;
}

.stat-item.pending {
    color: #d97706;
}

.btn-refresh {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    color: #374151;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-refresh:hover:not(:disabled) {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.btn-refresh:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.documents-filter {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto;
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-group label {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.filter-group select {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: white;
    color: #374151;
    font-size: 0.875rem;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.filter-group select:focus {
    border-color: #1f2937;
    outline: none;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

.btn-secondary {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    color: #374151;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
}

.documents-list {
    min-height: 400px;
}

.no-documents {
    text-align: center;
    padding: 64px 32px;
    color: #6b7280;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.no-documents i {
    font-size: 3rem;
    margin-bottom: 16px;
    color: #d1d5db;
}

.no-documents h4 {
    margin: 0 0 8px 0;
    color: #374151;
    font-size: 1.125rem;
}

.no-documents p {
    margin: 0;
    color: #6b7280;
}

.document-groups {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.document-group {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.group-header h4 {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
}

.group-header h4 i {
    color: #6b7280;
}

.group-count {
    background: #e5e7eb;
    color: #6b7280;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
}

.group-documents {
    padding: 24px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 64px;
    color: #6b7280;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.loading-state i {
    font-size: 2rem;
    margin-bottom: 16px;
    color: #1f2937;
}

@media (max-width: 768px) {
    .documents-tab {
        padding: 16px;
    }
    
    .documents-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .header-actions {
        align-items: flex-start;
        width: 100%;
    }
    
    .document-stats {
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .group-documents {
        padding: 20px;
        grid-template-columns: 1fr;
        gap: 16px;
    }
}
</style>