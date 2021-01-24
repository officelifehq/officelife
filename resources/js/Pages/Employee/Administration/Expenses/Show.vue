<style lang="scss" scoped>
.expense-summary {
  background-color: #E9EDF2;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/administration/expenses'">{{ $t('app.breadcrumb_employee_expenses') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_expense') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <expense-status :expense="expense" />
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import ExpenseStatus from '@/Shared/ExpenseStatus';

export default {
  components: {
    Layout,
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
