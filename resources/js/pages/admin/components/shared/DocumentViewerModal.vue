<template>
  <!-- Modal tetap dipanggil, tapi langsung open tab baru dan auto-close -->
  <div class="modal-overlay" @click.self="$emit('close')"></div>
</template>

<script setup>
import { onMounted, computed } from 'vue';

const props = defineProps({
  document: { type: Object, required: true }
});

const emit = defineEmits(['close']);

const fileUrl = computed(() => {
  let url = props.document.file_url || props.document.file_path || '';
  if (!url) return null;

  // jika path relatif, ubah jadi /storage/...
  if (!/^https?:\/\//i.test(url)) {
    if (url.includes('/admin/vendor_documents/')) {
      url = url.replace('/admin/vendor_documents/', '/storage/vendor_documents/');
    } else if (!url.startsWith('/')) {
      url = '/storage/' + url.replace(/^\/+/, '');
    }
  }

  return url;
});

onMounted(() => {
  if (fileUrl.value) {
    window.open(fileUrl.value, '_blank');
  }
  // langsung close modal
  emit('close');
});
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000;
}
.viewer-modal {
  background: white;
  border-radius: 12px;
  max-width: 95vw;
  width: 1200px;
  max-height: 95vh;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}
.modal-header {
  padding: 24px;
  border-bottom: 1px solid #e5e7eb;
  display: flex; justify-content: space-between; align-items: center;
  background: #f9fafb;
}
.modal-header h2 { margin: 0; font-size: 1.25rem; font-weight: 700; color: #1f2937; }
.modal-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #6b7280;
  cursor: pointer;
}
.modal-body { flex: 1; padding: 24px; background: #f3f4f6; min-height: 0; }
.loading-state, .error-state {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  height: 400px; color: #6b7280;
}
.viewer-actions {
  margin-top: 16px;
  display: flex;
  gap: 16px;
  justify-content: center;
}
.btn-download, .btn-external {
  padding: 8px 16px; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer;
  display: flex; align-items: center; gap: 8px; transition: all 0.3s ease;
}
.btn-download { background: #059669; color: white; }
.btn-download:hover { background: #047857; }
.btn-external { background: #2563eb; color: white; }
.btn-external:hover { background: #1d4ed8; }
.file-viewer, .image-viewer, .pdf-viewer {
  text-align: center;
}
.file-info i { font-size: 2rem; margin-bottom: 12px; }
.file-info h3 { font-size: 1.125rem; margin-bottom: 8px; color: #1f2937; }
.file-note { margin-top: 16px; color: #6b7280; font-size: 0.95rem; }
.btn-retry {
  margin-top: 16px;
  padding: 12px 20px;
  background: #1f2937;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}
.btn-retry:hover {
  background: #374151;
}
@media (max-width: 768px) {
  .viewer-modal { max-width: 100vw; width: 100vw; height: 100vh; max-height: 100vh; border-radius: 0; }
  .modal-header { padding: 16px; flex-direction: column; gap: 12px; align-items: flex-start; }
  .viewer-actions { flex-direction: column; gap: 12px; }
  .modal-body { padding: 12px; }
}
</style>