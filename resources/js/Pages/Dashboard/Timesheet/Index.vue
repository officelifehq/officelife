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

.approved {
  background-color: #E4F7E7;
  color: #1EAD2F;
}

.rejected {
  background-color: #FEEDE7;
  color: #E93804;
}

.stamp {
  top: -15px;
}

.rejected-timesheet-item:not(:first-child):before {
  content: '/';
  color: gray;
  margin-right: 10px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <dashboard-menu :employee="employee" />

      <div class="cf mw8 center">
        <span class="db fw5 mb2">
          <span class="mr1">
            📅
          </span> {{ $t('dashboard.timesheet_title') }}

          <help :url="$page.props.help_links.time_tracking" />
        </span>

        <div class="br3 mb3 bg-white box pa3 relative">
          <!-- information of timesheets that were rejected, if any -->
          <div v-if="rejectedTimesheets.length > 0" class="mb4 ba bb-gray pa3 br3">
            <p class="mt0 mb2"><span class="mr1">⚠️</span> {{ $t('dashboard.timesheet_rejected_timesheets') }}</p>
            <ul class="list ma0 pl0">
              <li v-for="timesheetItem in rejectedTimesheets" :key="timesheetItem.id" class="dib rejected-timesheet-item mb2 f6 mr2">
                <inertia-link :href="timesheetItem.url">{{ timesheetItem.started_at }}</inertia-link>
              </li>
            </ul>
          </div>

          <!-- timesheets selector -->
          <div class="mt0 mb5 lh-copy f6 tc relative">
            <ul class="list pl0 ma0">
              <li class="di mr3"><inertia-link :href="previousTimesheet.url" class="dib">&lt; {{ $t('dashboard.timesheet_previous_week') }}</inertia-link></li>
              <li class="di mr3 fw5">{{ timesheet.start_date }} - {{ timesheet.end_date }}</li>
              <li class="di"><inertia-link :href="nextTimesheet.url" class="dib">{{ $t('dashboard.timesheet_next_week') }} &gt;</inertia-link></li>
            </ul>

            <inertia-link v-if="currentTimesheet.id != timesheet.id" :href="currentTimesheet.url" class="absolute top-0 left-0">{{ $t('dashboard.timesheet_back_to_current') }}</inertia-link>
          </div>

          <!-- information to display when timesheet is either open or ready for approval-->
          <div v-if="!displayNewEntry && timesheetStatus != 'approved'" class="mb3 relative">
            <span class="absolute f7 grey">
              {{ $t('dashboard.timesheet_auto_save') }}
            </span>

            <!-- actions if timesheet is open or rejected -->
            <div v-if="timesheetStatus == 'open' || timesheetStatus == 'rejected'" class="tr">
              <a data-cy="timesheet-add-new-row" class="btn f5 mr2" @click.prevent="showProjectList()">
                {{ $t('dashboard.timesheet_add_new') }}
              </a>
              <a v-if="timesheet.entries.length > 0" data-cy="timesheet-submit-timesheet" class="btn add f5" @click.prevent="submit()">
                {{ $t('dashboard.timesheet_submit') }}
              </a>
            </div>

            <!-- Waiting for approval status -->
            <div v-if="timesheetStatus == 'ready_to_submit'" data-cy="timesheet-status-awaiting" class="tr">
              ⏳ {{ $t('dashboard.timesheet_status_ready') }}
            </div>
          </div>

          <!-- information to display when timesheet was approved or rejected -->
          <div v-if="timesheetStatus == 'approved' || timesheetStatus == 'rejected'" :class="'relative pa3 mb3 br3 flex items-center justify-around ' + timesheetStatus">
            <img v-if="timesheetStatus == 'rejected'" src="/img/streamline-icon-stamp@140x140.png" alt="rejected" height="80" width="80"
                 class="absolute stamp bg-white br-100 ba b--gray"
            />
            <img v-else src="/img/streamline-icon-approve-document@140x140.png" alt="approved" height="80" width="80"
                 class="absolute stamp bg-white br-100 ba b--gray"
            />

            <!-- approver name -->
            <div>
              <p v-if="timesheetStatus == 'approved'" class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_approved_by') }}</p>
              <p v-else class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_rejected_by') }}</p>

              <inertia-link v-if="hasID" :href="approverInformation.url" class="ma0">{{ approverInformation.name }}</inertia-link>
              <p v-else class="ma0">{{ approverInformation.name }}</p>
            </div>

            <!-- approved date -->
            <div>
              <p v-if="timesheetStatus == 'approved'" class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_approved_on') }}</p>
              <p v-else class="ttu f7 mb1 mt0">{{ $t('dashboard.timesheet_rejected_on') }}</p>
              <p class="ma0">{{ approverInformation.approved_at }}</p>
            </div>
          </div>

          <!-- add a new row -->
          <form v-if="displayNewEntry" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="addBlankTimesheetRow()">
            <!-- message to display if there are no projects in the account -->
            <div v-if="projects.length == 0" class="tc">
              {{ $t('dashboard.timesheet_no_projects') }}
              <inertia-link :href="'/' + $page.props.auth.company.id + '/company/projects/create'">{{ $t('dashboard.timesheet_create_project') }}</inertia-link>
            </div>

            <div v-else>
              <label class="db mb-2">
                {{ $t('dashboard.timesheet_create_choose_project') }}
              </label>
              <a-select
                v-model:value="form.project"
                :placeholder="$t('dashboard.timesheet_create_choose_project')"
                style="width: 400px; margin-bottom: 10px;"
                :options="projects"
                label-in-value
                show-search
                option-filter-prop="label"
                @change="showTasks"
              />

              <div v-if="displayTasks">
                <label class="db mb-2">
                  {{ $t('dashboard.timesheet_create_choose_task') }}
                </label>
                <a-select
                  v-model:value="form.task"
                  :placeholder="$t('dashboard.timesheet_create_choose_task')"
                  style="width: 400px; margin-bottom: 10px;"
                  :options="tasks"
                  show-search
                  option-filter-prop="label"
                  label-in-value
                  @change="setTaskDetails"
                />
              </div>
            </div>

            <!-- Actions -->
            <div v-if="projects.length != 0">
              <loading-button :class="'btn add w-auto-ns w-100 mb2 mr2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-timesheet-new-row'" />
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
            <timesheet-row v-for="row in timesheetRows" :key="row.task_id"
                           :row-coming-from-backend="row"
                           :timesheet="timesheet"
                           :timesheet-status="timesheetStatus"
                           @day-updated="refreshDayInformation"
                           @update-weekly-total="updateWeeklyTotal"
                           @row-deleted="removeRow"
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

              <!-- daily total -->
              <div v-for="n in 7" :key="n" class="tc pv2 dtc bt bl bb bb-gray f7 gray"
                   :class="[ n === 6 || n === 7 ? 'off-days' : '' ]"
              >
                {{ formatTime(dailyStats[n-1]) }}
              </div>
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
import LoadingButton from '@/Shared/LoadingButton';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    DashboardMenu,
    TimesheetRow,
    TimesheetHeader,
    LoadingButton,
    Help,
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
    approverInformation: {
      type: Array,
      default: null,
    },
    rejectedTimesheets: {
      type: Array,
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
      newEntry: {
        projectId: null,
        projectName: null,
        taskId: null,
        taskName: null,
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

  computed: {
    hasID() {
      return this.containsKey(this.approverInformation, 'id');
    }
  },

  mounted() {
    this.timesheetRows = this.timesheet.entries;
    this.timesheetStatus = this.timesheet.status;
    this.refreshWeeklyTotal();

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },

  methods: {
    // check if the object contains a specific key
    containsKey(obj, key ) {
      return Object.keys(obj).includes(key);
    },

    // Copy the information from the TimesheetRow to the localTimesheetRow, as
    // the data has changed in the child
    refreshDayInformation({id, day, value}) {
      var id = this.timesheetRows.findIndex(x => x.task_id === id);
      this.timesheetRows[id].days[day].total_of_minutes = value;
    },

    showProjectList() {
      this.getProjectList();
      this.displayNewEntry = true;

      this.form.project = null;
      this.form.task = null;
    },

    showTasks(value) {
      this.newEntry.projectId = value.key;
      this.newEntry.projectName = value.label;
      this.getTasksList();
      this.displayTasks = true;
    },

    setTaskDetails(value) {
      this.newEntry.taskId = value.key;
      this.newEntry.taskName = value.label;
    },

    getProjectList() {
      axios.get(this.timesheet.url.project_list)
        .then(response => {
          this.projects = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    getTasksList() {
      axios.get(`/${this.$page.props.auth.company.id}/dashboard/timesheet/${this.timesheet.id}/projects/${this.newEntry.projectId}/tasks`)
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
        project_id: this.newEntry.projectId,
        project_name: this.newEntry.projectName,
        project_code: this.newEntry.projectId,
        task_id: this.newEntry.taskId,
        task_title: this.newEntry.taskName,
        total_this_week: 0,
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
      this.timesheetRows[id].total_this_week = value;

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
    },

    removeRow({id}) {
      var id = this.timesheetRows.findIndex(x => x.task_id === id);
      this.timesheetRows.splice(id, 1);
    }
  },
};
</script>
