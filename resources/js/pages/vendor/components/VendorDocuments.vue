<!-- File: resources/js/pages/vendor/components/VendorDocuments.vue -->
<template>
    <div class="vendor-documents">
        <div class="documents-header">
            <h2>
                <i class="fas fa-file-alt"></i>
                Dokumen Vendor
            </h2>
            <p>
                Upload dan kelola dokumen persyaratan vendor sesuai tipe
                perusahaan Anda
            </p>
        </div>

        <div
            v-if="alertMsg"
            :class="['alert', alertTypeClass]"
            v-html="alertMsg"
        />

        <!-- Document Requirements based on Vendor Type -->
        <div class="document-requirements">
            <div class="requirement-header">
                <h3>
                    <i
                        :class="vendorTypeConfig.icon"
                        :style="{ color: vendorTypeConfig.color }"
                    ></i>
                    Dokumen untuk {{ vendorTypeConfig.name }}
                </h3>
                <div class="progress-indicator">
                    <span class="uploaded-count">{{ uploadedCount }}</span>
                    /
                    <span class="total-count">{{
                        requiredDocuments.length
                    }}</span>
                    dokumen terupload
                </div>
            </div>

            <div class="documents-grid">
                <div
                    v-for="doc in requiredDocuments"
                    :key="doc.id"
                    class="document-card"
                >
                    <div class="document-header">
                        <div class="doc-info">
                            <i :class="getDocumentIcon(doc.type)"></i>
                            <div>
                                <h4>{{ doc.name }}</h4>
                                <p>{{ doc.description }}</p>
                                <span v-if="doc.required" class="required-badge"
                                    >Wajib</span
                                >
                                <span v-else class="optional-badge"
                                    >Opsional</span
                                >
                            </div>
                        </div>
                        <div class="doc-status">
                            <i
                                v-if="doc.uploaded"
                                class="fas fa-check-circle text-success"
                            ></i>
                            <i v-else class="fas fa-clock text-warning"></i>
                        </div>
                    </div>

                    <div class="document-body">
                        <!-- Upload Area -->
                        <div v-if="!doc.uploaded" class="upload-area">
                            <input
                                :id="`file-${doc.id}`"
                                type="file"
                                :accept="doc.accept"
                                @change="handleFileUpload($event, doc)"
                                style="display: none"
                            />
                            <label :for="`file-${doc.id}`" class="upload-btn">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Upload {{ doc.name }}
                            </label>
                            <small class="upload-info">
                                {{ doc.accept }} • Max {{ doc.maxSize }}MB
                            </small>
                        </div>

                        <!-- Uploaded File Info -->
                        <div v-else class="uploaded-file">
                            <div class="file-info">
                                <i class="fas fa-file-pdf"></i>
                                <div>
                                    <p class="filename">{{ doc.filename }}</p>
                                    <small class="file-size"
                                        >{{ doc.fileSize }} •
                                        {{ doc.uploadDate }}</small
                                    >
                                </div>
                            </div>
                            <div class="file-actions">
                                <button
                                    @click="downloadFile(doc)"
                                    class="action-btn download"
                                >
                                    <i class="fas fa-download"></i>
                                </button>
                                <button
                                    @click="viewFile(doc)"
                                    class="action-btn view"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button
                                    @click="deleteFile(doc)"
                                    class="action-btn delete"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Progress -->
        <div v-if="uploading" class="upload-progress">
            <div class="progress-header">
                <h4>Uploading {{ currentUpload.name }}...</h4>
                <span>{{ uploadProgress }}%</span>
            </div>
            <div class="progress-bar">
                <div
                    class="progress-fill"
                    :style="{ width: uploadProgress + '%' }"
                ></div>
            </div>
        </div>

        <!-- Document History -->
        <div class="document-history">
            <h3>
                <i class="fas fa-history"></i>
                Riwayat Dokumen
            </h3>

            <div v-if="documentHistory.length === 0" class="empty-history">
                <i class="fas fa-inbox"></i>
                <p>Belum ada riwayat upload dokumen</p>
            </div>

            <div v-else class="history-list">
                <div
                    v-for="history in documentHistory"
                    :key="history.id"
                    class="history-item"
                >
                    <div class="history-icon">
                        <i :class="getHistoryIcon(history.action)"></i>
                    </div>
                    <div class="history-content">
                        <p>
                            <strong>{{ history.action }}</strong> -
                            {{ history.document_name }}
                        </p>
                        <small
                            >{{ history.created_at }} oleh
                            {{ history.user_name }}</small
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
    vendor: Object,
    vendorType: String,
});

const alertMsg = ref("");
const alertType = ref("success");
const uploading = ref(false);
const uploadProgress = ref(0);
const currentUpload = ref({});
const documentHistory = ref([]);

const alertTypeClass = computed(() => {
    return {
        error: "alert-error",
        success: "alert-success",
        info: "alert-info",
    }[alertType.value];
});

const vendorTypeConfig = computed(() => {
    const configs = {
        SC: {
            name: "Subcontractor",
            icon: "fas fa-hard-hat",
            color: "#007bff",
        },
        DS: { name: "Distributor", icon: "fas fa-truck", color: "#28a745" },
        MF: {
            name: "Manufacturer/Fabrication",
            icon: "fas fa-industry",
            color: "#6f42c1",
        },
        FW: {
            name: "Forwarder",
            icon: "fas fa-shipping-fast",
            color: "#20c997",
        },
    };
    return configs[props.vendorType] || configs["SC"];
});

const baseDocuments = [
    {
        id: "siup",
        name: "SIUP",
        description: "Surat Izin Usaha Perdagangan",
        type: "legal",
        required: true,
        accept: ".pdf,",
        maxSize: 5,
        uploaded: false,
    },
    {
        id: "npwp",
        name: "NPWP",
        description: "Nomor Pokok Wajib Pajak Perusahaan",
        type: "legal",
        required: true,
        accept: ".pdf,",
        maxSize: 5,
        uploaded: false,
    },
    {
        id: "akta_pendirian",
        name: "Akta Pendirian",
        description: "Akta Pendirian Perusahaan",
        type: "legal",
        required: true,
        accept: ".pdf",
        maxSize: 10,
        uploaded: false,
    },
    {
        id: "sk_kemenkumham",
        name: "SK Kemenkumham",
        description: "Surat Keputusan Kementerian Hukum dan HAM",
        type: "legal",
        required: true,
        accept: ".pdf",
        maxSize: 10,
        uploaded: false,
    },
    {
        id: "tdp",
        name: "TDP",
        description: "Tanda Daftar Perusahaan",
        type: "business",
        required: true,
        accept: ".pdf,",
        maxSize: 5,
        uploaded: false,
    },
];

// Vendor-specific documents
const vendorSpecificDocs = {
    SC: [
        {
            id: "sik_konstruksi",
            name: "SIK Konstruksi",
            description: "Surat Izin Kerja Konstruksi",
            type: "license",
            required: true,
            accept: ".pdf",
            maxSize: 5,
            uploaded: false,
        },
        {
            id: "iso_9001",
            name: "ISO 9001",
            description: "Sertifikat ISO 9001 Quality Management",
            type: "certificate",
            required: false,
            accept: ".pdf",
            maxSize: 5,
            uploaded: false,
        },
    ],
    DS: [
        {
            id: "surat_distributorship",
            name: "Surat Distributorship",
            description: "Surat Penunjukan sebagai Distributor",
            type: "business",
            required: true,
            accept: ".pdf",
            maxSize: 5,
            uploaded: false,
        },
        {
            id: "catalog_produk",
            name: "Katalog Produk",
            description: "Katalog Produk yang Didistribusikan",
            type: "product",
            required: false,
            accept: ".pdf",
            maxSize: 20,
            uploaded: false,
        },
    ],
    MF: [
        {
            id: "iso_14001",
            name: "ISO 14001",
            description: "Sertifikat ISO 14001 Environmental Management",
            type: "certificate",
            required: false,
            accept: ".pdf",
            maxSize: 5,
            uploaded: false,
        },
        {
            id: "sertifikat_produk",
            name: "Sertifikat Produk",
            description: "Sertifikat Produk Manufaktur",
            type: "certificate",
            required: true,
            accept: ".pdf",
            maxSize: 10,
            uploaded: false,
        },
    ],
    FW: [
        {
            id: "siup_freight",
            name: "SIUP Freight Forwarding",
            description: "Surat Izin Usaha Freight Forwarding",
            type: "license",
            required: true,
            accept: ".pdf",
            maxSize: 5,
            uploaded: false,
        },
        {
            id: "member_forwarder",
            name: "Member Forwarder Association",
            description: "Keanggotaan Asosiasi Forwarder",
            type: "membership",
            required: false,
            accept: ".pdf",
            maxSize: 5,
            uploaded: false,
        },
    ],
};

const requiredDocuments = computed(() => {
    const specific = vendorSpecificDocs[props.vendorType] || [];
    return [...baseDocuments, ...specific];
});

const uploadedCount = computed(() => {
    return requiredDocuments.value.filter((doc) => doc.uploaded).length;
});

onMounted(() => {
    loadDocumentStatus();
    loadDocumentHistory();
});

function getDocumentIcon(type) {
    const icons = {
        legal: "fas fa-gavel",
        business: "fas fa-briefcase",
        license: "fas fa-certificate",
        certificate: "fas fa-award",
        product: "fas fa-box",
        membership: "fas fa-users",
    };
    return icons[type] || "fas fa-file";
}

function getHistoryIcon(action) {
    const icons = {
        Upload: "fas fa-upload text-success",
        Update: "fas fa-edit text-info",
        Delete: "fas fa-trash text-danger",
        Download: "fas fa-download text-primary",
    };
    return icons[action] || "fas fa-file";
}

function showAlert(type, msg) {
    alertType.value = type;
    alertMsg.value = msg;
    setTimeout(() => (alertMsg.value = ""), 5000);
}

async function handleFileUpload(event, document) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file size
    if (file.size > document.maxSize * 1024 * 1024) {
        showAlert(
            "error",
            `File terlalu besar. Maksimal ${document.maxSize}MB`
        );
        return;
    }

    // Validate file type
    const allowedTypes = document.accept.split(",").map((type) => type.trim());
    const fileExtension = "." + file.name.split(".").pop().toLowerCase();
    if (!allowedTypes.includes(fileExtension)) {
        showAlert(
            "error",
            `Tipe file tidak diizinkan. Gunakan: ${document.accept}`
        );
        return;
    }

    uploading.value = true;
    currentUpload.value = document;
    uploadProgress.value = 0;

    try {
        const formData = new FormData();
        formData.append("file", file);
        formData.append("document_type", document.id);
        formData.append("vendor_id", props.vendor.id);

        const token = localStorage.getItem("token");
        const response = await axios.post(
            "/api/vendor/documents/upload",
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "multipart/form-data",
                },
                onUploadProgress: (progressEvent) => {
                    uploadProgress.value = Math.round(
                        (progressEvent.loaded * 100) / progressEvent.total
                    );
                },
            }
        );

        if (response.data.success) {
            document.uploaded = true;
            document.filename = file.name;
            document.fileSize = formatFileSize(file.size);
            document.uploadDate = new Date().toLocaleDateString("id-ID");

            showAlert("success", `${document.name} berhasil diupload!`);
            await loadDocumentHistory();
        }
    } catch (error) {
        console.error("Upload error:", error);
        showAlert(
            "error",
            error.response?.data?.message || "Gagal upload dokumen"
        );
    } finally {
        uploading.value = false;
        uploadProgress.value = 0;
        currentUpload.value = {};
        event.target.value = ""; // Reset input
    }
}

async function downloadFile(document) {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get(
            `/api/vendor/documents/${document.id}/download`,
            {
                headers: { Authorization: `Bearer ${token}` },
                responseType: "blob",
            }
        );

        // Create download link
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", document.filename);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);

        showAlert("success", `${document.name} berhasil didownload`);
    } catch (error) {
        showAlert("error", "Gagal download dokumen");
    }
}

function viewFile(document) {
    // Open file in new tab
    const token = localStorage.getItem("token");
    const url = `${axios.defaults.baseURL}/api/vendor/documents/${document.id}/view?token=${token}`;
    window.open(url, "_blank");
}

async function deleteFile(document) {
    if (!confirm(`Hapus dokumen ${document.name}?`)) return;

    try {
        const token = localStorage.getItem("token");
        await axios.delete(`/api/vendor/documents/${document.id}`, {
            headers: { Authorization: `Bearer ${token}` },
        });

        document.uploaded = false;
        document.filename = "";
        document.fileSize = "";
        document.uploadDate = "";

        showAlert("success", `${document.name} berhasil dihapus`);
        await loadDocumentHistory();
    } catch (error) {
        showAlert("error", "Gagal hapus dokumen");
    }
}

async function loadDocumentStatus() {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/api/vendor/documents", {
            headers: { Authorization: `Bearer ${token}` },
        });

        if (response.data.success) {
            const uploadedDocs = response.data.data.documents;

            // Update document status
            requiredDocuments.value.forEach((doc) => {
                const uploaded = uploadedDocs.find(
                    (up) => up.document_type === doc.id
                );
                if (uploaded) {
                    doc.uploaded = true;
                    doc.filename = uploaded.filename;
                    doc.fileSize = uploaded.file_size;
                    doc.uploadDate = new Date(
                        uploaded.created_at
                    ).toLocaleDateString("id-ID");
                }
            });
        }
    } catch (error) {
        console.error("Load documents error:", error);
    }
}

async function loadDocumentHistory() {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/api/vendor/documents/history", {
            headers: { Authorization: `Bearer ${token}` },
        });

        if (response.data.success) {
            documentHistory.value = response.data.data.history;
        }
    } catch (error) {
        console.error("Load history error:", error);
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}
</script>

<style scoped>
.vendor-documents {
    max-width: 1200px;
    margin: 0 auto;
}

.documents-header {
    text-align: center;
    margin-bottom: 40px;
}

.documents-header h2 {
    color: #1e3c72;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.documents-header p {
    color: #666;
    font-size: 1.1em;
}

.document-requirements {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.requirement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.requirement-header h3 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    color: #1e3c72;
}

.progress-indicator {
    font-size: 1.1em;
    font-weight: 600;
}

.uploaded-count {
    color: #28a745;
}

.total-count {
    color: #666;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.document-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
}

.document-card:hover {
    border-color: #1e3c72;
    box-shadow: 0 5px 15px rgba(30, 60, 114, 0.1);
}

.document-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    background: #f8f9fa;
}

.doc-info {
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.doc-info i {
    font-size: 2em;
    color: #1e3c72;
    margin-top: 5px;
}

.doc-info h4 {
    margin: 0 0 5px 0;
    color: #1e3c72;
}

.doc-info p {
    margin: 0 0 10px 0;
    color: #666;
    font-size: 0.9em;
}

.required-badge {
    background: #dc3545;
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.75em;
    font-weight: 600;
}

.optional-badge {
    background: #28a745;
    color: white;
    padding: 3px 8px;
    border-radius: 4px;
    font-size: 0.75em;
    font-weight: 600;
}

.doc-status i {
    font-size: 1.5em;
}

.text-success {
    color: #28a745;
}
.text-warning {
    color: #ffc107;
}

.document-body {
    padding: 20px;
}

.upload-area {
    text-align: center;
    padding: 30px;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    transition: all 0.3s;
}

.upload-area:hover {
    border-color: #1e3c72;
    background: #f8f9fa;
}

.upload-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #1e3c72;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.upload-btn:hover {
    background: #2a5298;
    transform: translateY(-2px);
}

.upload-info {
    display: block;
    margin-top: 10px;
    color: #666;
    font-size: 0.8em;
}

.uploaded-file {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-info i {
    font-size: 1.5em;
    color: #dc3545;
}

.filename {
    margin: 0;
    font-weight: 600;
    color: #1e3c72;
}

.file-size {
    color: #666;
}

.file-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.action-btn.download {
    background: #28a745;
    color: white;
}

.action-btn.view {
    background: #007bff;
    color: white;
}

.action-btn.delete {
    background: #dc3545;
    color: white;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.upload-progress {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.progress-header h4 {
    margin: 0;
    color: #1e3c72;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #28a745;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.document-history {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.document-history h3 {
    color: #1e3c72;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.empty-history {
    text-align: center;
    padding: 40px;
    color: #666;
}

.empty-history i {
    font-size: 3em;
    margin-bottom: 15px;
    opacity: 0.5;
}

.history-list {
    max-height: 400px;
    overflow-y: auto;
}

.history-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.3s;
}

.history-item:hover {
    background: #f8f9fa;
}

.history-item:last-child {
    border-bottom: none;
}

.history-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.history-content p {
    margin: 0 0 5px 0;
    color: #1e3c72;
}

.history-content small {
    color: #666;
}

.alert {
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 8px;
    font-weight: 500;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-error {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-info {
    background-color: #cce7ff;
    border: 1px solid #b8daff;
    color: #004085;
}

@media (max-width: 768px) {
    .documents-grid {
        grid-template-columns: 1fr;
    }

    .requirement-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .uploaded-file {
        flex-direction: column;
        gap: 15px;
    }

    .file-actions {
        justify-content: center;
    }
}
</style>
