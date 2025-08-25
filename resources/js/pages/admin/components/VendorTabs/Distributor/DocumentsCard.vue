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

        <!-- Gunakan konten yang sama seperti DocumentsCard subcontractor dengan penyesuaian untuk distributor -->
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

                    <!-- Sisa konten sama seperti DocumentsCard subcontractor -->
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
// Script sama seperti DocumentsCard subcontractor
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

// Sisa logic sama...
</script>

<style scoped>
/* Style sama seperti DocumentsCard subcontractor dengan perubahan warna header */
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
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

/* Sisa styling sama... */
</style>