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

          <softwares
            v-if="permissions.can_see_software"
            :softwares="softwares"
          />

          <!-- <holidays
            :employee="employee"
          /> -->
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
import Expenses from '@/Pages/Employee/Administration/Partials/Expenses';
import Timesheets from '@/Pages/Employee/Administration/Partials/Timesheets';
import Hardware from '@/Pages/Employee/Administration/Partials/Hardware';
import Softwares from '@/Pages/Employee/Administration/Partials/Software';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    Expenses,
    Timesheets,
    Hardware,
    Softwares,
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
    softwares: {
      type: Object,
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
