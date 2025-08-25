<template>
    <div class="contact-person-card">
        <div v-if="Array.isArray(contacts)" class="contact-list">
            <div 
                v-for="(contact, index) in contacts" 
                :key="index"
                class="contact-item"
            >
                <div class="contact-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="contact-info">
                    <div class="contact-name">{{ contact.name || 'Tidak disebutkan' }}</div>
                    <div class="contact-details">
                        <span v-if="contact.position" class="contact-position">
                            <i class="fas fa-briefcase"></i>
                            {{ contact.position }}
                        </span>
                        <span v-if="contact.phone" class="contact-phone">
                            <i class="fas fa-phone"></i>
                            {{ contact.phone }}
                        </span>
                        <span v-if="contact.email" class="contact-email">
                            <i class="fas fa-envelope"></i>
                            {{ contact.email }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-else-if="contacts" class="single-contact">
            <div class="contact-item">
                <div class="contact-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="contact-info">
                    <div class="contact-name">{{ contacts.name || 'Tidak disebutkan' }}</div>
                    <div class="contact-details">
                        <span v-if="contacts.position" class="contact-position">
                            <i class="fas fa-briefcase"></i>
                            {{ contacts.position }}
                        </span>
                        <span v-if="contacts.phone" class="contact-phone">
                            <i class="fas fa-phone"></i>
                            {{ contacts.phone }}
                        </span>
                        <span v-if="contacts.email" class="contact-email">
                            <i class="fas fa-envelope"></i>
                            {{ contacts.email }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-else class="no-contact">
            <i class="fas fa-user-slash"></i>
            <span>Belum ada contact person</span>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    contacts: {
        type: [Array, Object],
        default: null
    }
})
</script>

<style scoped>
.contact-person-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.contact-list {
    display: flex;
    flex-direction: column;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    border-bottom: 1px solid #f3f4f6;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-avatar {
    width: 48px;
    height: 48px;
    background: #e5e7eb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6b7280;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.contact-info {
    flex: 1;
}

.contact-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 1rem;
    margin-bottom: 8px;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.contact-details span {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #6b7280;
}

.contact-details i {
    width: 16px;
    text-align: center;
}

.no-contact {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 32px;
    color: #9ca3af;
    font-style: italic;
}

@media (max-width: 768px) {
    .contact-item {
        padding: 16px;
    }
    
    .contact-details {
        gap: 6px;
    }
}
</style>