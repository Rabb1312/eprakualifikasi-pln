<template>
  <div class="subcontractor-holding">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-building text-primary me-2"></i>
          Holding Company Management
          <span class="subtitle">Configure holding company information</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Company Information Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-building-flag"></i>
            <h6 class="section-title">Company Information</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Informasi dasar perusahaan induk atau holding company yang mengawasi perusahaan Anda.
            </p>
          </div>

          <div class="company-info-grid">
            <div class="info-card">
              <div class="info-card-header">
                <i class="fas fa-id-card"></i>
                <h6>Basic Company Details</h6>
              </div>
              <div class="info-card-body">
                <div class="form-grid">
                  <div class="form-group">
                    <label for="holding_nama_perusahaan" class="form-label">
                      <i class="fas fa-building me-1"></i>
                      Nama Perusahaan Induk
                    </label>
                    <input
                      id="holding_nama_perusahaan"
                      v-model="formData.holding_nama_perusahaan"
                      type="text"
                      class="form-control"
                      placeholder="Contoh: PT Holding Corporation Indonesia"
                    />
                  </div>

                  <div class="form-group">
                    <label for="holding_tanggal_berdiri" class="form-label">
                      <i class="fas fa-calendar-alt me-1"></i>
                      Tanggal Berdiri
                    </label>
                    <input
                      id="holding_tanggal_berdiri"
                      v-model="formData.holding_tanggal_berdiri"
                      type="date"
                      class="form-control"
                    />
                  </div>

                  <div class="form-group full-width">
                    <label for="holding_alamat" class="form-label">
                      <i class="fas fa-map-marker-alt me-1"></i>
                      Alamat Lengkap
                    </label>
                    <textarea
                      id="holding_alamat"
                      v-model="formData.holding_alamat"
                      rows="3"
                      class="form-control"
                      placeholder="Alamat lengkap kantor pusat perusahaan holding"
                    ></textarea>
                  </div>

                  <div class="form-group">
                    <label for="holding_phone" class="form-label">
                      <i class="fas fa-phone me-1"></i>
                      Nomor Telepon
                    </label>
                    <input
                      id="holding_phone"
                      v-model="formData.holding_phone"
                      type="tel"
                      class="form-control"
                      placeholder="+62 21 xxxx xxxx"
                    />
                  </div>

                  <div class="form-group">
                    <label for="holding_nama_direktur" class="form-label">
                      <i class="fas fa-user-tie me-1"></i>
                      Nama Direktur Utama
                    </label>
                    <input
                      id="holding_nama_direktur"
                      v-model="formData.holding_nama_direktur"
                      type="text"
                      class="form-control"
                      placeholder="Nama direktur utama perusahaan"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Financial Information Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-chart-pie"></i>
            <h6 class="section-title">Financial Information</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Informasi keuangan dan struktur kepemilikan perusahaan holding.
            </p>
          </div>

          <div class="financial-grid">
            <div class="financial-card">
              <div class="financial-card-header">
                <i class="fas fa-money-bill-wave"></i>
                <h6>Capital Structure</h6>
              </div>
              <div class="financial-card-body">
                <div class="form-grid">
                  <div class="form-group">
                    <label for="holding_modal_dasar" class="form-label">
                      <i class="fas fa-coins me-1"></i>
                      Modal Dasar
                    </label>
                    <div class="currency-input">
                      <span class="currency-prefix">Rp</span>
                      <input
                        id="holding_modal_dasar"
                        v-model="formData.holding_modal_dasar"
                        type="number"
                        class="form-control currency-field"
                        placeholder="0"
                        step="0.01"
                      />
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="holding_modal_dikeluarkan" class="form-label">
                      <i class="fas fa-hand-holding-usd me-1"></i>
                      Modal Dikeluarkan
                    </label>
                    <div class="currency-input">
                      <span class="currency-prefix">Rp</span>
                      <input
                        id="holding_modal_dikeluarkan"
                        v-model="formData.holding_modal_dikeluarkan"
                        type="number"
                        class="form-control currency-field"
                        placeholder="0"
                        step="0.01"
                      />
                    </div>
                  </div>

                  <div class="form-group full-width">
                    <label for="holding_pemegang_saham" class="form-label">
                      <i class="fas fa-users me-1"></i>
                      Struktur Pemegang Saham
                    </label>
                    <textarea
                      id="holding_pemegang_saham"
                      v-model="formData.holding_pemegang_saham"
                      rows="4"
                      class="form-control"
                      placeholder="Daftar pemegang saham dan persentase kepemilikan, contoh:
• PT XYZ Corporation - 60%
• PT ABC Holdings - 25%
• Investor Individu - 15%"
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Persons Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-address-book"></i>
            <h6 class="section-title">Contact Persons</h6>
            <div class="section-actions">
              <button @click="addContact" class="btn btn-add">
                <i class="fas fa-plus me-1"></i>
                Tambah Kontak
              </button>
            </div>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Daftar kontak person yang dapat dihubungi terkait perusahaan holding.
            </p>
          </div>

          <div v-if="contactPersons.length === 0" class="empty-state">
            <i class="fas fa-address-book"></i>
            <p>Belum ada contact person yang ditambahkan</p>
            <small>Klik "Tambah Kontak" untuk menambahkan kontak baru</small>
          </div>

          <div v-else class="contacts-grid">
            <div
              v-for="(contact, index) in contactPersons"
              :key="'contact_' + index"
              class="contact-card"
            >
              <div class="contact-card-header">
                <div class="contact-card-title">
                  <i class="fas fa-user me-2"></i>
                  <span>Contact Person {{ index + 1 }}</span>
                </div>
                <button
                  v-if="contactPersons.length > 1"
                  @click="removeContact(index)"
                  class="btn btn-delete-small"
                  title="Hapus Kontak"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </div>

              <div class="contact-card-body">
                <div class="form-grid">
                  <div class="form-group">
                    <label :for="'contact_nama_' + index" class="form-label">
                      <i class="fas fa-user me-1"></i>
                      Nama Lengkap
                    </label>
                    <input
                      :id="'contact_nama_' + index"
                      v-model="contact.nama"
                      type="text"
                      class="form-control"
                      placeholder="Nama lengkap kontak person"
                    />
                  </div>

                  <div class="form-group">
                    <label :for="'contact_jabatan_' + index" class="form-label">
                      <i class="fas fa-briefcase me-1"></i>
                      Jabatan
                    </label>
                    <input
                      :id="'contact_jabatan_' + index"
                      v-model="contact.jabatan"
                      type="text"
                      class="form-control"
                      placeholder="Jabatan dalam perusahaan"
                    />
                  </div>

                  <div class="form-group">
                    <label :for="'contact_telepon_' + index" class="form-label">
                      <i class="fas fa-phone me-1"></i>
                      Nomor Telepon
                    </label>
                    <input
                      :id="'contact_telepon_' + index"
                      v-model="contact.telepon"
                      type="tel"
                      class="form-control"
                      placeholder="+62 xxx xxxx xxxx"
                    />
                  </div>

                  <div class="form-group">
                    <label :for="'contact_email_' + index" class="form-label">
                      <i class="fas fa-envelope me-1"></i>
                      Email
                    </label>
                    <input
                      :id="'contact_email_' + index"
                      v-model="contact.email"
                      type="email"
                      class="form-control"
                      placeholder="email@perusahaan.com"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button
            @click="saveHolding"
            :disabled="loading"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            {{ loading ? "Menyimpan..." : "Simpan Informasi Holding Company" }}
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
});

const emit = defineEmits(["updated"]);

const loading = ref(false);
const alertMsg = ref("");
const alertType = ref("success");

const formData = reactive({
  holding_nama_perusahaan: "",
  holding_tanggal_berdiri: "",
  holding_alamat: "",
  holding_phone: "",
  holding_modal_dasar: "",
  holding_modal_dikeluarkan: "",
  holding_pemegang_saham: "",
  holding_nama_direktur: "",
});

const contactPersons = ref([{ nama: "", jabatan: "", telepon: "", email: "" }]);

watch(
  () => props.subcontractorData,
  (newData) => {
    if (newData) {
      initializeFormData();
    }
  },
  { immediate: true, deep: true }
);

function initializeFormData() {
  if (!props.subcontractorData) return;

  const data = props.subcontractorData;

  formData.holding_nama_perusahaan = data.holding_nama_perusahaan || "";
  formData.holding_tanggal_berdiri = data.holding_tanggal_berdiri || "";
  formData.holding_alamat = data.holding_alamat || "";
  formData.holding_phone = data.holding_phone || "";
  formData.holding_modal_dasar = data.holding_modal_dasar || "";
  formData.holding_modal_dikeluarkan = data.holding_modal_dikeluarkan || "";
  formData.holding_pemegang_saham = data.holding_pemegang_saham || "";
  formData.holding_nama_direktur = data.holding_nama_direktur || "";

  // Initialize contact persons
  if (
    data.holding_contact_person &&
    Array.isArray(data.holding_contact_person)
  ) {
    contactPersons.value = data.holding_contact_person.map((contact) => ({
      nama: contact.nama || "",
      jabatan: contact.jabatan || "",
      telepon: contact.telepon || "",
      email: contact.email || "",
    }));
  }

  // Ensure at least one contact person
  if (contactPersons.value.length === 0) {
    contactPersons.value = [
      { nama: "", jabatan: "", telepon: "", email: "" },
    ];
  }
}

function addContact() {
  contactPersons.value.push({
    nama: "",
    jabatan: "",
    telepon: "",
    email: "",
  });
}

function removeContact(index) {
  if (contactPersons.value.length > 1) {
    contactPersons.value.splice(index, 1);
  }
}

async function saveHolding() {
  loading.value = true;

  try {
    const dataToSave = {
      ...formData,
      holding_contact_person: contactPersons.value,
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
      showAlert(
        "success",
        "Informasi holding company berhasil disimpan!"
      );
      emit("updated", response.data.data.subcontractor_data);
    } else {
      showAlert(
        "error",
        response.data.message ||
          "Gagal menyimpan informasi holding company"
      );
    }
  } catch (error) {
    console.error("Save holding error:", error);
    showAlert(
      "error",
      error.response?.data?.message ||
        "Gagal menyimpan informasi holding company"
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
.subcontractor-holding {
  max-width: 1200px;
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

.company-info-grid,
.financial-grid {
  display: grid;
  gap: 1.5rem;
}

.info-card,
.financial-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.info-card::before,
.financial-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.info-card:hover::before,
.financial-card:hover::before {
  left: 100%;
}

.info-card:hover,
.financial-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.info-card-header,
.financial-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  border-bottom: 1px solid #dee2e6;
}

.info-card-header i,
.financial-card-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.info-card-header h6,
.financial-card-header h6 {
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.info-card-body,
.financial-card-body {
  padding: 1.5rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

.form-group {
  position: relative;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
}

.form-label i {
  color: #007bff;
  font-size: 0.9rem;
}

.form-control {
  padding: 0.875rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #f8f9fa;
  width: 100%;
}

.form-control:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.currency-input {
  position: relative;
  display: flex;
  align-items: center;
}

.currency-prefix {
  position: absolute;
  left: 1rem;
  color: #6c757d;
  font-weight: 600;
  z-index: 1;
  font-size: 0.9rem;
}

.currency-field {
  padding-left: 3rem;
}

.empty-state {
  text-align: center;
  padding: 3rem 2rem;
  color: #6c757d;
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border-radius: 12px;
  border: 2px dashed #dee2e6;
}

.empty-state i {
  font-size: 3rem;
  color: #dee2e6;
  margin-bottom: 1rem;
}

.empty-state p {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.empty-state small {
  font-size: 0.9rem;
  color: #8e9ba8;
}

.contacts-grid {
  display: grid;
  gap: 1.5rem;
}

.contact-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.contact-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.contact-card:hover::before {
  left: 100%;
}

.contact-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.contact-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.contact-card-title {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
}

.contact-card-title i {
  color: #007bff;
}

.contact-card-body {
  padding: 1.5rem;
}

.btn {
  padding: 0.75rem 1.5rem;
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

.btn-add {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.btn-delete-small {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
  padding: 0.5rem;
  min-width: auto;
  font-size: 0.8rem;
}

.btn-delete-small:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
  min-width: 280px;
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
  .subcontractor-holding {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
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
  
  .form-grid {
    grid-template-columns: 1fr;
  }
}
</style>