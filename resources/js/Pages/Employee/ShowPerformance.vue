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
        :employee-teams="employeeTeams"
        :positions="positions"
        :teams="teams"
        :statuses="statuses"
        :pronouns="pronouns"
      />

      <!-- CENTRAL CONTENT -->
      <div class="cf mw9 center">
        <template v-if="employee.locked">
          <div class="w-30 center tc ba bb-gray ph3 pv2 mb4 br3 bg-white">
            🔐 {{ $t('employee.account_locked') }}
          </div>
        </template>

        <div class="cf mw7 center br3 mt3 mb5 tc">
          <div class="cf dib btn-group">
            <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id" class="f6 fl ph3 pv2 dib pointer no-underline" :class="{'selected':(menu == 'all')}">
              All information
            </inertia-link>
            <inertia-link :href="'/' + $page.auth.company.id + '/employees/' + employee.id + '/performance'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':(menu == 'performance')}" data-cy="dashboard-team-tab">
              Performance
            </inertia-link>
          </div>
        </div>

        <!-- LEFT COLUMN -->
        <div class="fl w-40-l w-100">
        </div>

        <!-- RIGHT COLUMN -->
        <div class="fl w-60-l w-100 pl4-l">
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import vClickOutside from 'v-click-outside';
import Layout from '@/Shared/Layout';
import HeaderEmployee from '@/Pages/Employee/Partials/HeaderEmployee';

export default {
  components: {
    Layout,
    HeaderEmployee,
  },

  props: {
    employee: {
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
    positions: {
      type: Array,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
    statuses: {
      type: Array,
      default: null,
    },
    pronouns: {
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

  methods: {
    employeeOrAtLeastHR() {
      if (this.$page.auth.employee.permission_level <= 200) {
        return true;
      }

      if (!this.employee.user) {
        return false;
      }

      if (this.$page.auth.user.id == this.employee.user.id) {
        return true;
      }
    },

    employeeOrAccountant() {
      if (this.isAccountant) {
        return true;
      }

      if (this.employee.user) {
        if (this.$page.auth.user.id == this.employee.user.id) {
          return true;
        }
      }

      return false;
    }
  }
};

</script>
