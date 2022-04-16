<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <dashboard-menu :employee="employee" />
    </div>

    <discipline-case :data="disciplineCases" />

    <timesheets
      :data="employeesWithoutManagersWithPendingTimesheets"
      :statistics="statisticsAboutTimesheets"
    />
  </layout>
</template>

<script>
import Timesheets from '@/Pages/Dashboard/HR/Partials/Timesheets';
import DisciplineCase from '@/Pages/Dashboard/HR/Partials/DisciplineCase';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Timesheets,
    DisciplineCase,
    Layout,
    DashboardMenu,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    employeesWithoutManagersWithPendingTimesheets: {
      type: Object,
      default: null,
    },
    statisticsAboutTimesheets: {
      type: Object,
      default: null,
    },
    disciplineCases: {
      type: Object,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },
};
</script>
