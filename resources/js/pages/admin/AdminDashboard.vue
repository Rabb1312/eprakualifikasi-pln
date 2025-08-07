<template>
  <div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
      <div class="header-left">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang kembali, {{ user?.fullname || user?.username }}</p>
      </div>
      <div class="header-right">
        <div class="user-info">
          <i class="fas fa-user-circle"></i>
          <span>{{ user?.username }}</span>
          <button @click="logout" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
      <!-- Total Users -->
      <div class="stat-card total">
        <div class="stat-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.total }}</h3>
          <p>Total User</p>
          <small>Semua vendor terdaftar</small>
        </div>
      </div>

      <!-- Distributor -->
      <div class="stat-card distributor">
        <div class="stat-icon">
          <i class="fas fa-truck"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.DS }}</h3>
          <p>Distributor (DS)</p>
          <small>Vendor suplai material/equipment</small>
        </div>
      </div>

      <!-- Subcontractor -->
      <div class="stat-card subcontractor">
        <div class="stat-icon">
          <i class="fas fa-hard-hat"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.SC }}</h3>
          <p>Subcontractor (SC)</p>
          <small>Vendor penyedia jasa pekerjaan</small>
        </div>
      </div>

      <!-- Manufacturer -->
      <div class="stat-card manufacturer">
        <div class="stat-icon">
          <i class="fas fa-industry"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.MF }}</h3>
          <p>Manufacturer (MF)</p>
          <small>Vendor fabrikasi/manufaktur</small>
        </div>
      </div>

      <!-- Forwarder -->
      <div class="stat-card forwarder">
        <div class="stat-icon">
          <i class="fas fa-shipping-fast"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.FW }}</h3>
          <p>Forwarder (FW)</p>
          <small>Vendor jasa pengiriman</small>
        </div>
      </div>

      <!-- Active Users -->
      <div class="stat-card active">
        <div class="stat-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.active }}</h3>
          <p>User Aktif</p>
          <small>Status aktif</small>
        </div>
      </div>

      <!-- Inactive Users -->
      <div class="stat-card inactive">
        <div class="stat-icon">
          <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.inactive }}</h3>
          <p>User Tidak Aktif</p>
          <small>Status tidak aktif</small>
        </div>
      </div>

      <!-- Admin Users -->
      <div class="stat-card admin">
        <div class="stat-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-content">
          <h3>{{ stats.admin }}</h3>
          <p>Administrator</p>
          <small>Level admin</small>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <h2>Aksi Cepat</h2>
      <div class="actions-grid">
        <router-link to="/admin/users" class="action-card">
          <i class="fas fa-users-cog"></i>
          <h3>Kelola User</h3>
          <p>Tambah, edit, hapus user</p>
        </router-link>
        <router-link to="/admin/vendors" class="action-card">
          <i class="fas fa-building"></i>
          <h3>Data Vendor</h3>
          <p>Lihat data vendor terdaftar</p>
        </router-link>
        <router-link to="/admin/reports" class="action-card">
          <i class="fas fa-chart-bar"></i>
          <h3>Laporan</h3>
          <p>Generate laporan statistik</p>
        </router-link>
        <router-link to="/admin/settings" class="action-card">
          <i class="fas fa-cog"></i>
          <h3>Pengaturan</h3>
          <p>Konfigurasi sistem</p>
        </router-link>
      </div>
    </div>

    <!-- Recent Users Table -->
    <div class="recent-users">
      <div class="table-header">
        <h2>User Terbaru</h2>
        <router-link to="/admin/users" class="view-all-btn">
          Lihat Semua <i class="fas fa-arrow-right"></i>
        </router-link>
      </div>
      <div class="table-wrapper">
        <table class="users-table">
          <thead>
            <tr>
              <th>Username</th>
              <th>Email</th>
              <th>Tipe</th>
              <th>Status</th>
              <th>Terdaftar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in recentUsers" :key="user.id">
              <td>{{ user.username }}</td>
              <td>{{ user.email }}</td>
              <td>
                <span :class="['type-badge', user.type?.toLowerCase()]">
                  {{ getTypeName(user.type) }}
                </span>
              </td>
              <td>
                <span :class="['status-badge', user.status]">
                  {{ user.status }}
                </span>
              </td>
              <td>{{ formatDate(user.created_at) }}</td>
            </tr>
            <tr v-if="recentUsers.length === 0">
              <td colspan="5" class="no-data">Tidak ada data user</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const user = ref(null)
const stats = ref({
  total: 0,
  DS: 0,
  SC: 0,
  MF: 0,
  FW: 0,
  active: 0,
  inactive: 0,
  admin: 0
})
const recentUsers = ref([])
const loading = ref(true)

// Set axios base URL
axios.defaults.baseURL = 'http://eprakualifikasi-pln.test'

onMounted(async () => {
  await loadUserData()
  await loadDashboardStats()
  await loadRecentUsers()
  loading.value = false
})

async function loadUserData() {
  try {
    const userData = localStorage.getItem('user')
    if (userData) {
      user.value = JSON.parse(userData)
    }
    
    // Set token untuk request
    const token = localStorage.getItem('token')
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    } else {
      router.push('/login')
    }
  } catch (error) {
    console.error('Error loading user data:', error)
    router.push('/login')
  }
}

async function loadDashboardStats() {
  try {
    const response = await axios.get('/api/dashboard/stats')
    if (response.data.success) {
      stats.value = response.data.data
    }
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

async function loadRecentUsers() {
  try {
    const response = await axios.get('/api/users?limit=5&sort=created_at&order=desc')
    if (response.data.success) {
      recentUsers.value = response.data.data.data || response.data.data
    }
  } catch (error) {
    console.error('Error loading recent users:', error)
  }
}

function getTypeName(type) {
  const types = {
    'DS': 'Distributor',
    'SC': 'Subcontractor', 
    'MF': 'Manufacturer',
    'FW': 'Forwarder'
  }
  return types[type] || type || 'Admin'
}

function formatDate(dateString) {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}

async function logout() {
  try {
    await axios.post('/api/logout')
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    delete axios.defaults.headers.common['Authorization']
    router.push('/')
  }
}
</script>

<style scoped>
.dashboard-container {
  min-height: 100vh;
  background: #f8f9fa;
  padding: 20px;
  font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.dashboard-header {
  background: white;
  padding: 25px 30px;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  margin-bottom: 30px;
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

.user-info {
  display: flex;
  align-items: center;
  gap: 15px;
  color: #666;
}

.user-info i {
  font-size: 1.5em;
  color: #1e3c72;
}

.logout-btn {
  background: #dc3545;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.3s;
}

.logout-btn:hover {
  background: #c82333;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.stat-card {
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  gap: 20px;
  transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5em;
  color: white;
}

.stat-card.total .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.distributor .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.subcontractor .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card.manufacturer .stat-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.stat-card.forwarder .stat-icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.stat-card.active .stat-icon { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
.stat-card.inactive .stat-icon { background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); }
.stat-card.admin .stat-icon { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }

.stat-content h3 {
  margin: 0 0 5px 0;
  font-size: 2.2em;
  font-weight: 700;
  color: #1e3c72;
}

.stat-content p {
  margin: 0 0 5px 0;
  font-weight: 600;
  color: #333;
  font-size: 1.1em;
}

.stat-content small {
  color: #666;
  font-size: 0.9em;
}

.quick-actions {
  margin-bottom: 40px;
}

.quick-actions h2 {
  color: #1e3c72;
  margin-bottom: 20px;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.action-card {
  background: white;
  padding: 30px;
  border-radius: 15px;
  text-decoration: none;
  color: inherit;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.3s, box-shadow 0.3s;
}

.action-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  text-decoration: none;
  color: inherit;
}

.action-card i {
  font-size: 2.5em;
  color: #1e3c72;
  margin-bottom: 15px;
}

.action-card h3 {
  margin: 0 0 10px 0;
  color: #1e3c72;
}

.action-card p {
  margin: 0;
  color: #666;
  font-size: 0.9em;
}

.recent-users {
  background: white;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  overflow: hidden;
}

.table-header {
  padding: 25px 30px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-header h2 {
  margin: 0;
  color: #1e3c72;
}

.view-all-btn {
  color: #1e3c72;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s;
}

.view-all-btn:hover {
  color: #ffd700;
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
  padding: 15px 20px;
  text-align: left;
  font-weight: 600;
  color: #1e3c72;
  border-bottom: 2px solid #eee;
}

.users-table td {
  padding: 15px 20px;
  border-bottom: 1px solid #eee;
}

.type-badge, .status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8em;
  font-weight: 600;
  text-transform: uppercase;
}

.type-badge.ds { background: #e3f2fd; color: #1976d2; }
.type-badge.sc { background: #f3e5f5; color: #7b1fa2; }
.type-badge.mf { background: #e8f5e8; color: #388e3c; }
.type-badge.fw { background: #fff3e0; color: #f57c00; }

.status-badge.aktif { background: #e8f5e8; color: #2e7d32; }
.status-badge.tidak_aktif { background: #ffebee; color: #d32f2f; }

.no-data {
  text-align: center;
  color: #666;
  font-style: italic;
  padding: 30px;
}

@media (max-width: 768px) {
  .dashboard-container {
    padding: 15px;
  }
  
  .dashboard-header {
    flex-direction: column;
    gap: 20px;
    text-align: center;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .stat-card {
    flex-direction: column;
    text-align: center;
  }
}
</style>