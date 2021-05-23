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

.expense-action {
  width: 80px;
}

.expense-status {
  width: 180px;
}

.expense-amount {
  width: 150px;
}

.expense-badge-rejected_by_manager,
.expense-badge-rejected_by_accounting {
  background-color: #E35763;
  color: #fff;
}

.expense-badge-accepted {
  background-color: #11A054;
  color: #fff;
}

.status-badge {
  width: 300px;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <dashboard-menu :employee="employee" />

      <!-- LIST OF EXPENSES THAT ARE AWAITING FOR ACCOUNTING VALIDATION -->
      <div class="cf mw7 center mb2 fw5">
        <span class="mr1">
          💵
        </span> {{ $t('dashboard.accountant_awaiting_accounting_title') }}

        <help :url="$page.props.help_links.accoutant_expenses" :datacy="'help-icon-expense'" />
      </div>

      <div class="cf mw7 center br3 mb5 bg-white box relative">
        <!-- list -->
        <div v-if="awaitingAccountingExpenses.length > 0">
          <ul class="list pl0 mt0 mb0">
            <li v-for="expense in awaitingAccountingExpenses" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="pa3 expense-item dt-ns bb bb-gray bb-gray-hover pa3 w-100">
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

        <!-- blank state -->
        <div v-else>
          <img loading="lazy" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-ribbon-coin-1@140x140.png" />

          <p class="fw5 mt3 tc">{{ $t('dashboard.accounting_expense_blank_state') }}</p>
        </div>
      </div>

      <!-- LIST OF EXPENSES THAT ARE AWAITING FOR MANAGER VALIDATION -->
      <div class="cf mw7 center mb2 fw5">
        <span class="mr1">
          💵
        </span> {{ $t('dashboard.accountant_awaiting_manager_title') }}

        <help :url="$page.props.help_links.employee_expenses" :datacy="'help-icon-expense'" />
      </div>

      <div class="cf mw7 center br3 mb5 bg-white box relative">
        <!-- list -->
        <div v-if="awaitingManagerExpenses.length > 0">
          <ul class="list mt0 mb0 pl0">
            <li v-for="expense in awaitingManagerExpenses" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="expense-item dt-ns bb bb-gray bb-gray-hover pa3 w-100">
              <div class="mb3">
                <p class="ma0 mb2 f6 gray">{{ $t('dashboard.accounting_expense_managers_submitted_by') }}</p>
                <small-name-and-avatar
                  v-if="expense.employee.id"
                  :name="expense.employee.name"
                  :avatar="expense.employee.avatar"
                  :size="'18px'"
                  :top="'1px'"
                  :margin-between-name-avatar="'25px'"
                />

                <span v-else>{{ expense.employee.employee_name }}</span>
              </div>

              <div v-if="expense.managers" class="mb3">
                <p class="ma0 mb2 f6 gray">{{ $t('dashboard.accounting_expense_managers_approvers') }}</p>
                <small-name-and-avatar
                  v-for="manager in expense.managers"
                  :key="manager.id"
                  :name="manager.name"
                  :avatar="manager.avatar"
                  :class="'mr2'"
                  :size="'18px'"
                  :top="'1px'"
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

      <!-- LIST OF EXPENSES THAT HAVE BEEN ACCEPTED OR REJECTED -->
      <div class="cf mw7 center mb2 fw5">
        <span class="mr1">
          💵
        </span> {{ $t('dashboard.accounting_accepted_rejected_expenses') }}

        <help :url="$page.props.help_links.employee_expenses" :datacy="'help-icon-expense'" />
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box relative">
        <!-- list -->
        <div v-if="acceptedOrRejected.length > 0">
          <ul class="list mt0 mb0 pl0">
            <li v-for="expense in acceptedOrRejected" :key="expense.id" :data-cy="'expense-list-item-' + expense.id" class="expense-item dt-ns bb bb-gray bb-gray-hover pa3 w-100 pa3">
              <div class="dt-row-ns">
                <div class="dtc-ns db mb3 mb0-ns">
                  <inertia-link :href="expense.url" :data-cy="'expense-cta-' + expense.id" class="dib mb2">{{ expense.title }}</inertia-link>
                  <ul class="f7 fw3 grey list pl0 mb2">
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

              <!-- status + employee name -->
              <div class="dt-row-ns">
                <div class="dtc-ns db mb3 mb0-ns">
                  <small-name-and-avatar
                    v-if="expense.employee.id"
                    :name="expense.employee.name"
                    :avatar="expense.employee.avatar"
                    :class="'gray'"
                    :size="'18px'"
                    :top="'1px'"
                    :margin-between-name-avatar="'25px'"
                  />

                  <span v-else>{{ expense.employee.employee_name }}</span>
                </div>

                <div class="dtc-ns db mb3 mb0-ns tr-ns tl status-badge">
                  <span class="br3 f7 fw3 ph2 pv1 di" :class="'expense-badge-' + expense.status" :data-cy="'expense-' + expense.id + '-status-' + expense.status">{{ $t('dashboard.expense_show_status_' + expense.status) }}</span>
                </div>
              </div>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-else class="pa3">
          <img loading="lazy" class="db center mb4" alt="no expenses to validate" src="/img/streamline-icon-money-safe-open-2@140x140.png" />

          <p class="fw5 mt3 tc">{{ $t('dashboard.accounting_expense_all_blank_state') }}</p>
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
    acceptedOrRejected: {
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
