<template>
  <div class="after-sales-card">
    <div v-if="hasData" class="after-sales-content">
      <div class="section-header">
        <div class="header-icon">
          <i class="fas fa-headset"></i>
        </div>
        <div class="header-info">
          <h3>After Sales Info</h3>
          <p>Layanan purna jual yang disediakan oleh distributor</p>
        </div>
      </div>
      <div class="content-sections">
        <div class="info-section">
          <h4>
            <i class="fas fa-concierge-bell"></i>
            After Sales Details
          </h4>
          <ul class="simple-after-sales-list">
            <li>
              <strong>Priority Treatment:</strong>
              <span>{{ afterSalesInfo.priority_treatment || '-' }}</span>
            </li>
            <li>
              <strong>Complaint Agreement:</strong>
              <span>{{ afterSalesInfo.complaint_agreement || '-' }}</span>
            </li>
            <li>
              <strong>Contact Centre:</strong>
              <span>{{ afterSalesInfo.has_contact_centre || '-' }}</span>
            </li>
          </ul>
        </div>
        <!-- Bisa tambahkan field lain dari after_sales jika ada -->
      </div>
    </div>
    <div v-else class="no-data">
      <div class="no-data-illustration">
        <i class="fas fa-headset"></i>
        <h4>Belum Ada Data After Sales Service</h4>
        <p>Data layanan purna jual belum dilengkapi untuk distributor ini</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({ data: Object, vendor: Object })

// FIX: Check for flat structure dari database
const hasData = computed(() => !!(
  props.data.priority_treatment || 
  props.data.complaint_agreement ||
  props.data.has_contact_centre ||
  props.data.after_sales
))

const afterSalesInfo = computed(() => ({
  priority_treatment: props.data.priority_treatment || props.data.after_sales?.priority_treatment,
  complaint_agreement: props.data.complaint_agreement || props.data.after_sales?.complaint_agreement,
  has_contact_centre: props.data.has_contact_centre || props.data.after_sales?.has_contact_centre
}))
</script>

<style scoped>
.after-sales-card {
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
.simple-after-sales-list { padding: 24px; list-style: none; }
.simple-after-sales-list li { margin-bottom: 12px; }
.simple-after-sales-list strong { color: #2563eb; font-weight: 600; }
.no-data { display: flex; align-items: center; justify-content: center; height: 400px; text-align: center; }
.no-data-illustration { max-width: 400px; }
.no-data-illustration i { font-size: 4rem; color: #d1d5db; margin-bottom: 20px; }
.no-data-illustration h4 { margin: 0 0 12px 0; color: #6b7280; font-size: 1.25rem; font-weight: 600; }
.no-data-illustration p { margin: 0; color: #9ca3af; line-height: 1.5; }
</style>