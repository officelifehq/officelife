<style scoped>
.avatar {
  width: 80px;
  height: 80px;
  top: 19%;
  left: 50%;
  margin-top: -40px; /* Half the height */
  margin-left: -40px; /* Half the width */
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw7 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">
              {{ $page.auth.company.name }}
            </inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/employees'">
              {{ $t('app.breadcrumb_employee_list') }}
            </inertia-link>
          </li>
          <li class="di">
            {{ employee.name }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw9 center br3 mb4 bg-white box relative z-1">
        <div class="pa3 relative pt5">
          <!-- EDIT BUTTON -->
          <img v-show="$page.auth.employee.permission_level <= 200 || $page.auth.user.user_id == employee.user.id" src="/img/menu_button.svg" class="box-edit-button absolute br-100 pa2 bg-white pointer" data-cy="edit-profile-button" @click="profileMenu = true" />

          <!-- EDIT MENU -->
          <div v-if="profileMenu" v-click-outside="toggleProfileMenu" class="popupmenu absolute br2 bg-white z-max tl pv2 ph3 bounceIn faster">
            <ul class="list ma0 pa0">
              <li v-show="$page.auth.employee.permission_level <= 200" class="pv2">
                <a class="pointer" data-cy="add-manager-button">Edit</a>
              </li>
              <li v-show="$page.auth.employee.permission_level <= 200" class="pv2">
                <a class="pointer" data-cy="add-direct-report-button">Delete</a>
              </li>
              <li v-show="$page.auth.employee.permission_level <= 200 || $page.auth.user.user_id == employee.user.id" class="pv2">
                <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id + '/logs'" class="pointer" data-cy="view-log-button">
                  View change log
                </inertia-link>
              </li>
            </ul>
          </div>

          <!-- AVATAR -->
          <img :src="employee.avatar" class="avatar absolute br-100 db center" width="80" height="80" />
          <h2 class="tc normal mb1">
            {{ employee.name }}
          </h2>
          <ul class="list tc pa0 f6 mb0">
            <li class="di-l db mb0-l mb2 mr2">
              <assign-employee-position
                :employee="employee"
                :positions="positions"
              />
            </li>
            <li class="di-l db mb0-l mb2 mr2">
              No hire date
            </li>
            <li class="di-l db mb0-l mb2 mr2">
              <assign-employee-status
                :employee="employee"
                :statuses="statuses"
              />
            </li>
            <li class="di-l db mb0-l mb2">
              <assign-employee-team
                :employee="employee"
                :teams="teams"
              />
            </li>
          </ul>
        </div>
      </div>

      <div class="cf mw9 center">
        <!-- LEFT COLUMN -->
        <div class="fl w-40-l w-100">
          <assign-employee-hierarchy
            :employee="employee"
            :managers="managers"
            :direct-reports="directReports"
          />
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-60-l w-100 pl4-l">
          <worklogs
            :worklogs="worklogs"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import vClickOutside from 'v-click-outside';
import Layout from '@/Shared/Layout';
import AssignEmployeePosition from '@/Pages/Employee/AssignEmployeePosition';
import AssignEmployeeStatus from '@/Pages/Employee/AssignEmployeeStatus';
import AssignEmployeeTeam from '@/Pages/Employee/AssignEmployeeTeam';
import AssignEmployeeHierarchy from '@/Pages/Employee/AssignEmployeeHierarchy';
import Worklogs from '@/Pages/Employee/Worklogs';

export default {
  components: {
    Layout,
    AssignEmployeePosition,
    AssignEmployeeStatus,
    AssignEmployeeTeam,
    AssignEmployeeHierarchy,
    Worklogs,
  },

  directives: {
    clickOutside: vClickOutside.directive
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
    managers: {
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
      type: Array,
      default: null,
    },
    statuses: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      profileMenu: false,
    };
  },

  mounted() {
    if (localStorage.success) {
      this.$snotify.success(localStorage.success, {
        timeout: 2000,
        showProgressBar: true,
        closeOnClick: true,
        pauseOnHover: true,
      });
      localStorage.clear();
    }
  },

  methods: {
    toggleProfileMenu() {
      this.profileMenu = false;
    },
  }
};

</script>
