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

    <timesheet-approvals
      :timesheets-stats="timesheetsStats"
    />

    <one-on-one-with-direct-report
      :one-on-ones="oneOnOnes"
    />

    <contract-renewal
      v-if="contractRenewals.length != 0"
      :contract-renewals="contractRenewals"
    />

    <discipline-case
      v-if="disciplinesCases.length != 0"
      :cases="disciplinesCases"
    />

    <expense
      :expenses="pendingExpenses"
      :default-currency="defaultCurrency"
    />
  </layout>
</template>

<script>
import Expense from '@/Pages/Dashboard/Manager/Partials/Expense.vue';
import OneOnOneWithDirectReport from '@/Pages/Dashboard/Manager/Partials/OneOnOneWithDirectReport.vue';
import ContractRenewal from '@/Pages/Dashboard/Manager/Partials/ContractRenewal.vue';
import TimesheetApprovals from '@/Pages/Dashboard/Manager/Partials/TimesheetApprovals.vue';
import DisciplineCase from '@/Pages/Dashboard/Manager/Partials/DisciplineCase.vue';
import Layout from '@/Shared/Layout.vue';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu.vue';

export default {
  components: {
    Expense,
    OneOnOneWithDirectReport,
    Layout,
    DashboardMenu,
    ContractRenewal,
    TimesheetApprovals,
    DisciplineCase,
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
    pendingExpenses: {
      type: Array,
      default: null,
    },
    oneOnOnes: {
      type: Array,
      default: null,
    },
    contractRenewals: {
      type: Array,
      default: null,
    },
    timesheetsStats: {
      type: Object,
      default: null,
    },
    disciplinesCases: {
      type: Object,
      default: null,
    },
    defaultCurrency: {
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
