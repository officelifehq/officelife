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
    <div class="ph2 ph0-ns">
      <dashboard-menu :employee="employee" />

      <div class="cf mw8 center br3 mb3 bg-white box pa3 relative">
        <!-- days selector -->
        <div class="mt0 mb5 lh-copy f6 tc relative">
          <ul class="list pl0 ma0">
            <li class="di mr3"><inertia-link :href="previousTimesheet.url" class="dib">&lt; {{ $t('dashboard.timesheet_previous_week') }}</inertia-link></li>
            <li class="di mr3 fw5">{{ timesheet.start_date }} - {{ timesheet.end_date }}</li>
            <li class="di"><inertia-link :href="nextTimesheet.url" class="dib">{{ $t('dashboard.timesheet_next_week') }} &gt;</inertia-link></li>
          </ul>

          <inertia-link v-if="currentTimesheet.id != timesheet.id" :href="currentTimesheet.url" class="absolute top-0 left-0">{{ $t('dashboard.timesheet_back_to_current') }}</inertia-link>
        </div>

        <div v-if="!displayNewEntry" class="mb3 relative">
          <span class="absolute f7 grey">
            {{ $t('dashboard.timesheet_auto_save') }}
          </span>

          <div v-if="timesheetStatus == 'open'" class="tr">
            <a data-cy="timesheet-add-new-row" class="btn f5 mr2" @click.prevent="showProjectList()">
              {{ $t('dashboard.timesheet_add_new') }}
            </a>
            <a data-cy="timesheet-submit-timesheet" class="btn add f5" @click.prevent="submit()">
              {{ $t('dashboard.timesheet_submit') }}
            </a>
          </div>

          <div v-if="timesheetStatus == 'ready_to_submit'" data-cy="timesheet-status-awaiting" class="tr">
            ⏳ {{ $t('dashboard.timesheet_status_ready') }}
          </div>
        </div>

        <!-- add a new row -->
        <form v-if="displayNewEntry" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="addBlankTimesheetRow()">
          <!-- message to display if there are no projects in the account -->
          <div v-if="projects.length == 0" class="tc">
            {{ $t('dashboard.timesheet_no_projects') }}
            <inertia-link :href="'/' + $page.props.auth.company.id + '/projects/create'">{{ $t('dashboard.timesheet_create_project') }}</inertia-link>
          </div>

          <span v-else class="bb b--dotted bt-0 bl-0 br-0 pointer">
            <select-box v-model="form.project"
                        :options="projects"
                        :name="'project_id'"
                        :errors="$page.props.errors.project_id"
                        :placeholder="$t('dashboard.timesheet_create_choose_project')"
                        :label="$t('dashboard.timesheet_create_choose_project')"
                        :data-cy="'project-selector'"
                        :required="true"
                        @input="showTasks($event)"
            />

            <select-box
              v-if="displayTasks"
              v-model="form.task"
              :options="tasks"
              :name="'task_id'"
              :errors="$page.props.errors.task_id"
              :placeholder="$t('dashboard.timesheet_create_choose_task')"
              :label="$t('dashboard.timesheet_create_choose_task')"
              :required="true"
              :data-cy="'task-selector'"
              @input="showTasks($event)"
            />
          </span>

          <!-- Actions -->
          <div v-if="projects.length != 0">
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 mr2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-timesheet-new-row'" />
            <a data-cy="cancel-button" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="displayNewEntry = false">
              {{ $t('app.cancel') }}
            </a>
          </div>
        </form>

        <div v-if="timesheetRows.length == 0" class="tc mv4">
          <img loading="lazy" src="/img/streamline-icon-building-site@140x140.png" height="140" width="140" alt="building"
               class="top-1 left-1"
          />
          <p>{{ $t('dashboard.timesheet_blank') }}</p>
        </div>

        <div v-else class="dt bt br bb-gray w-100">
          <!-- header -->
          <timesheet-header :days="daysHeader" />

          <!-- entries -->
          <timesheet-row v-for="row in timesheetRows" :key="row.id"
                         :row-coming-from-backend="row"
                         :timesheet="timesheet"
                         :timesheet-status="timesheetStatus"
                         @day-updated="refreshDayInformation"
                         @update-weekly-total="updateWeeklyTotal"
          />

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
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';
import TimesheetRow from '@/Pages/Dashboard/Timesheet/Partials/TimesheetRow';
import TimesheetHeader from '@/Pages/Dashboard/Timesheet/Partials/TimesheetHeader';
import SelectBox from '@/Shared/Select';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    DashboardMenu,
    TimesheetRow,
    TimesheetHeader,
    SelectBox,
    LoadingButton,
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
      type: Object,
      default: null,
    },
    daysHeader: {
      type: Object,
      default: null,
    },
    nextTimesheet: {
      type: Object,
      default: null,
    },
    previousTimesheet: {
      type: Object,
      default: null,
    },
    currentTimesheet: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        project: null,
        task: null,
        errors: [],
      },
      loadingState: '',
      displayNewEntry: false,
      displayTasks: false,
      timesheetRows: [],
      timesheetStatus: null,
      projects: [],
      tasks: [],
      dailyStats: [0, 0, 0, 0, 0, 0, 0, 0],
      weeklyTotalHumanReadable: 0,
    };
  },

  mounted() {
    this.timesheetRows = this.timesheet.entries;
    this.timesheetStatus = this.timesheet.status;
    this.refreshWeeklyTotal();

    if (localStorage.success) {
      flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },

  methods: {

    // Copy the information from the TimesheetRow to the localTimesheetRow, as
    // the data has changed in the child
    refreshDayInformation({id, day, value}) {
      var id = this.timesheetRows.findIndex(x => x.task_id === id);
      var row = this.timesheetRows[id];
      row.days[day].total_of_minutes = value;
      this.$set(this.timesheetRows, id, row);
    },

    showProjectList() {
      this.getProjectList();
      this.displayNewEntry = true;

      this.form.project = null;
      this.form.task = null;
    },

    showTasks() {
      this.getTasksList();
      this.displayTasks = true;
    },

    getProjectList() {
      axios.get(`/${this.$page.props.auth.company.id}/dashboard/timesheet/projects`)
        .then(response => {
          this.projects = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    getTasksList() {
      axios.get(`/${this.$page.props.auth.company.id}/dashboard/timesheet/${this.timesheet.id}/projects/${this.form.project.value}/tasks`)
        .then(response => {
          this.tasks = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    submit() {
      if (confirm(this.$t('dashboard.timesheet_submit_confirm', { time: this.weeklyTotalHumanReadable }))) {
        axios.post(`${this.$page.props.auth.company.id}/dashboard/timesheet/${this.timesheet.id}/submit`)
          .then(response => {
            this.timesheetStatus = 'ready_to_submit';
          })
          .catch(error => {
            this.form.errors = error.response.data;
          });
      }
    },

    addBlankTimesheetRow() {
      this.timesheetRows.push({
        project_id: this.form.project.value,
        project_name: this.form.project.label,
        project_code: this.form.project.code,
        task_id: this.form.task.value,
        task_title: this.form.task.label,
        total_this_week: 0,
        data_from_backend: false,
        days: [
          {
            day_of_week: 1,
            total_of_minutes: 0,
          },
          {
            day_of_week: 2,
            total_of_minutes: 0,
          },
          {
            day_of_week: 3,
            total_of_minutes: 0,
          },
          {
            day_of_week: 4,
            total_of_minutes: 0,
          },
          {
            day_of_week: 5,
            total_of_minutes: 0,
          },
          {
            day_of_week: 6,
            total_of_minutes: 0,
          },
          {
            day_of_week: 7,
            total_of_minutes: 0,
          },
        ],
      });

      this.displayNewEntry = false;
    },

    updateWeeklyTotal({id, value}) {
      var id = this.timesheetRows.findIndex(x => x.task_id === id);
      var row = this.timesheetRows[id];
      row.total_this_week = value;
      this.$set(this.timesheetRows, id, row);

      this.refreshWeeklyTotal();
      this.refreshDailyTotal();
    },

    refreshWeeklyTotal() {
      var total = 0;
      for(var i = 0; i < this.timesheetRows.length; i++){
        total = total + this.timesheetRows[i].total_this_week;
      }

      this.weeklyTotalHumanReadable = this.formatTime(total);
    },

    refreshDailyTotal() {
      this.dailyStats = [];

      this.timesheetRows.forEach(row => {
        for(var day = 0; day < 7; day++) {
          if (this.dailyStats[day]) {
            this.dailyStats[day] = parseInt(this.dailyStats[day]) + parseInt(row.days[day].total_of_minutes);
          } else {
            this.dailyStats[day] = parseInt(row.days[day].total_of_minutes);
          }
        }
      });
    },

    formatTime(timeInMinutes) {
      var hours = Math.floor(timeInMinutes / 60);
      var minutes = timeInMinutes % 60;

      // this adds leading zero to minutes, if needed
      const zeroPad = (num, places) => String(num).padStart(places, '0');
      return hours + 'h' + zeroPad(minutes, 2);
    }
  },
};
</script>