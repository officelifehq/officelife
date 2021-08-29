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

      <!-- Main content -->
      <div class="cf mw9 center br3 mb5">
        <!-- left column -->
        <div class="fl w-25 pa2">
          <profile-sidebar
            :employee="employee"
            :permissions="permissions"
            :uploadcare-public-key="uploadcarePublicKey"
          />
        </div>

        <!-- right column -->
        <div class="fl w-75 pa2 pl4-l">
          <profile-header
            :employee="employee"
            :permissions="permissions"
          />

          <profile-tab-switcher
            :employee="employee"
            :permissions="permissions"
            :menu="menu"
          />

          <hierarchy
            :employee="employee"
            :permissions="permissions"
            :managers-of-employee="managersOfEmployee"
            :direct-reports="directReports"
          />

          <employee-past-positions
            v-if="positions.length != 0"
            :positions="positions"
          />

          <skills
            :employee="employee"
            :permissions="permissions"
            :skills="skills"
          />

          <question
            :questions="questions"
          />

          <e-coffee
            v-if="ecoffees.eCoffees.length > 0"
            :ecoffees="ecoffees"
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
import Hierarchy from '@/Pages/Employee/Partials/Hierarchy';
import Question from '@/Pages/Employee/Partials/Question';
import Skills from '@/Pages/Employee/Partials/Skills';
import ECoffee from '@/Pages/Employee/Partials/ECoffee';
import EmployeePastPositions from '@/Pages/Employee/Partials/EmployeePastPositions';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    Hierarchy,
    Question,
    Skills,
    ECoffee,
    EmployeePastPositions,
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
    ecoffees: {
      type: Object,
      default: null,
    },
    uploadcarePublicKey: {
      type: String,
      default: null,
    },
    positions: {
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
