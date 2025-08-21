<template>
  <div class="forwarder-documents">
    <div class="tab-header">
      <h3>
        <i class="fas fa-file-alt"></i>
        Attachment/Documents
      </h3>
      <p>
        Document checklist and submission status for forwarder qualification.<br>
        Daftar dokumen dan status pengajuan untuk kualifikasi forwarder.
      </p>
    </div>
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
      <p>Loading documents...</p>
    </div>
    <div v-else class="documents-section">
      <div class="summary-cards">
        <div class="summary-card total">
          <div class="card-icon"><i class="fas fa-file-alt"></i></div>
          <div class="card-content">
            <h4>{{ stats.total || 0 }}</h4>
            <p>Total Documents</p>
          </div>
        </div>
        <div class="summary-card required">
          <div class="card-icon"><i class="fas fa-exclamation-circle"></i></div>
          <div class="card-content">
            <h4>{{ stats.required || 0 }}</h4>
            <p>Required Documents</p>
          </div>
        </div>
        <div class="summary-card submitted">
          <div class="card-icon"><i class="fas fa-check-circle"></i></div>
          <div class="card-content">
            <h4>{{ stats.approved || 0 }}</h4>
            <p>Approved Documents</p>
          </div>
        </div>
        <div class="summary-card completion">
          <div class="card-icon"><i class="fas fa-chart-pie"></i></div>
          <div class="card-content">
            <h4>{{ completionPercentage }}%</h4>
            <p>Completion Rate</p>
          </div>
        </div>
      </div>
      <div v-if="Object.keys(attachments).length > 0" class="attachment-tabs">
        <div class="tabs-header">
          <button
            v-for="(attachment, key) in attachments"
            :key="key"
            @click="activeTab = key"
            :class="['tab-btn', { active: activeTab === key }]"
          >
            {{ attachment.title }}
            <span class="tab-count">({{ attachment.documents?.length || 0 }})</span>
          </button>
        </div>
        <div class="tab-content">
          <div v-if="attachments[activeTab]" class="attachment-content">
            <div class="table-header">
              <h4>
                {{ attachments[activeTab].title }} - Document Upload & Review
              </h4>
              <div class="table-actions">
                <button @click="refreshDocuments" class="btn btn-outline btn-sm">
                  <i class="fas fa-sync-alt"></i>
                  Refresh
                </button>
              </div>
            </div>
            <div class="table-container">
              <div v-if="attachments[activeTab]?.grouped_documents && Object.keys(attachments[activeTab].grouped_documents).length > 0">
                <div
                  v-for="(group, groupIndex) in Object.values(attachments[activeTab].grouped_documents)"
                  :key="groupIndex"
                  class="document-group"
                >
                  <div class="group-header">
                    <h5>{{ group.group_number }}. {{ group.group_name }}</h5>
                  </div>
                  <table class="documents-table">
                    <thead>
                      <tr>
                        <th class="no-col">No</th>
                        <th class="document-col">Document Name</th>
                        <th class="status-col">Status</th>
                        <th class="expiry-col">Expiry Date</th>
                        <th class="file-col">Files</th>
                        <th class="actions-col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(doc, index) in group.documents"
                        :key="doc.id || index"
                        :class="getRowClass(doc)"
                      >
                        <td class="text-center">{{ doc.document_number }}</td>
                        <td class="document-name">
                          <div class="document-info">
                            <span class="document-title">{{ doc.document_name }}</span>
                            <span v-if="doc.document_subtitle" class="document-subtitle">{{ doc.document_subtitle }}</span>
                            <span v-if="doc.is_required" class="required-badge">Required</span>
                            <span v-if="doc.allows_multiple" class="multiple-badge">Multiple Files</span>
                            <span v-if="doc.is_expired" class="expired-badge">Expired</span>
                            <span v-if="doc.is_expiring_soon && !doc.is_expired" class="expiring-badge">Expiring Soon</span>
                          </div>
                          <div v-if="doc.admin_notes" class="admin-notes">
                            <i class="fas fa-comment"></i>
                            <span>{{ doc.admin_notes }}</span>
                          </div>
                          <div v-if="doc.rejection_reason" class="rejection-reason">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>{{ doc.rejection_reason }}</span>
                          </div>
                        </td>
                        <td class="text-center">
                          <span :class="`status-badge status-${doc.status_badge?.class || 'secondary'}`">
                            <i :class="doc.status_badge?.icon || 'fa-upload'"></i>
                            {{ doc.status_badge?.text || 'Not Uploaded' }}
                          </span>
                        </td>
                        <td class="text-center">
                          <span v-if="doc.expiry_date" class="expiry-date">
                            {{ formatDate(doc.expiry_date) }}
                          </span>
                          <span v-else class="no-expiry">No Expiry</span>
                        </td>
                        <td class="file-display">
                          <div v-if="doc.allows_multiple && doc.all_files?.length" class="multiple-files">
                            <div class="file-count-badge">
                              <i class="fas fa-files"></i>
                              {{ doc.file_count }} file(s)
                            </div>
                            <div class="files-list">
                              <div v-for="file in doc.all_files" :key="file.id" class="file-item">
                                <div class="file-info">
                                  <div class="file-details">
                                    <span class="file-name" :title="file.file_name">{{ truncateFileName(file.file_name) }}</span>
                                    <span class="file-size">{{ file.file_size }}</span>
                                    <span class="file-version">v{{ file.version }}</span>
                                  </div>
                                  <div class="file-status">
                                    <span :class="`status-dot status-${file.status_badge?.class || 'secondary'}`" :title="file.status_badge?.text"></span>
                                  </div>
                                </div>
                                <div class="file-actions">
                                  <button @click="downloadDocument(file)" class="btn btn-xs btn-outline" title="Download">
                                    <i class="fas fa-download"></i>
                                  </button>
                                  <button v-if="file.status !== 'approved'" @click="deleteSpecificFile(file)" class="btn btn-xs btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div v-else-if="!doc.allows_multiple && doc.file_name" class="single-file">
                            <div class="file-info">
                              <div class="file-details">
                                <span class="file-name" :title="doc.file_name">{{ truncateFileName(doc.file_name) }}</span>
                                <span class="file-size">{{ doc.file_size }}</span>
                                <span class="file-version">v{{ doc.version }}</span>
                                <span v-if="doc.expiry_date" class="expiry-info">
                                  Exp: {{ formatDate(doc.expiry_date) }}
                                </span>
                              </div>
                            </div>
                            <div class="file-actions">
                              <a v-if="doc.file_url" :href="doc.file_url" target="_blank" rel="noopener" class="btn btn-xs btn-outline" title="View">
                                <i class="fas fa-eye"></i>
                              </a>
                              <button @click="downloadDocument(doc)" class="btn btn-xs btn-outline" title="Download">
                                <i class="fas fa-download"></i>
                              </button>
                            </div>
                          </div>
                          <div v-else-if="doc.allows_multiple" class="no-files-multiple">
                            <div class="file-count-badge">
                              <i class="fas fa-files"></i>
                              0 file(s)
                            </div>
                            <span class="no-file-text">No files uploaded yet</span>
                            <small class="upload-hint">Click Upload to add multiple files</small>
                          </div>
                          <span v-else class="no-file">No file uploaded</span>
                        </td>
                        <td class="text-center">
                          <div class="action-buttons">
                            <a
                              v-if="doc.has_template"
                              :href="doc.template_download_url"
                              target="_blank"
                              class="btn btn-sm btn-info"
                              :title="`Download template: ${doc.template_filename}`"
                            >
                              <i class="fas fa-download"></i>
                              Template
                            </a>
                            <button
                              @click="openUploadModal(doc)"
                              class="btn btn-sm btn-primary"
                              :disabled="uploadingDocuments[doc.id]"
                            >
                              <i class="fas fa-upload"></i>
                              {{ getUploadButtonText(doc) }}
                            </button>
                            <button
                              v-if="hasFiles(doc)"
                              @click="downloadDocument(doc)"
                              class="btn btn-sm btn-outline"
                            >
                              <i class="fas fa-download"></i>
                              Download
                            </button>
                            <button
                              v-if="hasFiles(doc) && canDeleteDocument(doc)"
                              @click="deleteDocument(doc)"
                              class="btn btn-sm btn-danger"
                            >
                              <i class="fas fa-trash"></i>
                              {{ doc.allows_multiple && doc.file_count > 1 ? 'Delete All' : 'Delete' }}
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div v-else>
                <table class="documents-table">
                  <thead>
                    <tr>
                      <th class="no-col">No</th>
                      <th class="document-col">Document Name</th>
                      <th class="status-col">Status</th>
                      <th class="expiry-col">Expiry Date</th>
                      <th class="file-col">Files</th>
                      <th class="actions-col">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(doc, idx) in attachments[activeTab]?.documents || []" :key="doc.id || idx" :class="getRowClass(doc)">
                      <td class="text-center">{{ doc.document_number || idx + 1 }}</td>
                      <td class="document-name">
                        <div class="document-info">
                          <span class="document-title">{{ doc.document_name }}</span>
                          <span v-if="doc.document_subtitle" class="document-subtitle">{{ doc.document_subtitle }}</span>
                          <span v-if="doc.is_required" class="required-badge">Required</span>
                          <span v-if="doc.allows_multiple" class="multiple-badge">Multiple Files</span>
                          <span v-if="doc.is_expired" class="expired-badge">Expired</span>
                          <span v-if="doc.is_expiring_soon && !doc.is_expired" class="expiring-badge">Expiring Soon</span>
                        </div>
                        <div v-if="doc.admin_notes" class="admin-notes">
                          <i class="fas fa-comment"></i>
                          <span>{{ doc.admin_notes }}</span>
                        </div>
                        <div v-if="doc.rejection_reason" class="rejection-reason">
                          <i class="fas fa-exclamation-triangle"></i>
                          <span>{{ doc.rejection_reason }}</span>
                        </div>
                      </td>
                      <td class="text-center">
                        <span :class="`status-badge status-${doc.status_badge?.class || 'secondary'}`">
                          <i :class="doc.status_badge?.icon || 'fa-upload'"></i>
                          {{ doc.status_badge?.text || 'Not Uploaded' }}
                        </span>
                      </td>
                      <td class="text-center">
                        <span v-if="doc.expiry_date" class="expiry-date">
                          {{ formatDate(doc.expiry_date) }}
                        </span>
                        <span v-else class="no-expiry">No Expiry</span>
                      </td>
                      <td class="file-display">
                        <div v-if="doc.allows_multiple && doc.all_files?.length" class="multiple-files">
                          <div class="file-count-badge">
                            <i class="fas fa-files"></i>
                            {{ doc.file_count }} file(s)
                          </div>
                          <div class="files-list">
                            <div v-for="file in doc.all_files" :key="file.id" class="file-item">
                              <div class="file-info">
                                <div class="file-details">
                                  <span class="file-name" :title="file.file_name">{{ truncateFileName(file.file_name) }}</span>
                                  <span class="file-size">{{ file.file_size }}</span>
                                  <span class="file-version">v{{ file.version }}</span>
                                </div>
                                <div class="file-status">
                                  <span :class="`status-dot status-${file.status_badge?.class || 'secondary'}`" :title="file.status_badge?.text"></span>
                                </div>
                              </div>
                              <div class="file-actions">
                                <button @click="downloadDocument(file)" class="btn btn-xs btn-outline" title="Download">
                                  <i class="fas fa-download"></i>
                                </button>
                                <button v-if="file.status !== 'approved'" @click="deleteSpecificFile(file)" class="btn btn-xs btn-danger" title="Delete">
                                  <i class="fas fa-trash"></i>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div v-else-if="!doc.allows_multiple && doc.file_name" class="single-file">
                          <div class="file-info">
                            <div class="file-details">
                              <span class="file-name" :title="doc.file_name">{{ truncateFileName(doc.file_name) }}</span>
                              <span class="file-size">{{ doc.file_size }}</span>
                              <span class="file-version">v{{ doc.version }}</span>
                              <span v-if="doc.expiry_date" class="expiry-info">
                                Exp: {{ formatDate(doc.expiry_date) }}
                              </span>
                            </div>
                          </div>
                          <div class="file-actions">
                            <a v-if="doc.file_url" :href="doc.file_url" target="_blank" rel="noopener" class="btn btn-xs btn-outline" title="View">
                              <i class="fas fa-eye"></i>
                            </a>
                            <button @click="downloadDocument(doc)" class="btn btn-xs btn-outline" title="Download">
                              <i class="fas fa-download"></i>
                            </button>
                          </div>
                        </div>
                        <div v-else-if="doc.allows_multiple" class="no-files-multiple">
                          <div class="file-count-badge">
                            <i class="fas fa-files"></i>
                            0 file(s)
                          </div>
                          <span class="no-file-text">No files uploaded yet</span>
                          <small class="upload-hint">Click Upload to add multiple files</small>
                        </div>
                        <span v-else class="no-file">No file uploaded</span>
                      </td>
                      <td class="text-center">
                        <div class="action-buttons">
                          <a
                            v-if="doc.has_template"
                            :href="doc.template_download_url"
                            target="_blank"
                            class="btn btn-sm btn-info"
                            :title="`Download template: ${doc.template_filename}`"
                          >
                            <i class="fas fa-download"></i>
                            Template
                          </a>
                          <button
                            @click="openUploadModal(doc)"
                            class="btn btn-sm btn-primary"
                            :disabled="uploadingDocuments[doc.id]"
                          >
                            <i class="fas fa-upload"></i>
                            {{ getUploadButtonText(doc) }}
                          </button>
                          <button
                            v-if="hasFiles(doc)"
                            @click="downloadDocument(doc)"
                            class="btn btn-sm btn-outline"
                          >
                            <i class="fas fa-download"></i>
                            Download
                          </button>
                          <button
                            v-if="hasFiles(doc) && canDeleteDocument(doc)"
                            @click="deleteDocument(doc)"
                            class="btn btn-sm btn-danger"
                          >
                            <i class="fas fa-trash"></i>
                            {{ doc.allows_multiple && doc.file_count > 1 ? 'Delete All' : 'Delete' }}
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="no-data-container">
        <div class="no-data-content">
          <i class="fas fa-file-alt no-data-icon"></i>
          <h4>No Documents Found</h4>
          <p>Document templates are being initialized. Please refresh the page.</p>
          <button @click="refreshDocuments" class="btn btn-primary">
            <i class="fas fa-sync-alt"></i>
            Refresh Documents
          </button>
        </div>
      </div>
      <div v-if="showUploadModal" class="modal-overlay" @click="closeUploadModal">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h4>
              <i class="fas fa-upload"></i>
              Upload Document{{ selectedDocument?.allows_multiple ? 's' : '' }}
            </h4>
            <button @click="closeUploadModal" class="modal-close">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="document-info-modal">
              <h5>{{ selectedDocument?.document_name }}</h5>
              <div v-if="selectedDocument?.has_template" class="template-section">
                <div class="template-info">
                  <i class="fas fa-file-excel text-success"></i>
                  <span>Template tersedia - Anda dapat upload PDF atau Excel</span>
                </div>
                <a
                  :href="selectedDocument.template_download_url"
                  target="_blank"
                  class="btn btn-sm btn-success template-download-btn"
                >
                  <i class="fas fa-download"></i>
                  Download Template: {{ selectedDocument.template_filename }}
                </a>
                <small class="template-help">
                  Untuk Excel: Download template, isi sesuai format, lalu upload. <br/>
                  Untuk PDF: Upload dokumen PDF langsung.
                </small>
              </div>
              <div v-else class="pdf-only-notice">
                <div class="pdf-info">
                  <i class="fas fa-file-pdf text-danger"></i>
                  <span>Dokumen ini hanya menerima format PDF</span>
                </div>
                <small class="pdf-help">
                  Pastikan dokumen Anda dalam format PDF sebelum upload.
                </small>
              </div>
              <div class="document-badges">
                <span v-if="selectedDocument?.is_required" class="required-text">
                  <i class="fas fa-exclamation-circle"></i>
                  Required Document
                </span>
                <span v-if="selectedDocument?.allows_multiple" class="multiple-text">
                  <i class="fas fa-files"></i>
                  Multiple Files Allowed (Max {{ maxFiles }})
                </span>
                <span v-if="selectedDocument?.has_template" class="template-text">
                  <i class="fas fa-file-excel"></i>
                  PDF/Excel Accepted
                </span>
                <span v-else class="pdf-only-text">
                  <i class="fas fa-file-pdf"></i>
                  PDF Only
                </span>
              </div>
            </div>
            <div class="upload-form">
              <div class="form-group">
                <label>Select File{{ selectedDocument?.allows_multiple ? 's' : '' }}:</label>
                <div class="file-input-container">
                  <input
                    ref="modalFileInput"
                    type="file"
                    :multiple="selectedDocument?.allows_multiple"
                    :accept="selectedDocument?.has_template ? '.pdf,.xlsx,.xls' : '.pdf'"
                    @change="handleMultipleFileSelection"
                    class="file-input"
                    :class="{ 'has-files': selectedFiles.length > 0 }"
                  />
                  <div class="file-input-overlay">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>
                      {{ selectedDocument?.allows_multiple ? 'Drop files here or click to select' : 'Drop file here or click to select' }}
                    </span>
                  </div>
                </div>
                <div class="file-requirements">
                  <p>
                    <i class="fas fa-info-circle"></i>
                    <span v-if="selectedDocument?.has_template">
                      Supported: PDF, Excel (XLSX, XLS) (Max 10MB each)
                    </span>
                    <span v-else>
                      Supported: PDF only (Max 10MB each)
                    </span>
                    <span v-if="selectedDocument?.allows_multiple">
                      | Maximum {{ maxFiles }} files
                    </span>
                  </p>
                </div>
              </div>
              <div v-if="selectedDocument?.has_expiry_date" class="form-group">
                <label>
                  <i class="fas fa-calendar-alt"></i>
                  Expiry Date:
                </label>
                <input
                  v-model="uploadForm.expiry_date"
                  type="date"
                  :min="getTomorrowDate()"
                  class="date-input"
                />
                <small class="form-help">
                  This expiry date will be applied to {{ selectedDocument?.allows_multiple ? 'all selected files' : 'the selected file' }}
                </small>
              </div>
              <div v-if="selectedFiles.length" class="selected-files">
                <h6>
                  <i class="fas fa-list"></i>
                  Selected Files ({{ selectedFiles.length }}/{{ selectedDocument?.allows_multiple ? maxFiles : 1 }})
                </h6>
                <div class="files-preview">
                  <div
                    v-for="(file, index) in selectedFiles"
                    :key="index"
                    class="file-preview-item"
                  >
                    <div class="file-preview">
                      <i :class="getFileIcon(file.type)"></i>
                      <div class="file-details">
                        <span class="file-name" :title="file.name">{{ truncateFileName(file.name) }}</span>
                        <span class="file-size">{{ formatFileSize(file.size) }}</span>
                        <span class="file-type">{{ file.type.split('/')[1].toUpperCase() }}</span>
                      </div>
                    </div>
                    <button @click="removeFile(index)" class="remove-file-btn" title="Remove file">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div v-if="uploadProgress.show" class="upload-progress">
                  <div class="progress-header">
                    <span>Uploading {{ uploadProgress.current }}/{{ uploadProgress.total }} files...</span>
                    <span>{{ uploadProgress.percentage }}%</span>
                  </div>
                  <div class="progress-bar">
                    <div class="progress-fill" :style="{ width: uploadProgress.percentage + '%' }"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button @click="closeUploadModal" class="btn btn-outline">
              <i class="fas fa-times"></i>
              Cancel
            </button>
            <button
              @click="uploadDocumentFiles"
              :disabled="!selectedFiles.length || uploadingDocuments[selectedDocument?.id]"
              class="btn btn-primary"
            >
              <i class="fas fa-upload"></i>
              {{ uploadingDocuments[selectedDocument?.id] ? 'Uploading...' : `Upload ${selectedFiles.length} File${selectedFiles.length > 1 ? 's' : ''}` }}
            </button>
          </div>
        </div>
      </div>
      <div class="requirements-section">
        <h4>
          <i class="fas fa-info-circle"></i>
          Document Requirements & Guidelines
        </h4>
        <div class="requirements-grid">
          <div class="requirement-item">
            <h5>
              <i class="fas fa-file-pdf"></i> Format Requirements
            </h5>
            <ul>
              <li><strong>Default:</strong> PDF format only (max 10MB per file)</li>
              <li><strong>Template documents:</strong> PDF or Excel (XLSX, XLS) allowed</li>
              <li>Template documents are marked with <span class="template-badge-small">Template Available</span> badge</li>
              <li>Use provided Excel templates for structured data documents</li>
              <li>Multiple files allowed for certain document types</li>
              <li>Ensure documents are clear and readable</li>
            </ul>
          </div>
          <div class="requirement-item">
            <h5>
              <i class="fas fa-shield-alt"></i> Security & Compliance
            </h5>
            <ul>
              <li>All documents must be valid and current</li>
              <li>Scanned copies must be clear and complete</li>
              <li>Official seals and signatures must be visible</li>
              <li>Documents will be verified for authenticity</li>
              <li>Each file is reviewed individually</li>
            </ul>
          </div>
          <div class="requirement-item">
            <h5><i class="fas fa-clock"></i> Review Process</h5>
            <ul>
              <li>Documents are reviewed individually by admin</li>
              <li>Approved documents get green checkmark</li>
              <li>Rejected documents need to be re-uploaded</li>
              <li>Check admin notes for feedback</li>
              <li>Multiple files can have different statuses</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <AlertComponent v-if="alertMsg" :type="alertType" :message="alertMsg" @close="alertMsg = ''" />
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import axios from 'axios'
import AlertComponent from '../AlertComponent.vue'

const loading = ref(true)
const attachments = ref({})
const activeTab = ref('attachment_1')
const alertMsg = ref('')
const alertType = ref('success')
const showUploadModal = ref(false)
const selectedDocument = ref(null)
const selectedFiles = ref([])
const maxFiles = ref(5)
const uploadingDocuments = ref({})
const modalFileInput = ref(null)
const stats = ref({})

const uploadProgress = reactive({
  show: false,
  current: 0,
  total: 0,
  percentage: 0
})

const uploadForm = reactive({
  expiry_date: ''
})

const API_BASE_URL = "http://eprakualifikasi-pln.test"

const completionPercentage = computed(() => {
  if (!stats.value.total || stats.value.total === 0) return 0
  return Math.round((stats.value.approved / stats.value.total) * 100)
})

onMounted(async () => {
  await loadDocuments()
})

async function loadDocuments() {
  loading.value = true
  resetAllUploadStates()
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      showAlert('error', 'Authentication required')
      return
    }
    const response = await axios.get(`${API_BASE_URL}/api/vendor/documents?t=${Date.now()}`, {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
        'Cache-Control': 'no-cache'
      }
    })
    if (response.data.success) {
      attachments.value = response.data.data.attachments || {}
      stats.value = response.data.data.stats || {}
    } else {
      showAlert('error', response.data.message || 'Failed to load documents')
    }
  } catch (error) {
    handleApiError(error)
  } finally {
    loading.value = false
  }
}

async function refreshDocuments() {
  await loadDocuments()
  showAlert('info', 'Documents refreshed')
}

function openUploadModal(document) {
  resetAllUploadStates()
  selectedDocument.value = document
  selectedFiles.value = []
  uploadForm.expiry_date = document.expiry_date || ''
  showUploadModal.value = true
  if (modalFileInput.value) {
    modalFileInput.value.value = ''
  }
}

function closeUploadModal() {
  showUploadModal.value = false
  selectedDocument.value = null
  selectedFiles.value = []
  uploadForm.expiry_date = ''
  uploadProgress.show = false
  uploadProgress.current = 0
  uploadProgress.total = 0
  uploadProgress.percentage = 0
  uploadingDocuments.value = {}
}

function resetAllUploadStates() {
  uploadingDocuments.value = {}
}

function handleMultipleFileSelection(event) {
  const files = Array.from(event.target.files)
  if (selectedDocument.value?.allows_multiple) {
    if (files.length > maxFiles.value) {
      showAlert('error', `Maximum ${maxFiles.value} files allowed per document`)
      return
    }
    const validFiles = files.filter(file => validateFile(file))
    selectedFiles.value = validFiles
    if (validFiles.length !== files.length) {
      showAlert('warning', `${files.length - validFiles.length} file(s) were skipped due to validation errors`)
    }
  } else {
    const file = files[0]
    if (file && validateFile(file)) {
      selectedFiles.value = [file]
    }
  }
}

function validateFile(file) {
  const maxSize = 10 * 1024 * 1024 // 10MB
  const hasTemplate = selectedDocument.value?.has_template || false
  const allowedTypes = hasTemplate
    ? [
        'application/pdf',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
      ]
    : [
        'application/pdf'
      ]
  if (file.size > maxSize) {
    showAlert('error', `File "${file.name}" is too large. Maximum size is 10MB.`)
    return false
  }
  if (!allowedTypes.includes(file.type)) {
    const allowedFormats = hasTemplate ? 'PDF or Excel (XLSX, XLS)' : 'PDF only'
    showAlert('error', `File "${file.name}" has unsupported format. Only ${allowedFormats} files are allowed for this document.`)
    return false
  }
  return true
}

function removeFile(index) {
  selectedFiles.value.splice(index, 1)
}

async function uploadDocumentFiles() {
  if (!selectedFiles.value.length || !selectedDocument.value) return
  const documentIdentifier = selectedDocument.value.id || selectedDocument.value.document_type
  uploadingDocuments.value[documentIdentifier] = true
  uploadProgress.show = true
  uploadProgress.current = 0
  uploadProgress.total = selectedFiles.value.length
  uploadProgress.percentage = 0
  try {
    const formData = new FormData()
    if (selectedDocument.value.id) {
      formData.append('document_id', selectedDocument.value.id)
    } else {
      formData.append('document_type', selectedDocument.value.document_type)
    }
    selectedFiles.value.forEach((file, index) => {
      formData.append('files[]', file)
      if (selectedDocument.value.has_expiry_date && uploadForm.expiry_date) {
        formData.append(`expiry_dates[${index}]`, uploadForm.expiry_date)
      }
    })
    const token = localStorage.getItem('token')
    const response = await axios.post(`${API_BASE_URL}/api/vendor/documents/upload`, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: (progressEvent) => {
        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        uploadProgress.percentage = percentCompleted
      }
    })
    if (response.data.success) {
      const fileCount = selectedFiles.value.length
      showAlert('success', `${fileCount} file(s) uploaded successfully and submitted for review!`)
      closeUploadModal()
      await loadDocuments()
    } else {
      showAlert('error', response.data.message || 'Failed to upload files')
    }
  } catch (error) {
    showAlert('error', error.response?.data?.message || 'Failed to upload files')
  } finally {
    uploadingDocuments.value[documentIdentifier] = false
    uploadProgress.show = false
    uploadingDocuments.value = { ...uploadingDocuments.value }
  }
}

async function downloadDocument(doc) {
  try {
    const token = localStorage.getItem('token')
    const response = await axios.get(`${API_BASE_URL}/api/vendor/documents/${doc.id}/download`, {
      headers: { Authorization: `Bearer ${token}` },
      responseType: 'blob'
    })
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', doc.file_name || 'document')
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)
    showAlert('success', 'Document downloaded successfully')
  } catch (error) {
    showAlert('error', 'Failed to download document')
  }
}

async function deleteDocument(document) {
  try {
    const deleteableCount = await getDeleteableFilesCount(document)
    let message
    if (document.allows_multiple) {
      if (deleteableCount > 1) {
        message = `Are you sure you want to delete all ${deleteableCount} files for "${document.document_name}"? This action cannot be undone.`
      } else if (deleteableCount === 1) {
        message = `Are you sure you want to delete the file for "${document.document_name}"?`
      } else {
        showAlert('info', 'No files to delete')
        return
      }
    } else {
      message = `Are you sure you want to delete "${document.document_name}"?`
    }
    if (!confirm(message)) return
    const deleteButtonKey = document.id || document.document_type
    uploadingDocuments.value[deleteButtonKey] = true
    const token = localStorage.getItem('token')
    const response = await axios.delete(`${API_BASE_URL}/api/vendor/documents/${document.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    if (response.data.success) {
      let successMessage = response.data.message || 'Document(s) deleted successfully'
      if (response.data.data?.deleted_count) {
        const count = response.data.data.deleted_count
        successMessage = `Successfully deleted ${count} file${count > 1 ? 's' : ''} for "${document.document_name}"`
      }
      showAlert('success', successMessage)
      await loadDocuments()
    } else {
      showAlert('error', response.data.message || 'Failed to delete document')
    }
  } catch (error) {
    showAlert('error', error.response?.data?.message || 'Failed to delete document')
  } finally {
    const deleteButtonKey = document.id || document.document_type
    uploadingDocuments.value[deleteButtonKey] = false
  }
}

async function getDeleteableFilesCount(document) {
  try {
    if (!document.allows_multiple) {
      return document.file_name ? 1 : 0
    }
    const token = localStorage.getItem('token')
    const response = await axios.get(`${API_BASE_URL}/api/vendor/documents/${document.id}/deleteable-count`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    if (response.data.success) {
      return response.data.data.deleteable_count
    }
    return document.all_files?.filter(file => file.status !== 'approved').length || 0
  } catch (error) {
    if (document.allows_multiple) {
      return document.all_files?.filter(file => file.status !== 'approved').length || 0
    }
    return document.file_name ? 1 : 0
  }
}

async function deleteSpecificFile(file) {
  if (!confirm(`Are you sure you want to delete "${file.file_name}"?`)) return
  try {
    const token = localStorage.getItem('token')
    const response = await axios.delete(`${API_BASE_URL}/api/vendor/documents/${file.id}`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    if (response.data.success) {
      showAlert('success', 'File deleted successfully')
      await loadDocuments()
    } else {
      showAlert('error', response.data.message || 'Failed to delete file')
    }
  } catch (error) {
    showAlert('error', error.response?.data?.message || 'Failed to delete file')
  }
}

function getRowClass(document) {
  return {
    'required-row': document.is_required,
    'approved-row': document.status === 'approved',
    'rejected-row': document.status === 'rejected',
    'expired-row': document.is_expired,
    'expiring-row': document.is_expiring_soon && !document.is_expired,
    'multiple-row': document.allows_multiple
  }
}

function getUploadButtonText(document) {
  if (document.allows_multiple) {
    return document.file_count > 0 ? 'Add More' : 'Upload Files'
  } else {
    return document.file_name ? 'Re-upload' : 'Upload'
  }
}

function hasFiles(document) {
  if (document.allows_multiple) {
    return document.file_count > 0
  } else {
    return document.file_name
  }
}

function canDeleteDocument(document) {
  if (document.allows_multiple) {
    return document.all_files?.some(file => file.status !== 'approved') || false
  } else {
    return document.status !== 'approved'
  }
}

function truncateFileName(fileName, maxLength = 25) {
  if (!fileName || fileName.length <= maxLength) return fileName
  const extension = fileName.split('.').pop()
  const nameWithoutExt = fileName.substring(0, fileName.lastIndexOf('.'))
  const truncated = nameWithoutExt.substring(0, maxLength - extension.length - 4) + '...'
  return truncated + '.' + extension
}

function formatDate(dateString) {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('id-ID')
}

function getTomorrowDate() {
  const tomorrow = new Date()
  tomorrow.setDate(tomorrow.getDate() + 1)
  return tomorrow.toISOString().split('T')[0]
}

function getFileIcon(type) {
  if (type.includes('pdf')) return 'fas fa-file-pdf text-danger'
  if (type.includes('sheet') || type.includes('excel')) return 'fas fa-file-excel text-success'
  return 'fas fa-file text-secondary'
}

function formatFileSize(bytes) {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

function handleApiError(error) {
  if (error.response?.status === 403) {
    showAlert('error', 'Access forbidden. Please check your permissions.')
  } else if (error.response?.status === 404) {
    showAlert('error', 'API endpoint not found. Please check server configuration.')
  } else {
    showAlert('error', error.response?.data?.message || 'An error occurred')
  }
}

function showAlert(type, message) {
  alertType.value = type
  alertMsg.value = message
  setTimeout(() => {
    alertMsg.value = ''
  }, 5000)
}
</script>

<style scoped>
/* ✅ Copy all styles from SubcontractorDocuments.vue */
.distributor-documents {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
}

.tab-header {
  margin-bottom: 2rem;
  text-align: center;
}

.tab-header h3 {
  color: #212529;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-size: 1.5rem;
}

.tab-header p {
  color: #6c757d;
  margin: 0;
  font-style: italic;
  line-height: 1.5;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 300px;
  gap: 1rem;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.documents-section {
  background: #f8f9fa;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}

.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.summary-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: transform 0.2s;
}

.summary-card:hover {
  transform: translateY(-2px);
}

.card-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
}

.summary-card.total .card-icon {
  background: #e9ecef;
  color: #495057;
}

.summary-card.required .card-icon {
  background: #fff3cd;
  color: #856404;
}

.summary-card.submitted .card-icon {
  background: #d4edda;
  color: #155724;
}

.summary-card.completion .card-icon {
  background: #d1ecf1;
  color: #0c5460;
}

.card-content h4 {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
  color: #212529;
}

.card-content p {
  margin: 0;
  color: #6c757d;
  font-size: 0.875rem;
}

.attachment-tabs {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
  overflow: hidden;
}

.tabs-header {
  display: flex;
  border-bottom: 1px solid #dee2e6;
  background: #f8f9fa;
}

.tab-btn {
  flex: 1;
  padding: 1rem 1.5rem;
  border: none;
  background: transparent;
  color: #6c757d;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  border-bottom: 3px solid transparent;
}

.tab-btn:hover {
  background: #e9ecef;
  color: #495057;
}

.tab-btn.active {
  color: #007bff;
  background: white;
  border-bottom-color: #007bff;
}

.tab-count {
  font-size: 0.875rem;
  color: #6c757d;
  margin-left: 0.5rem;
}

.tab-content {
  padding: 0;
}

.attachment-content {
  min-height: 400px;
}

.table-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
  background: #f8f9fa;
}

.table-header h4 {
  margin: 0;
  color: #495057;
}

.table-actions {
  display: flex;
  gap: 0.5rem;
}

.table-container {
  overflow-x: auto;
}

.document-group {
  margin-bottom: 2rem;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  overflow: hidden;
}

.group-header {
  background: #f2f7fb;
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #e9ecef;
}

.group-header h5 {
  margin: 0;
  font-weight: 600;
  color: #495057;
}

.documents-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 900px;
}

.documents-table th,
.documents-table td {
  padding: 1rem;
  border-bottom: 1px solid #dee2e6;
  vertical-align: middle;
}

.documents-table th {
  background: #f8f9fa;
  font-weight: 600;
  color: #495057;
  text-align: left;
}

.no-col {
  width: 60px;
  text-align: center;
}

.document-col {
  min-width: 300px;
}

.status-col {
  width: 150px;
  text-align: center;
}

.expiry-col {
  width: 120px;
  text-align: center;
}

.file-col {
  width: 250px;
}

.actions-col {
  width: 180px;
  text-align: center;
}

.text-center {
  text-align: center;
}

.document-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
  margin-bottom: 0.5rem;
}

.document-title {
  color: #495057;
  font-weight: 500;
}

.document-subtitle {
  display: block;
  font-style: italic;
  font-size: 0.875rem;
  color: #6c757d;
  margin-top: 0.25rem;
}

.required-badge,
.multiple-badge,
.expired-badge,
.expiring-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 500;
}

.required-badge {
  background: #dc3545;
  color: white;
}

.multiple-badge {
  background: #17a2b8;
  color: white;
}

.expired-badge {
  background: #6c757d;
  color: white;
}

.expiring-badge {
  background: #ffc107;
  color: #212529;
}

.file-display {
  padding: 0.5rem !important;
}

.multiple-files {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.file-count-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  background: #e9ecef;
  color: #495057;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 500;
  align-self: flex-start;
}

.files-list {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.file-item,
.single-file {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem;
  background: #f8f9fa;
  border-radius: 4px;
  border: 1px solid #e9ecef;
}

.file-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex: 1;
}

.file-details {
  display: flex;
  flex-direction: column;
  gap: 0.125rem;
}

.file-name {
  font-weight: 500;
  color: #495057;
  font-size: 0.875rem;
}

.file-size,
.file-version,
.expiry-info {
  color: #6c757d;
  font-size: 0.75rem;
}

.file-status {
  display: flex;
  align-items: center;
  margin-left: 0.5rem;
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  display: inline-block;
}

.status-dot.status-secondary { background: #6c757d; }
.status-dot.status-info { background: #17a2b8; }
.status-dot.status-warning { background: #ffc107; }
.status-dot.status-success { background: #28a745; }
.status-dot.status-danger { background: #dc3545; }

.file-actions {
  display: flex;
  gap: 0.25rem;
}

.no-files-multiple {
  text-align: center;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 4px;
  border: 1px dashed #dee2e6;
}

.no-file-text {
  color: #6c757d;
  font-style: italic;
  font-size: 0.875rem;
  display: block;
  margin-bottom: 0.25rem;
}

.upload-hint {
  color: #858585;
  font-size: 0.75rem;
}

.no-file,
.no-expiry {
  color: #6c757d;
  font-style: italic;
  font-size: 0.875rem;
  padding: 0.5rem;
  text-align: center;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

.status-secondary { background: #e9ecef; color: #6c757d; }
.status-info { background: #d1ecf1; color: #0c5460; }
.status-warning { background: #fff3cd; color: #856404; }
.status-success { background: #d4edda; color: #155724; }
.status-danger { background: #f8d7da; color: #721c24; }
.status-dark { background: #d6d8db; color: #1b1e21; }

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  font-size: 0.875rem;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
}

.btn-xs {
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
}

.btn-sm {
  padding: 0.375rem 0.75rem;
  font-size: 0.8125rem;
}

.btn-primary {
  background: #007bff;
  color: white;
  border: 1px solid #007bff;
}

.btn-primary:hover:not(:disabled) {
  background: #0056b3;
  border-color: #0056b3;
}

.btn-outline {
  background: white;
  color: #6c757d;
  border: 1px solid #ced4da;
}

.btn-outline:hover {
  background: #f8f9fa;
  border-color: #adb5bd;
}

.btn-danger {
  background: #dc3545;
  color: white;
  border: 1px solid #dc3545;
}

.btn-danger:hover {
  background: #c82333;
  border-color: #c82333;
}

.btn-info {
  background: #17a2b8;
  color: white;
  border: 1px solid #17a2b8;
}

.btn-info:hover {
  background: #138496;
  border-color: #138496;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.action-buttons {
  display: flex;
  gap: 0.25rem;
  flex-wrap: wrap;
  justify-content: center;
}

/* Row styling */
.required-row { background: #fff5f5; }
.approved-row { background: #f0fff4; }
.rejected-row { background: #fff5f5; }
.expired-row { background: #f8f9fa; }
.expiring-row { background: #fff8e1; }
.multiple-row { border-left: 4px solid #17a2b8; }

.admin-notes,
.rejection-reason {
  margin-top: 0.5rem;
  padding: 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
}

.admin-notes {
  background: #e7f3ff;
  color: #0056b3;
}

.rejection-reason {
  background: #fff5f5;
  color: #dc3545;
}

/* Enhanced Modal Styles */
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

.modal-content {
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
}

.modal-header h4 {
  margin: 0;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #6c757d;
  cursor: pointer;
  padding: 0.25rem;
}

.modal-close:hover {
  color: #495057;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding: 1.5rem;
  border-top: 1px solid #dee2e6;
}

/* Enhanced form styles */
.document-info-modal h5 {
  margin: 0 0 1rem 0;
  color: #495057;
}

.document-badges {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
  margin-bottom: 1rem;
}

.required-text,
.multiple-text,
.template-text,
.pdf-only-text {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-size: 0.875rem;
}

.required-text {
  background: #fff3cd;
  color: #856404;
  border: 1px solid #ffeaa7;
}

.multiple-text {
  background: #e7f3ff;
  color: #0056b3;
  border: 1px solid #bee5eb;
}

.template-text {
  background: #d1ecf1;
  color: #0c5460;
  border: 1px solid #bee5eb;
}

.pdf-only-text {
  background: #dc3545;
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 500;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

/* Enhanced file input */
.file-input-container {
  position: relative;
  border: 2px dashed #ced4da;
  border-radius: 8px;
  padding: 2rem;
  text-align: center;
  transition: all 0.3s ease;
  background: #fafafa;
}

.file-input-container:hover {
  border-color: #007bff;
  background: #fff8f0;
}

.file-input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

.file-input.has-files + .file-input-overlay {
  border-color: #28a745;
  color: #28a745;
}

.file-input-overlay {
  pointer-events: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  color: #6c757d;
}

.file-input-overlay i {
  font-size: 2rem;
}

.file-requirements {
  margin-top: 0.5rem;
}

.file-requirements p {
  margin: 0;
  color: #6c757d;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.date-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 0.875rem;
}

.date-input:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-help {
  display: block;
  margin-top: 0.25rem;
  color: #6c757d;
  font-size: 0.8125rem;
}

/* Template sections */
.template-section {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: #f0f9ff;
  border-radius: 6px;
  border: 1px solid #bee5eb;
}

.template-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
  color: #0c5460;
  font-weight: 500;
}

.template-download-btn {
  width: 100%;
  margin-bottom: 0.5rem;
}

.template-help {
  display: block;
  color: #6c757d;
  font-style: italic;
  line-height: 1.4;
}

.pdf-only-notice {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: #fff5f5;
  border-radius: 6px;
  border: 1px solid #f8d7da;
}

.pdf-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  color: #721c24;
  font-weight: 500;
}

.pdf-help {
  display: block;
  color: #6c757d;
  font-style: italic;
  line-height: 1.4;
}

/* Selected files preview */
.selected-files {
  margin-top: 1rem;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 8px;
  border: 1px solid #e9ecef;
}

.selected-files h6 {
  margin: 0 0 1rem 0;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.files-preview {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.file-preview-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem;
  background: white;
  border-radius: 6px;
  border: 1px solid #e9ecef;
}

.file-preview {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex: 1;
}

.file-preview i {
  font-size: 1.5rem;
}

.file-preview .file-details {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.file-preview .file-name {
  font-weight: 500;
  color: #495057;
}

.file-preview .file-size,
.file-preview .file-type {
  font-size: 0.75rem;
  color: #6c757d;
}

.remove-file-btn {
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 4px;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 0.75rem;
}

.remove-file-btn:hover {
  background: #c82333;
}

/* Upload progress */
.upload-progress {
  margin-top: 1rem;
  padding: 0.75rem;
  background: #e7f3ff;
  border-radius: 6px;
  border: 1px solid #bee5eb;
}

.progress-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  color: #0056b3;
}

.progress-bar {
  width: 100%;
  height: 8px;
  background: #e9ecef;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: #17a2b8;
  transition: width 0.3s ease;
}

/* No data container */
.no-data-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
  padding: 2rem;
}

.no-data-content {
  text-align: center;
  max-width: 400px;
}

.no-data-icon {
  font-size: 4rem;
  color: #6c757d;
  margin-bottom: 1rem;
}

.no-data-content h4 {
  color: #495057;
  margin-bottom: 1rem;
}

.no-data-content p {
  color: #6c757d;
  margin-bottom: 2rem;
  line-height: 1.6;
}

/* Requirements section */
.requirements-section {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #dee2e6;
}

.requirements-section h4 {
  color: #495057;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.requirements-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 1.5rem;
}

.requirement-item {
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 1.5rem;
  transition: transform 0.2s ease;
}

.requirement-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.requirement-item h5 {
  color: #495057;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.requirement-item ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.requirement-item li {
  padding: 0.5rem 0;
  border-bottom: 1px solid #f8f9fa;
  color: #6c757d;
  font-size: 0.875rem;
  position: relative;
  padding-left: 1.5rem;
}

.requirement-item li:before {
  content: "•";
  color: #007bff;
  position: absolute;
  left: 0;
  font-weight: bold;
}

.requirement-item li:last-child {
  border-bottom: none;
}

.template-badge-small {
  background: #d1ecf1;
  color: #0c5460;
  padding: 0.2rem 0.4rem;
  border-radius: 3px;
  font-size: 0.7rem;
  font-weight: 500;
}

/* Responsive design */
@media (max-width: 768px) {
  .distributor-documents {
    padding: 0.5rem;
  }

  .summary-cards {
    grid-template-columns: 1fr;
    gap: 0.5rem;
  }

  .requirements-grid {
    grid-template-columns: 1fr;
  }

  .action-buttons {
    flex-direction: column;
  }

  .table-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .documents-table {
    min-width: 700px;
  }

  .modal-content {
    width: 95%;
    margin: 1rem;
  }

  .file-input-container {
    padding: 1.5rem;
  }

  .files-preview {
    max-height: 200px;
    overflow-y: auto;
  }
}

@media (max-width: 480px) {
  .documents-table {
    min-width: 600px;
  }

  .file-col {
    width: 200px;
  }

  .actions-col {
    width: 150px;
  }
}
</style>