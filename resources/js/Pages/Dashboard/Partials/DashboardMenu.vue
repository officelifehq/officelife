<style lang="scss" scoped>
.avatar {
  flex-grow: 0;
  flex-shrink: 0;
  flex-basis: 55px;
}

.welcome {
  color: #68686B;
}
</style>

<template>
  <div class="cf mw8 center br3 mt5 mb5">
    <!-- employee information -->
    <div class="flex items-center mb5">
      <avatar :avatar="employee.avatar" :size="55" :url="employee.url" :class="'pointer avatar br-100 mr4'" />

      <div>
        <h2 class="fw3 mt0 mb2">
          {{ $t('dashboard.welcome_title', { name: employee.name }) }}
        </h2>
        <p class="ma0 lh-copy welcome">{{ $t('dashboard.welcome_message') }}</p>
      </div>
    </div>

    <!-- menu switcher -->
    <div class="tc">
      <div class="cf dib btn-group">
        <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(employee.dashboard_view == 'me')}">
          {{ $t('dashboard.tab_me') }}
        </inertia-link>
        <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/timesheet'" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(employee.dashboard_view == 'timesheet')}">
          {{ $t('dashboard.tab_timesheets') }}
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
        <inertia-link v-if="employee.can_manage_hr" :href="'/' + $page.props.auth.company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(employee.dashboard_view == 'hr')}" data-cy="dashboard-hr-tab">
          <span class="mr1">🔒</span> {{ $t('dashboard.tab_hr') }}
        </inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
  },
};
</script>
