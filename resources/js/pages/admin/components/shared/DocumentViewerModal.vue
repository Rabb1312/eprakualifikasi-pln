<template>
    <div class="modal-overlay" @click.self="$emit('close')">
        <div class="modal viewer-modal">
            <div class="modal-header">
                <div class="header-info">
                    <h2>{{ document.document_name }}</h2>
                    <div class="document-meta">
                        <span class="document-type">{{ document.document_type }}</span>
                        <span v-if="document.file_size" class="file-size">
                            {{ formatFileSize(document.file_size) }}
                        </span>
                    </div>
                </div>
                <div class="header-actions">
                    <button @click="downloadDocument" class="btn-download" title="Download">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                    <button @click="openInNewTab" class="btn-external" title="Buka di Tab Baru">
                        <i class="fas fa-external-link-alt"></i>
                        Buka di Tab Baru
                    </button>
                    <button @click="$emit('close')" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body">
                <div class="viewer-container">
                    <!-- Loading State -->
                    <div v-if="loading" class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Memuat dokumen...</p>
                    </div>

                    <!-- PDF Viewer -->
                    <div v-else-if="isPDF()" class="pdf-viewer">
                        <div class="pdf-toolbar">
                            <div class="toolbar-left">
                                <button @click="zoomOut" :disabled="currentZoom <= 50" class="zoom-btn">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <span class="zoom-level">{{ currentZoom }}%</span>
                                <button @click="zoomIn" :disabled="currentZoom >= 200" class="zoom-btn">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button @click="resetZoom" class="zoom-btn">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                    Fit
                                </button>
                            </div>
                            <div class="toolbar-right">
                                <button @click="toggleFullscreen" class="fullscreen-btn">
                                    <i :class="isFullscreen ? 'fas fa-compress' : 'fas fa-expand'"></i>
                                    {{ isFullscreen ? 'Exit Fullscreen' : 'Fullscreen' }}
                                </button>
                            </div>
                        </div>
                        <div class="pdf-container" ref="pdfContainer">
                            <iframe 
                                ref="pdfFrame"
                                :src="getPDFUrl()"
                                frameborder="0"
                                :style="{ transform: `scale(${currentZoom / 100})` }"
                                @load="loading = false"
                            ></iframe>
                        </div>
                    </div>

                    <!-- Image Viewer -->
                    <div v-else-if="isImage()" class="image-viewer">
                        <div class="image-toolbar">
                            <div class="toolbar-left">
                                <button @click="zoomOut" :disabled="currentZoom <= 25" class="zoom-btn">
                                    <i class="fas fa-search-minus"></i>
                                </button>
                                <span class="zoom-level">{{ currentZoom }}%</span>
                                <button @click="zoomIn" :disabled="currentZoom >= 300" class="zoom-btn">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                                <button @click="resetZoom" class="zoom-btn">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                    Fit
                                </button>
                            </div>
                            <div class="toolbar-right">
                                <button @click="rotateImage" class="rotate-btn">
                                    <i class="fas fa-redo"></i>
                                    Rotate
                                </button>
                                <button @click="toggleFullscreen" class="fullscreen-btn">
                                    <i :class="isFullscreen ? 'fas fa-compress' : 'fas fa-expand'"></i>
                                    {{ isFullscreen ? 'Exit Fullscreen' : 'Fullscreen' }}
                                </button>
                            </div>
                        </div>
                        <div class="image-container" ref="imageContainer">
                            <img 
                                ref="imageElement"
                                :src="document.file_path" 
                                :alt="document.document_name"
                                :style="{ 
                                    transform: `scale(${currentZoom / 100}) rotate(${currentRotation}deg)`,
                                    maxWidth: 'none',
                                    maxHeight: 'none'
                                }"
                                @load="loading = false"
                                @error="handleImageError"
                            />
                        </div>
                    </div>

                    <!-- Office Document Viewer -->
                    <div v-else-if="isOfficeDocument()" class="office-viewer">
                        <div class="office-info">
                            <div class="file-icon">
                                <i :class="getDocumentIcon()"></i>
                            </div>
                            <div class="file-details">
                                <h3>{{ document.document_name }}</h3>
                                <p>{{ document.file_type }}</p>
                                <p>Dokumen Office - {{ formatFileSize(document.file_size) }}</p>
                            </div>
                        </div>
                        <div class="office-actions">
                            <button @click="downloadDocument" class="btn-primary">
                                <i class="fas fa-download"></i>
                                Download untuk Melihat
                            </button>
                            <button @click="openInNewTab" class="btn-secondary">
                                <i class="fas fa-external-link-alt"></i>
                                Buka di Tab Baru
                            </button>
                        </div>
                        <div class="office-note">
                            <i class="fas fa-info-circle"></i>
                            <p>Dokumen Office tidak dapat ditampilkan di browser. Silakan download untuk melihat isi dokumen.</p>
                        </div>
                    </div>

                    <!-- Other File Types -->
                    <div v-else class="file-viewer">
                        <div class="file-info">
                            <div class="file-icon">
                                <i :class="getDocumentIcon()"></i>
                            </div>
                            <div class="file-details">
                                <h3>{{ document.document_name }}</h3>
                                <p>{{ document.file_type || 'Unknown File Type' }}</p>
                                <p v-if="document.file_size">{{ formatFileSize(document.file_size) }}</p>
                                <p v-if="document.uploaded_at">
                                    Uploaded: {{ formatDate(document.uploaded_at) }}
                                </p>
                            </div>
                        </div>
                        <div class="file-actions">
                            <button @click="downloadDocument" class="btn-primary">
                                <i class="fas fa-download"></i>
                                Download File
                            </button>
                            <button @click="openInNewTab" class="btn-secondary">
                                <i class="fas fa-external-link-alt"></i>
                                Buka di Tab Baru
                            </button>
                        </div>
                        <div class="file-note">
                            <i class="fas fa-info-circle"></i>
                            <p>Preview tidak tersedia untuk tipe file ini. Silakan download untuk melihat isi file.</p>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div v-if="error" class="error-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Gagal Memuat Dokumen</h3>
                        <p>{{ error }}</p>
                        <button @click="retryLoad" class="btn-retry">
                            <i class="fas fa-redo"></i>
                            Coba Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    document: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['close'])

const loading = ref(true)
const error = ref(null)
const currentZoom = ref(100)
const currentRotation = ref(0)
const isFullscreen = ref(false)

const pdfContainer = ref(null)
const pdfFrame = ref(null)
const imageContainer = ref(null)
const imageElement = ref(null)

onMounted(() => {
    loading.value = true
    // Listen for fullscreen changes
    document.addEventListener('fullscreenchange', handleFullscreenChange)
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange)
    document.addEventListener('mozfullscreenchange', handleFullscreenChange)
    document.addEventListener('MSFullscreenChange', handleFullscreenChange)
})

onUnmounted(() => {
    document.removeEventListener('fullscreenchange', handleFullscreenChange)
    document.removeEventListener('webkitfullscreenchange', handleFullscreenChange)
    document.removeEventListener('mozfullscreenchange', handleFullscreenChange)
    document.removeEventListener('MSFullscreenChange', handleFullscreenChange)
})

function isPDF() {
    return props.document.file_type?.toLowerCase().includes('pdf')
}

function isImage() {
    const type = props.document.file_type?.toLowerCase() || ''
    return type.includes('image') || type.includes('jpg') || type.includes('png') || 
           type.includes('jpeg') || type.includes('gif') || type.includes('webp')
}

function isOfficeDocument() {
    const type = props.document.file_type?.toLowerCase() || ''
    return type.includes('word') || type.includes('excel') || type.includes('powerpoint') ||
           type.includes('docx') || type.includes('xlsx') || type.includes('pptx') ||
           type.includes('doc') || type.includes('xls') || type.includes('ppt')
}

function getDocumentIcon() {
    const type = props.document.file_type?.toLowerCase() || ''
    if (type.includes('pdf')) return 'fas fa-file-pdf'
    if (type.includes('image')) return 'fas fa-file-image'
    if (type.includes('word')) return 'fas fa-file-word'
    if (type.includes('excel')) return 'fas fa-file-excel'
    if (type.includes('powerpoint')) return 'fas fa-file-powerpoint'
    return 'fas fa-file-alt'
}

function getPDFUrl() {
    return `${props.document.file_path}#zoom=${currentZoom.value}&view=FitH`
}

function zoomIn() {
    if (isPDF()) {
        currentZoom.value = Math.min(currentZoom.value + 25, 200)
    } else {
        currentZoom.value = Math.min(currentZoom.value + 25, 300)
    }
}

function zoomOut() {
    if (isPDF()) {
        currentZoom.value = Math.max(currentZoom.value - 25, 50)
    } else {
        currentZoom.value = Math.max(currentZoom.value - 25, 25)
    }
}

function resetZoom() {
    currentZoom.value = 100
}

function rotateImage() {
    currentRotation.value = (currentRotation.value + 90) % 360
}

function toggleFullscreen() {
    const element = pdfContainer.value || imageContainer.value
    
    if (!document.fullscreenElement) {
        element.requestFullscreen?.() || 
        element.webkitRequestFullscreen?.() || 
        element.mozRequestFullScreen?.() || 
        element.msRequestFullscreen?.()
    } else {
        document.exitFullscreen?.() || 
        document.webkitExitFullscreen?.() || 
        document.mozCancelFullScreen?.() || 
        document.msExitFullscreen?.()
    }
}

function handleFullscreenChange() {
    isFullscreen.value = !!document.fullscreenElement
}

function downloadDocument() {
    if (props.document.file_path) {
        const link = document.createElement('a')
        link.href = props.document.file_path
        link.download = props.document.file_name || 'document'
        link.click()
    }
}

function openInNewTab() {
    if (props.document.file_path) {
        window.open(props.document.file_path, '_blank')
    }
}

function handleImageError() {
    error.value = 'Gagal memuat gambar. File mungkin rusak atau tidak dapat diakses.'
    loading.value = false
}

function retryLoad() {
    error.value = null
    loading.value = true
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
        month: 'long',
        year: 'numeric'
    })
}
</script>

<style scoped>
.viewer-modal {
    max-width: 95vw;
    width: 1200px;
    max-height: 95vh;
    display: flex;
    flex-direction: column;
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9fafb;
}

.header-info h2 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.3;
}

.document-meta {
    display: flex;
    gap: 16px;
    font-size: 0.875rem;
    color: #6b7280;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-download,
.btn-external {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-download {
    background: #059669;
    color: white;
}

.btn-external {
    background: #2563eb;
    color: white;
}

.btn-download:hover {
    background: #047857;
}

.btn-external:hover {
    background: #1d4ed8;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #6b7280;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f3f4f6;
    color: #374151;
}

.modal-body {
    flex: 1;
    padding: 0;
    background: #f3f4f6;
    min-height: 0;
}

.viewer-container {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.loading-state,
.error-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 500px;
    color: #6b7280;
}

.loading-state i {
    font-size: 2rem;
    margin-bottom: 16px;
    color: #1f2937;
}

.error-state i {
    font-size: 3rem;
    margin-bottom: 16px;
    color: #dc2626;
}

.error-state h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
}

.btn-retry {
    margin-top: 16px;
    padding: 12px 20px;
    background: #1f2937;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-retry:hover {
    background: #374151;
}

.pdf-viewer,
.image-viewer {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.pdf-toolbar,
.image-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    background: white;
    border-bottom: 1px solid #e5e7eb;
}

.toolbar-left,
.toolbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.zoom-btn,
.rotate-btn,
.fullscreen-btn {
    padding: 8px 12px;
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    color: #374151;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.zoom-btn:hover:not(:disabled),
.rotate-btn:hover,
.fullscreen-btn:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
}

.zoom-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.zoom-level {
    padding: 8px 12px;
    background: #1f2937;
    color: white;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.875rem;
    min-width: 60px;
    text-align: center;
}

.pdf-container,
.image-container {
    flex: 1;
    overflow: auto;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.pdf-container iframe {
    width: 100%;
    height: 100%;
    border: none;
    transform-origin: center center;
    transition: transform 0.3s ease;
}

.image-container img {
    display: block;
    transform-origin: center center;
    transition: transform 0.3s ease;
    cursor: grab;
}

.image-container img:active {
    cursor: grabbing;
}

.office-viewer,
.file-viewer {
    height: 500px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    text-align: center;
}

.office-info,
.file-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 32px;
}

.file-icon {
    width: 80px;
    height: 80px;
    background: #f3f4f6;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #6b7280;
    margin-bottom: 20px;
}

.file-details h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-weight: 600;
    max-width: 400px;
    word-break: break-word;
}

.file-details p {
    margin: 4px 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.office-actions,
.file-actions {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
}

.btn-primary {
    background: #1f2937;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    background: #374151;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.office-note,
.file-note {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 20px;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 8px;
    color: #0c4a6e;
    font-size: 0.875rem;
    max-width: 500px;
}

.office-note p,
.file-note p {
    margin: 0;
    line-height: 1.5;
}

/* Fullscreen styles */
.pdf-container:fullscreen,
.image-container:fullscreen {
    background: black;
}

.pdf-container:fullscreen iframe {
    width: 100vw;
    height: 100vh;
}

@media (max-width: 768px) {
    .viewer-modal {
        max-width: 100vw;
        width: 100vw;
        height: 100vh;
        max-height: 100vh;
        border-radius: 0;
    }
    
    .modal-header {
        padding: 16px;
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .pdf-toolbar,
    .image-toolbar {
        padding: 8px 12px;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .toolbar-left,
    .toolbar-right {
        gap: 4px;
    }
    
    .zoom-btn,
    .rotate-btn,
    .fullscreen-btn {
        padding: 6px 8px;
        font-size: 0.75rem;
    }
    
    .office-actions,
    .file-actions {
        flex-direction: column;
        width: 100%;
        max-width: 300px;
    }
}
</style>