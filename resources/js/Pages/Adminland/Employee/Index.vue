<style lang="scss" scoped>
.employee-item {
  &:last-child {
    border-bottom: 0;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_employees') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.employees_title', {company: $page.props.auth.company.name}) }}
          </h2>

          <ul class="list pl3">
            <li class="mb5"><span class="mr1">🧸</span> <inertia-link :href="statistics.url_new" :data-cy="'add-employee-button'">{{ $t('account.employees_cta') }}</inertia-link></li>

            <li class="mb3 gray f6">{{ $t('account.employees_description_1') }}</li>
            <li class="mb5"><span class="mr1">👉</span> <inertia-link :href="statistics.url_all" data-cy="all-employee-link">{{ $t('account.employees_cta_view_all_employees', { count: statistics.number_of_employees }) }}</inertia-link></li>
            <li class="mb3 gray f6">{{ $t('account.employees_description_2') }}</li>
            <li class="mb3">
              <span class="mr1">👉</span>
              <inertia-link v-if="statistics.number_of_active_accounts != 0" :href="statistics.url_active">{{ $t('account.employees_cta_view_active_employees', { count: statistics.number_of_active_accounts }) }}</inertia-link>
              <span v-else class="gray">{{ $t('account.employees_cta_view_active_employees', { count: 0 }) }}</span>
            </li>
            <li class="mb3">
              <span class="mr1">👉</span>
              <inertia-link v-if="statistics.number_of_locked_accounts != 0" :href="statistics.url_locked">{{ $t('account.employees_cta_view_locked_employees', { count: statistics.number_of_locked_accounts }) }}</inertia-link>
              <span v-else class="gray">{{ $t('account.employees_cta_view_locked_employees', { count: 0 }) }}</span>
            </li>
            <li class="mb3">
              <span class="mr1">👉</span>
              <inertia-link v-if="statistics.number_of_employees_without_hire_date != 0" :href="statistics.url_no_hiring_date">{{ $t('account.employees_cta_view_employees_without_hiring_date', { count: statistics.number_of_employees_without_hire_date }) }}</inertia-link>
              <span v-else class="gray">{{ $t('account.employees_cta_view_employees_without_hiring_date', { count: 0 }) }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    statistics: {
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
