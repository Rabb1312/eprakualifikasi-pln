import { createRouter, createWebHistory } from 'vue-router';


// Cek login dengan localStorage token
function isLoggedIn() {
  return !!localStorage.getItem('token');
}

const routes = [
  { path: '/', component: () => import('@/pages/Home.vue') },
  { path: '/login', component: () => import('@/pages/auth/Login.vue') },
  { path: '/registration', component: () => import('@/pages/auth/Registration.vue') },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  // Jika route butuh auth dan belum login, redirect ke login
  if (to.meta.requiresAuth && !isLoggedIn()) {
    next({ name: 'login' });
  }
  // Jika user sudah login dan akses login page, redirect ke home
  else if (to.meta.guest && isLoggedIn()) {
    next({ name: 'home' });
  } else {
    next();
  }
});

export default router;