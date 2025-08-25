<template>
    <div class="scope-of-work-card">
        <div v-if="hasScope" class="scope-content">
            <div class="section-header">
                <div class="header-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="header-info">
                    <h3>Scope of Work</h3>
                    <p>Ruang lingkup pekerjaan yang dapat dikerjakan subcontractor</p>
                </div>
            </div>

            <div class="scope-details">
                <div class="scope-text">
                    <div class="text-content">
                        <p>{{ data.scope_of_work }}</p>
                    </div>
                </div>

                <!-- Parse and display work categories if possible -->
                <div v-if="workCategories.length > 0" class="work-categories">
                    <h4>
                        <i class="fas fa-list"></i>
                        Work Categories
                    </h4>
                    <div class="categories-grid">
                        <div 
                            v-for="(category, index) in workCategories"
                            :key="index"
                            class="category-item"
                        >
                            <div class="category-icon">
                                <i :class="getCategoryIcon(category)"></i>
                            </div>
                            <span>{{ category }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="no-data">
            <div class="no-data-illustration">
                <i class="fas fa-tasks"></i>
                <h4>Belum Ada Data Scope of Work</h4>
                <p>Ruang lingkup pekerjaan belum dilengkapi untuk subcontractor ini</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    vendor: {
        type: Object,
        required: true
    }
})

const hasScope = computed(() => {
    return !!(props.data.scope_of_work && props.data.scope_of_work.trim())
})

const workCategories = computed(() => {
    if (!props.data.scope_of_work) return []
    
    // Try to extract categories from scope text
    const text = props.data.scope_of_work.toLowerCase()
    const categories = []
    
    const categoryKeywords = {
        'Civil Construction': ['civil', 'construction', 'building', 'structure'],
        'Mechanical Installation': ['mechanical', 'machinery', 'equipment'],
        'Electrical Installation': ['electrical', 'electric', 'power', 'lighting'],
        'Piping Installation': ['piping', 'pipe', 'plumbing'],
        'Welding & Fabrication': ['welding', 'fabrication', 'steel work'],
        'Painting & Coating': ['painting', 'coating', 'finishing'],
        'Instrumentation': ['instrumentation', 'control', 'automation'],
        'Maintenance': ['maintenance', 'repair', 'service']
    }
    
    Object.entries(categoryKeywords).forEach(([category, keywords]) => {
        if (keywords.some(keyword => text.includes(keyword))) {
            categories.push(category)
        }
    })
    
    return categories
})

function getCategoryIcon(category) {
    const icons = {
        'Civil Construction': 'fas fa-hammer',
        'Mechanical Installation': 'fas fa-cogs',
        'Electrical Installation': 'fas fa-bolt',
        'Piping Installation': 'fas fa-pipe',
        'Welding & Fabrication': 'fas fa-fire',
        'Painting & Coating': 'fas fa-paint-brush',
        'Instrumentation': 'fas fa-gauge-high',
        'Maintenance': 'fas fa-tools'
    }
    return icons[category] || 'fas fa-check-circle'
}
</script>

<style scoped>
.scope-of-work-card {
    padding: 24px;
    min-height: 400px;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f3f4f6;
}

.header-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.header-info h3 {
    margin: 0 0 4px 0;
    color: #1f2937;
    font-size: 1.5rem;
    font-weight: 700;
}

.header-info p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.scope-details {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.scope-text {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.text-content {
    padding: 32px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-left: 4px solid #10b981;
}

.text-content p {
    margin: 0;
    color: #374151;
    line-height: 1.8;
    font-size: 1rem;
    text-align: justify;
}

.work-categories {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
}

.work-categories h4 {
    margin: 0 0 20px 0;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.work-categories h4 i {
    color: #10b981;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border-radius: 8px;
    border: 1px solid #d1fae5;
    transition: all 0.3s ease;
}

.category-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

.category-icon {
    width: 36px;
    height: 36px;
    background: #10b981;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.category-item span {
    font-weight: 600;
    color: #065f46;
    font-size: 0.875rem;
}

.no-data {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 400px;
    text-align: center;
}

.no-data-illustration {
    max-width: 400px;
}

.no-data-illustration i {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 20px;
}

.no-data-illustration h4 {
    margin: 0 0 12px 0;
    color: #6b7280;
    font-size: 1.25rem;
    font-weight: 600;
}

.no-data-illustration p {
    margin: 0;
    color: #9ca3af;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .scope-of-work-card {
        padding: 16px;
    }
    
    .text-content {
        padding: 20px;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>