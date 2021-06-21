<style lang="scss" scoped>
.top-1 {
  top: 20px;
}

.small-avatar {
  margin-left: -8px;
  box-shadow: 0 0 0 2px #fff;
}

.all-avatars {
  left: 10px;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        ⏲
      </span> {{ $t('dashboard.manager_timesheet_title') }}

      <help :url="$page.props.help_links.one_on_ones" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <!-- BLANK STATE -->
      <div v-if="timesheetsStats.employees.length == 0" data-cy="expense-list-blank-state">
        <img loading="lazy" class="db center mb4 mt3" alt="no timesheets to validate" src="/img/streamline-icon-employee-checklist-6@140x140.png" height="80"
             width="80"
        />

        <p class="fw5 mt3 tc">{{ $t('dashboard.manager_timesheet_blank_state') }}</p>
      </div>

      <!-- NOT BLANK STATE :-) -->
      <div v-else class="flex justify-between items-center">
        <!-- stats -->
        <div>
          <img loading="lazy" src="/img/streamline-icon-employee-checklist-6@140x140.png" width="90" alt="meeting" class="absolute-ns di-ns dn top-1 left-1" />

          <p class="pl6-ns pl3 pb3 pt4 pr3 ma0">
            {{ $tc('dashboard.manager_timesheet_summary_count', timesheetsStats.totalNumberOfTimesheetsToValidate, { count: timesheetsStats.totalNumberOfTimesheetsToValidate}) }}
          </p>

          <!-- avatars -->
          <div v-if="timesheetsStats.employees.length > 0" class="pl6-ns pl3 mb3">
            <div class="flex items-center relative tr all-avatars">
              <avatar v-for="member in timesheetsStats.employees" :key="member.id" :avatar="member.avatar" :size="32" :class="'br-100 small-avatar'" />
            </div>
          </div>
        </div>

        <!-- CTA -->
        <inertia-link :href="timesheetsStats.url_view_all" class="btn w-auto-ns w-100 mr2 pv2 ph3 mr3">{{ $t('app.view') }}</inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Help,
    Avatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    timesheetsStats: {
      type: Object,
      default: null,
    },
  },
};
</script>
