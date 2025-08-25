<template>
    <div class="admin-layout">
        <!-- Sidebar -->
        <Sidebar :user="currentUser" @toggle="handleSidebarToggle" />
        
        <!-- Main Content -->
        <div :class="['main-content', { 'sidebar-collapsed': sidebarCollapsed }]">
            <!-- Top Header -->
            <div class="top-header">
                <div class="header-left">
                    <button @click="toggleMobileSidebar" class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Kelola Vendor</h1>
                    <p class="header-subtitle">Manajemen data vendor terdaftar</p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <router-link to="/admin/dashboard" class="header-btn-link">
                            <i class="fas fa-tachometer-alt"></i>
                        </router-link>
                        <button @click="loadVendors" class="btn-refresh">
                            <i class="fas fa-sync-alt" :class="{ 'fa-spin': loading }"></i>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Filters Section -->
                <div class="filters-card">
                    <div class="filter-row">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input
                                v-model="filters.search"
                                @input="searchVendors"
                                placeholder="Cari nama perusahaan, nomor vendor, NPWP, atau nama user..."
                            />
                        </div>
                        <div class="filter-group">
                            <label>Tipe Perusahaan</label>
                            <select v-model="filters.tipe_perusahaan" @change="filterVendors">
                                <option value="">Semua Tipe</option>
                                <option value="DS">Distributor (DS)</option>
                                <option value="SC">Subcontractor (SC)</option>
                                <option value="MF">Manufacturer (MF)</option>
                                <option value="FW">Forwarder (FW)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Status Verifikasi</label>
                            <select v-model="filters.status" @change="filterVendors">
                                <option value="">Semua Status</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="unverified">Belum Verifikasi</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Profil</label>
                            <select v-model="filters.profile_completed" @change="filterVendors">
                                <option value="">Semua Profil</option>
                                <option value="completed">Lengkap</option>
                                <option value="incomplete">Belum Lengkap</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Status User</label>
                            <select v-model="filters.user_status" @change="filterVendors">
                                <option value="">Semua User</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="filter-actions">
                            <button @click="resetFilters" class="btn-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ pagination.total || 0 }}</div>
                                <div class="stat-label">Total Vendor</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon verified">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    {{ vendors.filter((v) => v.verified_at).length }}
                                </div>
                                <div class="stat-label">Terverifikasi</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon unverified">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    {{ vendors.filter((v) => !v.verified_at).length }}
                                </div>
                                <div class="stat-label">Belum Verifikasi</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon completed">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    {{ vendors.filter((v) => v.profile_completed).length }}
                                </div>
                                <div class="stat-label">Profil Lengkap</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendors Table -->
                <div class="table-card">
                    <div class="table-header">
                        <h2>Daftar Vendor</h2>
                        <div class="table-actions">
                            <button @click="loadVendors" class="btn-refresh">
                                <i
                                    class="fas fa-sync-alt"
                                    :class="{ 'fa-spin': loading }"
                                ></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-wrapper" v-if="!loading">
                        <table class="vendors-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Vendor</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Tipe</th>
                                    <th>User</th>
                                    <th>Status User</th>
                                    <th>Profil</th>
                                    <th>Verifikasi</th>
                                    <!-- <th>Terdaftar</th> -->
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(vendor, index) in vendors" :key="vendor.id">
                                    <td>{{ getRowNumber(index) }}</td>
                                    <td>
                                        <div class="vendor-number">
                                            <strong>{{ vendor.nomor_vendor }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="company-cell">
                                            <div class="company-name">{{ vendor.nama_perusahaan }}</div>
                                            <div class="company-npwp" v-if="vendor.npwp">
                                                NPWP: {{ vendor.npwp }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span :class="['type-badge', vendor.tipe_perusahaan.toLowerCase()]">
                                            {{ getTypeName(vendor.tipe_perusahaan) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-cell">
                                            <div class="user-avatar-small">
                                                {{ vendor.user?.username?.charAt(0)?.toUpperCase() || 'V' }}
                                            </div>
                                            <div class="user-info">
                                                <div class="username">{{ vendor.user?.username }}</div>
                                                <div class="user-email">{{ vendor.user?.email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span :class="['status-badge', vendor.user?.status]">
                                            {{ vendor.user?.status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="profile-status">
                                            <div class="progress-bar">
                                                <div 
                                                    class="progress-fill"
                                                    :style="{ width: vendor.completion_percentage + '%' }"
                                                ></div>
                                            </div>
                                            <span class="progress-text">{{ vendor.completion_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span v-if="vendor.verified_at" class="verification-badge verified">
                                            <i class="fas fa-check-circle"></i>
                                            Terverifikasi
                                        </span>
                                        <span v-else class="verification-badge unverified">
                                            <i class="fas fa-clock"></i>
                                            Pending
                                        </span>
                                    </td>
                                    <!-- <td>{{ formatDate(vendor.created_at) }}</td> -->
                                    <td>
                                        <div class="action-buttons">
                                            <button
                                                @click="viewVendor(vendor)"
                                                class="btn-action view"
                                                title="Lihat Detail"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                v-if="!vendor.verified_at"
                                                @click="verifyVendor(vendor)"
                                                class="btn-action verify"
                                                title="Verifikasi"
                                            >
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button
                                                v-if="vendor.verified_at"
                                                @click="rejectVendor(vendor)"
                                                class="btn-action reject"
                                                title="Batalkan Verifikasi"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="vendors.length === 0">
                                    <td colspan="10" class="no-data">
                                        <i class="fas fa-building"></i>
                                        <p>Tidak ada data vendor</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Loading State -->
                    <div v-if="loading" class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Memuat data vendor...</p>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination" v-if="pagination.last_page > 1">
                        <button
                            @click="changePage(pagination.current_page - 1)"
                            :disabled="pagination.current_page === 1"
                            class="page-btn"
                        >
                            <i class="fas fa-chevron-left"></i>
                        </button>

                        <span class="page-info">
                            Halaman {{ pagination.current_page }} dari
                            {{ pagination.last_page }} ({{ pagination.total }} total)
                        </span>

                        <button
                            @click="changePage(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page"
                            class="page-btn"
                        >
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div 
            v-if="showMobileSidebar" 
            class="sidebar-overlay"
            @click="closeMobileSidebar"
        ></div>

        <!-- Vendor Detail Modal -->
        <VendorDetailModal
        v-if="showDetailModal"
        :vendor="selectedVendor"
        @close="showDetailModal = false"
        @verify="verifyVendor"
        @reject="rejectVendor"
    />

        <!-- Reject Modal -->
        <div
            v-if="showRejectModal"
            class="modal-overlay"
            @click.self="showRejectModal = false"
        >
            <div class="modal">
                <div class="modal-header">
                    <h2>Batalkan Verifikasi</h2>
                    <button @click="showRejectModal = false" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form @submit.prevent="confirmReject" class="modal-body">
                    <div class="form-group">
                        <label>Alasan pembatalan verifikasi <span class="required">*</span></label>
                        <textarea
                            v-model="rejectReason"
                            required
                            placeholder="Masukkan alasan pembatalan verifikasi..."
                            rows="4"
                        ></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="showRejectModal = false" class="btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn-danger" :disabled="formLoading">
                            <span v-if="!formLoading">
                                <i class="fas fa-times"></i> Batalkan Verifikasi
                            </span>
                            <span v-else>
                                <i class="fas fa-spinner fa-spin"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notification -->
        <div v-if="toast.show" :class="['toast', toast.type]">
            <i :class="['fas', getToastIcon(toast.type)]"></i>
            <span>{{ toast.message }}</span>
        </div>
    </div>
</template>

<script setup>
// Script tetap sama seperti sebelumnya
import { ref, onMounted } from "vue";
import axios from "axios";
import Sidebar from './components/Sidebar.vue';
import VendorDetailModal from './components/VendorDetailModal.vue'

// Reactive data
const vendors = ref([]);
const pagination = ref({});
const loading = ref(false);
const formLoading = ref(false);
const sidebarCollapsed = ref(false);
const showMobileSidebar = ref(false);

// Modals
const showDetailModal = ref(false);
const showRejectModal = ref(false);

const selectedVendor = ref(null);
const vendorToReject = ref(null);
const rejectReason = ref('');
const currentUser = ref(null);

// Filters
const filters = ref({
    search: "",
    tipe_perusahaan: "",
    status: "",
    profile_completed: "",
    user_status: ""
});

// Toast notification
const toast = ref({
    show: false,
    type: "success",
    message: "",
});

// Set axios base URL
axios.defaults.baseURL = "http://eprakualifikasi-pln.test";

onMounted(async () => {
    await loadCurrentUser();
    await loadVendors();
});

// Sidebar functions
const handleSidebarToggle = (collapsed) => {
    sidebarCollapsed.value = collapsed;
}

const toggleMobileSidebar = () => {
    showMobileSidebar.value = !showMobileSidebar.value;
}

const closeMobileSidebar = () => {
    showMobileSidebar.value = false;
}

function getRowNumber(index) {
    const currentPage = pagination.value.current_page || 1;
    const perPage = pagination.value.per_page || 10;
    return (currentPage - 1) * perPage + index + 1;
}

async function loadCurrentUser() {
    const userData = localStorage.getItem("user");
    if (userData) {
        currentUser.value = JSON.parse(userData);
    }

    const token = localStorage.getItem("token");
    if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    }
}

async function loadVendors(page = 1) {
    loading.value = true;
    try {
        const params = new URLSearchParams();
        params.append("page", page);

        if (filters.value.search) params.append("search", filters.value.search);
        if (filters.value.tipe_perusahaan) params.append("tipe_perusahaan", filters.value.tipe_perusahaan);
        if (filters.value.status) params.append("status", filters.value.status);
        if (filters.value.profile_completed) params.append("profile_completed", filters.value.profile_completed);
        if (filters.value.user_status) params.append("user_status", filters.value.user_status);

        const response = await axios.get(`/api/admin/vendors?${params}`);

        if (response.data.success) {
            vendors.value = response.data.data.data;
            pagination.value = {
                current_page: response.data.data.current_page,
                last_page: response.data.data.last_page,
                total: response.data.data.total,
                per_page: response.data.data.per_page,
            };
        }
    } catch (error) {
        console.error("Error loading vendors:", error);
        showToast("error", "Gagal memuat data vendor");
    } finally {
        loading.value = false;
    }
}

async function verifyVendor(vendor) {
    try {
        const response = await axios.patch(`/api/admin/vendors/${vendor.id}/verify`);

        if (response.data.success) {
            showToast("success", "Vendor berhasil diverifikasi");
            showDetailModal.value = false;
            await loadVendors();
        }
    } catch (error) {
        showToast(
            "error",
            error.response?.data?.message || "Gagal memverifikasi vendor"
        );
    }
}

function rejectVendor(vendor) {
    vendorToReject.value = vendor;
    rejectReason.value = '';
    showDetailModal.value = false;
    showRejectModal.value = true;
}

async function confirmReject() {
    if (!rejectReason.value.trim()) {
        showToast("error", "Alasan pembatalan harus diisi");
        return;
    }

    formLoading.value = true;
    try {
        const response = await axios.patch(
            `/api/admin/vendors/${vendorToReject.value.id}/reject`,
            { reason: rejectReason.value }
        );

        if (response.data.success) {
            showToast("success", "Verifikasi vendor berhasil dibatalkan");
            showRejectModal.value = false;
            await loadVendors();
        }
    } catch (error) {
        showToast(
            "error",
            error.response?.data?.message || "Gagal membatalkan verifikasi"
        );
    } finally {
        formLoading.value = false;
    }
}

function viewVendor(vendor) {
    selectedVendor.value = vendor
    showDetailModal.value = true
}

function searchVendors() {
    loadVendors(1);
}

function filterVendors() {
    loadVendors(1);
}

function resetFilters() {
    filters.value = {
        search: "",
        tipe_perusahaan: "",
        status: "",
        profile_completed: "",
        user_status: ""
    };
    loadVendors(1);
}

function changePage(page) {
    if (page >= 1 && page <= pagination.value.last_page) {
        loadVendors(page);
    }
}

function getTypeName(type) {
    const types = {
        DS: "Distributor",
        SC: "Subcontractor",
        MF: "Manufacturer",
        FW: "Forwarder",
    };
    return types[type] || type;
}

function formatDate(dateString) {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
}

function showToast(type, message) {
    toast.value = { show: true, type, message };
    setTimeout(() => {
        toast.value.show = false;
    }, 5000);
}

function getToastIcon(type) {
    const icons = {
        success: "fa-check-circle",
        error: "fa-exclamation-circle",
        warning: "fa-exclamation-triangle",
        info: "fa-info-circle",
    };
    return icons[type] || "fa-info-circle";
}
</script>

<style scoped>
/* ===== LAYOUT ===== */
.admin-layout {
    display: flex;
    min-height: 100vh;
    background: #f8fafc;
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main-content {
    flex: 1;
    margin-left: 280px;
    transition: margin-left 0.3s ease;
    display: flex;
    flex-direction: column;
}

.main-content.sidebar-collapsed {
    margin-left: 80px;
}

/* ===== TOP HEADER ===== */
.top-header {
    background: white;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.header-left h1 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
}

.header-subtitle {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

.mobile-menu-btn {
    display: none;
    width: 40px;
    height: 40px;
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.mobile-menu-btn:hover {
    background: #f3f4f6;
    color: #374151;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-btn-link {
    width: 44px;
    height: 44px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    cursor: pointer;
    border-radius: 10px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    text-decoration: none;
}

.header-btn-link:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
    color: #374151;
    text-decoration: none;
}

/* ===== DASHBOARD CONTENT ===== */
.dashboard-content {
    flex: 1;
    padding: 32px;
    overflow-y: auto;
    background: #f8fafc;
}

/* ===== FILTERS ===== */
.filters-card {
    background: white;
    padding: 24px;
    margin-bottom: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.filter-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
    gap: 16px;
    align-items: end;
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    z-index: 1;
}

.search-box input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.search-box input:focus {
    border-color: #1f2937;
    outline: none;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
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
    margin: 0;
}

.filter-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    background: white;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.filter-group select:focus {
    border-color: #1f2937;
    outline: none;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

/* ===== BUTTONS ===== */
.btn-primary {
    background: #1f2937;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
}

.btn-primary:hover {
    background: #374151;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 12px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-refresh {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
}

.btn-refresh:hover {
    background: #e5e7eb;
}

/* ===== STATS CARDS ===== */
.stats-section {
    margin-bottom: 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: #f3f4f6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #374151;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-icon.verified {
    background: #d1fae5;
    color: #065f46;
}

.stat-icon.unverified {
    background: #fef3c7;
    color: #92400e;
}

.stat-icon.completed {
    background: #dbeafe;
    color: #1e40af;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

/* ===== TABLE ===== */
.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    margin-bottom: 24px;
}

.table-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h2 {
    margin: 0;
    color: #1f2937;
    font-size: 1.25rem;
    font-weight: 700;
}

.table-wrapper {
    overflow-x: auto;
}

.vendors-table {
    width: 100%;
    border-collapse: collapse;
}

.vendors-table th {
    background: #f9fafb;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
    font-size: 0.875rem;
}

.vendors-table td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
    font-size: 0.875rem;
}

.vendors-table tr:hover {
    background: #f9fafb;
}

/* ===== VENDOR-SPECIFIC STYLES ===== */
.vendor-number {
    font-weight: 700;
    color: #1f2937;
}

.company-cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.company-name {
    font-weight: 600;
    color: #1f2937;
}

.company-npwp {
    font-size: 0.75rem;
    color: #6b7280;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar-small {
    width: 32px;
    height: 32px;
    background: #1f2937;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.user-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.username {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.user-email {
    font-size: 0.75rem;
    color: #6b7280;
}

.profile-status {
    display: flex;
    align-items: center;
    gap: 8px;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background: #f3f4f6;
    border-radius: 3px;
    overflow: hidden;
    min-width: 60px;
}

.progress-fill {
    height: 100%;
    background: #059669;
    border-radius: 3px;
    transition: width 0.5s ease;
}

.progress-text {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.75rem;
    min-width: 35px;
    text-align: right;
}

/* ===== BADGES ===== */
.type-badge,
.status-badge,
.verification-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.type-badge.ds {
    background: #e0e7ff;
    color: #3730a3;
}

.type-badge.sc {
    background: #fef3c7;
    color: #92400e;
}

.type-badge.mf {
    background: #d1fae5;
    color: #065f46;
}

.type-badge.fw {
    background: #fed7d7;
    color: #991b1b;
}

.status-badge.aktif {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.tidak_aktif {
    background: #fef2f2;
    color: #991b1b;
}

.verification-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.verification-badge.verified {
    background: #d1fae5;
    color: #065f46;
}

.verification-badge.unverified {
    background: #fef3c7;
    color: #92400e;
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 4px;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-action.view {
    background: #dbeafe;
    color: #1e40af;
}

.btn-action.verify {
    background: #d1fae5;
    color: #065f46;
}

.btn-action.reject {
    background: #fef2f2;
    color: #991b1b;
}

.btn-action:hover:not(:disabled) {
    transform: scale(1.1);
}

.btn-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* ===== LOADING & NO DATA ===== */
.loading-state,
.no-data {
    padding: 64px;
    text-align: center;
    color: #6b7280;
}

.loading-state i,
.no-data i {
    font-size: 2rem;
    margin-bottom: 16px;
    color: #d1d5db;
}

.no-data i {
    font-size: 3rem;
    display: block;
}

/* ===== PAGINATION ===== */
.pagination {
    padding: 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 16px;
}

.page-btn {
    background: #1f2937;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.page-btn:hover:not(:disabled) {
    background: #374151;
}

.page-btn:disabled {
    background: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
}

.page-info {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

/* ===== MODALS ===== */
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
    padding: 20px;
}

.modal {
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.detail-modal {
    max-width: 500px;
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: #1f2937;
    font-size: 1.25rem;
    font-weight: 700;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #6b7280;
    cursor: pointer;
    width: 32px;
    height: 32px;
    border-radius: 6px;
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
    padding: 24px;
}

.modal-footer {
    padding: 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* ===== DETAIL MODAL CONTENT ===== */
.vendor-detail {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.detail-section {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f3f4f6;
}

.detail-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.detail-section h3 {
    margin: 0 0 16px 0;
    color: #1f2937;
    font-size: 1.125rem;
    font-weight: 700;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row label {
    font-weight: 600;
    color: #6b7280;
    margin: 0;
    font-size: 0.875rem;
}

/* ===== FORM ELEMENTS ===== */
.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.required {
    color: #ef4444;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #1f2937;
    outline: none;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

/* ===== TOAST ===== */
.toast {
    position: fixed;
    top: 24px;
    right: 24px;
    padding: 16px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    z-index: 1100;
    display: flex;
    align-items: center;
    gap: 8px;
    animation: slideIn 0.3s ease;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    font-size: 0.875rem;
}

.toast.success {
    background: #059669;
}

.toast.error {
    background: #dc2626;
}

.toast.warning {
    background: #d97706;
}

.toast.info {
    background: #0284c7;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* ===== SIDEBAR OVERLAY ===== */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1400px) {
    .filter-row {
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 12px;
    }
    
    .filter-group:nth-child(5) {
        grid-column: 1 / -2;
        margin-top: 16px;
    }
}

@media (max-width: 1200px) {
    .filter-row {
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 16px;
    }

    .search-box {
        grid-column: 1 / -1;
        margin-bottom: 16px;
    }
    
    .filter-group:nth-child(5) {
        grid-column: auto;
        margin-top: 0;
    }
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
    
    .main-content.sidebar-collapsed {
        margin-left: 0;
    }
    
    .mobile-menu-btn {
        display: flex;
    }
    
    .sidebar-overlay {
        display: block;
    }
    
    .dashboard-content {
        padding: 20px;
    }
    
    .filter-row {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .header-left {
        align-items: flex-start;
    }
    
    .header-left h1 {
        font-size: 1.5rem;
    }
    
    .table-wrapper {
        overflow-x: scroll;
    }
    
    .vendors-table {
        min-width: 800px;
    }
}

@media (max-width: 480px) {
    .top-header {
        padding: 0 16px;
    }
    
    .dashboard-content {
        padding: 16px;
    }
    
    .stats-grid {
        gap: 16px;
    }
    
    .stat-card {
        padding: 20px;
    }
}
</style>