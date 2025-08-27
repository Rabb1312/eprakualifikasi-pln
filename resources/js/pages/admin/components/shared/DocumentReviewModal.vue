<template>
    <div class="modal-overlay" @click.self="$emit('close')">
        <div class="modal review-modal">
            <div class="modal-header">
                <h2>Review Dokumen</h2>
                <button @click="$emit('close')" class="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="document-info">
                    <h3>{{ document.document_name }}</h3>
                    <p>Status: {{ document.status }}</p>
                </div>

                <div class="review-actions" style="flex-direction: column; gap: 16px;">
                    <textarea
                        v-model="notes"
                        placeholder="Catatan admin (optional)"
                        rows="2"
                        style="width: 100%; margin-bottom: 10px;"
                    ></textarea>

                    <div v-if="showReject" style="margin-bottom: 10px;">
                        <textarea
                            v-model="reason"
                            placeholder="Alasan penolakan"
                            rows="2"
                            style="width: 100%;"
                        ></textarea>
                    </div>
                    <div style="display: flex; gap: 16px;">
                        <button
                            @click="onApprove"
                            class="btn-approve"
                        >
                            <i class="fas fa-check"></i>
                            Approve
                        </button>
                        <button
                            @click="showReject = true"
                            v-if="!showReject"
                            class="btn-reject"
                        >
                            <i class="fas fa-times"></i>
                            Reject
                        </button>
                        <button
                            @click="onReject"
                            v-if="showReject"
                            class="btn-reject"
                        >
                            <i class="fas fa-times"></i>
                            Submit Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    document: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close', 'approve', 'reject']);

const notes = ref('');
const reason = ref('');
const showReject = ref(false);

// Fungsi approve
function onApprove() {
    emit('approve', props.document, notes.value);
}

// Fungsi reject
function onReject() {
    emit('reject', props.document, reason.value, notes.value);
}
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.review-modal {
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 700;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #6b7280;
    cursor: pointer;
}

.modal-body {
    padding: 24px;
}

.document-info {
    margin-bottom: 24px;
}

.document-info h3 {
    margin: 0 0 8px 0;
    color: #1f2937;
}

.review-actions {
    display: flex;
    gap: 16px;
}

.btn-approve {
    flex: 1;
    background: #059669;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-reject {
    flex: 1;
    background: #dc2626;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
</style>