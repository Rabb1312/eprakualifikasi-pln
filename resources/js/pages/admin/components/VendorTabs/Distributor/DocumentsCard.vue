<template>
    <div class="documents-card">
        <div class="section-header">
            <div class="header-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="header-info">
                <h3>Attachments/Documents</h3>
                <p>Lampiran dan dokumen yang diperlukan untuk distributor</p>
            </div>
        </div>
        <div v-if="hasDocuments" class="documents-content">
            <div class="documents-grid">
                <div v-for="doc in documents" :key="doc.id || doc.document_type" class="document-item">
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
                </div>
            </div>
        </div>
        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-file-alt"></i>
                <h4>Belum Ada Dokumen</h4>
                <p>Dokumen dan lampiran belum diupload untuk distributor ini</p>
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

const documents = computed(() => {
    if (!props.data.documents) return []
    return Array.isArray(props.data.documents) ? props.data.documents : []
})
const hasDocuments = computed(() => documents.value.length > 0)
function getDocumentIcon(doc) {
    const type = (doc.document_type || '').toLowerCase()
    const icons = {
        'npwp': 'fas fa-id-card',
        'siup': 'fas fa-file-signature',
        'akta': 'fas fa-file-contract',
        'ijin usaha': 'fas fa-certificate',
        'other': 'fas fa-file'
    }
    for (const [key, icon] of Object.entries(icons)) {
        if (type.includes(key)) return icon
    }
    return 'fas fa-file-alt'
}
function getStatusClass(status) {
    if (!status) return 'waiting'
    return status.toLowerCase()
}
function getStatusIcon(status) {
    if (!status) return 'fas fa-clock'
    switch (status.toLowerCase()) {
        case 'approved': return 'fas fa-check-circle'
        case 'waiting': return 'fas fa-clock'
        case 'rejected': return 'fas fa-times-circle'
        default: return 'fas fa-clock'
    }
}
function getStatusText(status) {
    if (!status) return 'Waiting'
    switch (status.toLowerCase()) {
        case 'approved': return 'Approved'
        case 'waiting': return 'Waiting'
        case 'rejected': return 'Rejected'
        default: return 'Waiting'
    }
}
</script>


<style scoped>
.documents-card {
    padding: 24px;
    min-height: 400px;
}
.section-header { display: flex; align-items: center; gap: 16px; margin-bottom: 32px; padding-bottom: 20px; border-bottom: 2px solid #f3f4f6; }
.header-icon { width: 56px; height: 56px; background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3); }
.header-info h3 { margin: 0 0 4px 0; color: #1f2937; font-size: 1.5rem; font-weight: 700; }
.header-info p { margin: 0; color: #6b7280; font-size: 0.875rem; }
.content-sections { display: flex; flex-direction: column; gap: 32px; }
.capabilities-section, .standards-section, .team-section { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
.capabilities-section h4, .standards-section h4, .team-section h4 { margin: 0; padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; color: #374151; font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.capabilities-section h4 i { color: #059669; }
.standards-section h4 i { color: #d97706; }
.team-section h4 i { color: #dc2626; }
/* ... lanjutkan style sesuai kebutuhan ... */
.no-data { display: flex; align-items: center; justify-content: center; height: 400px; text-align: center; }
.no-data-illustration { max-width: 400px; }
.no-data-illustration i { font-size: 4rem; color: #d1d5db; margin-bottom: 20px; }
.no-data-illustration h4 { margin: 0 0 12px 0; color: #6b7280; font-size: 1.25rem; font-weight: 600; }
.no-data-illustration p { margin: 0; color: #9ca3af; line-height: 1.5; }
</style>