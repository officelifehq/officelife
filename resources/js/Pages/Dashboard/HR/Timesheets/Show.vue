<style lang="scss" scoped>
.project {
  width: 280px;
  max-width: 400px;
}

.off-days {
  color: #4b7682;
  background-color: #e6f5f9;
}

.approved {
  background-color: #E4F7E7;
  color: #1EAD2F;
}

.rejected {
  background-color: #FEEDE7;
  color: #E93804;
}

.open,
.ready_to_submit {
  background-color: #ececec;
  color: #99776d;
}

.stamp {
  top: -15px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/dashboard/hr/timesheets'"
                  :previous="$t('app.breadcrumb_employee_timesheets')"
      >
        {{ $t('app.breadcrumb_employee_timesheet') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
        <!-- title -->
        <div class="title-section mb3">
          <h2 class="tc fw5 mt0">
            {{ $t('employee.timesheets_details_show') }}

            <span class="db f6 mt3 mb4 fw4">
              {{ timesheet.start_date }} → {{ timesheet.end_date }}
            </span>

            <help :url="$page.props.help_links.one_on_ones" :top="'0px'" />
          </h2>

          <!-- information to display when timesheet was approved or rejected -->
          <div v-if="timesheet.status == 'approved' || timesheet.status == 'rejected'" :class="'relative pa3 mb3 br3 flex items-center justify-around ' + timesheet.status">
            <img v-if="timesheet.status == 'rejected'" src="/img/streamline-icon-stamp@140x140.png" alt="rejected" height="80" width="80"
                 class="absolute stamp bg-white br-100 ba b--gray"
            />
            <img v-else src="/img/streamline-icon-approve-document@140x140.png" alt="approved" height="80" width="80"
                 class="absolute stamp bg-white br-100 ba b--gray"
            />

            <!-- approver name -->
            <div>
              <p v-if="timesheet.status == 'approved'" class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_approved_by') }}</p>
              <p v-else class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_rejected_by') }}</p>

              <inertia-link v-if="hasID" :href="approverInformation.url" class="ma0">{{ approverInformation.name }}</inertia-link>
              <p v-else class="ma0">{{ approverInformation.name }}</p>
            </div>

            <!-- approved date -->
            <div>
              <p v-if="timesheet.status == 'approved'" class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_approved_on') }}</p>
              <p v-else class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_rejected_on') }}</p>
              <p class="ma0">{{ approverInformation.approved_at }}</p>
            </div>
          </div>

          <!-- information to display when timesheet is not yet approved or  -->
          <div v-if="timesheet.status == 'open' || timesheet.status == 'ready_to_submit'" :class="'relative pa3 mb3 br3 flex items-center ' + timesheet.status">
            <img src="/img/streamline-icon-employee-planner-3@140x140.png" alt="stamp" height="80" width="80"
                 class="relative stamp bg-white br-100 ba b--gray" style="top: 0;"
            />

            <!-- Status -->
            <div class="pl4">
              <p class="ttu f7 mb1 mt0">{{ $t('employee.timesheets_details_status') }}</p>
              <p class="ma0">{{ $t('employee.timesheets_details_status_' + timesheet.status) }}</p>
            </div>
          </div>
        </div>

        <div class="dt bt br bb-gray w-100">
          <!-- header -->
          <timesheet-header :days="daysHeader" />

          <!-- row -->
          <div v-for="row in timesheet.entries" :key="row.id" class="dt-row">
            <div class="project f6 ph2 pv3 dtc bl bb bb-gray v-mid">
              <div class="flex justify-between items-center">
                <div>
                  <span class="db pb1 fw5 lh-copy">
                    {{ row.task_title }}
                  </span>
                  <inertia-link :href="row.project_url" class="dib">
                    {{ row.project_name }}
                  </inertia-link>
                </div>
                <span class="f7 fw5">
                  {{ formatTime(row.total_this_week) }}
                </span>
              </div>
            </div>

            <!-- monday -->
            <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc">
              {{ formatTime(row.days[0].total_of_minutes) }}
            </div>

            <!-- tuesday -->
            <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc">
              {{ formatTime(row.days[1].total_of_minutes) }}
            </div>

            <!-- wednesday -->
            <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc">
              {{ formatTime(row.days[2].total_of_minutes) }}
            </div>

            <!-- thursday -->
            <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc">
              {{ formatTime(row.days[3].total_of_minutes) }}
            </div>

            <!-- friday -->
            <div class="ph2 pv2 dtc bl bb bb-gray v-mid tc">
              {{ formatTime(row.days[4].total_of_minutes) }}
            </div>

            <!-- saturday -->
            <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days tc">
              {{ formatTime(row.days[5].total_of_minutes) }}
            </div>

            <!-- sunday -->
            <div class="ph2 pv2 dtc bl bb bb-gray v-mid off-days tc">
              {{ formatTime(row.days[6].total_of_minutes) }}
            </div>
          </div>

          <!-- total -->
          <div class="dt-row">
            <div class="f6 ph2 dtc bt bl bb bb-gray project v-mid">
              <div class="flex justify-between items-center">
                <span class="db pb1 fw5">
                  {{ $t('dashboard.timesheet_total') }}
                </span>
                <span class="f7 fw5">
                  {{ weeklyTotalHumanReadable }}
                </span>
              </div>
            </div>

            <!-- daily total: monday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              {{ formatTime(dailyStats[0]) }}
            </div>
            <!-- daily total: tuesday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              {{ formatTime(dailyStats[1]) }}
            </div>
            <!-- daily total: wednesday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              {{ formatTime(dailyStats[2]) }}
            </div>
            <!-- daily total: thursday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              {{ formatTime(dailyStats[3]) }}
            </div>
            <!-- daily total: friday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              {{ formatTime(dailyStats[4]) }}
            </div>
            <!-- daily total: saturday -->
            <div class="tc pv2 dtc bt bl bb bb-gray off-days f7 gray">
              {{ formatTime(dailyStats[5]) }}
            </div>
            <!-- daily total: sunday -->
            <div class="tc pv2 bt bl bb bb-gray off-days f7 gray">
              {{ formatTime(dailyStats[6]) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';
import TimesheetHeader from '@/Pages/Dashboard/Timesheet/Partials/TimesheetHeader';

export default {
  components: {
    Layout,
    Breadcrumb,
    Help,
    TimesheetHeader,
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
    daysHeader: {
      type: Object,
      default: null,
    },
    approverInformation: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      weeklyTotalHumanReadable: 0,
      dailyStats: [0, 0, 0, 0, 0, 0, 0, 0],
    };
  },

  computed: {
    hasID() {
      return this.containsKey(this.approverInformation, 'id');
    }
  },

  mounted() {
    this.refreshWeeklyTotal();
    this.refreshDailyTotal();
  },

  methods: {
    // check if the object contains a specific key
    containsKey(obj, key ) {
      return Object.keys(obj).includes(key);
    },

    formatTime(timeInMinutes) {
      var hours = Math.floor(timeInMinutes / 60);
      var minutes = timeInMinutes % 60;

      // this adds leading zero to minutes, if needed
      const zeroPad = (num, places) => String(num).padStart(places, '0');
      return hours + 'h' + zeroPad(minutes, 2);
    },

    refreshWeeklyTotal() {
      var total = 0;
      for(var i = 0; i < this.timesheet.entries.length; i++){
        total = total + this.timesheet.entries[i].total_this_week;
      }

      this.weeklyTotalHumanReadable = this.formatTime(total);
    },

    refreshDailyTotal() {
      this.dailyStats = [];

      this.timesheet.entries.forEach(row => {
        for(var day = 0; day < 7; day++) {
          if (this.dailyStats[day]) {
            this.dailyStats[day] = parseInt(this.dailyStats[day]) + parseInt(row.days[day].total_of_minutes);
          } else {
            this.dailyStats[day] = parseInt(row.days[day].total_of_minutes);
          }
        }
      });
    },
  },
};
</script>
