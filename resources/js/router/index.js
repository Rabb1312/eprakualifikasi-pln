import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import Login from '../pages/auth/Login.vue';

// Cek login dengan localStorage token
function isLoggedIn() {
  return !!localStorage.getItem('token');
}

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guest: true }
  },
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