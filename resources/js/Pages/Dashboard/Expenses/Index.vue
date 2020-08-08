<style scoped>
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
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ $page.auth.company.name }}
        </h2>
      </div>

      <dashboard-menu :employee="employee" />

      <!-- LIST OF EXPENSES THAT ARE AWAITING FOR ACCOUNTING VALIDATION -->
      <div class="cf mw7 center mb2 fw5">
        <span class="mr2">
          💵
        </span> {{ $t('dashboard.accountant_awaiting_accounting_title') }}

        <help :url="$page.help_links.accoutant_expenses" :datacy="'help-icon-expense'" />
      </div>

      <div class="cf mw7 center br3 mb5 bg-white box pa3 relative">
        <!-- list -->
        <div v-if="awaitingAccountingExpenses.length > 0">
          <ul class="list pl0 mt0 mb0">
            <li v-for="expense in awaitingAccountingExpenses" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="expense-item dt-ns br bl bb bb-gray bb-gray-hover pa3 w-100">
              <div class="mb3">
                <small-name-and-avatar
                  v-if="expense.employee"
                  :name="expense.employee.name"
                  :avatar="expense.employee.avatar"
                  :classes="'gray'"
                  :size="'18px'"
                  :top="'0px'"
                  :margin-between-name-avatar="'25px'"
                />

                <span v-else>{{ expense.employee_name }}</span>
              </div>

              <div class="dt-row-ns">
                <div class="dtc-ns db mb3 mb0-ns">
                  <div class="mb2">{{ expense.title }}</div>
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
                <div class="expense-action tr-ns dtc-ns v-mid f6 db">
                  <inertia-link :href="expense.url" :data-cy="'expense-cta-' + expense.id">{{ $t('app.view') }}</inertia-link>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-else>
          <img loading="lazy" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-ribbon-coin-1@140x140.png" />

          <p class="fw5 mt3 tc">{{ $t('dashboard.accounting_expense_blank_state') }}</p>
        </div>
      </div>

      <!-- LIST OF EXPENSES THAT ARE AWAITING FOR MANAGER VALIDATION -->
      <div class="cf mw7 center mb2 fw5">
        <span class="mr2">
          💵
        </span> {{ $t('dashboard.accountant_awaiting_manager_title') }}

        <help :url="$page.help_links.employee_expenses" :datacy="'help-icon-expense'" />
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box pa3 relative">
        <p class="mb2 mt0 lh-copy f6">{{ $t('dashboard.accounting_expense_managers_description') }}</p>

        <!-- list -->
        <div v-if="awaitingManagerExpenses.length > 0">
          <ul class="list pl0 mt0 mb0">
            <li v-for="expense in awaitingManagerExpenses" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="expense-item dt-ns br bl bb bb-gray bb-gray-hover pa3 w-100">
              <div class="mb3">
                <p class="ma0 mb2 f6 gray">{{ $t('dashboard.accounting_expense_managers_submitted_by') }}</p>
                <small-name-and-avatar
                  v-if="expense.employee"
                  :name="expense.employee.name"
                  :avatar="expense.employee.avatar"
                  :size="'18px'"
                  :top="'0px'"
                  :margin-between-name-avatar="'25px'"
                />

                <span v-else>{{ expense.employee_name }}</span>
              </div>

              <div v-if="expense.managers" class="mb3">
                <p class="ma0 mb2 f6 gray">{{ $t('dashboard.accounting_expense_managers_approvers') }}</p>
                <small-name-and-avatar
                  v-for="manager in expense.managers"
                  :key="manager.id"
                  :name="manager.name"
                  :avatar="manager.avatar"
                  :classes="'mr2'"
                  :size="'18px'"
                  :top="'0px'"
                  :margin-between-name-avatar="'25px'"
                />
              </div>

              <div class="dt-row-ns">
                <div class="dtc-ns db mb3 mb0-ns">
                  <div class="mb2">{{ expense.title }}</div>
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

        <!-- blank state -->
        <div v-else>
          <img loading="lazy" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-cheer-party-4@140x140.png" />

          <p class="fw5 mt3 tc">{{ $t('dashboard.accounting_expense_blank_state') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Help from '@/Shared/Help';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Help,
    Layout,
    DashboardMenu,
    SmallNameAndAvatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    awaitingAccountingExpenses: {
      type: Array,
      default: null,
    },
    awaitingManagerExpenses: {
      type: Array,
      default: null,
    },
  },
};
</script>
