<template>
  <div class="forwarder-general">
    <div class="tab-header">
      <h3>
        <i class="fas fa-info-circle"></i>
        General Information
      </h3>
      <p>
        Basic information about your company from vendor registration.
      </p>
    </div>

    <div class="form-grid">
      <!-- Row 1 -->
      <div class="form-row">
        <div class="form-group">
          <label>Nomor Vendor</label>
          <input
            type="text"
            :value="vendor.nomor_vendor"
            readonly
            class="readonly-input"
          />
        </div>
        <div class="form-group">
          <label>Tipe Perusahaan</label>
          <input
            type="text"
            value="Forwarder"
            readonly
            class="readonly-input"
          />
        </div>
      </div>

      <!-- Row 2 -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Nama Perusahaan</label>
          <input
            type="text"
            :value="vendor.nama_perusahaan"
            readonly
            class="readonly-input"
          />
        </div>
      </div>

      <!-- Row 3 -->
      <div class="form-row">
        <div class="form-group">
          <label>NPWP</label>
          <input
            v-model="formData.npwp"
            type="text"
            placeholder="00.000.000.0-000.000"
            pattern="\\d{2}\\.\\d{3}\\.\\d{3}\\.\\d{1}-\\d{3}\\.\\d{3}"
          />
        </div>
        <div class="form-group">
          <label>Tanggal Berdiri</label>
          <input
            v-model="formData.tanggal_berdiri"
            type="date"
            required
          />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Tanggal Mulai Beroperasi</label>
          <input
            v-model="formData.tanggal_beroperasi"
            type="date"
            required
          />
        </div>
        <div class="form-group">
          <label>Nomor Telepon</label>
          <input
            v-model="formData.phone"
            type="tel"
            placeholder="+62 xxx xxxx xxxx"
            required
          />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group full-width">
          <label>Alamat</label>
          <textarea
            v-model="formData.alamat"
            rows="3"
            placeholder="Alamat lengkap perusahaan"
            required
          ></textarea>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Kode Pos</label>
          <input
            v-model="formData.kode_pos"
            type="text"
            maxlength="5"
            placeholder="12345"
          />
        </div>
        <div class="form-group">
          <label>Website</label>
          <input
            v-model="formData.website"
            type="url"
            placeholder="https://www.example.com"
          />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group full-width">
          <label>Alamat Kantor Operasional</label>
          <textarea
            v-model="formData.alamat_kantor_operasional"
            rows="3"
            placeholder="Alamat kantor operasional (jika berbeda dengan alamat utama)"
          ></textarea>
        </div>
      </div>

      <!-- Fleet Ownership Section -->
      <div class="form-row">
        <div class="form-group full-width">
          <h4 class="section-title">
            <i class="fas fa-truck"></i>
            Fleet Information / Informasi Armada
          </h4>
        </div>
      </div>

      <!-- In Possession -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>a. In possession / Kepemilikan:</label>
          <div class="radio-group">
            <div class="radio-option">
              <input
                type="radio"
                id="possession_none"
                value="tidak"
                v-model="formData.kepemilikan"
              />
              <label for="possession_none" class="radio-label">
                <i class="fas fa-times-circle"></i>
                None / Tidak
              </label>
            </div>
            <div class="radio-option">
              <input
                type="radio"
                id="possession_one"
                value="satu"
                v-model="formData.kepemilikan"
              />
              <label for="possession_one" class="radio-label">
                <i class="fas fa-truck"></i>
                One / Satu
              </label>
            </div>
            <div class="radio-option">
              <input
                type="radio"
                id="possession_more"
                value="lebih dari satu"
                v-model="formData.kepemilikan"
              />
              <label for="possession_more" class="radio-label">
                <i class="fas fa-shipping-fast"></i>
                More than one / Lebih dari satu
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Fleet Ownership Status -->
      <div class="form-row" v-if="formData.kepemilikan && formData.kepemilikan !== 'tidak'">
        <div class="form-group full-width">
          <label>b. Fleet Ownership status / Status kepemilikan armada:</label>
          <div class="radio-group">
            <div class="radio-option">
              <input
                type="radio"
                id="ownership_rent"
                value="sewa"
                v-model="formData.kepemilikan_armada"
              />
              <label for="ownership_rent" class="radio-label">
                <i class="fas fa-handshake"></i>
                On rent / Sewa
              </label>
            </div>
            <div class="radio-option">
              <input
                type="radio"
                id="ownership_own"
                value="miliki pribadi"
                v-model="formData.kepemilikan_armada"
              />
              <label for="ownership_own" class="radio-label">
                <i class="fas fa-home"></i>
                Own property / Miliki pribadi
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Person -->
      <div class="form-row">
        <div class="form-group full-width">
          <h4 class="section-title">
            <i class="fas fa-users"></i>
            Personnel Information / Informasi Personel
          </h4>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group full-width">
          <label>Contact Person</label>
          <div
            v-for="(cp, idx) in formData.contact_person"
            :key="idx"
            class="contact-person-row"
          >
            <input
              v-model="cp.nama"
              type="text"
              placeholder="Nama"
            />
            <input
              v-model="cp.jabatan"
              type="text"
              placeholder="Jabatan"
            />
            <input
              v-model="cp.telepon"
              type="text"
              placeholder="Telepon"
            />
            <input
              v-model="cp.email"
              type="email"
              placeholder="Email"
            />
            <button @click="removeContactPerson(idx)" type="button" class="btn-delete">
              🗑️
            </button>
          </div>
          <button
            @click="addContactPerson"
            type="button"
            class="btn btn-sm btn-add"
          >
            <i class="fas fa-plus"></i>
            Add Contact Person
          </button>
        </div>
      </div>

      <!-- Top Level Management -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Top Level Management</label>
          <div
            v-for="(top, idx) in formData.top_level"
            :key="idx"
            class="management-row"
          >
            <input
              v-model="top.nama"
              type="text"
              placeholder="Nama"
            />
            <input
              v-model="top.jabatan"
              type="text"
              placeholder="Jabatan"
            />
            <input
              v-model="top.telepon"
              type="text"
              placeholder="Telepon"
            />
            <input
              v-model="top.email"
              type="email"
              placeholder="Email"
            />
            <button @click="removeTopLevel(idx)" type="button" class="btn-delete">
              🗑️
            </button>
          </div>
          <button
            @click="addTopLevel"
            type="button"
            class="btn btn-sm btn-add"
          >
            <i class="fas fa-plus"></i>
            Add Top Level
          </button>
        </div>
      </div>

      <!-- Middle Level Management -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Middle Level Management</label>
          <div
            v-for="(mid, idx) in formData.mid_level"
            :key="idx"
            class="management-row"
          >
            <input
              v-model="mid.nama"
              type="text"
              placeholder="Nama"
            />
            <input
              v-model="mid.jabatan"
              type="text"
              placeholder="Jabatan"
            />
            <input
              v-model="mid.telepon"
              type="text"
              placeholder="Telepon"
            />
            <input
              v-model="mid.email"
              type="email"
              placeholder="Email"
            />
            <button @click="removeMidLevel(idx)" type="button" class="btn-delete">
              🗑️
            </button>
          </div>
          <button
            @click="addMidLevel"
            type="button"
            class="btn btn-sm btn-add"
          >
            <i class="fas fa-plus"></i>
            Add Middle Level
          </button>
        </div>
      </div>

      <!-- Sales/Marketing -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Sales/Marketing</label>
          <div
            v-for="(sales, idx) in formData.sales_marketing"
            :key="idx"
            class="management-row"
          >
            <input
              v-model="sales.nama"
              type="text"
              placeholder="Nama"
            />
            <input
              v-model="sales.jabatan"
              type="text"
              placeholder="Jabatan"
            />
            <input
              v-model="sales.telepon"
              type="text"
              placeholder="Telepon"
            />
            <input
              v-model="sales.email"
              type="email"
              placeholder="Email"
            />
            <button
              @click="removeSalesMarketing(idx)"
              type="button"
              class="btn-delete"
            >
              🗑️
            </button>
          </div>
          <button
            @click="addSalesMarketing"
            type="button"
            class="btn btn-sm btn-add"
          >
            <i class="fas fa-plus"></i>
            Add Sales/Marketing
          </button>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button
        @click="saveGeneral"
        :disabled="loading"
        class="btn btn-primary"
      >
        <i class="fas fa-save"></i>
        {{ loading ? "Saving..." : "Save General Information" }}
      </button>
    </div>

    <AlertComponent
      v-if="alertMsg"
      :type="alertType"
      :message="alertMsg"
      @close="alertMsg = ''"
    />
  </div>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import axios from "axios";
import AlertComponent from "../AlertComponent.vue";

const props = defineProps({
  vendor: Object,
});

const emit = defineEmits(["updated"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const formData = reactive({
  npwp: "",
  tanggal_berdiri: "",
  tanggal_beroperasi: "",
  phone: "",
  alamat: "",
  kode_pos: "",
  website: "",
  alamat_kantor_operasional: "",
  contact_person: [],
  top_level: [],
  mid_level: [],
  sales_marketing: [],
  kepemilikan: "", // Field baru untuk possession
  kepemilikan_armada: "", // Field baru untuk fleet ownership status
});

watch(
    () => props.vendor,
    (newVendor) => {
        if (newVendor) {
            initializeFormData();
        }
    },
    { immediate: true, deep: true }
);

watch(
  () => formData.kepemilikan,
  (newValue) => {
    // Reset kepemilikan_armada jika kepemilikan berubah ke "tidak"
    if (newValue === "tidak") {
      formData.kepemilikan_armada = "";
    }
  }
);

function initializeFormData() {
  if (!props.vendor) return;
  formData.npwp = props.vendor.npwp || "";
  formData.tanggal_berdiri = props.vendor.tanggal_berdiri || "";
  formData.tanggal_beroperasi = props.vendor.tanggal_beroperasi || "";
  formData.phone = props.vendor.phone || "";
  formData.alamat = props.vendor.alamat || "";
  formData.kode_pos = props.vendor.kode_pos || "";
  formData.website = props.vendor.website || "";
  formData.alamat_kantor_operasional = props.vendor.alamat_kantor_operasional || "";
  formData.contact_person = props.vendor.contact_person || [];
  formData.top_level = props.vendor.top_level || [];
  formData.mid_level = props.vendor.mid_level || [];
  formData.sales_marketing = props.vendor.sales_marketing || [];
  formData.kepemilikan = props.vendor.kepemilikan || "";
  formData.kepemilikan_armada = props.vendor.kepemilikan_armada || "";
}

// Array handler functions
function addContactPerson() {
  formData.contact_person.push({
    nama: "",
    jabatan: "",
    telepon: "",
    email: ""
  });
}
function removeContactPerson(idx) {
  formData.contact_person.splice(idx, 1);
}
function addTopLevel() {
  formData.top_level.push({ nama: "", jabatan: "", telepon: "", email: "" });
}
function removeTopLevel(idx) {
  formData.top_level.splice(idx, 1);
}
function addMidLevel() {
  formData.mid_level.push({ nama: "", jabatan: "", telepon: "", email: "" });
}
function removeMidLevel(idx) {
  formData.mid_level.splice(idx, 1);
}
function addSalesMarketing() {
  formData.sales_marketing.push({
    nama: "",
    jabatan: "",
    telepon: "",
    email: ""
  });
}
function removeSalesMarketing(idx) {
  formData.sales_marketing.splice(idx, 1);
}

async function saveGeneral() {
  loading.value = true;
  try {
    const token = localStorage.getItem("token");
    const response = await axios.put("/api/vendor/profile", formData, {
      headers: { Authorization: `Bearer ${token}` }
    });

    if (response.data.success) {
      showAlert("success", "General information saved successfully!");
      emit("updated", response.data.data.vendor); 
    } else {
      showAlert(
        "error",
        response.data.message || "Failed to save general information"
      );
    }
  } catch (error) {
    showAlert(
      "error",
      error.response?.data?.message ||
        "Failed to save general information"
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
.forwarder-general {
  max-width: 800px;
}

.management-row,
.contact-person-row {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  align-items: center;
}

.tab-header {
  margin-bottom: 2rem;
}

.tab-header h3 {
  color: #212529;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.tab-header p {
  color: #6c757d;
  margin: 0;
}

.form-grid {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

.section-title {
  color: #007bff;
  font-size: 1.1rem;
  font-weight: 600;
  margin: 1.5rem 0 1rem 0;
  padding: 0.75rem 0;
  border-bottom: 2px solid #e9ecef;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-group input,
.form-group textarea,
.form-group select {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 0.875rem;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.readonly-input {
  background: #f8f9fa !important;
  color: #6c757d !important;
  cursor: not-allowed;
}

/* Radio Group Styles */
.radio-group {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-top: 0.5rem;
}

.radio-option {
  display: flex;
  align-items: center;
}

.radio-option input[type="radio"] {
  margin: 0;
  margin-right: 0.5rem;
  transform: scale(1.2);
}

.radio-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-weight: 500;
  color: #495057;
  padding: 0.5rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  transition: all 0.2s ease;
  background: #f8f9fa;
}

.radio-label:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.radio-option input[type="radio"]:checked + .radio-label {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

.radio-label i {
  font-size: 1rem;
}

.form-actions {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #dee2e6;
}

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary {
  background: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #0056b3;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
}

.btn-add {
  background: #28a745;
  color: white;
  margin-top: 0.5rem;
}

.btn-add:hover {
  background: #218838;
}

.btn-delete {
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 0.5rem;
  cursor: pointer;
  transition: background 0.2s;
  min-width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-delete:hover {
  background: #c82333;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .radio-group {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .management-row,
  .contact-person-row {
    flex-direction: column;
    gap: 0.5rem;
  }
}
</style>