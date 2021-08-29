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
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/employees'"
        :root="$t('app.breadcrumb_employee_list')"
        :has-more="false"
      >
        {{ employee.name }}
      </breadcrumb>

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

          <worklogs
            :permissions="permissions"
            :employee="employee"
            :worklog="worklog"
            :weeks="weeks"
          />

          <projects
            :employee="employee"
            :projects="projects"
          />

          <groups
            :employee="employee"
            :groups="groups"
          />

          <recent-ships
            :ships="ships"
          />

          <work-from-home
            v-if="workFromHomes.feature_enabled"
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProfileHeader from '@/Pages/Employee/Partials/ProfileHeader';
import ProfileSidebar from '@/Pages/Employee/Partials/ProfileSidebar';
import ProfileTabSwitcher from '@/Pages/Employee/Partials/ProfileTabSwitcher';
import Worklogs from '@/Pages/Employee/Work/Partials/Worklogs';
import WorkFromHome from '@/Pages/Employee/Work/Partials/WorkFromHome';
import RecentShips from '@/Pages/Employee/Work/Partials/RecentShips';
import Projects from '@/Pages/Employee/Work/Partials/Projects';
import Groups from '@/Pages/Employee/Work/Partials/Groups';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    Worklogs,
    WorkFromHome,
    RecentShips,
    Projects,
    Groups,
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
    worklog: {
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
    groups: {
      type: Array,
      default: null,
    },
    weeks: {
      type: Array,
      default: null,
    },
  },
};

</script>
