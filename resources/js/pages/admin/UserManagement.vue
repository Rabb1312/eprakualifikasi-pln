<template>
    <div class="user-management">
        <!-- Header Section -->
         <div class="page-header">
    <div class="header-left">
      <h1>Kelola User</h1>
      <p>Manajemen data user dan vendor</p>
    </div>
    <div class="header-right">
      <router-link to="/admin/dashboard" class="btn-dashboard">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </router-link>
      <button @click="showCreateModal = true" class="btn-primary">
        <i class="fas fa-plus"></i> Tambah User
      </button>
    </div>
  </div>

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
        <div class="stats-row">
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <div>
                    <h3>{{ pagination.total || 0 }}</h3>
                    <p>Total User</p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-user-check"></i>
                <div>
                    <h3>
                        {{ users.filter((u) => u.status === "aktif").length }}
                    </h3>
                    <p>User Aktif</p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-user-times"></i>
                <div>
                    <h3>
                        {{
                            users.filter((u) => u.status === "tidak_aktif")
                                .length
                        }}
                    </h3>
                    <p>User Tidak Aktif</p>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-user-shield"></i>
                <div>
                    <h3>
                        {{ users.filter((u) => u.level === "admin").length }}
                    </h3>
                    <p>Administrator</p>
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
                            <!-- GANTI DARI user.id KE getRowNumber(index) -->
                            <td>
                                <div class="username-cell">
                                    <i class="fas fa-user-circle"></i>
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
                                <span v-else class="type-badge admin"
                                    >Admin</span
                                >
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

                    <!-- TAMBAH FULLNAME UNTUK SEMUA LEVEL -->
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

                    <!-- TIPE HANYA UNTUK USER -->
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

                    <!-- STATUS UNTUK NON-USER -->
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

// Reactive data
const users = ref([]);
const pagination = ref({});
const loading = ref(false);
const formLoading = ref(false);

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

function getRowNumber(index) {
    // Hitung nomor berdasarkan halaman dan index
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
.user-management {
    padding: 0;
    background: #f8f9fa;
    min-height: 100vh;
    font-family: "Montserrat", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Header */
.page-header {
    background: white;
    padding: 25px 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-left h1 {
    color: #1e3c72;
    margin: 0 0 5px 0;
    font-size: 2em;
}

.header-left p {
    color: #666;
    margin: 0;
    font-size: 1.1em;
}

.header-right {
  display: flex;
  gap: 15px;
  align-items: center;
}

.btn-primary {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
}

.btn-dashboard {
  background: #17a2b8;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.btn-dashboard:hover {
  background: #138496;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(23,162,184,0.3);
  text-decoration: none;
  color: white;
}

/* Filters */
.filters-card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.filter-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 15px;
    align-items: end;
}

.search-box {
    position: relative;
    min-width: 300px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    z-index: 1;
}

.search-box input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.search-box input:focus {
    border-color: #1e3c72;
    outline: none;
    box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 150px; /* TAMBAH MIN WIDTH */
}

.filter-group label {
    font-weight: 600;
    color: #1e3c72;
    font-size: 13px;
    margin: 0;
}

.filter-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    background: white;
    cursor: pointer;
}

.filter-group select:focus {
    border-color: #1e3c72;
    outline: none;
    box-shadow: 0 0 0 3px rgba(30, 60, 114, 0.1);
}

.filter-actions {
    display: flex;
    align-items: flex-end;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 12px 15px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-secondary:hover {
    background: #5a6268;
}

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-item {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-item i {
    font-size: 2em;
    color: #1e3c72;
}

.stat-item h3 {
    margin: 0;
    font-size: 1.8em;
    color: #1e3c72;
}

.stat-item p {
    margin: 0;
    color: #666;
    font-size: 0.9em;
}

/* Table */
.table-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table-header {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h2 {
    margin: 0;
    color: #1e3c72;
}

.btn-refresh {
    background: #17a2b8;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
}

.table-wrapper {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th {
    background: #f8f9fa;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #1e3c72;
    border-bottom: 2px solid #eee;
    white-space: nowrap;
}

.users-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.username-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.username-cell i {
    color: #1e3c72;
    font-size: 1.2em;
}

/* Badges */
.level-badge,
.type-badge,
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 600;
    text-transform: uppercase;
}

.level-badge.admin {
    background: #e3f2fd;
    color: #1976d2;
}
.level-badge.user {
    background: #f3e5f5;
    color: #7b1fa2;
}

.type-badge.ds {
    background: #e3f2fd;
    color: #1976d2;
}
.type-badge.sc {
    background: #f3e5f5;
    color: #7b1fa2;
}
.type-badge.mf {
    background: #e8f5e8;
    color: #388e3c;
}
.type-badge.fw {
    background: #fff3e0;
    color: #f57c00;
}
.type-badge.admin {
    background: #fce4ec;
    color: #ad1457;
}

.status-toggle {
    border: none;
    padding: 6px 12px;
    border-radius: 20px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.8em;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 5px;
}

.status-toggle.aktif {
    background: #e8f5e8;
    color: #2e7d32;
}

.status-toggle.tidak_aktif {
    background: #ffebee;
    color: #d32f2f;
}

.status-toggle:hover {
    transform: scale(1.05);
}

.status-toggle:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.btn-action.view {
    background: #17a2b8;
    color: white;
}

.btn-action.edit {
    background: #ffc107;
    color: #212529;
}

.btn-action.delete {
    background: #dc3545;
    color: white;
}

.btn-action:hover {
    transform: scale(1.1);
}

.btn-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Loading State */
.loading-state {
    padding: 60px;
    text-align: center;
    color: #666;
}

.loading-state i {
    font-size: 2em;
    margin-bottom: 15px;
    color: #1e3c72;
}

/* No Data */
.no-data {
    text-align: center;
    padding: 60px;
    color: #666;
}

.no-data i {
    font-size: 3em;
    margin-bottom: 15px;
    color: #ddd;
    display: block;
}

/* Pagination */
.pagination {
    padding: 20px 25px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
}

.page-btn {
    background: #1e3c72;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
}

.page-btn:hover:not(:disabled) {
    background: #2a5298;
}

.page-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.page-info {
    color: #666;
    font-size: 0.9em;
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
    border-radius: 15px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.modal-header {
    padding: 25px 30px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: #1e3c72;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5em;
    color: #666;
    cursor: pointer;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close:hover {
    background: #f8f9fa;
}

.modal-body {
    padding: 30px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1e3c72;
}

.required {
    color: #dc3545;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #1e3c72;
    outline: none;
}

.modal-footer {
    padding: 20px 30px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 15px;
}

/* Delete Modal */
.delete-modal {
    max-width: 400px;
}

.delete-icon {
    text-align: center;
    margin-bottom: 20px;
}

.delete-icon i {
    font-size: 4em;
    color: #dc3545;
}

.warning {
    color: #dc3545;
    font-weight: 600;
    font-size: 0.9em;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-danger:hover {
    background: #c82333;
}

/* Detail Modal */
.detail-modal {
    max-width: 500px;
}

.user-detail {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f8f9fa;
}

.detail-row label {
    font-weight: 600;
    color: #666;
    margin: 0;
}

/* Alert */
.alert {
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

/* Toast */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    z-index: 1100;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideIn 0.3s ease;
}

.toast.success {
    background: #28a745;
}

.toast.error {
    background: #dc3545;
}

.toast.warning {
    background: #ffc107;
    color: #212529;
}

.toast.info {
    background: #17a2b8;
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

/* Responsive */
/* Alternative Layout untuk Screen Sedang */
@media (max-width: 1200px) {
    .filter-row {
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 15px;
    }

    .search-box {
        grid-column: 1 / -1; /* FULL WIDTH */
        margin-bottom: 15px;
        min-width: unset;
    }

    .filter-row {
        grid-template-rows: auto auto;
    }
}

/* Mobile Layout */
@media (max-width: 768px) {
    .filters-card {
        padding: 20px;
    }

    .filter-row {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }

    .search-box,
    .filter-group {
        min-width: unset;
        width: 100%;
    }

    .filter-actions {
        justify-content: center;
    }
}
</style>
