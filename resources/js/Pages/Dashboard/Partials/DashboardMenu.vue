<template>
  <div class="cf mw7 center br3 mb5 tc">
    <div class="cf dib btn-group">
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(employee.dashboard_view == 'me')}">
        Me
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'team')}" data-cy="dashboard-team-tab">
        My team
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/company'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'company')}" data-cy="dashboard-company-tab">
        My company
      </inertia-link>
      <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'hr')}" data-cy="dashboard-hr-tab">
        HR area
      </inertia-link>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
  },
};
</script>
