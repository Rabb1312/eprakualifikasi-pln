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
                    <h1>Kelola User</h1>
                    <p class="header-subtitle">Manajemen data user dan vendor</p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <router-link to="/admin/dashboard" class="header-btn-link">
                            <i class="fas fa-tachometer-alt"></i>
                        </router-link>
                        <button @click="showCreateModal = true" class="btn-primary">
                            <i class="fas fa-plus"></i> Tambah User
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
                                @input="searchUsers"
                                placeholder="Cari username, email, atau nama lengkap..."
                            />
                        </div>
                        <div class="filter-group">
                            <label>Level</label>
                            <select v-model="filters.level" @change="filterUsers">
                                <option value="">Semua Level</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Tipe</label>
                            <select v-model="filters.type" @change="filterUsers">
                                <option value="">Semua Tipe</option>
                                <option value="DS">Distributor (DS)</option>
                                <option value="SC">Subcontractor (SC)</option>
                                <option value="MF">Manufacturer (MF)</option>
                                <option value="FW">Forwarder (FW)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Status</label>
                            <select v-model="filters.status" @change="filterUsers">
                                <option value="">Semua Status</option>
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
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">{{ pagination.total || 0 }}</div>
                                <div class="stat-label">Total User</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon active">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    {{ users.filter((u) => u.status === "aktif").length }}
                                </div>
                                <div class="stat-label">User Aktif</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon inactive">
                                <i class="fas fa-user-times"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    {{
                                        users.filter((u) => u.status === "tidak_aktif")
                                            .length
                                    }}
                                </div>
                                <div class="stat-label">User Tidak Aktif</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon admin">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    {{ users.filter((u) => u.level === "admin").length }}
                                </div>
                                <div class="stat-label">Administrator</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-card">
                    <div class="table-header">
                        <h2>Daftar User</h2>
                        <div class="table-actions">
                            <button @click="loadUsers" class="btn-refresh">
                                <i
                                    class="fas fa-sync-alt"
                                    :class="{ 'fa-spin': loading }"
                                ></i>
                            </button>
                        </div>
                    </div>

                    <div class="table-wrapper" v-if="!loading">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Terdaftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users" :key="user.id">
                                    <td>{{ getRowNumber(index) }}</td>
                                    <td>
                                        <div class="username-cell">
                                            <div class="user-avatar-small">
                                                {{ user.username?.charAt(0)?.toUpperCase() || 'U' }}
                                            </div>
                                            {{ user.username }}
                                        </div>
                                    </td>
                                    <td>{{ user.fullname || "-" }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>
                                        <span :class="['level-badge', user.level]">
                                            {{ user.level }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            v-if="user.type"
                                            :class="[
                                                'type-badge',
                                                user.type.toLowerCase(),
                                            ]"
                                        >
                                            {{ getTypeName(user.type) }}
                                        </span>
                                        <span v-else class="type-badge admin">Admin</span>
                                    </td>
                                    <td>
                                        <button
                                            @click="toggleUserStatus(user)"
                                            :class="['status-toggle', user.status]"
                                            :disabled="user.id === currentUser?.id"
                                        >
                                            <i
                                                :class="[
                                                    'fas',
                                                    user.status === 'aktif'
                                                        ? 'fa-check-circle'
                                                        : 'fa-times-circle',
                                                ]"
                                            ></i>
                                            {{ user.status }}
                                        </button>
                                    </td>
                                    <td>{{ formatDate(user.created_at) }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button
                                                @click="viewUser(user)"
                                                class="btn-action view"
                                                title="Lihat Detail"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                @click="editUser(user)"
                                                class="btn-action edit"
                                                title="Edit User"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                @click="deleteUser(user)"
                                                class="btn-action delete"
                                                title="Hapus User"
                                                :disabled="user.id === currentUser?.id"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="users.length === 0">
                                    <td colspan="9" class="no-data">
                                        <i class="fas fa-users"></i>
                                        <p>Tidak ada data user</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Loading State -->
                    <div v-if="loading" class="loading-state">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Memuat data user...</p>
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

        <!-- Create/Edit Modal -->
        <div
            v-if="showCreateModal || showEditModal"
            class="modal-overlay"
            @click.self="closeModal"
        >
            <div class="modal">
                <div class="modal-header">
                    <h2>
                        {{ showCreateModal ? "Tambah User Baru" : "Edit User" }}
                    </h2>
                    <button @click="closeModal" class="modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form
                    @submit.prevent="
                        showCreateModal ? createUser() : updateUser()
                    "
                    class="modal-body"
                >
                    <div v-if="formErrors.length" class="alert alert-error">
                        <ul>
                            <li v-for="error in formErrors" :key="error">
                                {{ error }}
                            </li>
                        </ul>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label
                                >Username <span class="required">*</span></label
                            >
                            <input
                                v-model="userForm.username"
                                type="text"
                                required
                                placeholder="Username"
                            />
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input
                                v-model="userForm.email"
                                type="email"
                                required
                                placeholder="Email"
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Nama Lengkap
                            <span
                                v-if="userForm.level === 'user'"
                                class="required"
                                >*</span
                            >
                        </label>
                        <input
                            v-model="userForm.fullname"
                            type="text"
                            :required="userForm.level === 'user'"
                            placeholder="Nama Lengkap (opsional untuk admin)"
                        />
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label
                                >Password
                                <span v-if="showCreateModal" class="required"
                                    >*</span
                                ></label
                            >
                            <input
                                v-model="userForm.password"
                                type="password"
                                :required="showCreateModal"
                                :placeholder="
                                    showCreateModal
                                        ? 'Password'
                                        : 'Kosongkan jika tidak ingin mengubah'
                                "
                            />
                        </div>
                        <div class="form-group">
                            <label>Level <span class="required">*</span></label>
                            <select
                                v-model="userForm.level"
                                required
                                @change="onLevelChange"
                            >
                                <option value="">Pilih Level</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="userForm.level === 'user'" class="form-row">
                        <div class="form-group">
                            <label
                                >Tipe Vendor
                                <span class="required">*</span></label
                            >
                            <select
                                v-model="userForm.type"
                                :required="userForm.level === 'user'"
                            >
                                <option value="">Pilih Tipe</option>
                                <option value="DS">Distributor (DS)</option>
                                <option value="SC">Subcontractor (SC)</option>
                                <option value="MF">Manufacturer (MF)</option>
                                <option value="FW">Forwarder (FW)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label
                                >Status <span class="required">*</span></label
                            >
                            <select v-model="userForm.status" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="userForm.level !== 'user'" class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select v-model="userForm.status" required>
                            <option value="">Pilih Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            @click="closeModal"
                            class="btn-secondary"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="btn-primary"
                            :disabled="formLoading"
                        >
                            <span v-if="!formLoading">
                                <i
                                    :class="[
                                        'fas',
                                        showCreateModal ? 'fa-plus' : 'fa-save',
                                    ]"
                                ></i>
                                {{
                                    showCreateModal
                                        ? "Tambah User"
                                        : "Update User"
                                }}
                            </span>
                            <span v-else>
                                <i class="fas fa-spinner fa-spin"></i>
                                {{
                                    showCreateModal
                                        ? "Menambah..."
                                        : "Mengupdate..."
                                }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="modal-overlay"
            @click.self="showDeleteModal = false"
        >
            <div class="modal delete-modal">
                <div class="modal-header">
                    <h2>Konfirmasi Hapus</h2>
                </div>
                <div class="modal-body">
                    <div class="delete-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <p>
                        Apakah Anda yakin ingin menghapus user
                        <strong>{{ userToDelete?.username }}</strong
                        >?
                    </p>
                    <p class="warning">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
                <div class="modal-footer">
                    <button
                        @click="showDeleteModal = false"
                        class="btn-secondary"
                    >
                        Batal
                    </button>
                    <button
                        @click="confirmDelete"
                        class="btn-danger"
                        :disabled="formLoading"
                    >
                        <span v-if="!formLoading">
                            <i class="fas fa-trash"></i> Hapus User
                        </span>
                        <span v-else>
                            <i class="fas fa-spinner fa-spin"></i> Menghapus...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- User Detail Modal -->
        <div
            v-if="showDetailModal"
            class="modal-overlay"
            @click.self="showDetailModal = false"
        >
            <div class="modal detail-modal">
                <div class="modal-header">
                    <h2>Detail User</h2>
                    <button
                        @click="showDetailModal = false"
                        class="modal-close"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body" v-if="selectedUser">
                    <div class="user-detail">
                        <div class="detail-row">
                            <label>ID:</label>
                            <span>{{ selectedUser.id }}</span>
                        </div>
                        <div class="detail-row">
                            <label>Username:</label>
                            <span>{{ selectedUser.username }}</span>
                        </div>
                        <div class="detail-row">
                            <label>Nama Lengkap:</label>
                            <span>{{ selectedUser.fullname || "-" }}</span>
                        </div>
                        <div class="detail-row">
                            <label>Email:</label>
                            <span>{{ selectedUser.email }}</span>
                        </div>
                        <div class="detail-row">
                            <label>Level:</label>
                            <span
                                class="level-badge"
                                :class="selectedUser.level"
                                >{{ selectedUser.level }}</span
                            >
                        </div>
                        <div class="detail-row" v-if="selectedUser.type">
                            <label>Tipe:</label>
                            <span
                                class="type-badge"
                                :class="selectedUser.type.toLowerCase()"
                            >
                                {{ getTypeName(selectedUser.type) }}
                            </span>
                        </div>
                        <div class="detail-row">
                            <label>Status:</label>
                            <span
                                class="status-badge"
                                :class="selectedUser.status"
                                >{{ selectedUser.status }}</span
                            >
                        </div>
                        <div class="detail-row">
                            <label>Terdaftar:</label>
                            <span>{{
                                formatDate(selectedUser.created_at)
                            }}</span>
                        </div>
                        <div class="detail-row" v-if="selectedUser.last_login">
                            <label>Login Terakhir:</label>
                            <span>{{
                                formatDate(selectedUser.last_login)
                            }}</span>
                        </div>
                        <div
                            class="detail-row"
                            v-if="selectedUser.email_verified_at"
                        >
                            <label>Email Terverifikasi:</label>
                            <span>{{
                                formatDate(selectedUser.email_verified_at)
                            }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="editUser(selectedUser)" class="btn-primary">
                        <i class="fas fa-edit"></i> Edit User
                    </button>
                    <button
                        @click="showDetailModal = false"
                        class="btn-secondary"
                    >
                        Tutup
                    </button>
                </div>
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
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import Sidebar from './components/Sidebar.vue'; // Import Sidebar

// Reactive data
const users = ref([]);
const pagination = ref({});
const loading = ref(false);
const formLoading = ref(false);
const sidebarCollapsed = ref(false);
const showMobileSidebar = ref(false);

// Modals
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showDetailModal = ref(false);

// Form data
const userForm = ref({
    username: "",
    email: "",
    password: "",
    fullname: "",
    level: "",
    type: "",
    status: "",
});

const formErrors = ref([]);
const selectedUser = ref(null);
const userToDelete = ref(null);
const currentUser = ref(null);

// Filters
const filters = ref({
    search: "",
    level: "",
    type: "",
    status: "",
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
    await loadUsers();
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

async function loadUsers(page = 1) {
    loading.value = true;
    try {
        const params = new URLSearchParams();
        params.append("page", page);

        if (filters.value.search) params.append("search", filters.value.search);
        if (filters.value.level) params.append("level", filters.value.level);
        if (filters.value.type) params.append("type", filters.value.type);
        if (filters.value.status) params.append("status", filters.value.status);

        const response = await axios.get(`/api/users?${params}`);

        if (response.data.success) {
            users.value = response.data.data.data;
            pagination.value = {
                current_page: response.data.data.current_page,
                last_page: response.data.data.last_page,
                total: response.data.data.total,
                per_page: response.data.data.per_page,
            };
        }
    } catch (error) {
        console.error("Error loading users:", error);
        showToast("error", "Gagal memuat data user");
    } finally {
        loading.value = false;
    }
}

async function createUser() {
    formLoading.value = true;
    formErrors.value = [];

    try {
        const response = await axios.post("/api/users", userForm.value);

        if (response.data.success) {
            showToast("success", "User berhasil ditambahkan");
            closeModal();
            await loadUsers();
        }
    } catch (error) {
        if (error.response?.data?.errors) {
            formErrors.value = Object.values(error.response.data.errors).flat();
        } else {
            formErrors.value = [
                error.response?.data?.message || "Gagal menambah user",
            ];
        }
    } finally {
        formLoading.value = false;
    }
}

async function updateUser() {
    formLoading.value = true;
    formErrors.value = [];

    try {
        const response = await axios.put(
            `/api/users/${selectedUser.value.id}`,
            userForm.value
        );

        if (response.data.success) {
            showToast("success", "User berhasil diupdate");
            closeModal();
            await loadUsers();
        }
    } catch (error) {
        if (error.response?.data?.errors) {
            formErrors.value = Object.values(error.response.data.errors).flat();
        } else {
            formErrors.value = [
                error.response?.data?.message || "Gagal mengupdate user",
            ];
        }
    } finally {
        formLoading.value = false;
    }
}

async function confirmDelete() {
    formLoading.value = true;

    try {
        const response = await axios.delete(
            `/api/users/${userToDelete.value.id}`
        );

        if (response.data.success) {
            showToast("success", "User berhasil dihapus");
            showDeleteModal.value = false;
            await loadUsers();
        }
    } catch (error) {
        showToast(
            "error",
            error.response?.data?.message || "Gagal menghapus user"
        );
    } finally {
        formLoading.value = false;
    }
}

async function toggleUserStatus(user) {
    try {
        const response = await axios.patch(
            `/api/users/${user.id}/toggle-status`
        );

        if (response.data.success) {
            user.status = response.data.data.status;
            showToast(
                "success",
                `Status user berhasil diubah menjadi ${user.status}`
            );
        }
    } catch (error) {
        showToast(
            "error",
            error.response?.data?.message || "Gagal mengubah status user"
        );
    }
}

function editUser(user) {
    selectedUser.value = user;
    userForm.value = {
        username: user.username,
        email: user.email,
        password: "",
        fullname: user.fullname || "",
        level: user.level,
        type: user.type || "",
        status: user.status,
    };
    formErrors.value = [];
    showDetailModal.value = false;
    showEditModal.value = true;
}

function viewUser(user) {
    selectedUser.value = user;
    showDetailModal.value = true;
}

function deleteUser(user) {
    userToDelete.value = user;
    showDeleteModal.value = true;
}

function closeModal() {
    showCreateModal.value = false;
    showEditModal.value = false;
    showDetailModal.value = false;
    formErrors.value = [];
    userForm.value = {
        username: "",
        email: "",
        password: "",
        fullname: "",
        level: "",
        type: "",
        status: "",
    };
}

function onLevelChange() {
    if (userForm.value.level === "admin") {
        userForm.value.type = "";
    }
}

function searchUsers() {
    loadUsers(1);
}

function filterUsers() {
    loadUsers(1);
}

function resetFilters() {
    filters.value = {
        search: "",
        level: "",
        type: "",
        status: "",
    };
    loadUsers(1);
}

function changePage(page) {
    if (page >= 1 && page <= pagination.value.last_page) {
        loadUsers(page);
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
        hour: "2-digit",
        minute: "2-digit",
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
.admin-layout {
    display: flex;
    min-height: 100vh;
    background: #f8fafc;
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

/* Top Header */
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

/* Dashboard Content */
.dashboard-content {
    flex: 1;
    padding: 32px;
    overflow-y: auto;
    background: #f8fafc;
}

/* Filters */
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
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
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

/* Stats Section */
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

.stat-icon.active {
    background: #d1fae5;
    color: #065f46;
}

.stat-icon.inactive {
    background: #fef3c7;
    color: #92400e;
}

.stat-icon.admin {
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

/* Table */
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

.btn-refresh {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-refresh:hover {
    background: #e5e7eb;
}

.table-wrapper {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background: #f9fafb;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
    font-size: 0.875rem;
}

.users-table td {
    padding: 16px;
    border-bottom: 1px solid #f3f4f6;
    vertical-align: middle;
    font-size: 0.875rem;
}

.users-table tr:hover {
    background: #f9fafb;
}

.username-cell {
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

/* Badges */
.level-badge,
.type-badge,
.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.level-badge.admin {
    background: #dbeafe;
    color: #1e40af;
}

.level-badge.user {
    background: #f3e8ff;
    color: #7c3aed;
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

.type-badge.admin {
    background: #f3e8ff;
    color: #6b21a8;
}

.status-toggle {
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.75rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 4px;
}

.status-toggle.aktif {
    background: #d1fae5;
    color: #065f46;
}

.status-toggle.tidak_aktif {
    background: #fef2f2;
    color: #991b1b;
}

.status-toggle:hover:not(:disabled) {
    transform: scale(1.05);
}

.status-toggle:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Action Buttons */
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

.btn-action.edit {
    background: #fef3c7;
    color: #92400e;
}

.btn-action.delete {
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

/* Loading State */
.loading-state {
    padding: 64px;
    text-align: center;
    color: #6b7280;
}

.loading-state i {
    font-size: 2rem;
    margin-bottom: 16px;
    color: #1f2937;
}

/* No Data */
.no-data {
    text-align: center;
    padding: 64px;
    color: #6b7280;
}

.no-data i {
    font-size: 3rem;
    margin-bottom: 16px;
    color: #d1d5db;
    display: block;
}

/* Pagination */
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

/* Modal */
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

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
.form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #1f2937;
    outline: none;
    box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.1);
}

.modal-footer {
    padding: 24px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Delete Modal */
.delete-modal {
    max-width: 400px;
}

.delete-icon {
    text-align: center;
    margin-bottom: 16px;
}

.delete-icon i {
    font-size: 3rem;
    color: #ef4444;
}

.warning {
    color: #ef4444;
    font-weight: 600;
    font-size: 0.875rem;
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

/* Detail Modal */
.detail-modal {
    max-width: 500px;
}

.user-detail {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.detail-row label {
    font-weight: 600;
    color: #6b7280;
    margin: 0;
    font-size: 0.875rem;
}

/* Alert */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 0.875rem;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

.alert ul {
    margin: 0;
    padding-left: 16px;
}

/* Toast */
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

/* Responsive */
@media (max-width: 1200px) {
    .filter-row {
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 16px;
    }

    .search-box {
        grid-column: 1 / -1;
        margin-bottom: 16px;
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

    .form-row {
        grid-template-columns: 1fr;
    }
    
    .header-left {
        align-items: flex-start;
    }
    
    .header-left h1 {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .top-header {
        padding: 0 16px;
    }
    
    .dashboard-content {
        padding: 16px;
    }
}
</style>