<style scoped>
.dummy {
  right: 40px;
  bottom: 20px;
}

.grid {
  display: grid;
  grid-template-columns: 3fr repeat(7, 1fr);
  align-items: center;
}

.project {
  width: 280px;
  max-width: 400px;
}

.off-days {
  color: #4b7682;
  background-color: #e6f5f9;
}

.add-new-entry {
  background-color: #f3ffee;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.props.auth.company.name }}
        </h2>
      </div>

      <dashboard-menu :employee="employee" />

      <div class="cf mw8 center br3 mb3 bg-white box pa3 relative">
        <p class="mt0 mb2 lh-copy f6">{{ $t('dashboard.manager_expense_description') }}</p>

        <div class="dt bt br bb-gray">
          <!-- header -->
          <div class="dt-row">
            <div class="dtc bl bb bb-gray project">
            </div>
            <!-- monday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                Mon
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- tuesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                T
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- wednesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                W
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- thursday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                T
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- friday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                T
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- saturday -->
            <div class="tc pv2 dtc bl bb bb-gray off-days">
              <span class="db">
                S
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
            <!-- sunday -->
            <div class="tc pv2 bl bb bb-gray off-days">
              <span class="db">
                S
              </span>
              <span class="f7 gray">
                22 nov
              </span>
            </div>
          </div>

          <!-- entries -->
          <timesheet-row @update-day="updateDay" />
          <timesheet-row @update-day="updateDay" />
          <timesheet-row @update-day="updateDay" />

          <!-- add a new entry cta -->
          <div class="dt-row">
            <span class="dtc bl bb-gray add-new-entry pv2 tc f6">
              <span class="bb b--dotted bt-0 bl-0 br-0 pointer">
                + add a new entry
              </span>
            </span>
            <div class="dtc add-new-entry"></div>
            <div class="dtc add-new-entry"></div>
            <div class="dtc add-new-entry"></div>
            <div class="dtc add-new-entry"></div>
            <div class="dtc add-new-entry"></div>
            <div class="dtc add-new-entry"></div>
            <div class="dtc add-new-entry"></div>
          </div>

          <!-- add a new entry -->

          <div class="dt-row">
            <div class="f6 ph2 dtc bt bl bb bb-gray project v-mid">
              <div class="flex justify-between items-center">
                <span class="db pb1 fw5">
                  Total
                </span>
                <span class="f7 fw5">
                  37h30
                </span>
              </div>
            </div>
            <!-- monday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              37.5
            </div>
            <!-- tuesday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              37.5
            </div>
            <!-- wednesday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              37.5
            </div>
            <!-- thursday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              37.5
            </div>
            <!-- friday -->
            <div class="tc pv2 dtc bt bl bb bb-gray f7 gray">
              37.5
            </div>
            <!-- saturday -->
            <div class="tc pv2 dtc bt bl bb bb-gray off-days f7 gray">
              37.5
            </div>
            <!-- sunday -->
            <div class="tc pv2 bt bl bb bb-gray off-days f7 gray">
              37.5
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';
import TimesheetRow from '@/Pages/Dashboard/Timesheet/Partials/TimesheetRow';

export default {
  components: {
    Layout,
    DashboardMenu,
    TimesheetRow,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    timesheet: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        title: null,
        content: null,
        errors: [],
      },
      loadingState: '',
      mondays: 0,
      tusdays: 0,
      wednesdays: 0,
      thursdays: 0,
      fridays: 0,
      saturdays: 0,
      sundays: 0,
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },

  methods: {
    updateDay({day, value}) {
      if (day == 1) {
        this.mondays = this.mondays + value;
      }
      if (day == 2) {
        this.tuesdays = this.tuesdays + value;
      }
      if (day == 3) {
        this.wednesdays = this.wednesdays + value;
      }
      if (day == 4) {
        this.thursdays = this.thursdays + value;
      }
      if (day == 5) {
        this.fridays = this.fridays + value;
      }
      if (day == 6) {
        this.saturdays = this.saturdays + value;
      }
      if (day == 7) {
        this.sundays = this.sundays + value;
      }
    },
  },
};
</script>
