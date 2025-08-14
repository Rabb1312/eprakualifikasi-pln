<!-- resources/js/views/components/subcontractor/SubcontractorDocuments.vue -->
<template>
    <div class="subcontractor-documents">
        <div class="tab-header">
            <h3>
                <i class="fas fa-file-alt"></i>
                Attachment/Documents
            </h3>
            <p>
                Document checklist and submission status for subcontractor
                qualification / Daftar dokumen dan status pengajuan untuk
                kualifikasi subkontraktor.
            </p>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-container">
            <div class="spinner"></div>
            <p>Loading documents...</p>
        </div>

        <div v-else class="documents-section">
            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card total">
                    <div class="card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-content">
                        <h4>{{ stats.total || 0 }}</h4>
                        <p>Total Documents</p>
                    </div>
                </div>

                <div class="summary-card required">
                    <div class="card-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="card-content">
                        <h4>{{ stats.required || 0 }}</h4>
                        <p>Required Documents</p>
                    </div>
                </div>

                <div class="summary-card submitted">
                    <div class="card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <h4>{{ stats.approved || 0 }}</h4>
                        <p>Approved Documents</p>
                    </div>
                </div>

                <div class="summary-card completion">
                    <div class="card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="card-content">
                        <h4>{{ completionPercentage }}%</h4>
                        <p>Completion Rate</p>
                    </div>
                </div>
            </div>

            <!-- Documents Table -->
            <div class="documents-table-section">
                <div class="table-header">
                    <h4>Individual Document Upload & Review</h4>
                    <div class="table-actions">
                        <button
                            @click="refreshDocuments"
                            class="btn btn-outline btn-sm"
                        >
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="documents-table">
                        <thead>
                            <tr>
                                <th class="no-col">No</th>
                                <th class="document-col">Document Name</th>
                                <th class="status-col">Status</th>
                                <th class="expiry-col">Expiry Date</th>
                                <th class="file-col">File</th>
                                <th class="actions-col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(document, index) in documents"
                                :key="document.id"
                                :class="getRowClass(document)"
                            >
                                <td class="text-center">{{ index + 1 }}</td>
                                <td class="document-name">
                                    <div class="document-info">
                                        <span class="document-title">{{
                                            document.document_name
                                        }}</span>
                                        <span
                                            v-if="document.is_required"
                                            class="required-badge"
                                            >Required</span
                                        >
                                        <span
                                            v-if="document.is_expired"
                                            class="expired-badge"
                                            >Expired</span
                                        >
                                        <span
                                            v-if="
                                                document.is_expiring_soon &&
                                                !document.is_expired
                                            "
                                            class="expiring-badge"
                                            >Expiring Soon</span
                                        >
                                    </div>
                                    <div
                                        v-if="document.admin_notes"
                                        class="admin-notes"
                                    >
                                        <i class="fas fa-comment"></i>
                                        <span>{{ document.admin_notes }}</span>
                                    </div>
                                    <div
                                        v-if="document.rejection_reason"
                                        class="rejection-reason"
                                    >
                                        <i
                                            class="fas fa-exclamation-triangle"
                                        ></i>
                                        <span>{{
                                            document.rejection_reason
                                        }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        :class="`status-badge status-${document.status_badge.class}`"
                                    >
                                        <i
                                            :class="document.status_badge.icon"
                                        ></i>
                                        {{ document.status_badge.text }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        v-if="document.expiry_date"
                                        class="expiry-date"
                                    >
                                        {{ formatDate(document.expiry_date) }}
                                    </span>
                                    <span v-else class="no-expiry"
                                        >No Expiry</span
                                    >
                                </td>
                                <td class="text-center">
                                    <div v-if="document.file_name" class="file-info">
        <div class="file-details">
            <span class="file-name">{{ document.file_name }}</span>
            <span class="file-size">{{ document.file_size }}</span>
            <span class="file-version">v{{ document.version }}</span>
            <span v-if="document.expiry_date" class="expiry-date">
                Expiry: {{ formatDate(document.expiry_date) }}
            </span>
        </div>
        <div class="file-actions">
            <a v-if="document.file_url" :href="document.file_url" target="_blank" rel="noopener" class="btn btn-sm btn-outline">
                <i class="fas fa-eye"></i> View
            </a>
            <button @click="downloadDocument(doc)" class="btn btn-sm btn-outline">
                <i class="fas fa-download"></i> Download
            </button>
        </div>
    </div>
    <span v-else class="no-file">No file uploaded</span>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <!-- Upload Button -->
                                        <button
                                            @click="openUploadModal(document)"
                                            class="btn btn-sm btn-primary"
                                            :disabled="
                                                uploadingDocuments[document.id]
                                            "
                                        >
                                            <i class="fas fa-upload"></i>
                                            {{
                                                document.file_name
                                                    ? "Re-upload"
                                                    : "Upload"
                                            }}
                                        </button>

                                        <!-- Download Button -->
                                        <button
                                            v-if="document.file_name"
                                            @click="downloadDocument(document)"
                                            class="btn btn-sm btn-outline"
                                        >
                                            <i class="fas fa-download"></i>
                                            Download
                                        </button>

                                        <!-- Delete Button -->
                                        <button
                                            v-if="
                                                document.file_name &&
                                                document.status !== 'approved'
                                            "
                                            @click="deleteDocument(document)"
                                            class="btn btn-sm btn-danger"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upload Modal -->
            <div
                v-if="showUploadModal"
                class="modal-overlay"
                @click="closeUploadModal"
            >
                <div class="modal-content" @click.stop>
                    <div class="modal-header">
                        <h4>Upload Document</h4>
                        <button @click="closeUploadModal" class="modal-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="document-info-modal">
                            <h5>{{ selectedDocument?.document_name }}</h5>
                            <p
                                v-if="selectedDocument?.is_required"
                                class="required-text"
                            >
                                <i class="fas fa-exclamation-circle"></i>
                                This is a required document
                            </p>
                        </div>

                        <div class="upload-form">
                            <div class="form-group">
                                <label>Select File:</label>
                                <input
                                    ref="modalFileInput"
                                    type="file"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                    @change="handleFileSelection"
                                    class="file-input"
                                />
                                <p class="file-requirements">
                                    Supported formats: PDF, DOC, DOCX, JPG, PNG
                                    (Max 10MB)
                                </p>
                            </div>

                            <div
                                v-if="selectedDocument?.has_expiry_date"
                                class="form-group"
                            >
                                <label>Expiry Date:</label>
                                <input
                                    v-model="uploadForm.expiry_date"
                                    type="date"
                                    :min="getTomorrowDate()"
                                    class="date-input"
                                />
                            </div>

                            <div v-if="selectedFile" class="selected-file">
                                <div class="file-preview">
                                    <i
                                        :class="getFileIcon(selectedFile.type)"
                                    ></i>
                                    <div class="file-details">
                                        <span class="file-name">{{
                                            selectedFile.name
                                        }}</span>
                                        <span class="file-size">{{
                                            formatFileSize(selectedFile.size)
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            @click="closeUploadModal"
                            class="btn btn-outline"
                        >
                            Cancel
                        </button>
                        <button
                            @click="uploadDocumentFile"
                            :disabled="
                                !selectedFile ||
                                uploadingDocuments[selectedDocument?.id]
                            "
                            class="btn btn-primary"
                        >
                            <i class="fas fa-upload"></i>
                            {{
                                uploadingDocuments[selectedDocument?.id]
                                    ? "Uploading..."
                                    : "Upload Document"
                            }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Document Requirements -->
            <div class="requirements-section">
                <h4>Document Requirements & Guidelines</h4>
                <div class="requirements-grid">
                    <div class="requirement-item">
                        <h5>
                            <i class="fas fa-file-pdf"></i> Format Requirements
                        </h5>
                        <ul>
                            <li>PDF format preferred for official documents</li>
                            <li>Images: JPG, PNG (max 5MB per file)</li>
                            <li>
                                Documents: DOC, DOCX, PDF (max 10MB per file)
                            </li>
                            <li>Ensure documents are clear and readable</li>
                        </ul>
                    </div>

                    <div class="requirement-item">
                        <h5>
                            <i class="fas fa-shield-alt"></i> Security &
                            Compliance
                        </h5>
                        <ul>
                            <li>All documents must be valid and current</li>
                            <li>Scanned copies must be clear and complete</li>
                            <li>
                                Official seals and signatures must be visible
                            </li>
                            <li>Documents will be verified for authenticity</li>
                        </ul>
                    </div>

                    <div class="requirement-item">
                        <h5><i class="fas fa-clock"></i> Review Process</h5>
                        <ul>
                            <li>
                                Documents are reviewed individually by admin
                            </li>
                            <li>Approved documents get green checkmark</li>
                            <li>Rejected documents need to be re-uploaded</li>
                            <li>Check admin notes for feedback</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert -->
        <AlertComponent
            v-if="alertMsg"
            :type="alertType"
            :message="alertMsg"
            @close="alertMsg = ''"
        />
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import axios from "axios";
import AlertComponent from "../AlertComponent.vue";

const props = defineProps({
    subcontractorData: Object,
});

const emit = defineEmits(["updated"]);

// ✅ Reactive data
const loading = ref(true);
const alertMsg = ref("");
const alertType = ref("success");
const documents = ref([]);
const stats = ref({});
const showUploadModal = ref(false);
const selectedDocument = ref(null);
const selectedFile = ref(null);
const uploadingDocuments = ref({});
const modalFileInput = ref(null);

const uploadForm = reactive({
    expiry_date: "",
});

// ✅ API Base URL
const API_BASE_URL = "http://eprakualifikasi-pln.test";

// ✅ Computed
const completionPercentage = computed(() => {
    if (!stats.value.total || stats.value.total === 0) return 0;
    return Math.round((stats.value.approved / stats.value.total) * 100);
});

// ✅ Methods
onMounted(async () => {
    console.log("🚀 SubcontractorDocuments mounted");
    await loadDocuments();
});

async function loadDocuments() {
    loading.value = true;

    try {
        const token = localStorage.getItem("token");
        if (!token) {
            showAlert("error", "Authentication required");
            return;
        }

        console.log(
            "📤 Making request to:",
            `${API_BASE_URL}/api/vendor/documents`
        );

        const response = await axios.get(
            `${API_BASE_URL}/api/vendor/documents`,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: "application/json",
                },
            }
        );

        console.log("📥 Documents response:", response.data);

        if (response.data.success) {
            documents.value = response.data.data.documents || [];
            stats.value = response.data.data.stats || {};
            console.log("✅ Documents loaded:", documents.value.length);
        } else {
            showAlert(
                "error",
                response.data.message || "Failed to load documents"
            );
        }
    } catch (error) {
        console.error("❌ Load documents error:", error);
        if (error.response?.status === 403) {
            showAlert(
                "error",
                "Access forbidden. Please check your permissions."
            );
        } else if (error.response?.status === 404) {
            showAlert(
                "error",
                "API endpoint not found. Please check server configuration."
            );
        } else {
            showAlert(
                "error",
                error.response?.data?.message || "Failed to load documents"
            );
        }
    } finally {
        loading.value = false;
    }
}

async function refreshDocuments() {
    await loadDocuments();
    showAlert("info", "Documents refreshed");
}

function openUploadModal(document) {
    selectedDocument.value = document;
    selectedFile.value = null;
    uploadForm.expiry_date = document.expiry_date || "";
    showUploadModal.value = true;

    // Reset file input
    if (modalFileInput.value) {
        modalFileInput.value.value = "";
    }
}

function closeUploadModal() {
    showUploadModal.value = false;
    selectedDocument.value = null;
    selectedFile.value = null;
    uploadForm.expiry_date = "";
}

function handleFileSelection(event) {
    const file = event.target.files[0];
    if (file && validateFile(file)) {
        selectedFile.value = file;
    }
}

function validateFile(file) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = [
        "application/pdf",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "image/jpeg",
        "image/jpg",
        "image/png",
    ];

    if (file.size > maxSize) {
        showAlert(
            "error",
            `File ${file.name} is too large. Maximum size is 10MB.`
        );
        return false;
    }

    if (!allowedTypes.includes(file.type)) {
        showAlert("error", `File ${file.name} has unsupported format.`);
        return false;
    }

    return true;
}

async function uploadDocumentFile() {
    if (!selectedFile.value || !selectedDocument.value) return;

    const documentId = selectedDocument.value.id;
    uploadingDocuments.value[documentId] = true;

    try {
        const formData = new FormData();
        formData.append('document_id', documentId);
        formData.append('file', selectedFile.value);

        // Jika dokumen punya expiry date, tambahkan ke formData
        if (selectedDocument.value.has_expiry_date && uploadForm.expiry_date) {
            formData.append('expiry_date', uploadForm.expiry_date);
        }

        const token = localStorage.getItem('token');
        const response = await axios.post(`${API_BASE_URL}/api/vendor/documents/upload`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
                'Content-Type': 'multipart/form-data',
            },
        });

        if (response.data.success) {
            showAlert('success', 'Document uploaded successfully and submitted for review!');
            closeUploadModal();
            await loadDocuments(); // ✅ Memuat ulang daftar dokumen untuk merefresh status
        } else {
            showAlert('error', response.data.message || 'Failed to upload document');
        }
    } catch (error) {
        console.error('Upload error:', error);
        showAlert('error', error.response?.data?.message || 'Failed to upload document');
    } finally {
        uploadingDocuments.value[documentId] = false;
    }
}

async function downloadDocument(doc) {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get(
            `${API_BASE_URL}/api/vendor/documents/${doc.id}/download`,
            {
                headers: { Authorization: `Bearer ${token}` },
                responseType: 'blob',
            }
        );

        // ✅ Ini akan menggunakan global window.document
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', doc.file_name || 'document');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);

        showAlert('success', 'Document downloaded successfully');
    } catch (error) {
        console.error('Download error:', error);
        showAlert('error', 'Failed to download document');
    }
}

async function deleteDocument(document) {
    if (!confirm(`Are you sure you want to delete ${document.document_name}?`))
        return;

    try {
        const token = localStorage.getItem("token");
        const response = await axios.delete(
            `${API_BASE_URL}/api/vendor/documents/${document.id}`,
            {
                headers: { Authorization: `Bearer ${token}` },
            }
        );

        if (response.data.success) {
            showAlert("success", "Document deleted successfully");
            await loadDocuments(); // Refresh documents list
        } else {
            showAlert(
                "error",
                response.data.message || "Failed to delete document"
            );
        }
    } catch (error) {
        console.error("Delete error:", error);
        showAlert(
            "error",
            error.response?.data?.message || "Failed to delete document"
        );
    }
}

// ✅ Helper functions
function getRowClass(document) {
    return {
        "required-row": document.is_required,
        "approved-row": document.status === "approved",
        "rejected-row": document.status === "rejected",
        "expired-row": document.is_expired,
        "expiring-row": document.is_expiring_soon && !document.is_expired,
    };
}

function formatDate(dateString) {
    if (!dateString) return "-";
    return new Date(dateString).toLocaleDateString("id-ID");
}

function getTomorrowDate() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    return tomorrow.toISOString().split("T")[0];
}

function getFileIcon(type) {
    if (type.includes("pdf")) return "fas fa-file-pdf text-danger";
    if (type.includes("word") || type.includes("document"))
        return "fas fa-file-word text-primary";
    if (type.includes("image")) return "fas fa-file-image text-success";
    return "fas fa-file text-secondary";
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}

function showAlert(type, message) {
    alertType.value = type;
    alertMsg.value = message;
    setTimeout(() => {
        alertMsg.value = "";
    }, 5000);
}
</script>

<style scoped>
/* Keep all existing styles exactly as they are */
.subcontractor-documents {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem;
}

.tab-header {
    margin-bottom: 2rem;
    text-align: center;
}

.tab-header h3 {
    color: #212529;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1.5rem;
}

.tab-header p {
    color: #6c757d;
    margin: 0;
    font-style: italic;
    line-height: 1.5;
}

.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    gap: 1rem;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #fd7e14;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.documents-section {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s;
}

.summary-card:hover {
    transform: translateY(-2px);
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.summary-card.total .card-icon {
    background: #e9ecef;
    color: #495057;
}

.summary-card.required .card-icon {
    background: #fff3cd;
    color: #856404;
}

.summary-card.submitted .card-icon {
    background: #d4edda;
    color: #155724;
}

.summary-card.completion .card-icon {
    background: #d1ecf1;
    color: #0c5460;
}

.card-content h4 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    color: #212529;
}

.card-content p {
    margin: 0;
    color: #6c757d;
    font-size: 0.875rem;
}

.documents-table-section {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    background: #f8f9fa;
}

.table-header h4 {
    margin: 0;
    color: #495057;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
}

.table-container {
    overflow-x: auto;
}

.documents-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.documents-table th,
.documents-table td {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.documents-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #495057;
    text-align: left;
}

.no-col {
    width: 60px;
    text-align: center;
}

.document-col {
    min-width: 300px;
}

.status-col {
    width: 150px;
    text-align: center;
}

.expiry-col {
    width: 120px;
    text-align: center;
}

.file-col {
    width: 200px;
}

.actions-col {
    width: 200px;
    text-align: center;
}

.text-center {
    text-align: center;
}

.document-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.document-title {
    color: #495057;
    font-weight: 500;
}

.required-badge {
    background: #dc3545;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.expired-badge {
    background: #6c757d;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.expiring-badge {
    background: #ffc107;
    color: #212529;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-secondary {
    background: #e9ecef;
    color: #6c757d;
}

.status-info {
    background: #d1ecf1;
    color: #0c5460;
}

.status-warning {
    background: #fff3cd;
    color: #856404;
}

.status-success {
    background: #d4edda;
    color: #155724;
}

.status-danger {
    background: #f8d7da;
    color: #721c24;
}

.status-dark {
    background: #d6d8db;
    color: #1b1e21;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
    flex-wrap: wrap;
    justify-content: center;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.btn-primary {
    background: #fd7e14;
    color: white;
    border: 1px solid #fd7e14;
}

.btn-primary:hover:not(:disabled) {
    background: #e56b00;
    border-color: #e56b00;
}

.btn-outline {
    background: white;
    color: #6c757d;
    border: 1px solid #ced4da;
}

.btn-outline:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: 1px solid #dc3545;
}

.btn-danger:hover {
    background: #c82333;
    border-color: #c82333;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.file-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.875rem;
}

.file-name {
    font-weight: 500;
    color: #495057;
}

.file-size {
    color: #6c757d;
    font-size: 0.75rem;
}

.file-version {
    color: #6c757d;
    font-size: 0.75rem;
}

.no-file,
.no-expiry {
    color: #6c757d;
    font-style: italic;
    font-size: 0.875rem;
}

.required-row {
    background: #fff5f5;
}

.approved-row {
    background: #f0fff4;
}

.rejected-row {
    background: #fff5f5;
}

.expired-row {
    background: #f8f9fa;
}

.expiring-row {
    background: #fff8e1;
}

.admin-notes,
.rejection-reason {
    margin-top: 0.5rem;
    padding: 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.admin-notes {
    background: #e7f3ff;
    color: #0056b3;
}

.rejection-reason {
    background: #fff5f5;
    color: #dc3545;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.modal-header h4 {
    margin: 0;
    color: #495057;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #6c757d;
    cursor: pointer;
    padding: 0.25rem;
}

.modal-close:hover {
    color: #495057;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 1.5rem;
    border-top: 1px solid #dee2e6;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.file-input,
.date-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 0.875rem;
}

.file-input:focus,
.date-input:focus {
    outline: none;
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.requirements-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #dee2e6;
}

.requirements-section h4 {
    color: #495057;
    margin-bottom: 1.5rem;
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.requirement-item {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
}

.requirement-item h5 {
    color: #495057;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.requirement-item ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.requirement-item li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
    color: #6c757d;
    font-size: 0.875rem;
    position: relative;
    padding-left: 1.5rem;
}

.requirement-item li:before {
    content: "•";
    color: #fd7e14;
    position: absolute;
    left: 0;
    font-weight: bold;
}

.requirement-item li:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .summary-cards {
        grid-template-columns: 1fr;
    }

    .requirements-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .table-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>
