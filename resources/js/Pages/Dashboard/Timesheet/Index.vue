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
        <div class="mt0 mb4 lh-copy f6 tc">
          <ul class="list pl0 ma0">
            <li class="di mr3"><inertia-link :href="previousTimesheet.url" class="dib">&lt; {{ $t('dashboard.timesheet_previous_week') }}</inertia-link></li>
            <li class="di mr3 fw5">{{ timesheet.start_date }} - {{ timesheet.end_date }}</li>
            <li class="di"><inertia-link :href="nextTimesheet.url" class="dib">{{ $t('dashboard.timesheet_next_week') }} &gt;</inertia-link></li>
          </ul>
        </div>

        <div class="tr mb3">
          <a v-if="!displayNewEntry" class="btn f5" @click.prevent="showProjectList()">
            {{ $t('dashboard.timesheet_add_new') }}
          </a>
        </div>

        <!-- add a new row -->
        <form v-if="displayNewEntry" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="addTimesheetRow()">
          <span class="bb b--dotted bt-0 bl-0 br-0 pointer">
            <select-box :id="'employee_id'"
                        v-model="form.project"
                        :options="projects"
                        :name="'employee_id'"
                        :errors="$page.props.errors.employee_id"
                        :placeholder="'Choose a project'"
                        :label="'Choose a project'"
                        :value="form.employee_id"
                        :datacy="'employee-selector'"
                        :required="true"
                        @input="showTasks($event)"
            />

            <select-box
              v-if="displayTasks"
              :id="'employee_id'"
              v-model="form.task"
              :options="tasks"
              :name="'employee_id'"
              :errors="$page.props.errors.employee_id"
              :placeholder="'Select a task'"
              :label="'Select a task'"
              :value="form.employee_id"
              :required="true"
              :datacy="'employee-selector'"
              @input="showTasks($event)"
            />
          </span>

          <!-- Actions -->
          <div>
            <loading-button :classes="'btn add w-auto-ns w-100 mb2 mr2 pv2 ph3'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-update-news-button'" />
            <a data-cy="cancel-button" class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click.prevent="displayNewEntry = false">
              {{ $t('app.cancel') }}
            </a>
          </div>
        </form>

        <div class="dt bt br bb-gray w-100">
          <!-- header -->
          <div class="dt-row">
            <div class="dtc bl bb bb-gray project">
            </div>
            <!-- monday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ daysHeader.monday.short }}
              </span>
              <span class="f7 gray">
                {{ daysHeader.monday.full }}
              </span>
            </div>
            <!-- tuesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ daysHeader.tuesday.short }}
              </span>
              <span class="f7 gray">
                {{ daysHeader.tuesday.full }}
              </span>
            </div>
            <!-- wednesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ daysHeader.wednesday.short }}
              </span>
              <span class="f7 gray">
                {{ daysHeader.wednesday.full }}
              </span>
            </div>
            <!-- thursday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ daysHeader.thursday.short }}
              </span>
              <span class="f7 gray">
                {{ daysHeader.thursday.full }}
              </span>
            </div>
            <!-- friday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ daysHeader.friday.short }}
              </span>
              <span class="f7 gray">
                {{ daysHeader.friday.full }}
              </span>
            </div>
            <!-- saturday -->
            <div class="tc pv2 dtc bl bb bb-gray off-days">
              <span class="db">
                {{ daysHeader.saturday.short }}
              </span>
              <span class="f7 gray">
                {{ daysHeader.saturday.full }}
              </span>
            </div>
            <!-- sunday -->
            <div class="tc pv2 bl bb bb-gray off-days">
              <span class="db">
                {{ daysHeader.sunday.short }}
              </span>
              <span class="f7 gray">
                {{ daysHeader.sunday.full }}
              </span>
            </div>
          </div>

          <!-- entries -->
          <timesheet-row v-for="row in timesheetRows" :key="row.id"
                         :row-coming-from-backend="row"
                         :timesheet="timesheet"
                         @update-day="updateDay"
                         @update-weekly-total="updateWeeklyTotal"
          />

          <!-- <div v-if="timesheetRows.length == 0">
            <img loading="lazy" src="/img/streamline-icon-building-site@140x140.png" width="140" alt="building" class="top-1 left-1" />
            <p>You haven't added any projects yet to track your time against.</p>
          </div> -->

          <!-- total -->
          <div class="dt-row">
            <div class="f6 ph2 dtc bt bl bb bb-gray project v-mid">
              <div class="flex justify-between items-center">
                <span class="db pb1 fw5">
                  Total
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
import SelectBox from '@/Shared/Select';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    DashboardMenu,
    TimesheetRow,
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
      projects: [],
      tasks: [],
      dailyStats: [0, 0, 0, 0, 0, 0, 0, 0],
      weeklyTotalHumanReadable: 0,
    };
  },

  mounted() {
    this.timesheetRows = this.timesheet.entries;
    this.refreshWeeklyTotal();

    if (localStorage.success) {
      flash(localStorage.success, 'success');

      localStorage.removeItem('success');
    }
  },

  methods: {
    updateDay({id, day, value}) {
      var id = this.timesheetRows.findIndex(x => x.task_id === id);
      var row = this.timesheetRows[id];
      row.days[day].total_of_minutes = value;
      this.$set(this.timesheetRows, id, row);
    },

    showProjectList() {
      this.form.project = null;
      this.form.task = null;

      this.getProjectList();
      this.displayNewEntry = true;
    },

    showTasks() {
      this.getTasksList();
      this.displayTasks = true;
    },

    getProjectList() {
      axios.get('/' + this.$page.props.auth.company.id + '/dashboard/timesheet/projects')
        .then(response => {
          this.projects = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    getTasksList() {
      axios.get('/' + this.$page.props.auth.company.id + '/dashboard/timesheet/' + this.timesheet.id + '/projects/' + this.form.project.value + '/tasks')
        .then(response => {
          this.tasks = response.data.data;
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    addTimesheetRow() {
      this.timesheetRows.push({
        project_id: this.form.project.value,
        project_name: this.form.project.label,
        project_code: this.form.project.code,
        task_id: this.form.task.value,
        task_title: this.form.task.label,
        total_this_week: 0,
        data_from_backend: false,
        days: {
          monday: {
            day_of_week: 1,
            total_of_minutes: 0,
          },
          tuesday: {
            day_of_week: 2,
            total_of_minutes: 0,
          },
          wednesday: {
            day_of_week: 3,
            total_of_minutes: 0,
          },
          thursday: {
            day_of_week: 4,
            total_of_minutes: 0,
          },
          friday: {
            day_of_week: 5,
            total_of_minutes: 0,
          },
          saturday: {
            day_of_week: 6,
            total_of_minutes: 0,
          },
          sunday: {
            day_of_week: 7,
            total_of_minutes: 0,
          },
        },
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
