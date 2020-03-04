<style lang="scss" scoped>
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ company.name }}
        </h2>
      </div>

      <div class="cf mw7 center br3 mb5 tc">
        <div class="cf dib btn-group">
          <inertia-link :href="'/' + company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'me')}">
            Me
          </inertia-link>
          <inertia-link :href="'/' + company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'team')}" data-cy="dashboard-team-tab">
            My team
          </inertia-link>
          <inertia-link :href="'/' + company.id + '/dashboard/company'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'company')}" data-cy="dashboard-company-tab">
            My company
          </inertia-link>
          <inertia-link :href="'/' + company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'hr')}" data-cy="dashboard-hr-tab">
            HR area
          </inertia-link>
        </div>
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box">
        <div class="pa3 tc">
          {{ message }}
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
