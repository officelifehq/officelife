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

.expense-action {
  width: 80px;
}

.expense-status {
  width: 180px;
}

.expense-amount {
  width: 150px;
}

.expense-badge-waiting {
  background-color: #FFF6E4;
  color: #083255;
}
</style>

<template>
  <div :class="'mb5'">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        💵
      </span> {{ $t('dashboard.manager_expense_title') }}

      <help :url="$page.props.help_links.manager_expenses" :datacy="'help-icon-expense'" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box pa3 relative">
      <!-- BLANK STATE -->
      <div v-if="expenses.length == 0" data-cy="expense-list-blank-state">
        <img loading="lazy" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-cheer-party-4@140x140.png" />

        <p class="fw5 mt3 tc">{{ $t('dashboard.manager_expense_blank_state') }}</p>
      </div>

      <!-- LIST OF IN PROGRESS EXPENSES -->
      <div v-if="expenses.length > 0">
        <p class="mt0 mb2 lh-copy f6">{{ $t('dashboard.manager_expense_description') }}</p>
        <ul class="list pl0 mb0">
          <li v-for="expense in expenses" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="expense-item dt-ns br bl bb bb-gray bb-gray-hover pa3 w-100">
            <div class="mb3">
              <small-name-and-avatar
                v-if="expense.employee.id"
                :name="expense.employee.name"
                :avatar="expense.employee.avatar"
                :class="'gray'"
                :size="'18px'"
                :top="'0px'"
                :margin-between-name-avatar="'25px'"
              />

              <span v-else>{{ expense.employee.employee_name }}</span>
            </div>

            <div class="dt-row-ns">
              <div class="dtc-ns db mb3 mb0-ns">
                <inertia-link :href="expense.url" :data-cy="'expense-cta-' + expense.id" class="dib mb2">{{ expense.title }}</inertia-link>
                <ul class="f7 fw3 grey list pl0">
                  <li class="mr2 di">{{ expense.expensed_at }}</li>
                  <li v-if="expense.category" class="di">{{ expense.category }}</li>
                </ul>
              </div>

              <div class="expense-amount tc-ns dtc-ns v-mid fw5 db mb3 mb0-ns">
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
</template>

<script>
import Help from '@/Shared/Help';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Help,
    SmallNameAndAvatar,
  },

  props: {
    expenses: {
      type: Array,
      default: null,
    },
    defaultCurrency: {
      type: Object,
      default: null,
    },
  },
};
</script>
