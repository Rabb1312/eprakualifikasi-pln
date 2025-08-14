<!-- resources/js/views/components/AlertComponent.vue -->
<template>
  <div :class="['alert', `alert-${type}`]" v-if="message">
    <div class="alert-content">
      <i :class="alertIcon"></i>
      <span v-html="message"></span>
    </div>
    <button @click="$emit('close')" class="alert-close">
      <i class="fas fa-times"></i>
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
  },
  message: String
})

defineEmits(['close'])

const alertIcon = computed(() => {
  const icons = {
    success: 'fas fa-check-circle',
    error: 'fas fa-exclamation-circle',
    warning: 'fas fa-exclamation-triangle',
    info: 'fas fa-info-circle'
  }
  return icons[props.type]
})
</script>

<style scoped>
.alert {
  position: fixed;
  top: 20px;
  right: 20px;
  max-width: 400px;
  padding: 1rem 1.5rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: space-between;
  animation: slideIn 0.3s ease-out;
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

.alert-success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.alert-warning {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeaa7;
}

.alert-info {
  background: #d1ecf1;
  color: #0c5460;
  border: 1px solid #bee5eb;
}

.alert-content {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex: 1;
}

.alert-close {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
  margin-left: 1rem;
  opacity: 0.7;
  transition: opacity 0.2s;
}

.alert-close:hover {
  opacity: 1;
}
</style>