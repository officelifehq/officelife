<template>
  <div class="cf mw7 center br3 mb5 tc">
    <div class="cf dib btn-group">
      <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(employee.dashboard_view == 'me')}">
        {{ $t('dashboard.tab_me') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'team')}" data-cy="dashboard-team-tab">
        {{ $t('dashboard.tab_my_team') }}
      </inertia-link>
      <inertia-link v-if="employee.is_manager" :href="'/' + $page.props.auth.company.id + '/dashboard/manager'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'manager')}" data-cy="dashboard-manager-tab">
        <span class="mr1">🔒</span> {{ $t('dashboard.tab_manager') }}
      </inertia-link>
      <inertia-link v-if="employee.can_manage_expenses" :href="'/' + $page.props.auth.company.id + '/dashboard/expenses'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'expenses')}" data-cy="dashboard-expenses-tab">
        <span class="mr1">🔒</span> {{ $t('dashboard.tab_expenses') }}
      </inertia-link>
      <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'hr')}" data-cy="dashboard-hr-tab">
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
