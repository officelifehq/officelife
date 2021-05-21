<style lang="scss" scoped>
.timesheet-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.timesheet-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.timesheet-badge-rejected {
  background-color: #E35763;
  color: #fff;
  top: -3px;
}

.timesheet-badge-approved {
  background-color: #68D391;
  color: #fff;
  top: -3px;
}
</style>

<template>
  <div>
    <span class="db fw5 mb2">
      <span class="mr1">
        ⏳
      </span> {{ $t('employee.timesheets_title') }}

      <help :url="$page.props.help_links.time_tracking" :top="'2px'" />
    </span>

    <div class="mb4 bg-white box cf">
      <div v-show="timesheets.entries.length > 0">
        <div v-for="timesheet in timesheets.entries" :key="timesheet.id" class="pa3 bb bb-gray bb-gray-hover w-100 flex items-center justify-between timesheet-item">
          <!-- timesheet info -->
          <div class="relative">
            <p class="ma0 mb2 f7 grey">{{ timesheet.started_at }} → {{ timesheet.ended_at }}</p>
            <p class="f4 ma0">
              <span class="br3 f7 fw3 ph2 pv1 dib relative mr1" :class="'timesheet-badge-' + timesheet.status" :data-cy="`timesheet-${timesheet.id}-status-${timesheet.status}`">{{ $t('employee.timesheets_details_status_' + timesheet.status) }}</span>
              {{ timesheet.duration }}
            </p>
          </div>

          <!-- view link -->
          <div>
            <inertia-link :href="timesheet.url" class="ma0 pa0 f6" :data-cy="'entry-item-' + timesheet.id">{{ $t('app.view') }}</inertia-link>
          </div>
        </div>

        <!-- view all link -->
        <div class="ph3 pv2 tc f6">
          <inertia-link :href="timesheets.view_all_url" data-cy="view-all-timesheets">{{ $t('employee.timesheets_view_all') }}</inertia-link>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="timesheets.entries.length == 0" class="pa3" data-cy="list-blank-state">
        <p class="mb0 mt0 lh-copy f6">{{ $t('employee.timesheets_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help
  },

  props: {
    timesheets: {
      type: Object,
      default: null,
    },
  },
};

</script>
