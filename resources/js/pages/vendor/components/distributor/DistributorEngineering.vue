<template>
  <div class="engineering-form">
    <h3>Engineering Activities</h3>
    <label class="desc-label">
      Please describe your Engineering activities (if any).<br>
      <span class="desc-sub">Tolong jelaskan aktivitas Teknik Anda (jika ada).</span>
    </label>
    <textarea v-model="form.engineering_activities" rows="8" class="form-control"></textarea>
    <div class="form-actions">
      <button @click="save" class="btn btn-primary">Save</button>
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
      }
    }
  },
  methods: {
    save() {
      this.$emit('save', this.form)
    }
  }
}
</script>
<style scoped>
.engineering-form {
  max-width: 900px; /* diperbesar */
  margin: 0 auto;
  background: #fff;
  padding: 2rem 1.5rem;
  border-radius: 14px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.engineering-form h3 {
  color: #000;
  margin-bottom: 1.5rem;
  font-size: 1.5rem;
  font-weight: 700;
}
.desc-label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
  display: block;
  font-size: 1.15rem;
}
.desc-sub {
  color: #6c757d;
  font-size: 1.05em;
  font-style: italic;
}
.form-control {
  width: 100%;
  min-height: 200px; /* diperbesar */
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 8px;
  font-size: 1.1rem;
  transition: border-color 0.2s;
  resize: vertical;
  margin-bottom: 0.5rem;
}
.form-control:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
}
.form-actions {
  display: flex;
  justify-content: center; /* tombol di tengah */
  margin-top: 2rem;
}
.btn-primary {
  background: #007bff;
  color: #fff;
  border: 1px solid #007bff;
  border-radius: 8px;
  padding: 0.9rem 2.2rem;
  font-weight: 500;
  font-size: 1.15rem;
  transition: background 0.2s;
  cursor: pointer;
}
.btn-primary:hover:not(:disabled) {
  background: #0056b3;
}
</style>