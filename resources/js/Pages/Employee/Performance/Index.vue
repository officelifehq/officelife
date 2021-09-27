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

          <rate-your-manager-poll-results
            :surveys="surveys"
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ProfileHeader from '@/Pages/Employee/Partials/ProfileHeader';
import ProfileSidebar from '@/Pages/Employee/Partials/ProfileSidebar';
import ProfileTabSwitcher from '@/Pages/Employee/Partials/ProfileTabSwitcher';
import RateYourManagerPollResults from '@/Pages/Employee/Performance/Partials/RateYourManagerPollResults';
import OneOnOne from '@/Pages/Employee/Performance/Partials/OneOnOneWithManager';

export default {
  components: {
    Layout,
    Breadcrumb,
    ProfileHeader,
    ProfileSidebar,
    ProfileTabSwitcher,
    RateYourManagerPollResults,
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
    notifications: {
      type: Array,
      default: null,
    },
    employeeTeams: {
      type: Array,
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
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  }
};

</script>
