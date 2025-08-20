<!-- File: resources/js/pages/vendor/components/VendorStatus.vue -->
<template>
    <div class="vendor-status">
        <div class="status-header">
            <h2>
                <i class="fas fa-check-circle"></i>
                Status Verifikasi Vendor
            </h2>
            <p>Pantau progres verifikasi dan persetujuan vendor Anda</p>
        </div>

        <!-- Current Status Card -->
        <div class="current-status-card" :class="statusClass">
            <div class="status-icon">
                <i :class="statusIcon"></i>
            </div>
            <div class="status-info">
                <h3>{{ statusTitle }}</h3>
                <p>{{ statusDescription }}</p>
                <div class="status-timestamp">
                    <i class="fas fa-clock"></i>
                    <span>{{ lastUpdated }}</span>
                </div>
            </div>
            <div class="status-badge">
                <span :class="['badge', statusClass]">{{ statusText }}</span>
            </div>
        </div>

        <!-- Progress Timeline -->
        <div class="verification-timeline">
            <h3>
                <i class="fas fa-route"></i>
                Timeline Verifikasi
            </h3>

            <div class="timeline">
                <div
                    v-for="(step, index) in timelineSteps"
                    :key="step.id"
                    :class="['timeline-item', step.status]"
                >
                    <div class="timeline-marker">
                        <i :class="step.icon"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h4>{{ step.title }}</h4>
                            <span class="timeline-date">{{ step.date }}</span>
                        </div>
                        <p>{{ step.description }}</p>
                        <div v-if="step.notes" class="timeline-notes">
                            <i class="fas fa-sticky-note"></i>
                            <span>{{ step.notes }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Requirements -->
        <div v-if="actionRequired" class="action-requirements">
            <h3>
                <i class="fas fa-exclamation-triangle"></i>
                Tindakan Diperlukan
            </h3>

            <div class="requirements-list">
                <div
                    v-for="requirement in requirements"
                    :key="requirement.id"
                    class="requirement-item"
                >
                    <div class="requirement-icon">
                        <i :class="requirement.icon"></i>
                    </div>
                    <div class="requirement-content">
                        <h4>{{ requirement.title }}</h4>
                        <p>{{ requirement.description }}</p>
                        <div
                            v-if="requirement.deadline"
                            class="requirement-deadline"
                        >
                            <i class="fas fa-calendar-alt"></i>
                            <span>Deadline: {{ requirement.deadline }}</span>
                        </div>
                    </div>
                    <div class="requirement-action">
                        <button
                            @click="handleRequirement(requirement)"
                            :class="['action-btn', requirement.type]"
                        >
                            {{ requirement.actionText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Details -->
        <div class="verification-details">
            <h3>
                <i class="fas fa-info-circle"></i>
                Detail Verifikasi
            </h3>

            <div class="details-grid">
                <div class="detail-card">
                    <div class="detail-header">
                        <i class="fas fa-user-check"></i>
                        <h4>Profil Perusahaan</h4>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <span class="label">Kelengkapan Data:</span>
                            <span class="value"
                                >{{ vendor.completion_percentage }}%</span
                            >
                            <div class="mini-progress">
                                <div
                                    class="mini-progress-fill"
                                    :style="{
                                        width:
                                            vendor.completion_percentage + '%',
                                    }"
                                ></div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <span class="label">Status Profil:</span>
                            <span
                                :class="[
                                    'value',
                                    'status',
                                    vendor.profile_completed
                                        ? 'complete'
                                        : 'incomplete',
                                ]"
                            >
                                {{
                                    vendor.profile_completed
                                        ? "Lengkap"
                                        : "Belum Lengkap"
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-header">
                        <i class="fas fa-file-alt"></i>
                        <h4>Dokumen</h4>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <span class="label">Dokumen Terupload:</span>
                            <span class="value"
                                >{{ documentStats.uploaded }}/{{
                                    documentStats.total
                                }}</span
                            >
                        </div>
                        <div class="detail-item">
                            <span class="label">Status Dokumen:</span>
                            <span
                                :class="[
                                    'value',
                                    'status',
                                    documentStats.status,
                                ]"
                            >
                                {{
                                    getDocumentStatusText(documentStats.status)
                                }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="detail-header">
                        <i class="fas fa-shield-alt"></i>
                        <h4>Keamanan</h4>
                    </div>
                    <div class="detail-content">
                        <div class="detail-item">
                            <span class="label">Email Verified:</span>
                            <span
                                :class="[
                                    'value',
                                    'status',
                                    user.email_verified_at
                                        ? 'verified'
                                        : 'unverified',
                                ]"
                            >
                                {{
                                    user.email_verified_at
                                        ? "Terverifikasi"
                                        : "Belum Verified"
                                }}
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Last Login:</span>
                            <span class="value">{{
                                formatDate(user.last_login)
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="contact-info">
            <h3>
                <i class="fas fa-headset"></i>
                Butuh Bantuan?
            </h3>

            <div class="contact-grid">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-content">
                        <h4>Telepon</h4>
                        <p>+62 21 1234 5678</p>
                        <small>Senin - Jumat, 08:00 - 17:00 WIB</small>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-content">
                        <h4>Email</h4>
                        <p>support@pln.co.id</p>
                        <small>Response dalam 1x24 jam</small>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="contact-content">
                        <h4>Live Chat</h4>
                        <p>Chat langsung dengan tim support</p>
                        <button class="chat-btn">
                            <i class="fas fa-comment-dots"></i>
                            Mulai Chat
                        </button>
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
    user: Object,
});

const documentStats = ref({
    uploaded: 0,
    total: 0,
    status: "incomplete",
});

const verificationData = ref({
    status: "pending",
    verified_at: null,
    verified_by: null,
    notes: null,
    last_updated: null,
});

const statusClass = computed(() => {
    const classes = {
        pending: "status-pending",
        under_review: "status-review",
        approved: "status-approved",
        rejected: "status-rejected",
        incomplete: "status-incomplete",
    };
    return classes[verificationData.value.status] || "status-pending";
});

const statusIcon = computed(() => {
    const icons = {
        pending: "fas fa-clock",
        under_review: "fas fa-search",
        approved: "fas fa-check-circle",
        rejected: "fas fa-times-circle",
        incomplete: "fas fa-exclamation-triangle",
    };
    return icons[verificationData.value.status] || "fas fa-clock";
});

const statusTitle = computed(() => {
    const titles = {
        pending: "Menunggu Review",
        under_review: "Sedang Direview",
        approved: "Vendor Disetujui",
        rejected: "Vendor Ditolak",
        incomplete: "Data Belum Lengkap",
    };
    return titles[verificationData.value.status] || "Status Tidak Diketahui";
});

const statusDescription = computed(() => {
    const descriptions = {
        pending:
            "Aplikasi vendor Anda sedang menunggu untuk direview oleh tim PLN.",
        under_review:
            "Tim PLN sedang melakukan review terhadap data dan dokumen yang Anda kirimkan.",
        approved:
            "Selamat! Vendor Anda telah disetujui dan dapat berpartisipasi dalam tender.",
        rejected:
            "Mohon maaf, aplikasi vendor Anda ditolak. Silakan perbaiki sesuai catatan.",
        incomplete:
            "Lengkapi data dan dokumen yang diperlukan untuk melanjutkan proses verifikasi.",
    };
    return (
        descriptions[verificationData.value.status] || "Status tidak diketahui."
    );
});

const statusText = computed(() => {
    const texts = {
        pending: "Menunggu",
        under_review: "Review",
        approved: "Disetujui",
        rejected: "Ditolak",
        incomplete: "Belum Lengkap",
    };
    return texts[verificationData.value.status] || "Unknown";
});

const lastUpdated = computed(() => {
    if (verificationData.value.last_updated) {
        return formatDate(verificationData.value.last_updated);
    }
    return "Belum ada update";
});

const timelineSteps = computed(() => {
    const baseSteps = [
        {
            id: "registration",
            title: "Registrasi",
            description: "Akun vendor berhasil dibuat",
            icon: "fas fa-user-plus",
            status: "completed",
            date: formatDate(props.user.created_at),
        },
        {
            id: "email_verification",
            title: "Verifikasi Email",
            description: "Email berhasil diverifikasi",
            icon: "fas fa-envelope-check",
            status: props.user.email_verified_at ? "completed" : "pending",
            date: props.user.email_verified_at
                ? formatDate(props.user.email_verified_at)
                : null,
        },
        {
            id: "profile_completion",
            title: "Lengkapi Profil",
            description: "Melengkapi data perusahaan",
            icon: "fas fa-building",
            status:
                props.vendor.completion_percentage >= 80
                    ? "completed"
                    : "in-progress",
            date: null,
        },
        {
            id: "document_upload",
            title: "Upload Dokumen",
            description: "Upload dokumen persyaratan",
            icon: "fas fa-upload",
            status:
                documentStats.value.status === "complete"
                    ? "completed"
                    : "pending",
            date: null,
        },
        {
            id: "verification",
            title: "Verifikasi",
            description: "Review oleh tim PLN",
            icon: "fas fa-search",
            status:
                verificationData.value.status === "approved"
                    ? "completed"
                    : verificationData.value.status === "under_review"
                    ? "in-progress"
                    : "pending",
            date: verificationData.value.verified_at
                ? formatDate(verificationData.value.verified_at)
                : null,
            notes: verificationData.value.notes,
        },
    ];

    return baseSteps;
});

const actionRequired = computed(() => {
    return requirements.value.length > 0;
});

const requirements = computed(() => {
    const reqs = [];

    if (!props.user.email_verified_at) {
        reqs.push({
            id: "email_verification",
            title: "Verifikasi Email",
            description:
                "Email Anda belum diverifikasi. Silakan cek kotak masuk.",
            icon: "fas fa-envelope",
            type: "warning",
            actionText: "Kirim Ulang",
        });
    }

    if (props.vendor.completion_percentage < 80) {
        reqs.push({
            id: "complete_profile",
            title: "Lengkapi Profil",
            description:
                "Profil perusahaan masih kurang lengkap. Minimal 80% harus terisi.",
            icon: "fas fa-user-edit",
            type: "primary",
            actionText: "Lengkapi Sekarang",
        });
    }

    if (documentStats.value.status !== "complete") {
        reqs.push({
            id: "upload_documents",
            title: "Upload Dokumen",
            description: "Beberapa dokumen wajib belum diupload.",
            icon: "fas fa-file-upload",
            type: "primary",
            actionText: "Upload Dokumen",
        });
    }

    return reqs;
});

onMounted(() => {
    loadVerificationStatus();
    loadDocumentStats();
});

function formatDate(dateString) {
    if (!dateString) return "Belum tersedia";

    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function getDocumentStatusText(status) {
    const texts = {
        complete: "Lengkap",
        incomplete: "Belum Lengkap",
        pending: "Menunggu Review",
    };
    return texts[status] || "Unknown";
}

function handleRequirement(requirement) {
    switch (requirement.id) {
        case "email_verification":
            // Trigger resend verification
            break;
        case "complete_profile":
            // Navigate to profile tab
            break;
        case "upload_documents":
            // Navigate to documents tab
            break;
    }
}

async function loadVerificationStatus() {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/api/vendor/verification-status", {
            headers: { Authorization: `Bearer ${token}` },
        });

        if (response.data.success) {
            verificationData.value = response.data.data.verification;
        }
    } catch (error) {
        console.error("Load verification status error:", error);
    }
}

async function loadDocumentStats() {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/api/vendor/documents/stats", {
            headers: { Authorization: `Bearer ${token}` },
        });

        if (response.data.success) {
            documentStats.value = response.data.data.stats;
        }
    } catch (error) {
        console.error("Load document stats error:", error);
    }
}
</script>

<style scoped>
.vendor-status {
    max-width: 1200px;
    margin: 0 auto;
}

.status-header {
    text-align: center;
    margin-bottom: 40px;
}

.status-header h2 {
    color: #1e3c72;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.status-header p {
    color: #666;
    font-size: 1.1em;
}

.current-status-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 25px;
    align-items: center;
    border-left: 5px solid #dee2e6;
}

.status-pending {
    border-left-color: #ffc107;
}
.status-review {
    border-left-color: #007bff;
}
.status-approved {
    border-left-color: #28a745;
}
.status-rejected {
    border-left-color: #dc3545;
}
.status-incomplete {
    border-left-color: #007bff;
}

.status-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5em;
    color: white;
}

.status-pending .status-icon {
    background: #ffc107;
}
.status-review .status-icon {
    background: #007bff;
}
.status-approved .status-icon {
    background: #28a745;
}
.status-rejected .status-icon {
    background: #dc3545;
}
.status-incomplete .status-icon {
    background: #007bff;
}

.status-info h3 {
    margin: 0 0 10px 0;
    color: #1e3c72;
    font-size: 1.5em;
}

.status-info p {
    margin: 0 0 15px 0;
    color: #666;
    line-height: 1.6;
}

.status-timestamp {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #999;
    font-size: 0.9em;
}

.status-badge .badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8em;
    letter-spacing: 0.5px;
}

.status-pending .badge {
    background: #fff3cd;
    color: #856404;
}
.status-review .badge {
    background: #cce7ff;
    color: #004085;
}
.status-approved .badge {
    background: #d4edda;
    color: #155724;
}
.status-rejected .badge {
    background: #f8d7da;
    color: #721c24;
}
.status-incomplete .badge {
    background: #ffe8d1;
    color: #8b4513;
}

.verification-timeline {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.verification-timeline h3 {
    color: #1e3c72;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.timeline {
    position: relative;
}

.timeline::before {
    content: "";
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-left: 60px;
    margin-bottom: 30px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2em;
    z-index: 1;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
}

.timeline-item.in-progress .timeline-marker {
    background: #007bff;
}

.timeline-item.pending .timeline-marker {
    background: #6c757d;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.timeline-header h4 {
    margin: 0;
    color: #1e3c72;
}

.timeline-date {
    color: #666;
    font-size: 0.9em;
}

.timeline-content p {
    margin: 0 0 10px 0;
    color: #666;
}

.timeline-notes {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #dc3545;
    font-size: 0.9em;
    font-style: italic;
}

.action-requirements {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-left: 5px solid #007bff;
}

.action-requirements h3 {
    color: #007bff;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.requirements-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.requirement-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 20px;
    align-items: center;
    padding: 20px;
    border: 2px solid #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s;
}

.requirement-item:hover {
    border-color: #007bff;
    background: #fff8f0;
}

.requirement-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3em;
}

.requirement-content h4 {
    margin: 0 0 8px 0;
    color: #1e3c72;
}

.requirement-content p {
    margin: 0 0 10px 0;
    color: #666;
}

.requirement-deadline {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #dc3545;
    font-size: 0.9em;
}

.action-btn {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.action-btn.primary {
    background: #1e3c72;
    color: white;
}

.action-btn.warning {
    background: #ffc107;
    color: #212529;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.verification-details {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.verification-details h3 {
    color: #1e3c72;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.detail-card {
    border: 2px solid #f8f9fa;
    border-radius: 12px;
    overflow: hidden;
}

.detail-header {
    background: #f8f9fa;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-header i {
    color: #1e3c72;
    font-size: 1.3em;
}

.detail-header h4 {
    margin: 0;
    color: #1e3c72;
}

.detail-content {
    padding: 20px;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-item .label {
    color: #666;
    font-weight: 500;
}

.detail-item .value {
    font-weight: 600;
    color: #1e3c72;
}

.detail-item .value.status.complete,
.detail-item .value.status.verified {
    color: #28a745;
}

.detail-item .value.status.incomplete,
.detail-item .value.status.unverified {
    color: #dc3545;
}

.mini-progress {
    width: 100px;
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
    margin-left: 10px;
}

.mini-progress-fill {
    height: 100%;
    background: #28a745;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.contact-info {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.contact-info h3 {
    color: #1e3c72;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
}

.contact-item {
    display: flex;
    gap: 15px;
    padding: 20px;
    border: 2px solid #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s;
}

.contact-item:hover {
    border-color: #1e3c72;
    background: #f8f9fa;
}

.contact-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #1e3c72;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3em;
    flex-shrink: 0;
}

.contact-content h4 {
    margin: 0 0 8px 0;
    color: #1e3c72;
}

.contact-content p {
    margin: 0 0 5px 0;
    color: #333;
    font-weight: 600;
}

.contact-content small {
    color: #666;
}

.chat-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    font-size: 0.9em;
    cursor: pointer;
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
}

.chat-btn:hover {
    background: #218838;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .current-status-card {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 20px;
    }

    .details-grid,
    .contact-grid {
        grid-template-columns: 1fr;
    }

    .requirement-item {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 15px;
    }

    .timeline-item {
        padding-left: 50px;
    }

    .timeline::before {
        left: 15px;
    }

    .timeline-marker {
        width: 30px;
        height: 30px;
        font-size: 1em;
    }
}
</style>
