<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <dashboard-menu :employee="employee" />
    </div>

    <timesheets
      :data="employeesWithoutManagersWithPendingTimesheets"
      :statistics="statisticsAboutTimesheets"
    />
  </layout>
</template>

<script>
import Timesheets from '@/Pages/Dashboard/HR/Partials/Timesheets';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Timesheets,
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
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },
};
</script>
