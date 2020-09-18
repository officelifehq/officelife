<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.auth.company.name }}
        </h2>
      </div>

      <dashboard-menu :employee="employee" />
    </div>

    <one-on-one-with-direct-report
      :one-on-ones="oneOnOnes"
    />

    <expense
      :expenses="pendingExpenses"
      :default-currency="defaultCurrency"
    />
  </layout>
</template>

<script>
import Expense from '@/Pages/Dashboard/Manager/Partials/Expense';
import OneOnOneWithDirectReport from '@/Pages/Dashboard/Manager/Partials/OneOnOneWithDirectReport';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Expense,
    OneOnOneWithDirectReport,
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
    pendingExpenses: {
      type: Array,
      default: null,
    },
    oneOnOnes: {
      type: Array,
      default: null,
    },
    defaultCurrency: {
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
