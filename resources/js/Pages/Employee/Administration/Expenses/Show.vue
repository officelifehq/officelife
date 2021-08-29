<style lang="scss" scoped>
.expense-summary {
  background-color: #E9EDF2;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/administration/expenses'"
                  :previous="$t('app.breadcrumb_employee_expenses')"
      >
        {{ $t('app.breadcrumb_employee_expense') }}
      </breadcrumb>

      <!-- BODY -->
      <expense-status :expense="expense" />
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import ExpenseStatus from '@/Shared/ExpenseStatus';

export default {
  components: {
    Layout,
    Breadcrumb,
    ExpenseStatus,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    expense: {
      type: Object,
      default: null,
    },
  },
};
</script>
