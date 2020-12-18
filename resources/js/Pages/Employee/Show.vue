<style lang="scss" scoped>
.avatar {
  width: 80px;
  height: 80px;
  top: 19%;
  left: 50%;
  margin-top: -40px; /* Half the height */
  margin-left: -40px; /* Half the width */
}

.you {
  background-color: #e6fffa;
  border-color: #38b2ac;
  color: #234e52;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- HEADER -->
      <header-employee
        :employee="employee"
        :permissions="permissions"
        :employee-teams="employeeTeams"
        :positions="positions"
        :teams="teams"
        :pronouns="pronouns"
      />

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

          <personal-description
            :employee="employee"
            :permissions="permissions"
          />

          <!-- skills -->
          <skills
            :employee="employee"
            :permissions="permissions"
            :skills="skills"
          />

          <location
            :employee="employee"
            :permissions="permissions"
          />

          <hierarchy
            :employee="employee"
            :permissions="permissions"
            :managers-of-employee="managersOfEmployee"
            :direct-reports="directReports"
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

          <question
            :questions="questions"
          />

          <recent-ships
            :ships="ships"
          />

          <expenses
            v-if="permissions.can_see_expenses"
            :expenses="expenses"
          />

          <one-on-one
            v-if="permissions.can_see_one_on_one_with_manager"
            :one-on-ones="oneOnOnes"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import HeaderEmployee from '@/Pages/Employee/Partials/HeaderEmployee';
import PersonalDescription from '@/Pages/Employee/Partials/PersonalDescription';
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

export default {
  components: {
    Layout,
    HeaderEmployee,
    PersonalDescription,
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
    employeeTeams: {
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
    teams: {
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
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
