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
import ProfileHeader from '@/Pages/Employee/Partials/ProfileHeader';
import ProfileSidebar from '@/Pages/Employee/Partials/ProfileSidebar';
import ProfileTabSwitcher from '@/Pages/Employee/Partials/ProfileTabSwitcher';
import Hierarchy from '@/Pages/Employee/Partials/Hierarchy';
import Question from '@/Pages/Employee/Partials/Question';
import Skills from '@/Pages/Employee/Partials/Skills';
import ECoffee from '@/Pages/Employee/Partials/ECoffee';

export default {
  components: {
    Layout,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    Hierarchy,
    Question,
    Skills,
    ECoffee,
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
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
