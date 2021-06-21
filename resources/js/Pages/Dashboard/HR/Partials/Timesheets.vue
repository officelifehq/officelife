<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}

.top-1 {
  top: 0.3rem;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        ⏲
      </span> {{ $t('dashboard.hr_timesheets_title') }}

      <help :url="$page.props.help_links.contract_renewal_dashboard" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <div class="flex justify-between items-center">
        <!-- stats -->
        <div>
          <img loading="lazy" src="/img/streamline-icon-employee-checklist-6@140x140.png" width="90" alt="meeting" class="absolute-ns di-ns dn top-1 left-1" />

          <p v-if="data.number_of_timesheets > 0" class="pl6-ns pl3 pb2 pt4 pr3 ma0 lh-copy">
            {{ $tc('dashboard.hr_timesheet_summary_count', data.number_of_timesheet, { count: data.number_of_timesheets}) }}
          </p>
          <p v-else class="pl6-ns pl3 pb4 pt4 pr3 mb2">
            {{ $t('dashboard.hr_timesheet_summary_blank') }}
          </p>

          <!-- avatars -->
          <div v-if="data.number_of_timesheets > 0" class="pl6-ns pl3 mb3">
            <div class="flex items-center relative tr all-avatars">
              <avatar v-for="member in data.employees" :key="member.id" :avatar="member.avatar" :size="32" :class="'br-100 small-avatar'" />
            </div>
          </div>
        </div>

        <!-- CTA -->
        <inertia-link v-if="data.number_of_timesheets > 0" :href="data.url_view_all" class="btn w-auto-ns w-100 mr2 pv2 ph3 mr3">{{ $t('app.view') }}</inertia-link>
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
    data: {
      type: Object,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
  },
};
</script>
