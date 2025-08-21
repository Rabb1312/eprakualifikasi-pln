<template>
  <div class="subcontractor-employees">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-users text-primary me-2"></i>
          Employee Management
          <span class="subtitle">Manage permanent construction employees data</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Employee Classification Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-chart-organization"></i>
            <h6 class="section-title">Employee Classification Matrix</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Masukkan jumlah total karyawan konstruksi tetap berdasarkan klasifikasi dan tingkat jabatan yang saat ini berada dalam daftar gaji perusahaan Anda.
            </p>
          </div>

          <div class="table-container">
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-lg-block">
              <table class="employees-table">
                <thead>
                  <tr>
                    <th class="col-no">No</th>
                    <th class="col-classification">Classification</th>
                    <th class="col-count">
                      <div class="header-with-icon">
                        <i class="fas fa-hard-hat"></i>
                        Craft Labor
                      </div>
                    </th>
                    <th class="col-count">
                      <div class="header-with-icon">
                        <i class="fas fa-user-tie"></i>
                        Foreman
                      </div>
                    </th>
                    <th class="col-count">
                      <div class="header-with-icon">
                        <i class="fas fa-user-cog"></i>
                        Supervisor
                      </div>
                    </th>
                    <th class="col-count">
                      <div class="header-with-icon">
                        <i class="fas fa-user-crown"></i>
                        Manager
                      </div>
                    </th>
                    <th class="col-total">
                      <div class="header-with-icon">
                        <i class="fas fa-calculator"></i>
                        Total
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(employee, index) in employees"
                    :key="'employee_' + index"
                    class="employee-row"
                    :class="{ 'highlight-row': employee.classification === 'Others' }"
                  >
                    <td class="row-number">{{ index + 1 }}</td>
                    <td class="classification-cell">
                      <div class="classification-label">
                        <i class="fas fa-tag"></i>
                        <strong>{{ employee.classification }}</strong>
                      </div>
                    </td>
                    <td>
                      <input
                        v-model.number="employee.craft_labor"
                        type="number"
                        min="0"
                        class="count-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </td>
                    <td>
                      <input
                        v-model.number="employee.foreman"
                        type="number"
                        min="0"
                        class="count-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </td>
                    <td>
                      <input
                        v-model.number="employee.supervisor"
                        type="number"
                        min="0"
                        class="count-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </td>
                    <td>
                      <input
                        v-model.number="employee.manager"
                        type="number"
                        min="0"
                        class="count-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </td>
                    <td class="total-cell">
                      <span class="total-badge">{{ getEmployeeTotal(employee) }}</span>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr class="total-row">
                    <td colspan="2">
                      <div class="total-label">
                        <i class="fas fa-calculator me-2"></i>
                        <strong>GRAND TOTAL</strong>
                      </div>
                    </td>
                    <td>
                      <span class="footer-total">{{ getTotalByType("craft_labor") }}</span>
                    </td>
                    <td>
                      <span class="footer-total">{{ getTotalByType("foreman") }}</span>
                    </td>
                    <td>
                      <span class="footer-total">{{ getTotalByType("supervisor") }}</span>
                    </td>
                    <td>
                      <span class="footer-total">{{ getTotalByType("manager") }}</span>
                    </td>
                    <td>
                      <span class="grand-total-badge">{{ getGrandTotal() }}</span>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-employees d-lg-none">
              <div
                v-for="(employee, index) in employees"
                :key="'mobile_employee_' + index"
                class="employee-card"
              >
                <div class="employee-card-header">
                  <div class="employee-card-title">
                    <i class="fas fa-tag me-2"></i>
                    <span>{{ employee.classification }}</span>
                  </div>
                  <div class="employee-card-total">
                    <span class="total-label">Total:</span>
                    <span class="total-value">{{ getEmployeeTotal(employee) }}</span>
                  </div>
                </div>

                <div class="employee-card-body">
                  <div class="employee-positions">
                    <div class="position-item">
                      <label class="position-label">
                        <i class="fas fa-hard-hat me-1"></i>
                        Craft Labor
                      </label>
                      <input
                        v-model.number="employee.craft_labor"
                        type="number"
                        min="0"
                        class="position-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </div>

                    <div class="position-item">
                      <label class="position-label">
                        <i class="fas fa-user-tie me-1"></i>
                        Foreman
                      </label>
                      <input
                        v-model.number="employee.foreman"
                        type="number"
                        min="0"
                        class="position-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </div>

                    <div class="position-item">
                      <label class="position-label">
                        <i class="fas fa-user-cog me-1"></i>
                        Supervisor
                      </label>
                      <input
                        v-model.number="employee.supervisor"
                        type="number"
                        min="0"
                        class="position-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </div>

                    <div class="position-item">
                      <label class="position-label">
                        <i class="fas fa-user-crown me-1"></i>
                        Manager
                      </label>
                      <input
                        v-model.number="employee.manager"
                        type="number"
                        min="0"
                        class="position-input"
                        @input="calculateTotal(index)"
                        placeholder="0"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Summary Statistics Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-chart-bar"></i>
            <h6 class="section-title">Employee Statistics</h6>
            <div class="section-actions">
              <button @click="resetAllData" class="btn btn-reset">
                <i class="fas fa-undo me-1"></i>
                Reset Data
              </button>
            </div>
          </div>

          <div class="summary-grid">
            <div class="summary-card craft-labor">
              <div class="summary-icon">
                <i class="fas fa-hard-hat"></i>
              </div>
              <div class="summary-content">
                <div class="summary-number">{{ getTotalByType("craft_labor") }}</div>
                <div class="summary-label">Craft Labor</div>
                <div class="summary-percentage">
                  {{ getPercentage(getTotalByType("craft_labor")) }}%
                </div>
              </div>
            </div>

            <div class="summary-card foreman">
              <div class="summary-icon">
                <i class="fas fa-user-tie"></i>
              </div>
              <div class="summary-content">
                <div class="summary-number">{{ getTotalByType("foreman") }}</div>
                <div class="summary-label">Foreman</div>
                <div class="summary-percentage">
                  {{ getPercentage(getTotalByType("foreman")) }}%
                </div>
              </div>
            </div>

            <div class="summary-card supervisor">
              <div class="summary-icon">
                <i class="fas fa-user-cog"></i>
              </div>
              <div class="summary-content">
                <div class="summary-number">{{ getTotalByType("supervisor") }}</div>
                <div class="summary-label">Supervisor</div>
                <div class="summary-percentage">
                  {{ getPercentage(getTotalByType("supervisor")) }}%
                </div>
              </div>
            </div>

            <div class="summary-card manager">
              <div class="summary-icon">
                <i class="fas fa-user-crown"></i>
              </div>
              <div class="summary-content">
                <div class="summary-number">{{ getTotalByType("manager") }}</div>
                <div class="summary-label">Manager</div>
                <div class="summary-percentage">
                  {{ getPercentage(getTotalByType("manager")) }}%
                </div>
              </div>
            </div>

            <div class="summary-card grand-total">
              <div class="summary-icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="summary-content">
                <div class="summary-number">{{ getGrandTotal() }}</div>
                <div class="summary-label">Total Employees</div>
                <div class="summary-percentage">100%</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button
            @click="saveEmployees"
            :disabled="loading"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Data Karyawan" }}
          </button>
        </div>

        <!-- Alert Component -->
        <AlertComponent
          v-if="alertMsg"
          :type="alertType"
          :message="alertMsg"
          @close="alertMsg = ''"
          class="alert-container"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import axios from "axios";
import AlertComponent from "../AlertComponent.vue";

const props = defineProps({
  subcontractorData: Object,
  employeeClassifications: Array,
});

const emit = defineEmits(["updated"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const employees = ref([]);

watch(
  () => props.subcontractorData,
  (newData) => {
    if (newData) {
      initializeEmployeeData();
    }
  },
  { immediate: true, deep: true }
);

watch(
  () => props.employeeClassifications,
  (newClassifications) => {
    if (newClassifications && newClassifications.length > 0) {
      initializeEmployeeData();
    }
  },
  { immediate: true }
);

function initializeEmployeeData() {
  if (
    !props.employeeClassifications ||
    props.employeeClassifications.length === 0
  )
    return;

  if (
    props.subcontractorData &&
    props.subcontractorData.employees &&
    Array.isArray(props.subcontractorData.employees)
  ) {
    // Use existing data
    employees.value = props.subcontractorData.employees.map((emp) => ({
      classification: emp.classification,
      craft_labor: Number(emp.craft_labor) || 0,
      foreman: Number(emp.foreman) || 0,
      supervisor: Number(emp.supervisor) || 0,
      manager: Number(emp.manager) || 0,
    }));
  } else {
    // Initialize with default data
    employees.value = props.employeeClassifications.map(
      (classification) => ({
        classification,
        craft_labor: 0,
        foreman: 0,
        supervisor: 0,
        manager: 0,
      })
    );
  }
}

function calculateTotal(index) {
  // This is called when input changes to trigger reactivity
  const employee = employees.value[index];
  if (employee) {
    // Ensure values are numbers
    employee.craft_labor = Number(employee.craft_labor) || 0;
    employee.foreman = Number(employee.foreman) || 0;
    employee.supervisor = Number(employee.supervisor) || 0;
    employee.manager = Number(employee.manager) || 0;
  }
}

function getEmployeeTotal(employee) {
  return (
    (Number(employee.craft_labor) || 0) +
    (Number(employee.foreman) || 0) +
    (Number(employee.supervisor) || 0) +
    (Number(employee.manager) || 0)
  );
}

function getTotalByType(type) {
  return employees.value.reduce((total, employee) => {
    return total + (Number(employee[type]) || 0);
  }, 0);
}

function getGrandTotal() {
  return employees.value.reduce((total, employee) => {
    return total + getEmployeeTotal(employee);
  }, 0);
}

function getPercentage(value) {
  const grandTotal = getGrandTotal();
  if (grandTotal === 0) return 0;
  return Math.round((value / grandTotal) * 100);
}

function resetAllData() {
  if (
    confirm(
      "Apakah Anda yakin ingin mereset semua data karyawan? Tindakan ini tidak dapat dibatalkan."
    )
  ) {
    employees.value.forEach((employee) => {
      employee.craft_labor = 0;
      employee.foreman = 0;
      employee.supervisor = 0;
      employee.manager = 0;
    });
    showAlert("info", "Data karyawan telah direset");
  }
}

async function saveEmployees() {
  loading.value = true;

  try {
    const dataToSave = {
      employees: employees.value,
    };

    const token = localStorage.getItem("token");
    const response = await axios.put(
      "/api/vendor/subcontractor",
      dataToSave,
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );

    if (response.data.success) {
      showAlert("success", "Data karyawan berhasil disimpan!");
      emit("updated", response.data.data.subcontractor_data);
    } else {
      showAlert(
        "error",
        response.data.message || "Gagal menyimpan data karyawan"
      );
    }
  } catch (error) {
    console.error("Save employees error:", error);
    showAlert(
      "error",
      error.response?.data?.message || "Gagal menyimpan data karyawan"
    );
  } finally {
    loading.value = false;
  }
}

function showAlert(type, message) {
  alertType.value = type;
  alertMsg.value = message;
  setTimeout(() => {
    alertMsg.value = "";
  }, 5000);
}
</script>

<style scoped>
.subcontractor-employees {
  max-width: 1400px;
  margin: 20px auto;
  padding: 0 20px;
}

.card {
  border-radius: 20px;
  box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
  border: none;
  background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
  overflow: hidden;
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 12px 50px rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
}

.card-header {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  border-bottom: none;
  padding: 2rem 2.5rem 1.5rem 2.5rem;
  color: white;
  position: relative;
}

.card-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.3;
}

.card-header h5 {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  position: relative;
  z-index: 1;
}

.subtitle {
  display: block;
  font-size: 0.9rem;
  font-weight: 400;
  opacity: 0.9;
  margin-top: 0.5rem;
}

.card-body {
  padding: 2.5rem;
  background: white;
}

.section-wrapper {
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 16px;
  padding: 2rem;
  margin-bottom: 2rem;
  border: 1px solid rgba(0, 123, 255, 0.1);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.section-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(180deg, #007bff 0%, #0056b3 100%);
  transition: width 0.3s ease;
}

.section-wrapper:hover::before {
  width: 6px;
}

.section-wrapper:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.section-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid rgba(0, 123, 255, 0.1);
}

.section-header .section-title {
  display: flex;
  align-items: center;
  font-size: 1.2rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
}

.section-header i {
  font-size: 1.2rem;
  color: #007bff;
  margin-right: 0.75rem;
  width: 20px;
  text-align: center;
}

.section-description {
  margin-bottom: 2rem;
  padding: 1rem;
  background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
  border-radius: 8px;
  border-left: 4px solid #007bff;
}

.section-description p {
  margin: 0;
  color: #495057;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.section-description i {
  color: #007bff;
}

.table-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #e9ecef;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.table-responsive {
  border-radius: 12px;
  overflow: hidden;
}

.employees-table {
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}

.employees-table thead th {
  background: linear-gradient(135deg, #495057 0%, #343a40 100%);
  color: white;
  font-weight: 600;
  padding: 1.25rem 1rem;
  font-size: 0.9rem;
  text-align: center;
  position: sticky;
  top: 0;
  z-index: 10;
}

.header-with-icon {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

.header-with-icon i {
  font-size: 1rem;
  opacity: 0.8;
}

.col-no { width: 60px; }
.col-classification { width: 25%; text-align: left !important; }
.col-count { width: 15%; }
.col-total { width: 10%; }

.employee-row {
  transition: all 0.3s ease;
  border-bottom: 1px solid #e9ecef;
}

.employee-row:hover {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.employee-row:last-child {
  border-bottom: none;
}

.highlight-row {
  background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
}

.employees-table td {
  padding: 1rem;
  text-align: center;
  vertical-align: middle;
}

.row-number {
  font-weight: 600;
  color: #007bff;
  font-size: 1rem;
}

.classification-cell {
  text-align: left !important;
}

.classification-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: #495057;
}

.classification-label i {
  color: #007bff;
  font-size: 0.9rem;
}

.count-input {
  width: 100%;
  max-width: 80px;
  padding: 0.5rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  text-align: center;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.count-input:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.total-badge {
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
  color: white;
  padding: 0.5rem 0.75rem;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.9rem;
  display: inline-block;
  min-width: 40px;
}

.total-row {
  background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
  border-top: 2px solid #007bff;
}

.total-label {
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: #495057;
}

.footer-total {
  background: #6c757d;
  color: white;
  padding: 0.5rem 0.75rem;
  border-radius: 15px;
  font-weight: 600;
  display: inline-block;
  min-width: 40px;
}

.grand-total-badge {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  padding: 0.75rem 1rem;
  border-radius: 20px;
  font-weight: 700;
  font-size: 1rem;
  display: inline-block;
  min-width: 50px;
}

.mobile-employees {
  display: grid;
  gap: 1.5rem;
  padding: 1rem;
}

.employee-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
}

.employee-card:hover {
  border-color: #007bff;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.15);
}

.employee-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.employee-card-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
}

.employee-card-title i {
  color: #007bff;
}

.employee-card-total {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.total-label {
  font-size: 0.85rem;
  color: #6c757d;
}

.total-value {
  background: #007bff;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-weight: 600;
  font-size: 0.9rem;
}

.employee-card-body {
  padding: 1.5rem;
}

.employee-positions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
}

.position-item {
  display: flex;
  flex-direction: column;
}

.position-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.85rem;
  display: flex;
  align-items: center;
}

.position-label i {
  color: #007bff;
  font-size: 0.8rem;
}

.position-input {
  padding: 0.75rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  text-align: center;
  font-weight: 600;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.position-input:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}

.summary-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.summary-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.summary-card:hover::before {
  left: 100%;
}

.summary-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.summary-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: white;
  flex-shrink: 0;
}

.craft-labor .summary-icon {
  background: linear-gradient(135deg, #fd7e14 0%, #e85d04 100%);
}

.foreman .summary-icon {
  background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);
}

.supervisor .summary-icon {
  background: linear-gradient(135deg, #20c997 0%, #198754 100%);
}

.manager .summary-icon {
  background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
}

.grand-total .summary-icon {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.summary-content {
  flex: 1;
}

.summary-number {
  font-size: 2rem;
  font-weight: 700;
  color: #212529;
  line-height: 1;
}

.summary-label {
  color: #6c757d;
  font-size: 0.9rem;
  margin: 0.25rem 0;
}

.summary-percentage {
  color: #007bff;
  font-weight: 600;
  font-size: 0.85rem;
}

.btn {
  padding: 0.875rem 1.5rem;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  position: relative;
  overflow: hidden;
}

.btn::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: all 0.3s ease;
}

.btn:hover::before {
  width: 300px;
  height: 300px;
}

.btn-reset {
  background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.btn-reset:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
}

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
  min-width: 200px;
  justify-content: center;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none !important;
}

.submit-section {
  text-align: center;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 2px solid rgba(0, 123, 255, 0.1);
}

.spinner-border {
  width: 1.2em;
  height: 1.2em;
  border-width: 0.15em;
}

.alert-container {
  margin-top: 1.5rem;
}

@keyframes spinner-border {
  100% {
    transform: rotate(360deg);
  }
}

@media (max-width: 1024px) {
  .subcontractor-employees {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .summary-grid {
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  }
}

@media (max-width: 768px) {
  .card-header,
  .card-body {
    padding: 1.5rem !important;
  }
  
  .section-wrapper {
    padding: 1.5rem;
  }
  
  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .summary-grid {
    grid-template-columns: 1fr;
  }
  
  .employee-positions {
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  }
}
</style>