<style lang="scss" scoped>
.expense-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.expense-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.expense-amount {
  width: 150px;
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
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        💵
      </span> {{ $t('employee.expense_title') }}
    </span>

    <div class="br3 bg-white box z-1">
      <!-- list of expenses -->
      <div v-if="expenses.expenses.length > 0">
        <ul class="list mt0 mb0 pl0">
          <li v-for="expense in expenses.expenses" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="expense-item dt-ns bb bb-gray bb-gray-hover pa3 w-100">
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
        <div class="ph3 pv2 tc f6 bt bb-gray">
          <inertia-link :href="expenses.url" data-cy="view-all-expenses">{{ $t('employee.expense_view_history') }}</inertia-link>
        </div>
      </div>
      <!-- no expenses in the last 30 days, but older expenses exist  -->
      <div v-if="expenses.expenses.length == 0 && expenses.hasMorePastExpenses">
        <p class="pa3 tc ma0">{{ $t('employee.expense_more_past_expenses', { number: expenses.totalPastExpenses }) }}</p>
        <div class="ph3 pv2 tc f6 bt bb-gray">
          <inertia-link :href="expenses.url" data-cy="view-all-expenses">{{ $t('employee.expense_view_history') }}</inertia-link>
        </div>
      </div>

      <!-- blank state -->
      <p v-if="expenses.expenses.length == 0 && !expenses.hasMorePastExpenses" class="pa3 mb0 mt0 lh-copy f6" data-cy="expense-blank-state">{{ $t('employee.expense_blank') }}</p>
    </div>
  </div>
</template>

<script>
export default {

  props: {
    expenses: {
      type: Object,
      default: null,
    },
  },
};
</script>
