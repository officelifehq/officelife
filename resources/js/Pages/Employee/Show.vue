<style lang="scss" scoped>
.you {
  background-color: #e6fffa;
  border-color: #38b2ac;
  color: #234e52;
}

.avatar {
  border: 1px solid #e1e4e8 !important;
  padding: 10px;
  background-color: #fff;
  border-radius: 7px;

  img {
    width: 100%;
    height: auto;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns mt4">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw7 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees'">{{ $t('app.breadcrumb_employee_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ employee.name }}
          </li>
        </ul>
      </div>

      <!-- -->
      <div class="cf mw9 center br3 mb5">
        <div class="fl w-25 pa2">
          <div class="db center mb4 avatar">
            <img :class="{'black-white':(employee.locked)}" loading="lazy" :src="employee.avatar" alt="avatar" />
          </div>

          <personal-description
            :employee="employee"
            :permissions="permissions"
          />

          <employee-important-dates
            :employee="employee"
            :permissions="permissions"
          />

          <employee-gender-pronoun
            :employee="employee"
            :pronouns="pronouns"
            :permissions="permissions"
          />

          <employee-status
            :employee="employee"
            :permissions="permissions"
          />

          <employee-contact
            :employee="employee"
            :permissions="permissions"
          />
        </div>

        <div class="fl w-75 pa2 pl4-l">
          <!-- information about the employee -->
          <headers
            :employee="employee"
            :permissions="permissions"
            :positions="positions"
            :teams="teams"
          />

          <div class="cf mw7 center br3 mt3 mb5 tc">
            <div class="cf dib btn-group">
              <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(menu == 'all')}">
                Presentation
              </inertia-link>
              <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'performance')}" data-cy="performance-tab">
                Work
              </inertia-link>
              <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'performance')}" data-cy="performance-tab">
                {{ $t('employee.menu_performance') }}
              </inertia-link>
              <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'performance')}" data-cy="performance-tab">
                Administration
              </inertia-link>
            </div>
          </div>

          <hierarchy
            :employee="employee"
            :permissions="permissions"
            :managers-of-employee="managersOfEmployee"
            :direct-reports="directReports"
          />

          <!-- skills -->
          <skills
            :employee="employee"
            :permissions="permissions"
            :skills="skills"
          />

          <question
            :questions="questions"
          />

          <recent-ships
            :ships="ships"
          />
        </div>
      </div>
    </div>


    <div class="ph2 ph5-ns">
      <!-- CENTRAL CONTENT -->
      <div class="cf mw9 center">
        <template v-if="employee.locked">
          <div class="w-30 center tc ba bb-gray ph3 pv2 mb4 br3 bg-white">
            🔐 {{ $t('employee.account_locked') }}
          </div>
        </template>

        <!-- menu -->
        <div v-if="permissions.can_see_performance_tab && surveys" class="cf mw7 center br3 mt3 mb5 tc" data-cy="employee-tab">
          <div class="cf dib btn-group">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(menu == 'all')}">
              {{ $t('employee.menu_all_information') }}
            </inertia-link>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/performance'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'performance')}" data-cy="performance-tab">
              {{ $t('employee.menu_performance') }}
            </inertia-link>
          </div>
        </div>

        <!-- LEFT COLUMN -->
        <div class="fl w-40-l w-100">
          <work-from-home
            :employee="employee"
            :permissions="permissions"
            :statistics="workFromHomes"
          />

          <location
            :employee="employee"
            :permissions="permissions"
          />

          <hardware
            v-if="permissions.can_see_hardware"
            :hardware="hardware"
          />

          <holidays
            :employee="employee"
          />
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-60-l w-100 pl4-l">
          <worklogs
            :permissions="permissions"
            :worklogs="worklogs"
          />

          <expenses
            v-if="permissions.can_see_expenses"
            :expenses="expenses"
          />

          <one-on-one
            v-if="permissions.can_see_one_on_one_with_manager"
            :one-on-ones="oneOnOnes"
          />

          <timesheets
            v-if="permissions.can_see_timesheets"
            :timesheets="timesheets"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Headers from '@/Pages/Employee/Partials/Headers';
import PersonalDescription from '@/Pages/Employee/Partials/PersonalDescription';
import EmployeeImportantDates from '@/Pages/Employee/Partials/EmployeeImportantDates';
import EmployeeStatus from '@/Pages/Employee/Partials/EmployeeStatus';
import EmployeeContact from '@/Pages/Employee/Partials/EmployeeContact';
import EmployeeGenderPronoun from '@/Pages/Employee/Partials/EmployeeGenderPronoun';
import Hierarchy from '@/Pages/Employee/Partials/Hierarchy';
import Worklogs from '@/Pages/Employee/Partials/Worklogs';
import Holidays from '@/Pages/Employee/Partials/Holidays';
import Location from '@/Pages/Employee/Partials/Location';
import WorkFromHome from '@/Pages/Employee/Partials/WorkFromHome';
import Question from '@/Pages/Employee/Partials/Question';
import Hardware from '@/Pages/Employee/Partials/Hardware';
import RecentShips from '@/Pages/Employee/Partials/RecentShips';
import Skills from '@/Pages/Employee/Partials/Skills';
import Expenses from '@/Pages/Employee/Partials/Expenses';
import OneOnOne from '@/Pages/Employee/Partials/OneOnOneWithManager';
import Timesheets from '@/Pages/Employee/Partials/Timesheets';

export default {
  components: {
    Layout,
    Headers,
    PersonalDescription,
    EmployeeImportantDates,
    EmployeeStatus,
    EmployeeContact,
    EmployeeGenderPronoun,
    Hierarchy,
    Worklogs,
    Holidays,
    Location,
    WorkFromHome,
    Question,
    Hardware,
    RecentShips,
    Skills,
    Expenses,
    OneOnOne,
    Timesheets,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
    menu: {
      type: String,
      default: 'all',
    },
    teams: {
      type: Array,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    managersOfEmployee: {
      type: Array,
      default: null,
    },
    directReports: {
      type: Array,
      default: null,
    },
    positions: {
      type: Array,
      default: null,
    },
    worklogs: {
      type: Object,
      default: null,
    },
    pronouns: {
      type: Array,
      default: null,
    },
    workFromHomes: {
      type: Object,
      default: null,
    },
    questions: {
      type: Array,
      default: null,
    },
    hardware: {
      type: Array,
      default: null,
    },
    ships: {
      type: Array,
      default: null,
    },
    skills: {
      type: Array,
      default: null,
    },
    expenses: {
      type: Object,
      default: null,
    },
    surveys: {
      type: Object,
      default: null,
    },
    oneOnOnes: {
      type: Object,
      default: null,
    },
    timesheets: {
      type: Object,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
