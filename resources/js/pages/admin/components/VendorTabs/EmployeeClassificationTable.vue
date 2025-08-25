<template>
    <div class="employee-classification-table">
        <div v-if="employeeData && employeeData.length > 0" class="table-container">
            <div class="table-header">
                <h3>Total Permanent Construction Employees</h3>
                <div class="total-summary">
                    <span class="total-count">Total: {{ totalEmployees }} orang</span>
                </div>
            </div>
            
            <div class="table-wrapper">
                <table class="classification-table">
                    <thead>
                        <tr>
                            <th class="no-col">No</th>
                            <th class="classification-col">Classification</th>
                            <th class="count-col">Craft Labor</th>
                            <th class="count-col">Foreman</th>
                            <th class="count-col">Supervisor</th>
                            <th class="count-col">Manager</th>
                            <th class="total-col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="(employee, index) in employeeData" 
                            :key="index"
                            class="employee-row"
                        >
                            <td class="no-cell">{{ index + 1 }}</td>
                            <td class="classification-cell">
                                <div class="classification-name">
                                    <i class="fas fa-user-hard-hat"></i>
                                    {{ employee.classification }}
                                </div>
                            </td>
                            <td class="count-cell">{{ employee.craft_labor || 0 }}</td>
                            <td class="count-cell">{{ employee.foreman || 0 }}</td>
                            <td class="count-cell">{{ employee.supervisor || 0 }}</td>
                            <td class="count-cell">{{ employee.manager || 0 }}</td>
                            <td class="total-cell">
                                <span class="total-badge">
                                    {{ getRowTotal(employee) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="totals-row">
                            <td colspan="2" class="totals-label">Total Keseluruhan</td>
                            <td class="totals-cell">{{ getTotalByType('craft_labor') }}</td>
                            <td class="totals-cell">{{ getTotalByType('foreman') }}</td>
                            <td class="totals-cell">{{ getTotalByType('supervisor') }}</td>
                            <td class="totals-cell">{{ getTotalByType('manager') }}</td>
                            <td class="grand-total-cell">
                                <span class="grand-total-badge">{{ totalEmployees }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div v-else class="no-employees">
            <div class="no-data-illustration">
                <i class="fas fa-users-slash"></i>
                <h4>Belum Ada Data Karyawan</h4>
                <p>Data klasifikasi karyawan belum tersedia untuk vendor ini</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    employees: {
        type: [Array, Object],
        default: () => []
    }
})

const employeeData = computed(() => {
    if (Array.isArray(props.employees)) {
        return props.employees
    }
    return []
})

const totalEmployees = computed(() => {
    return employeeData.value.reduce((total, employee) => {
        return total + getRowTotal(employee)
    }, 0)
})

function getRowTotal(employee) {
    const craftLabor = parseInt(employee.craft_labor || 0)
    const foreman = parseInt(employee.foreman || 0)
    const supervisor = parseInt(employee.supervisor || 0)
    const manager = parseInt(employee.manager || 0)
    
    return craftLabor + foreman + supervisor + manager
}

function getTotalByType(type) {
    return employeeData.value.reduce((total, employee) => {
        return total + parseInt(employee[type] || 0)
    }, 0)
}
</script>

<style scoped>
.employee-classification-table {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.table-header {
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    padding: 24px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.table-header h3::before {
    content: '👥';
    font-size: 1.5rem;
}

.total-summary {
    background: rgba(255, 255, 255, 0.1);
    padding: 8px 16px;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.total-count {
    font-weight: 600;
    font-size: 1rem;
}

.table-wrapper {
    overflow-x: auto;
    background: white;
}

.classification-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.classification-table th {
    background: #f8fafc;
    color: #374151;
    font-weight: 700;
    padding: 16px 12px;
    text-align: center;
    border-bottom: 2px solid #e5e7eb;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.classification-table td {
    padding: 16px 12px;
    border-bottom: 1px solid #f1f5f9;
    text-align: center;
    vertical-align: middle;
}

.no-col {
    width: 60px;
}

.classification-col {
    width: auto;
    text-align: left !important;
    min-width: 200px;
}

.count-col {
    width: 100px;
}

.total-col {
    width: 100px;
}

.employee-row:hover {
    background: #f8fafc;
}

.no-cell {
    font-weight: 600;
    color: #6b7280;
    background: #f9fafb;
}

.classification-cell {
    text-align: left !important;
    padding-left: 20px !important;
}

.classification-name {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    color: #1f2937;
}

.classification-name i {
    color: #3b82f6;
    width: 16px;
}

.count-cell {
    font-weight: 600;
    color: #374151;
}

.total-cell {
    background: #eff6ff;
}

.total-badge {
    background: #3b82f6;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 0.875rem;
}

.totals-row {
    background: #1f2937;
    color: white;
    font-weight: 700;
}

.totals-row td {
    border-bottom: none;
    padding: 20px 12px;
}

.totals-label {
    text-align: left !important;
    padding-left: 20px !important;
    font-size: 1rem;
}

.totals-cell {
    font-size: 1rem;
    font-weight: 700;
}

.grand-total-cell {
    background: #059669;
}

.grand-total-badge {
    background: white;
    color: #059669;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 900;
    font-size: 1.125rem;
}

.no-employees {
    padding: 60px 20px;
    text-align: center;
}

.no-data-illustration {
    max-width: 400px;
    margin: 0 auto;
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
    .table-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .table-wrapper {
        font-size: 0.75rem;
    }
    
    .classification-table th,
    .classification-table td {
        padding: 12px 8px;
    }
    
    .count-col,
    .total-col {
        width: 80px;
    }
    
    .classification-name {
        flex-direction: column;
        gap: 4px;
        text-align: center;
    }
}
</style>