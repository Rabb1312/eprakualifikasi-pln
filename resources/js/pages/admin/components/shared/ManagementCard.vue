<template>
    <div class="management-card">
        <div class="management-header">
            <div class="header-icon" :class="color">
                <i :class="icon"></i>
            </div>
            <h4>{{ title }}</h4>
        </div>
        
        <div v-if="Array.isArray(personnel) && personnel.length > 0" class="personnel-list">
            <div 
                v-for="(person, index) in personnel" 
                :key="index"
                class="personnel-item"
            >
                <div class="person-info">
                    <div class="person-name">{{ person.name || 'Tidak disebutkan' }}</div>
                    <div class="person-position" v-if="person.position">{{ person.position }}</div>
                </div>
                <div class="person-contact" v-if="person.phone || person.email">
                    <span v-if="person.phone" class="contact-info">
                        <i class="fas fa-phone"></i>
                        {{ person.phone }}
                    </span>
                    <span v-if="person.email" class="contact-info">
                        <i class="fas fa-envelope"></i>
                        {{ person.email }}
                    </span>
                </div>
            </div>
        </div>
        
        <div v-else class="no-personnel">
            <i class="fas fa-users-slash"></i>
            <span>Belum ada data personel</span>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true
    },
    personnel: {
        type: Array,
        default: () => []
    },
    icon: {
        type: String,
        default: 'fas fa-users'
    },
    color: {
        type: String,
        default: 'blue'
    }
})
</script>

<style scoped>
.management-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.management-header {
    background: #f9fafb;
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
}

.header-icon.gold {
    background: #fef3c7;
    color: #92400e;
}

.header-icon.blue {
    background: #dbeafe;
    color: #1e40af;
}

.header-icon.green {
    background: #d1fae5;
    color: #065f46;
}

.management-header h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

.personnel-list {
    display: flex;
    flex-direction: column;
}

.personnel-item {
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
}

.personnel-item:last-child {
    border-bottom: none;
}

.person-info {
    margin-bottom: 8px;
}

.person-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.person-position {
    font-size: 0.875rem;
    color: #6b7280;
    font-style: italic;
}

.person-contact {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.contact-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #6b7280;
}

.contact-info i {
    width: 14px;
    text-align: center;
}

.no-personnel {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 32px 20px;
    color: #9ca3af;
    font-style: italic;
}

@media (max-width: 768px) {
    .management-header {
        padding: 12px 16px;
    }
    
    .personnel-item {
        padding: 12px 16px;
    }
}
</style>