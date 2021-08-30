<style lang="scss" scoped>
.months {
  color: #718096;

  .selected {
    font-weight: 600;
    text-decoration: none;
    border-bottom: 0;
    padding-left: 10px;
  }
}

.years {
  .selected {
    border-bottom: 0;
    text-decoration: none;
    color: #4d4d4f;
  }
}

.timesheet-item:nth-child(2) {
  border-top: 1px solid #dae1e7;
}

.timesheet-item:first-child,
.timesheet-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.timesheet-item:last-child,
.timesheet-item:last-child:hover {
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
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

.timesheet-badge-open,
.timesheet-badge-ready_to_submit {
  background-color: #f0ecd4;
  color: #645050;
  top: -3px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/administration'"
                  :previous="employee.name"
      >
        {{ $t('app.breadcrumb_employee_timesheets') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 mt2 center tc normal mb0">
          {{ $t('employee.timesheets_details_title') }}

          <help :url="$page.props.help_links.time_tracking" :top="'0px'" />
        </h2>

        <p class="tc mt0 mb4 f6 gray">{{ $t('employee.timesheets_details_description') }}</p>

        <!-- list of years -->
        <ul class="list years tc mb5" data-cy="worklog-year-selector">
          <li class="di">{{ $t('employee.worklog_year_selector') }}</li>
          <li v-for="singleYear in years" :key="singleYear.number" class="di mh2">
            <inertia-link :href="singleYear.url" :class="{ selected: currentYear == singleYear.number }">{{ singleYear.number }}</inertia-link>
          </li>
        </ul>

        <!-- case when there are timesheets -->
        <template v-if="timesheet.entries.length > 0">
          <div class="cf w-100">
            <!-- left column -->
            <div class="fl-ns w-third-ns pa3">
              <!-- list of months -->
              <p class="f6 mt0 silver">{{ $t('employee.worklog_filter_month') }}</p>
              <ul class="pl0 list months f6">
                <li class="mb2"><inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/administration/timesheets/overview/' + year">All</inertia-link></li>
                <li v-for="month in months" :key="month.month" class="mb2" :data-cy="'worklog-month-selector-' + month.month">
                  <!-- we are viewing a specific month, so we need to highlight the proper month in the UI -->
                  <template v-if="currentMonth">
                    <inertia-link v-if="month.occurences != 0" :href="month.url" :class="{ selected: currentMonth == month.month }">{{ month.translation }} ({{ month.occurences }})</inertia-link>
                    <span v-if="month.occurences == 0">{{ month.translation }} ({{ month.occurences }})</span>
                  </template>

                  <template v-else>
                    <inertia-link v-if="month.occurences != 0" :href="month.url">{{ month.translation }} ({{ month.occurences }})</inertia-link>
                    <span v-if="month.occurences == 0">{{ month.translation }} ({{ month.occurences }})</span>
                  </template>
                </li>
              </ul>
            </div>

            <!-- right columns -->
            <div class="fl-ns w-two-thirds-ns pa3">
              <div class="flex-ns justify-around dn">
                <div>
                  <img loading="lazy" src="/img/streamline-icon-employee-checklist-6@140x140.png" height="60" width="60" alt="timesheets"
                       class="db center mb4"
                  />
                </div>
                <div>
                  <p class="mt0 f3 mb2">{{ timesheet.statistics.approved }}</p>
                  <p class="mt0 f6 gray">{{ $t('employee.timesheets_details_stat_approved') }}</p>
                </div>
                <div>
                  <p class="mt0 f3 mb2">{{ timesheet.statistics.rejected }}</p>
                  <p class="mt0 f6 gray">{{ $t('employee.timesheets_details_stat_rejected') }}</p>
                </div>
              </div>

              <div v-for="t in timesheet.entries" :key="t.id" class="pa3 bl br bb bb-gray bb-gray-hover w-100 flex items-center justify-between timesheet-item">
                <!-- timesheet info -->
                <div class="relative">
                  <p class="ma0 mb2 f7 grey">{{ t.started_at }} → {{ t.ended_at }}</p>
                  <p class="f4 ma0"><span class="br3 f7 fw3 ph2 pv1 dib relative mr1" :class="'timesheet-badge-' + t.status" :data-cy="`timesheet-${t.id}-status-${t.status}`">{{ $t('employee.timesheets_details_status_' + t.status) }}</span> {{ t.duration }}</p>
                </div>

                <!-- view link -->
                <div>
                  <inertia-link :href="t.url" class="ma0 pa0 f6" :data-cy="'entry-item-' + t.id">{{ $t('app.view') }}</inertia-link>
                </div>
              </div>

              <!-- blank state -->
              <p v-if="timesheet.entries.length == 0" class="tc mt5">{{ $t('employee.worklog_blank_state_for_month') }}</p>
            </div>
          </div>
        </template>

        <!-- case of no work from home entries -->
        <template v-else>
          <p class="tc pa3" data-cy="blank-worklog-message">{{ $t('employee.work_from_home_blank_state_for_month') }}</p>
        </template>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    timesheet: {
      type: Object,
      default: null,
    },
    year: {
      type: Number,
      default: null,
    },
    years: {
      type: Object,
      default: null,
    },
    months: {
      type: Array,
      default: null,
    },
    currentYear: {
      type: Number,
      default: null,
    },
    currentMonth: {
      type: Number,
      default: null,
    }
  },
};

</script>
