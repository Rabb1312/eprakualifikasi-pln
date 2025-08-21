<template>
  <div class="distributor-engineering">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">
          <i class="fas fa-cogs text-primary me-2"></i>
          Engineering Activities Management
          <span class="subtitle">Document your technical engineering capabilities</span>
        </h5>
      </div>
      <div class="card-body">
        <!-- Engineering Activities Section -->
        <div class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-drafting-compass"></i>
            <h6 class="section-title">Engineering Activities Description</h6>
          </div>

          <div class="section-description">
            <p>
              <i class="fas fa-info-circle me-2"></i>
              Jelaskan secara detail aktivitas teknik dan engineering yang dilakukan oleh perusahaan Anda, termasuk layanan, kemampuan, dan keahlian teknis yang dimiliki.
            </p>
          </div>

          <div class="engineering-input-card">
            <div class="input-header">
              <i class="fas fa-edit"></i>
              <h6>Engineering Activities Details</h6>
              <div class="character-indicator">
                <span class="character-count">{{ form.engineering_activities.length }}</span>
                <span class="character-label">karakter</span>
              </div>
            </div>
            <div class="input-body">
              <div class="textarea-container">
                <label for="engineering_activities" class="form-label">
                  <span class="label-main">
                    Jelaskan aktivitas Teknik Anda (jika ada)
                  </span>
                  <small class="label-sub">
                    Please describe your Engineering activities (if any)
                  </small>
                </label>
                <textarea
                  id="engineering_activities"
                  v-model="form.engineering_activities"
                  rows="12"
                  class="form-control engineering-textarea"
                  placeholder="Jelaskan secara detail aktivitas engineering perusahaan Anda:

• Layanan Engineering yang Disediakan:
  - Design dan perencanaan teknis
  - Analisis dan perhitungan engineering
  - Konsultasi teknis dan solusi engineering
  - Review dan validasi desain

• Kemampuan Teknis:
  - Software engineering yang dikuasai (AutoCAD, SolidWorks, dll.)
  - Standar dan metodologi yang digunakan
  - Sertifikasi engineering yang dimiliki

• Tim Engineering:
  - Jumlah dan kualifikasi engineer
  - Spesialisasi bidang engineering
  - Pengalaman dan track record

• Contoh Proyek Engineering:
  - Proyek-proyek yang pernah dikerjakan
  - Kompleksitas dan skala proyek
  - Hasil dan pencapaian

• Value Added Services:
  - Training dan knowledge transfer
  - Maintenance engineering support
  - Customization dan modification services"
                ></textarea>
              </div>

              <div class="engineering-guidelines">
                <div class="guidelines-card">
                  <div class="guidelines-header">
                    <i class="fas fa-lightbulb text-warning"></i>
                    <span>Tips Pengisian</span>
                  </div>
                  <div class="guidelines-content">
                    <h6>Sertakan informasi berikut:</h6>
                    <ul class="guidelines-list">
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Jenis layanan engineering yang ditawarkan</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Software dan tools engineering yang digunakan</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Kualifikasi dan sertifikasi tim engineering</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Pengalaman proyek engineering sebelumnya</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Standar dan metodologi yang diterapkan</span>
                      </li>
                      <li>
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Value-added services yang dapat diberikan</span>
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="engineering-categories">
                  <div class="categories-header">
                    <i class="fas fa-tags text-info"></i>
                    <span>Kategori Engineering</span>
                  </div>
                  <div class="categories-content">
                    <div class="category-tags">
                      <div class="category-tag" v-for="category in engineeringCategories" :key="category.id">
                        <i :class="category.icon"></i>
                        <div class="tag-content">
                          <span class="tag-title">{{ category.title }}</span>
                          <small class="tag-description">{{ category.description }}</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Preview Section -->
        <div v-if="form.engineering_activities.trim()" class="section-wrapper">
          <div class="section-header">
            <i class="fas fa-eye"></i>
            <h6 class="section-title">Activities Preview & Analysis</h6>
          </div>

          <div class="preview-card">
            <div class="preview-header">
              <i class="fas fa-file-alt me-2"></i>
              <span>Engineering Activities Preview</span>
            </div>
            <div class="preview-body">
              <div class="preview-content">
                {{ form.engineering_activities }}
              </div>
              
              <div class="preview-stats">
                <div class="stats-grid">
                  <div class="stat-item">
                    <i class="fas fa-align-left"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getWordCount() }}</span>
                      <span class="stat-label">Kata</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <i class="fas fa-paragraph"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getParagraphCount() }}</span>
                      <span class="stat-label">Paragraf</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <i class="fas fa-list-ul"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getBulletPointCount() }}</span>
                      <span class="stat-label">Bullet Points</span>
                    </div>
                  </div>
                  <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <div class="stat-content">
                      <span class="stat-number">{{ getReadingTime() }}</span>
                      <span class="stat-label">Menit Baca</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button 
            @click="save" 
            :disabled="!form.engineering_activities.trim()"
            class="btn btn-primary"
          >
            <i class="fas fa-save"></i>
            Simpan Aktivitas Engineering
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['distributor'],
  data() {
    return {
      form: {
        engineering_activities: this.distributor?.engineering_activities || ''
      },
      engineeringCategories: [
        {
          id: 'design',
          title: 'Design Engineering',
          description: 'CAD, 3D modeling, technical drawing',
          icon: 'fas fa-drafting-compass'
        },
        {
          id: 'analysis',
          title: 'Engineering Analysis',
          description: 'FEA, simulation, calculation',
          icon: 'fas fa-calculator'
        },
        {
          id: 'consulting',
          title: 'Technical Consulting',
          description: 'Advisory, feasibility study',
          icon: 'fas fa-user-tie'
        },
        {
          id: 'project',
          title: 'Project Engineering',
          description: 'Project management, execution',
          icon: 'fas fa-project-diagram'
        },
        {
          id: 'testing',
          title: 'Testing & Validation',
          description: 'Quality assurance, compliance',
          icon: 'fas fa-check-double'
        },
        {
          id: 'maintenance',
          title: 'Maintenance Engineering',
          description: 'Preventive, predictive maintenance',
          icon: 'fas fa-wrench'
        }
      ]
    }
  },
  methods: {
    save() {
      this.$emit('save', this.form)
    },
    getWordCount() {
      return this.form.engineering_activities.trim().split(/\s+/).filter(word => word.length > 0).length;
    },
    getParagraphCount() {
      return this.form.engineering_activities.trim().split(/\n\s*\n/).filter(para => para.trim().length > 0).length;
    },
    getBulletPointCount() {
      return (this.form.engineering_activities.match(/[•\-\*]/g) || []).length;
    },
    getReadingTime() {
      const wordsPerMinute = 200;
      const words = this.getWordCount();
      return Math.max(1, Math.ceil(words / wordsPerMinute));
    }
  }
}
</script>

<style scoped>
.distributor-engineering {
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
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid rgba(0, 123, 255, 0.1);
}

.section-header i {
  font-size: 1.2rem;
  color: #007bff;
  margin-right: 0.75rem;
  width: 20px;
  text-align: center;
}

.section-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
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

.engineering-input-card {
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  position: relative;
}

.engineering-input-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.engineering-input-card:hover::before {
  left: 100%;
}

.engineering-input-card:hover {
  border-color: #007bff;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.input-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #dee2e6;
}

.input-header h6 {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin: 0;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
}

.input-header i {
  color: #007bff;
  font-size: 1.2rem;
  width: 20px;
  text-align: center;
}

.character-indicator {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.85rem;
}

.character-count {
  font-weight: 700;
  color: #007bff;
}

.character-label {
  color: #6c757d;
}

.input-body {
  padding: 1.5rem;
  display: grid;
  grid-template-columns: 1fr 350px;
  gap: 2rem;
}

.textarea-container {
  display: flex;
  flex-direction: column;
}

.form-label {
  margin-bottom: 1rem;
}

.label-main {
  display: block;
  font-weight: 600;
  color: #495057;
  font-size: 1rem;
  margin-bottom: 0.5rem;
}

.label-sub {
  color: #6c757d;
  font-style: italic;
  font-size: 0.85rem;
}

.engineering-textarea {
  padding: 1rem;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  font-size: 0.95rem;
  line-height: 1.6;
  resize: vertical;
  font-family: inherit;
  transition: all 0.3s ease;
  background: #f8f9fa;
  min-height: 350px;
}

.engineering-textarea:focus {
  border-color: #007bff;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.engineering-guidelines {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.guidelines-card,
.engineering-categories {
  background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
  border: 1px solid #e9ecef;
  border-radius: 8px;
  overflow: hidden;
}

.guidelines-header,
.categories-header {
  padding: 0.75rem 1rem;
  background: white;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  font-size: 0.9rem;
  color: #495057;
}

.guidelines-content,
.categories-content {
  padding: 1rem;
}

.guidelines-content h6 {
  color: #495057;
  font-size: 0.85rem;
  margin-bottom: 0.75rem;
}

.guidelines-list {
  margin: 0;
  padding: 0;
  list-style: none;
}

.guidelines-list li {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
  font-size: 0.8rem;
  line-height: 1.4;
}

.guidelines-list i {
  font-size: 0.7rem;
  margin-top: 0.1rem;
  flex-shrink: 0;
}

.category-tags {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.category-tag {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.category-tag:hover {
  border-color: #007bff;
  background: #e3f2fd;
}

.category-tag i {
  color: #007bff;
  font-size: 1rem;
  width: 16px;
  text-align: center;
  flex-shrink: 0;
}

.tag-content {
  flex: 1;
}

.tag-title {
  font-weight: 600;
  color: #495057;
  font-size: 0.8rem;
  display: block;
  margin-bottom: 0.1rem;
}

.tag-description {
  color: #6c757d;
  font-size: 0.7rem;
}

.preview-card {
  background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1.5rem;
  border-left: 4px solid #28a745;
  border: 1px solid rgba(40, 167, 69, 0.2);
}

.preview-header {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: #495057;
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}

.preview-header i {
  color: #28a745;
}

.preview-content {
  background: white;
  border-radius: 8px;
  padding: 1.5rem;
  border: 1px solid #e9ecef;
  margin-bottom: 1.5rem;
  white-space: pre-wrap;
  line-height: 1.6;
  color: #495057;
  font-size: 0.9rem;
  max-height: 300px;
  overflow-y: auto;
}

.preview-stats {
  background: white;
  border-radius: 8px;
  padding: 1rem;
  border: 1px solid #e9ecef;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  gap: 1rem;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.stat-item i {
  font-size: 1.2rem;
  color: #007bff;
  width: 20px;
  text-align: center;
}

.stat-content {
  flex: 1;
}

.stat-number {
  display: block;
  font-size: 1.2rem;
  font-weight: 700;
  color: #495057;
  line-height: 1;
}

.stat-label {
  font-size: 0.75rem;
  color: #6c757d;
}

.btn {
  padding: 0.875rem 2rem;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.95rem;
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

.btn-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
  min-width: 250px;
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

@media (max-width: 1024px) {
  .distributor-engineering {
    max-width: 100%;
    margin: 10px;
    padding: 0 10px;
  }
  
  .input-body {
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
  
  .stats-grid {
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  }
  
  .engineering-textarea {
    min-height: 250px;
  }
}
</style>