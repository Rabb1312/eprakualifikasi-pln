<template>
  <div class="aftersales-form">
    <h3>After Sales Support</h3>
    <div class="form-group">
      <label>Do you give priority treatment to over, short and damaged requests?</label>
      <select v-model="form.priority_treatment" class="form-control">
        <option value="">Pilih...</option>
        <option v-for="(text, val) in options.yes_no" :value="val" :key="val">{{ text }}</option>
      </select>
    </div>
    <div class="form-group mt-2">
      <label>Do you have any agreement to your principal related your product for any complaint for customer for returning goods, short and damage?</label>
      <select v-model="form.complaint_agreement" class="form-control">
        <option value="">Pilih...</option>
        <option v-for="(text, val) in options.yes_no" :value="val" :key="val">{{ text }}</option>
      </select>
    </div>
    <div class="form-group mt-2">
      <label>Do you have any contact centre number?</label>
      <select v-model="form.has_contact_centre" class="form-control">
        <option value="">Pilih...</option>
        <option v-for="(text, val) in options.yes_no" :value="val" :key="val">{{ text }}</option>
      </select>
    </div>
    <button @click="save" class="btn btn-primary mt-3">Save</button>
  </div>
</template>

<script>
export default {
  props: ['distributor', 'options'],
  data() {
    return {
      form: {
        priority_treatment: this.distributor?.priority_treatment || '',
        complaint_agreement: this.distributor?.complaint_agreement || '',
        has_contact_centre: this.distributor?.has_contact_centre || ''
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
.aftersales-form {
  max-width: 700px;
  margin: 0 auto;
  background: #fff;
  padding: 2rem 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.aftersales-form h3 {
  color: #000000;
  margin-bottom: 1.5rem;
  font-size: 1.3rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.form-group {
  margin-bottom: 1.5rem;
  display: flex;
  flex-direction: column;
}
.form-group label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}
.form-control {
  padding: 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: border-color 0.2s;
}
.form-control:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
}
.btn-primary {
  background: #007bff;
  color: #fff;
  border: 1px solid #007bff;
  border-radius: 6px;
  padding: 0.6rem 1.2rem;
  font-weight: 500;
  font-size: 1rem;
  transition: background 0.2s;
  cursor: pointer;
}
.btn-primary:hover:not(:disabled) {
  background: #0056b3;
}
.mt-2 { margin-top: 1rem; }
.mt-3 { margin-top: 1.5rem; }
</style>