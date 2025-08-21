<template>
  <div>
    <h2>Distributor e-Prakualifikasi</h2>
    <div class="tabs">
      <button
        v-for="tab in tabs"
        :key="tab.id"
        :class="{active: tab.id === activeTab, completed: tab.completed}"
        @click="activeTab = tab.id"
      >
        <i :class="tab.icon"></i> {{ tab.label }}
      </button>
    </div>
    <div v-if="activeTab === 'general'">
      <DistributorGeneral :vendor="vendor" />
    </div>
    <div v-if="activeTab === 'engineering'">
      <DistributorEngineering :distributor="distributor" @save="updateDistributor" />
    </div>
    <div v-if="activeTab === 'after_sales'">
      <DistributorAfterSales :distributor="distributor" :options="options" @save="updateDistributor" />
    </div>
    <div v-if="activeTab === 'documents'">
      <DistributorDocuments :vendor="vendor" :distributor="distributor" />
    </div>
    <div class="completion">
      <strong>Completion: {{ completion_percentage }}%</strong>
    </div>
  </div>
</template>

<script>
import DistributorGeneral from './DistributorGeneral.vue'
import DistributorEngineering from './DistributorEngineering.vue'
import DistributorAfterSales from './DistributorAfterSales.vue'
import DistributorDocuments from './DistributorDocuments.vue'

export default {
  components: {
    DistributorGeneral,
    DistributorEngineering,
    DistributorAfterSales,
    DistributorDocuments
  },
  data() {
    return {
      tabs: [],
      activeTab: 'general',
      vendor: {},
      distributor: {},
      options: {},
      completion_percentage: 0
    }
  },
  created() {
    this.fetchTabs()
  },
  methods: {
    async fetchTabs() {
      const res = await this.$axios.get('/api/vendor/distributor/tabs')
      if (res.data.success) {
        this.tabs = res.data.data.tabs
        this.vendor = res.data.data.vendor
        this.distributor = res.data.data.distributor
        this.options = res.data.data.options
        this.completion_percentage = res.data.data.completion_percentage
      }
    },
    async updateDistributor(formData) {
      const res = await this.$axios.put('/api/vendor/distributor', formData)
      if (res.data.success) {
        this.distributor = res.data.data.distributor
        this.completion_percentage = res.data.data.completion_percentage
        this.fetchTabs() // refresh completed status
        this.$toast.success('Data berhasil diperbarui!')
      } else {
        this.$toast.error(res.data.message)
      }
    }
  }
}
</script>