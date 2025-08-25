<template>
    <div :class="['sidebar', { collapsed: isCollapsed }]">
        <!-- Logo Section -->
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo">
                    <img
                        src="/assets/footer_logo.png"
                        alt="Logo PLN"
                        class="logo-header-img"
                    />
                </div>
            </div>
            <button @click="toggleSidebar" class="toggle-btn" v-if="!isMobile">
                <i
                    :class="
                        isCollapsed
                            ? 'fas fa-chevron-right'
                            : 'fas fa-chevron-left'
                    "
                ></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div v-if="!isCollapsed" class="section-title">Main</div>

                <!-- Dashboard -->
                <router-link
                    to="/admin/dashboard"
                    class="nav-item"
                    active-class="active"
                >
                    <i class="fas fa-tachometer-alt"></i>
                    <span v-if="!isCollapsed">Dashboard</span>
                </router-link>
            </div>

            <div class="nav-section">
                <div v-if="!isCollapsed" class="section-title">Management</div>

                <!-- Users Management -->
                <div class="nav-item-group">
                    <div
                        @click="toggleSubmenu('users')"
                        :class="[
                            'nav-item',
                            'has-submenu',
                            { active: activeSubmenu === 'users' },
                        ]"
                    >
                        <i class="fas fa-users"></i>
                        <span v-if="!isCollapsed">Users</span>
                        <i
                            v-if="!isCollapsed"
                            :class="[
                                'submenu-arrow',
                                'fas',
                                activeSubmenu === 'users'
                                    ? 'fa-chevron-up'
                                    : 'fa-chevron-down',
                            ]"
                        ></i>
                    </div>
                    <div
                        v-if="!isCollapsed && activeSubmenu === 'users'"
                        class="submenu"
                    >
                        <router-link to="/admin/users" class="submenu-item">
                            <i class="fas fa-list"></i>
                            <span>User List</span>
                        </router-link>
                    </div>
                </div>

                <!-- Vendors Management -->
                <div class="nav-item-group">
                    <div
                        @click="toggleSubmenu('vendors')"
                        :class="[
                            'nav-item',
                            'has-submenu',
                            { active: activeSubmenu === 'vendors' },
                        ]"
                    >
                        <i class="fas fa-building"></i>
                        <span v-if="!isCollapsed">Vendors</span>
                        <i
                            v-if="!isCollapsed"
                            :class="[
                                'submenu-arrow',
                                'fas',
                                activeSubmenu === 'vendors'
                                    ? 'fa-chevron-up'
                                    : 'fa-chevron-down',
                            ]"
                        ></i>
                    </div>
                    <div
                        v-if="!isCollapsed && activeSubmenu === 'vendors'"
                        class="submenu"
                    >
                        <router-link to="/admin/vendors" class="submenu-item">
                            <i class="fas fa-list"></i>
                            <span>Vendor List</span>
                        </router-link>
                        <router-link
                            to="/admin/vendors/categories"
                            class="submenu-item"
                        >
                            <i class="fas fa-tags"></i>
                            <span>Categories</span>
                        </router-link>
                        <router-link
                            to="/admin/vendors/verification"
                            class="submenu-item"
                        >
                            <i class="fas fa-check-circle"></i>
                            <span>Verification</span>
                        </router-link>
                    </div>
                </div>
            </div>

            <div class="nav-section">
                <div v-if="!isCollapsed" class="section-title">
                    Reports & Analytics
                </div>

                <!-- Reports -->
                <div class="nav-item-group">
                    <div
                        @click="toggleSubmenu('reports')"
                        :class="[
                            'nav-item',
                            'has-submenu',
                            { active: activeSubmenu === 'reports' },
                        ]"
                    >
                        <i class="fas fa-chart-bar"></i>
                        <span v-if="!isCollapsed">Reports</span>
                        <i
                            v-if="!isCollapsed"
                            :class="[
                                'submenu-arrow',
                                'fas',
                                activeSubmenu === 'reports'
                                    ? 'fa-chevron-up'
                                    : 'fa-chevron-down',
                            ]"
                        ></i>
                    </div>
                    <div
                        v-if="!isCollapsed && activeSubmenu === 'reports'"
                        class="submenu"
                    >
                        <router-link
                            to="/admin/reports/users"
                            class="submenu-item"
                        >
                            <i class="fas fa-users"></i>
                            <span>User Reports</span>
                        </router-link>
                        <router-link
                            to="/admin/reports/vendors"
                            class="submenu-item"
                        >
                            <i class="fas fa-building"></i>
                            <span>Vendor Reports</span>
                        </router-link>
                        <router-link
                            to="/admin/reports/activity"
                            class="submenu-item"
                        >
                            <i class="fas fa-history"></i>
                            <span>Activity Logs</span>
                        </router-link>
                    </div>
                </div>
            </div>

            <div class="nav-section">
                <div v-if="!isCollapsed" class="section-title">System</div>

                <!-- Settings -->
                <div class="nav-item-group">
                    <div
                        @click="toggleSubmenu('settings')"
                        :class="[
                            'nav-item',
                            'has-submenu',
                            { active: activeSubmenu === 'settings' },
                        ]"
                    >
                        <i class="fas fa-cog"></i>
                        <span v-if="!isCollapsed">Settings</span>
                        <i
                            v-if="!isCollapsed"
                            :class="[
                                'submenu-arrow',
                                'fas',
                                activeSubmenu === 'settings'
                                    ? 'fa-chevron-up'
                                    : 'fa-chevron-down',
                            ]"
                        ></i>
                    </div>
                    <div
                        v-if="!isCollapsed && activeSubmenu === 'settings'"
                        class="submenu"
                    >
                        <router-link
                            to="/admin/settings/general"
                            class="submenu-item"
                        >
                            <i class="fas fa-sliders-h"></i>
                            <span>General</span>
                        </router-link>
                        <router-link
                            to="/admin/settings/security"
                            class="submenu-item"
                        >
                            <i class="fas fa-shield-alt"></i>
                            <span>Security</span>
                        </router-link>
                        <router-link
                            to="/admin/settings/notifications"
                            class="submenu-item"
                        >
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </router-link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- User Profile Section -->
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <img
                        v-if="user?.avatar"
                        :src="user.avatar"
                        :alt="user.username"
                    />
                    <div v-else class="avatar-placeholder">
                        {{ user?.username?.charAt(0)?.toUpperCase() || "A" }}
                    </div>
                </div>
                <div v-if="!isCollapsed" class="user-info">
                    <div class="user-name">
                        {{ user?.fullname || user?.username }}
                    </div>
                    <div class="user-role">Administrator</div>
                </div>
                <div v-if="!isCollapsed" class="user-actions">
                    <button @click="logout" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const emit = defineEmits(["toggle"]);

// Props
const props = defineProps({
    user: {
        type: Object,
        default: () => ({}),
    },
});

// Reactive data
const isCollapsed = ref(false);
const activeSubmenu = ref("");
const isMobile = ref(false);

// Methods
const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
    emit("toggle", isCollapsed.value);
};

const toggleSubmenu = (menu) => {
    if (isCollapsed.value) {
        isCollapsed.value = false;
        emit("toggle", false);
    }
    activeSubmenu.value = activeSubmenu.value === menu ? "" : menu;
};

const checkMobile = () => {
    isMobile.value = window.innerWidth <= 768;
    if (isMobile.value) {
        isCollapsed.value = true;
    }
};

const logout = () => {
    // Implement logout logic
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    router.push("/");
};

// Lifecycle
onMounted(() => {
    checkMobile();
    window.addEventListener("resize", checkMobile);
});

onUnmounted(() => {
    window.removeEventListener("resize", checkMobile);
});
</script>

<style scoped>
.sidebar {
    width: 280px;
    height: 100vh;
    background: #1f2937;
    color: #e5e7eb;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    border-right: 1px solid #374151;
}

.sidebar.collapsed {
    width: 80px;
}

/* Header Section */
.sidebar-header {
    padding: 24px 20px;
    border-bottom: 1px solid #374151;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
    background: #111827;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo {
    display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
  gap: 6px;
  background: transparent;
  padding: 0;
}

.logo:hover {
    border-color: #fbbf24;
    background: #374151;
}

.logo-text h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #f9fafb;
}

.logo-text span {
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 500;
}

.toggle-btn {
    width: 32px;
    height: 32px;
    background: #374151;
    border: 1px solid #4b5563;
    border-radius: 6px;
    color: #d1d5db;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.toggle-btn:hover {
    background: #4b5563;
    border-color: #6b7280;
    color: #f9fafb;
}

/* Navigation Section */
.sidebar-nav {
    flex: 1;
    padding: 20px 0;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #4b5563 transparent;
}

.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: #4b5563;
    border-radius: 2px;
}

.nav-section {
    margin-bottom: 32px;
}

.section-title {
    padding: 0 20px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.nav-item,
.submenu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #d1d5db;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    font-weight: 500;
    position: relative;
    margin: 0 12px;
    border-radius: 8px;
}

.nav-item:hover,
.submenu-item:hover {
    background: #374151;
    color: #f9fafb;
    text-decoration: none;
}

.nav-item.active,
.submenu-item.active {
    background: #374151;
    color: #f9fafb;
    position: relative;
}

.nav-item.active::before {
    content: "";
    position: absolute;
    left: -12px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #fbbf24;
    border-radius: 0 2px 2px 0;
}

.nav-item i {
    width: 20px;
    text-align: center;
    font-size: 1rem;
    color: #9ca3af;
}

.nav-item:hover i,
.nav-item.active i {
    color: #fbbf24;
}

.has-submenu {
    justify-content: space-between;
}

.submenu-arrow {
    font-size: 0.75rem;
    transition: transform 0.3s ease;
    color: #9ca3af;
}

.submenu {
    background: #111827;
    margin: 4px 12px;
    border-radius: 8px;
    padding: 8px 0;
}

.submenu-item {
    padding: 8px 16px 8px 40px;
    font-size: 0.875rem;
    color: #9ca3af;
    margin: 0;
    border-radius: 6px;
}

.submenu-item i {
    font-size: 0.875rem;
    color: #6b7280;
}

.submenu-item:hover,
.submenu-item.active {
    background: #1f2937;
    color: #e5e7eb;
}

.submenu-item:hover i,
.submenu-item.active i {
    color: #fbbf24;
}

/* Footer Section */
.sidebar-footer {
    padding: 20px;
    border-top: 1px solid #374151;
    background: #111827;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #1f2937;
    border: 1px solid #374151;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.user-profile:hover {
    background: #374151;
    border-color: #4b5563;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid #374151;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #374151;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e5e7eb;
    font-weight: 600;
    font-size: 1rem;
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    color: #f9fafb;
    font-size: 0.875rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.75rem;
    color: #9ca3af;
}

.user-actions {
    flex-shrink: 0;
}

.logout-btn {
    width: 32px;
    height: 32px;
    background: #374151;
    border: 1px solid #4b5563;
    border-radius: 6px;
    color: #9ca3af;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.logout-btn:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.collapsed {
        transform: translateX(0);
        width: 280px;
    }
}

/* Smooth transitions for collapsed state */
.sidebar .nav-item span,
.sidebar .logo-text,
.sidebar .user-info,
.sidebar .user-actions,
.sidebar .section-title,
.sidebar .submenu-arrow {
    opacity: 1;
    transition: opacity 0.3s ease;
}

.sidebar.collapsed .nav-item span,
.sidebar.collapsed .logo-text,
.sidebar.collapsed .user-info,
.sidebar.collapsed .user-actions,
.sidebar.collapsed .section-title,
.sidebar.collapsed .submenu-arrow {
    opacity: 0;
}

/* Tooltip for collapsed state */
.sidebar.collapsed .nav-item:hover::after {
    content: attr(data-tooltip);
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    background: #111827;
    color: #e5e7eb;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.875rem;
    white-space: nowrap;
    z-index: 1001;
    margin-left: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: 1px solid #374151;
}

.logo-header {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    gap: 6px;
    background: transparent;
    padding: 0;
}

.logo-header-img {
    width: 190px;
    max-width: 100%;
    height: auto;
    display: block;
    background: transparent;
    box-shadow: none;
    border-radius: 0;
}
</style>
