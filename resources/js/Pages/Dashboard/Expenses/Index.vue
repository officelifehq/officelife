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
        💵 {{ $t('dashboard.accountant_awaiting_accounting_title') }}

        <help :url="$page.help_links.employee_expenses" :datacy="'help-icon-expense'" />
      </div>

      <div class="cf mw7 center br3 mb5 bg-white box pa3 relative">
        <div v-if="awaitingAccountingExpenses.length > 0">
          <ul class="list pl0 mt0 mb0">
            <li v-for="expense in awaitingAccountingExpenses" :key="expense.id" class="expense-item dt-ns br bl bb bb-gray bb-gray-hover pa3 w-100">
              <div class="dt-row-ns">
                <div class="dtc-ns db mb3 mb0-ns">
                  <div class="mb2">{{ expense.title }}</div>
                  <ul class="f7 fw3 grey list pl0">
                    <li class="mr2 di">{{ expense.expensed_at }}</li>
                    <li v-if="expense.category" class="di">{{ expense.category }}</li>
                  </ul>
                  <p class="db f7 fw3 grey mv0">
                    {{ $t('dashboard.accountant_employee') }}
                    <small-name-and-avatar
                      v-if="expense.employee"
                      :name="expense.employee.name"
                      :avatar="expense.employee.avatar"
                      :classes="'gray'"
                      :size="'18px'"
                      :top="'0px'"
                      :margin-between-name-avatar="'25px'"
                    />
                  </p>
                </div>
                <div class="expense-amount tc-ns dtc-ns v-mid fw5 db mb3 mb0-ns">
                  {{ expense.amount }}

                  <!-- converted amount -->
                  <div v-if="expense.converted_amount" class="db">{{ expense.converted_amount }}</div>
                </div>
                <div class="expense-status tc-ns dtc-ns v-mid db mb3 mb0-ns">
                  <span class="br3 expense-badge-waiting f7 fw5 ph3 pv2 di">{{ $t('dashboard.expense_show_status_' + expense.status) }}</span>
                </div>
                <div class="expense-action tr-ns dtc-ns v-mid f6 db">
                  <inertia-link :href="expense.url">{{ $t('app.view') }}</inertia-link>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- LIST OF EXPENSES THAT ARE AWAITING FOR MANAGER VALIDATION -->
      <div class="cf mw7 center mb2 fw5">
        💵 {{ $t('dashboard.accountant_awaiting_manager_title') }}

        <help :url="$page.help_links.employee_expenses" :datacy="'help-icon-expense'" />
      </div>

      <div class="cf mw7 center br3 mb3 bg-white box pa3 relative">
        <div v-if="awaitingManagerExpenses.length > 0">
          <ul class="list pl0 mt0 mb0">
            <li v-for="expense in awaitingManagerExpenses" :key="expense.id" class="expense-item dt-ns br bl bb bb-gray bb-gray-hover pa3 w-100">
              <div class="dt-row-ns">
                <div class="dtc-ns db mb3 mb0-ns">
                  <div class="mb2">{{ expense.title }}</div>
                  <ul class="f7 fw3 grey list pl0 pb2">
                    <li class="mr2 di">{{ expense.expensed_at }}</li>
                    <li v-if="expense.category" class="di">{{ expense.category }}</li>
                  </ul>
                  <p class="db f7 fw3 grey mv0">
                    {{ $t('dashboard.accountant_employee') }}
                    <small-name-and-avatar
                      v-if="expense.employee"
                      :name="expense.employee.name"
                      :avatar="expense.employee.avatar"
                      :classes="'gray'"
                      :size="'18px'"
                      :top="'0px'"
                      :margin-between-name-avatar="'25px'"
                    />
                  </p>
                  <p class="db f7 fw3 grey mv0">
                    {{ $t('dashboard.accountant_manager') }}
                    <small-name-and-avatar
                      v-if="expense.manager"
                      :name="expense.manager.name"
                      :avatar="expense.manager.avatar"
                      :classes="'gray'"
                      :size="'18px'"
                      :top="'0px'"
                      :margin-between-name-avatar="'25px'"
                    />
                  </p>
                </div>
                <div class="expense-amount tc-ns dtc-ns v-mid fw5 db mb3 mb0-ns">
                  {{ expense.amount }}

                  <!-- converted amount -->
                  <div v-if="expense.converted_amount" class="db">{{ expense.converted_amount }}</div>
                </div>
                <div class="expense-status tc-ns dtc-ns v-mid db mb3 mb0-ns">
                  <span class="br3 expense-badge-waiting f7 fw5 ph3 pv2 di">{{ $t('dashboard.expense_show_status_' + expense.status) }}</span>
                </div>
                <div class="expense-action tr-ns dtc-ns v-mid f6 db">
                  <inertia-link :href="expense.url">{{ $t('app.view') }}</inertia-link>
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
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
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
