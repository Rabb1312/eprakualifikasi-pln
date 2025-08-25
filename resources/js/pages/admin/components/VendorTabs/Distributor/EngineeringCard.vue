<template>
  <div class="engineering-card">
    <div v-if="hasData" class="engineering-content">
      <div class="section-header">
        <div class="header-icon">
          <i class="fas fa-cogs"></i>
        </div>
        <div class="header-info">
          <h3>Engineering Activities</h3>
          <p>Kemampuan teknis dan engineering yang dimiliki distributor</p>
        </div>
      </div>
      <div class="content-sections">
        <div class="info-section">
          <h4>
            <i class="fas fa-drafting-compass"></i>
            Engineering Activities
          </h4>
          <div class="engineering-activities">
            <p v-if="engineeringActivities">{{ engineeringActivities }}</p>
            <p v-else class="empty-engineering">Tidak ada data engineering activities</p>
          </div>
        </div>
        <!-- Bisa tambahkan info lain dari field engineering jika ada -->
      </div>
    </div>
    <div v-else class="no-data">
      <div class="no-data-illustration">
        <i class="fas fa-cogs"></i>
        <h4>Belum Ada Data Engineering</h4>
        <p>Data kemampuan engineering belum dilengkapi untuk distributor ini</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ data: Object, vendor: Object })

// FIX: Check for flat structure dari database
const hasData = computed(() => !!(
  props.data.engineering_activities || 
  props.data.engineering?.engineering_activities
))

const engineeringActivities = computed(() => 
  props.data.engineering_activities || 
  props.data.engineering?.engineering_activities || 
  ''
)
</script>

<style scoped>
.engineering-card {
  padding: 24px;
  min-height: 400px;
}
.section-header { display: flex; align-items: center; gap: 16px; margin-bottom: 32px; padding-bottom: 20px; border-bottom: 2px solid #f3f4f6; }
.header-icon { width: 56px; height: 56px; background: linear-gradient(135deg, #059669 0%, #047857 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3); }
.header-info h3 { margin: 0 0 4px 0; color: #1f2937; font-size: 1.5rem; font-weight: 700; }
.header-info p { margin: 0; color: #6b7280; font-size: 0.875rem; }
.content-sections { display: flex; flex-direction: column; gap: 32px; }
.info-section { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
.info-section h4 { margin: 0; padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; color: #374151; font-size: 1.125rem; font-weight: 600; display: flex; align-items: center; gap: 8px; }
.engineering-activities { padding: 24px; }
.empty-engineering { color: #9ca3af; font-style: italic; }
.no-data { display: flex; align-items: center; justify-content: center; height: 400px; text-align: center; }
.no-data-illustration { max-width: 400px; }
.no-data-illustration i { font-size: 4rem; color: #d1d5db; margin-bottom: 20px; }
.no-data-illustration h4 { margin: 0 0 12px 0; color: #6b7280; font-size: 1.25rem; font-weight: 600; }
.no-data-illustration p { margin: 0; color: #9ca3af; line-height: 1.5; }
</style>