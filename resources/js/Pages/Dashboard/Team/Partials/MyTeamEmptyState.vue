<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ company.name }}
        </h2>
      </div>

      <dashboard-menu :employee="employee" />

      <div class="cf mw7 center br3 mb3 bg-white box">
        <div class="pa3 tc">
          <img loading="lazy" height="140" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-friends-wave@140x140.png" />

          <p class="fw5 mt3 tc">{{ message }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Layout,
    DashboardMenu,
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    message: {
      type: String,
      default: null,
    }
  },
};
</script>
