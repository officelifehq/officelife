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

          <timesheets
            v-if="permissions.can_see_timesheets"
            :timesheets="timesheets"
          />

          <expenses
            v-if="permissions.can_see_expenses"
            :expenses="expenses"
          />

          <hardware
            v-if="permissions.can_see_hardware"
            :hardware="hardware"
          />

          <holidays
            :employee="employee"
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
import Expenses from '@/Pages/Employee/Administration/Partials/Expenses';
import Timesheets from '@/Pages/Employee/Administration/Partials/Timesheets';
import Hardware from '@/Pages/Employee/Administration/Partials/Hardware';
import Holidays from '@/Pages/Employee/Administration/Partials/Holidays';

export default {
  components: {
    Layout,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    Expenses,
    Timesheets,
    Hardware,
    Holidays,
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
    workFromHomes: {
      type: Object,
      default: null,
    },
    hardware: {
      type: Array,
      default: null,
    },
    expenses: {
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
