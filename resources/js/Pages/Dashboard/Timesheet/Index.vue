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
        <div class="mt0 mb4 lh-copy f6 flex justify-between items-center">
          <ul class="list pl0 ma0">
            <li class="di"><inertia-link :href="previousTimesheet.url" class="btn dib">sss</inertia-link></li>
            <li class="di">30/Nov/2020 - 06/Dec/2020</li>
            <li class="di"><inertia-link :href="nextTimesheet.url" class="btn dib">></inertia-link></li>
          </ul>

          <a v-if="!displayNewEntry" class="btn f5" @click.prevent="showProjectList()">
            add a new row
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
                {{ timesheet.days.monday.short }}
              </span>
              <span class="f7 gray">
                {{ timesheet.days.monday.full }}
              </span>
            </div>
            <!-- tuesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ timesheet.days.tuesday.short }}
              </span>
              <span class="f7 gray">
                {{ timesheet.days.tuesday.full }}
              </span>
            </div>
            <!-- wednesday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ timesheet.days.wednesday.short }}
              </span>
              <span class="f7 gray">
                {{ timesheet.days.wednesday.full }}
              </span>
            </div>
            <!-- thursday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ timesheet.days.thursday.short }}
              </span>
              <span class="f7 gray">
                {{ timesheet.days.thursday.full }}
              </span>
            </div>
            <!-- friday -->
            <div class="tc pv2 dtc bl bb bb-gray">
              <span class="db">
                {{ timesheet.days.friday.short }}
              </span>
              <span class="f7 gray">
                {{ timesheet.days.friday.full }}
              </span>
            </div>
            <!-- saturday -->
            <div class="tc pv2 dtc bl bb bb-gray off-days">
              <span class="db">
                {{ timesheet.days.saturday.short }}
              </span>
              <span class="f7 gray">
                {{ timesheet.days.saturday.full }}
              </span>
            </div>
            <!-- sunday -->
            <div class="tc pv2 bl bb bb-gray off-days">
              <span class="db">
                {{ timesheet.days.sunday.short }}
              </span>
              <span class="f7 gray">
                {{ timesheet.days.sunday.full }}
              </span>
            </div>
          </div>

          <!-- entries -->
          <timesheet-row v-for="row in timesheetRows" :key="row.id" :row="row" :timesheet="timesheet" @update-day="updateDay" />

          <!-- total -->
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
    this.timesheetRows = this.timesheet.entries;

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

    showProjectList() {
      this.form.project = null;
      this.form.task = null;

      this.getProjectList();
      this.displayNewEntry = true;
    },

    showTasks() {
      this.getTasks();
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

    getTasks() {
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
        days: {
          monday: {
            day_of_week: 1,
            total_of_hours: 0,
          },
          tuesday: {
            day_of_week: 2,
            total_of_hours: 0,
          },
          wednesday: {
            day_of_week: 3,
            total_of_hours: 0,
          },
          thursday: {
            day_of_week: 4,
            total_of_hours: 0,
          },
          friday: {
            day_of_week: 5,
            total_of_hours: 0,
          },
          saturday: {
            day_of_week: 6,
            total_of_hours: 0,
          },
          sunday: {
            day_of_week: 7,
            total_of_hours: 0,
          },
        },
      });

      this.displayNewEntry = false;
    },
  },
};
</script>
