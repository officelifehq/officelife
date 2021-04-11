<style lang="scss" scoped>
.you {
  background-color: #e6fffa;
  border-color: #38b2ac;
  color: #234e52;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns mt4">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw7 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
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
          <profile-sidebar
            :employee="employee"
            :permissions="permissions"
          />
        </div>

        <div class="fl w-75 pa2 pl4-l">
          <!-- information about the employee -->
          <profile-header
            :employee="employee"
            :permissions="permissions"
          />

          <profile-tab-switcher
            :employee="employee"
            :permissions="permissions"
            :menu="menu"
          />

          <projects
            :employee="employee"
            :projects="projects"
          />

          <worklogs
            :permissions="permissions"
            :worklogs="worklogs"
          />

          <recent-ships
            :ships="ships"
          />

          <work-from-home
            :employee="employee"
            :permissions="permissions"
            :statistics="workFromHomes"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ProfileHeader from '@/Pages/Employee/Partials/ProfileHeader';
import ProfileSidebar from '@/Pages/Employee/Partials/ProfileSidebar';
import ProfileTabSwitcher from '@/Pages/Employee/Partials/ProfileTabSwitcher';
import Worklogs from '@/Pages/Employee/Work/Partials/Worklogs';
import WorkFromHome from '@/Pages/Employee/Work/Partials/WorkFromHome';
import RecentShips from '@/Pages/Employee/Work/Partials/RecentShips';
import Projects from '@/Pages/Employee/Work/Partials/Projects';

export default {
  components: {
    Layout,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    Worklogs,
    WorkFromHome,
    RecentShips,
    Projects,
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
    notifications: {
      type: Array,
      default: null,
    },
    worklogs: {
      type: Object,
      default: null,
    },
    workFromHomes: {
      type: Object,
      default: null,
    },
    ships: {
      type: Array,
      default: null,
    },
    projects: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
