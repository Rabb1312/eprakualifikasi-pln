import { createRouter, createWebHistory } from 'vue-router';

function isLoggedIn() {
  return !!localStorage.getItem('token');
}

function isAdmin() {
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  return user.level === 'admin';
}

const routes = [
  { path: '/', component: () => import('@/pages/Home.vue'), name: 'home' },
  { path: '/login', component: () => import('@/pages/auth/Login.vue'), name: 'login', meta: { guest: true } },
  { path: '/registration', component: () => import('@/pages/auth/Registration.vue'), name: 'registration', meta: { guest: true } },
  { path: '/forgot-password', component: () => import('@/pages/auth/ForgotPassword.vue'), name: 'forgot-password', meta: { guest: true } },

  // Vendor Dashboard (User level)
  { path: '/vendor/dashboard', component: () => import('@/pages/vendor/VendorDashboard.vue'), name: 'vendor-dashboard', meta: { requiresAuth: true, requiresVendor: true } },
  
  // Admin Dashboard
  { path: '/admin/dashboard', component: () => import('@/pages/admin/AdminDashboard.vue'), name: 'admin-dashboard', meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/admin/users', component: () => import('@/pages/admin/UserManagement.vue'), name: 'admin-users', meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/admin/vendors', component: () => import('@/pages/admin/VendorManagement.vue'), name: 'admin-vendors', meta: { requiresAuth: true, requiresAdmin: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const loggedIn = isLoggedIn();
  const admin = isAdmin();
  const vendor = isVendor();

  if (to.meta.requiresAuth && !loggedIn) {
    next('/login');
    return;
  }

  if (to.meta.requiresAdmin && (!loggedIn || !admin)) {
    next('/login');
    return;
  }

  if (to.meta.guest && loggedIn) {
    if (admin) {
      next('/admin/dashboard');
    } else if (vendor) {
      next('/vendor/dashboard');
    } else {
      next('/');
    }
    return;
  }

  next();
});

function isVendor() {
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  return user.level === 'user';
}

export default router;