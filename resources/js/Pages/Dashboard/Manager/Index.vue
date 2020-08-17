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

      <rate-your-manager
        :employee="employee"
        :surveys="surveys"
      />

      <expense
        :employee="employee"
        :expenses="pendingExpenses"
        :default-currency="defaultCurrency"
      />
    </div>
  </layout>
</template>

<script>
import Expense from '@/Pages/Dashboard/Manager/Partials/Expense';
import RateYourManager from '@/Pages/Dashboard/Manager/Partials/RateYourManager';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Expense,
    RateYourManager,
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
    surveys: {
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
