<style lang="scss" scoped>
.you {
  background-color: #e6fffa;
  border-color: #38b2ac;
  color: #234e52;
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

      <!-- Main content -->
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
            :teams="teams"
          />

          <profile-tab-switcher
            :employee="employee"
            :menu="menu"
          />

          <hierarchy
            :employee="employee"
            :permissions="permissions"
            :managers-of-employee="managersOfEmployee"
            :direct-reports="directReports"
          />

          <skills
            :employee="employee"
            :permissions="permissions"
            :skills="skills"
          />

          <question
            :questions="questions"
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

        <!-- LEFT COLUMN -->
        <div class="fl w-40-l w-100">
          <location
            :employee="employee"
            :permissions="permissions"
          />
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-60-l w-100 pl4-l">
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
import Hierarchy from '@/Pages/Employee/Partials/Hierarchy';
import Location from '@/Pages/Employee/Partials/Location';
import Question from '@/Pages/Employee/Partials/Question';
import Skills from '@/Pages/Employee/Partials/Skills';

export default {
  components: {
    Layout,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    Hierarchy,
    Location,
    Question,
    Skills,
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
    questions: {
      type: Array,
      default: null,
    },
    skills: {
      type: Array,
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
