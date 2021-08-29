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
                  :previous-url="'/' + $page.props.auth.company.id + '/dashboard/expenses'"
                  :previous="$t('app.breadcrumb_employee_expenses')"
                  :has-more="false"
      >
        {{ $t('dashboard.manager_expense_detail_expense_section') }}
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
    expense: {
      type: Object,
      default: null,
    },
  },
};

</script>
