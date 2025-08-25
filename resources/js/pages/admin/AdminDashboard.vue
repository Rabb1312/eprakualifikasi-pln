<template>
    <div class="admin-layout">
        <!-- Sidebar -->
        <Sidebar :user="user" @toggle="handleSidebarToggle" />

        <!-- Main Content -->
        <div
            :class="['main-content', { 'sidebar-collapsed': sidebarCollapsed }]"
        >
            <!-- Top Header -->
            <div class="top-header">
                <div class="header-left">
                    <button
                        @click="toggleMobileSidebar"
                        class="mobile-menu-btn"
                    >
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Dashboard</h1>
                    <p class="header-subtitle">
                        Selamat datang, {{ user?.fullname || user?.username }}
                    </p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                      <button @click="logout" class="header-btn">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                        <div class="user-menu">
                            <div class="user-avatar">
                                {{
                                    user?.username?.charAt(0)?.toUpperCase() ||
                                    "A"
                                }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Summary Stats -->
                <div class="summary-section">
                    <div class="summary-card">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number">{{ stats.total }}</div>
                            <div class="summary-label">
                                Total Vendor Terdaftar
                            </div>
                            <div class="summary-trend">
                                <i class="fas fa-arrow-up"></i>
                                <span
                                    >+{{
                                        Math.round((stats.total / 100) * 12)
                                    }}% bulan ini</span
                                >
                            </div>
                        </div>
                        <div class="summary-chart">
                            <div class="mini-chart">
                                <div
                                    class="chart-bar"
                                    style="height: 20%"
                                ></div>
                                <div
                                    class="chart-bar"
                                    style="height: 40%"
                                ></div>
                                <div
                                    class="chart-bar"
                                    style="height: 60%"
                                ></div>
                                <div
                                    class="chart-bar"
                                    style="height: 80%"
                                ></div>
                                <div
                                    class="chart-bar"
                                    style="height: 100%"
                                ></div>
                                <div
                                    class="chart-bar"
                                    style="height: 70%"
                                ></div>
                                <div
                                    class="chart-bar"
                                    style="height: 90%"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Stats Cards -->
                <div class="vendor-section">
                    <h2 class="section-title">Kategori Vendor</h2>
                    <div class="vendor-grid">
                        <!-- Distributor -->
                        <div class="vendor-card">
                            <div class="vendor-header">
                                <div class="vendor-icon distributor">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="vendor-badge">DS</div>
                            </div>
                            <div class="vendor-content">
                                <div class="vendor-number">
                                    {{ stats.DS || 0 }}
                                </div>
                                <div class="vendor-label">Distributor</div>
                                <div class="vendor-description">
                                    Vendor suplai material & equipment
                                </div>
                            </div>
                        </div>

                        <!-- Subcontractor -->
                        <div class="vendor-card">
                            <div class="vendor-header">
                                <div class="vendor-icon subcontractor">
                                    <i class="fas fa-hard-hat"></i>
                                </div>
                                <div class="vendor-badge">SC</div>
                            </div>
                            <div class="vendor-content">
                                <div class="vendor-number">
                                    {{ stats.SC || 0 }}
                                </div>
                                <div class="vendor-label">Subcontractor</div>
                                <div class="vendor-description">
                                    Vendor penyedia jasa konstruksi
                                </div>
                            </div>
                        </div>

                        <!-- Manufacturer -->
                        <div class="vendor-card">
                            <div class="vendor-header">
                                <div class="vendor-icon manufacturer">
                                    <i class="fas fa-industry"></i>
                                </div>
                                <div class="vendor-badge">MF</div>
                            </div>
                            <div class="vendor-content">
                                <div class="vendor-number">
                                    {{ stats.MF || 0 }}
                                </div>
                                <div class="vendor-label">Manufacturer</div>
                                <div class="vendor-description">
                                    Vendor fabrikasi & manufaktur
                                </div>
                            </div>
                        </div>

                        <!-- Forwarder -->
                        <div class="vendor-card">
                            <div class="vendor-header">
                                <div class="vendor-icon forwarder">
                                    <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="vendor-badge">FW</div>
                            </div>
                            <div class="vendor-content">
                                <div class="vendor-number">
                                    {{ stats.FW || 0 }}
                                </div>
                                <div class="vendor-label">Forwarder</div>
                                <div class="vendor-description">
                                    Vendor jasa pengiriman & logistik
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Overview -->
                <div class="status-section">
                    <h2 class="section-title">Status Overview</h2>
                    <div class="status-grid">
                        <div class="status-card">
                            <div class="status-icon active">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="status-content">
                                <div class="status-number">
                                    {{ stats.active || 0 }}
                                </div>
                                <div class="status-label">Vendor Aktif</div>
                                <div class="status-subtitle">
                                    Vendor yang beroperasi
                                </div>
                            </div>
                        </div>

                        <div class="status-card">
                            <div class="status-icon inactive">
                                <i class="fas fa-pause-circle"></i>
                            </div>
                            <div class="status-content">
                                <div class="status-number">
                                    {{ stats.inactive || 0 }}
                                </div>
                                <div class="status-label">
                                    Vendor Tidak Aktif
                                </div>
                                <div class="status-subtitle">
                                    Vendor non-operasional
                                </div>
                            </div>
                        </div>

                        <div class="status-card">
                            <div class="status-icon admin">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="status-content">
                                <div class="status-number">
                                    {{ stats.admin || 0 }}
                                </div>
                                <div class="status-label">Administrator</div>
                                <div class="status-subtitle">
                                    System administrators
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="actions-section">
                    <h2 class="section-title">Quick Actions</h2>
                    <div class="actions-grid">
                        <router-link to="/admin/users" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            <div class="action-content">
                                <h3>Kelola User</h3>
                                <p>Tambah, edit, hapus user vendor</p>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </router-link>

                        <router-link to="/admin/vendors" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="action-content">
                                <h3>Data Vendor</h3>
                                <p>Lihat dan kelola data vendor</p>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </router-link>

                        <router-link to="/admin/settings" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="action-content">
                                <h3>Pengaturan</h3>
                                <p>Konfigurasi sistem aplikasi</p>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </router-link>
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
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import Sidebar from "./components/Sidebar.vue";

const router = useRouter();
const user = ref(null);
const stats = ref({
    total: 0,
    DS: 0,
    SC: 0,
    MF: 0,
    FW: 0,
    active: 0,
    inactive: 0,
    admin: 0,
});
const recentUsers = ref([]);
const loading = ref(true);
const sidebarCollapsed = ref(false);
const showMobileSidebar = ref(false);

// Set axios base URL
axios.defaults.baseURL = "http://eprakualifikasi-pln.test";

onMounted(async () => {
    await loadUserData();
    await loadDashboardStats();
    loading.value = false;
});

// Sidebar functions
const handleSidebarToggle = (collapsed) => {
    sidebarCollapsed.value = collapsed;
};

const toggleMobileSidebar = () => {
    showMobileSidebar.value = !showMobileSidebar.value;
};

const closeMobileSidebar = () => {
    showMobileSidebar.value = false;
};

// Progress calculations
const getProgressWidth = (value) => {
    if (!stats.value.total || stats.value.total === 0) return "0%";
    const percentage = (value / stats.value.total) * 100;
    return `${Math.min(percentage, 100)}%`;
};

const getProgressPercentage = (value) => {
    if (!stats.value.total || stats.value.total === 0) return 0;
    const percentage = (value / stats.value.total) * 100;
    return Math.round(percentage);
};

async function loadUserData() {
    try {
        const userData = localStorage.getItem("user");
        if (userData) {
            user.value = JSON.parse(userData);
        }

        // Set token untuk request
        const token = localStorage.getItem("token");
        if (token) {
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        } else {
            router.push("/login");
        }
    } catch (error) {
        console.error("Error loading user data:", error);
        router.push("/login");
    }
}

async function loadDashboardStats() {
    try {
        const response = await axios.get("/api/dashboard/stats");
        if (response.data.success) {
            stats.value = response.data.data;
        }
    } catch (error) {
        console.error("Error loading stats:", error);
    }
}

async function logout() {
    try {
        await axios.post("/api/logout");
    } catch (error) {
        console.error("Logout error:", error);
    } finally {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        delete axios.defaults.headers.common["Authorization"];
        router.push("/");
    }
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

.header-btn {
    width: 44px;
    height: 44px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    cursor: pointer;
    border-radius: 10px;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.header-btn:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
}

.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 18px;
    height: 18px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

.user-avatar {
    width: 44px;
    height: 44px;
    background: #1f2937;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-avatar:hover {
    background: #374151;
}

/* Dashboard Content */
.dashboard-content {
    flex: 1;
    padding: 32px;
    overflow-y: auto;
    background: #f8fafc;
}

.section-title {
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 24px 0;
}

/* Summary Section */
.summary-section {
    margin-bottom: 40px;
}

.summary-card {
    background: white;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 32px;
}

.summary-icon {
    width: 80px;
    height: 80px;
    background: #f3f4f6;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1f2937;
    font-size: 2rem;
}

.summary-content {
    flex: 1;
}

.summary-number {
    font-size: 3.5rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 8px;
}

.summary-label {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.summary-trend {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #059669;
    font-weight: 600;
    font-size: 0.875rem;
}

.summary-chart {
    width: 120px;
}

.mini-chart {
    display: flex;
    align-items: end;
    gap: 4px;
    height: 60px;
}

.chart-bar {
    flex: 1;
    background: #e5e7eb;
    border-radius: 2px;
    min-height: 8px;
    transition: all 0.3s ease;
}

/* Vendor Section */
.vendor-section {
    margin-bottom: 40px;
}

.vendor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.vendor-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.vendor-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.vendor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.vendor-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.vendor-icon.distributor {
    background: #1f2937;
}
.vendor-icon.subcontractor {
    background: #374151;
}
.vendor-icon.manufacturer {
    background: #4b5563;
}
.vendor-icon.forwarder {
    background: #6b7280;
}

.vendor-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
}

.vendor-number {
    font-size: 2.25rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 8px;
}

.vendor-label {
    font-size: 1.125rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
}

.vendor-description {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 20px;
}

.vendor-footer {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress-bar {
    flex: 1;
    height: 6px;
    background: #f3f4f6;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.5s ease;
}

.progress-fill.distributor {
    background: #1f2937;
}
.progress-fill.subcontractor {
    background: #374151;
}
.progress-fill.manufacturer {
    background: #4b5563;
}
.progress-fill.forwarder {
    background: #6b7280;
}

.progress-text {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
    min-width: 35px;
    text-align: right;
}

/* Status Section */
.status-section {
    margin-bottom: 40px;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.status-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: flex-start;
    gap: 20px;
    transition: all 0.3s ease;
}

.status-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.status-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.status-icon.active {
    background: #d1fae5;
    color: #065f46;
}

.status-icon.inactive {
    background: #fef3c7;
    color: #92400e;
}

.status-icon.admin {
    background: #dbeafe;
    color: #1e40af;
}

.status-content {
    flex: 1;
}

.status-number {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 4px;
}

.status-label {
    font-weight: 600;
    color: #374151;
    font-size: 1rem;
    margin-bottom: 4px;
}

.status-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 500;
}

/* Actions Section */
.actions-section {
    margin-bottom: 40px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.action-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
}

.action-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 48px;
    height: 48px;
    background: #f3f4f6;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #374151;
    flex-shrink: 0;
}

.action-content {
    flex: 1;
}

.action-content h3 {
    margin: 0 0 4px 0;
    color: #1f2937;
    font-size: 1rem;
    font-weight: 600;
}

.action-content p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

.action-arrow {
    color: #9ca3af;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.action-card:hover .action-arrow {
    color: #374151;
    transform: translateX(4px);
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

/* Responsive Design */
@media (max-width: 1024px) {
    .vendor-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
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

    .vendor-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .status-grid {
        grid-template-columns: 1fr;
    }

    .summary-card {
        flex-direction: column;
        text-align: center;
        gap: 20px;
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

    .summary-card {
        padding: 24px;
    }

    .vendor-card {
        padding: 20px;
    }
}
</style>
