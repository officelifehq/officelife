<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/employees'"
                  :previous="$t('app.breadcrumb_account_manage_employees')"
      >
        {{ $t('app.breadcrumb_account_employee_no_hiring_date') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.employees_all_no_hiring_date_title', {company: $page.props.auth.company.name}) }}
          </h2>

          <employee-list
            :employees="employees"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import EmployeeList from '@/Pages/Adminland/Employee/Partials/EmployeeList';

export default {
  components: {
    Layout,
    Breadcrumb,
    EmployeeList,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employees: {
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
