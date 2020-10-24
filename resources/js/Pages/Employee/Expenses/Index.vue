<style lang="scss" scoped>
.expense-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.expense-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.expense-badge-rejected_by_manager,
.expense-badge-rejected_by_accounting {
  background-color: #E35763;
  color: #fff;
  height: 8px;
  top: 3px;
}

.expense-badge-manager_approval,
.expense-badge-accounting_approval {
  background-color: #FAF089;
  color: #083255;
  height: 8px;
  top: 3px;
}

.expense-badge-accepted {
  background-color: #68D391;
  color: #fff;
  height: 8px;
  top: 3px;
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" data-cy="breadcrumb-employee">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_expenses') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <h2 class="pa3 mt2 mb4 center tc normal">
          {{ $t('employee.expense_index_title') }}
        </h2>

        <div class="flex-ns justify-around dn">
          <div>
            <img loading="lazy" class="db center mb4" alt="total of expenses" src="/img/streamline-icon-startup-benefit@60x60.png" width="60" />
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ statistics.reimbursedAmount }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.expense_index_stat_total_reimbursed') }}</p>
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ statistics.numberOfAcceptedExpenses }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.expense_index_stat_total_accepted') }}</p>
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ statistics.numberOfInProgressExpenses }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.expense_index_stat_total_pending') }}</p>
          </div>
          <div>
            <p class="mt0 f3 mb2">{{ statistics.numberOfRejectedExpenses }}</p>
            <p class="mt0 f6 gray">{{ $t('employee.expense_index_stat_total_rejected') }}</p>
          </div>
        </div>

        <div v-if="expenses.length > 0">
          <ul class="list mt0 mb0 pb3 pr3 pl3">
            <li v-for="expense in expenses" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="expense-item dt-ns br bl bb bb-gray bb-gray-hover pa3 w-100">
              <div class="dt-row-ns">
                <div class="dtc-ns db mb3 mb0-ns">
                  <div class="relative">
                    <span class="br3 f7 fw3 ph2 pv1 dib relative mr1" :class="'expense-badge-' + expense.status" :data-cy="'expense-' + expense.id + '-status-' + expense.status"></span>
                    <inertia-link :href="expense.url" :data-cy="'expense-cta-' + expense.id" class="dib mb2">{{ expense.title }}</inertia-link>
                  </div>
                  <ul class="f7 fw3 grey list pl0">
                    <li class="mr2 di">{{ expense.expensed_at }}</li>
                    <li v-if="expense.category" class="di">{{ expense.category }}</li>
                  </ul>
                </div>

                <div class="expense-amount tr-ns dtc-ns v-mid fw5 db mb3 mb0-ns">
                  {{ expense.amount }}

                  <!-- converted amount -->
                  <div v-if="expense.converted_amount" class="db f6 fw4 mt2 gray">{{ expense.converted_amount }}</div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
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
    expenses: {
      type: Array,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  created() {
  },

  methods: {
  },
};

</script>
