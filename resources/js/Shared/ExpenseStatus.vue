<style lang="scss" scoped>
.expense-summary,
.actions {
  background-color: #E9EDF2;
}
</style>

<template>
  <div class="mw7 center br3 mb5 bg-white box relative z-1">
    <div class="cf pa3 pv4-ns bb bb-gray expense-summary">
      <!-- waiting for manager approval -->
      <div v-if="expense.status == 'manager_approval'" class="tc">
        <p class="mt0">⌛</p>
        <p>{{ $t('dashboard.expense_detail_status_manager_approval_title') }}</p>
      </div>

      <!-- rejected by manager -->
      <div v-if="expense.status == 'rejected_by_manager'" class="tc">
        <p class="mt0">👎</p>
        <p>{{ $t('dashboard.expense_detail_status_rejected_by_manager_title') }}</p>
        <p class="i">{{ expense.manager_rejection_explanation }}</p>
      </div>

      <!-- waiting for accountant approval -->
      <div v-if="expense.status == 'accounting_approval'" class="tc">
        <p class="mt0">⌛</p>
        <p>{{ $t('dashboard.expense_detail_status_accounting_approval_title') }}</p>
      </div>

      <!-- rejected by accountant -->
      <div v-if="expense.status == 'rejected_by_accounting'" class="tc">
        <p class="mt0">👎</p>
        <p>{{ $t('dashboard.expense_detail_status_rejected_by_accounting_title') }}</p>
        <p class="i">{{ expense.accounting_rejection_explanation }}</p>
      </div>

      <!-- accepted -->
      <div v-if="expense.status == 'accepted'" class="tc">
        <p class="mt0">👍</p>
        <p>{{ $t('dashboard.expense_detail_status_accepted_title') }}</p>
      </div>
    </div>

    <!-- Expense title -->
    <div class="bb bb-gray">
      <h2 class="ph3 mt4 center tc normal mb1" data-cy="expense-title">
        {{ expense.title }}
      </h2>
      <p class="f5 tc gray fw1" :class="expense.converted_amount ? 'mb2' : 'mb4'" data-cy="expense-amount">{{ expense.amount }}</p>
      <p v-if="expense.converted_amount" class="f6 tc mb4 gray fw1">({{ expense.converted_amount }})</p>
    </div>

    <!-- Detail of the expense -->
    <div class="pa3 bb bb-gray">
      <h3 class="fw5 f5">
        <span class="mr2">
          💵
        </span> {{ $t('dashboard.accounting_expense_detail_expense_section') }}
      </h3>

      <ul class="list ma0 pl0">
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_expense_type') }}</span>
          <span>{{ expense.category }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_expense_date') }}</span>
          <span>{{ expense.expensed_at }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_expense_value') }}</span>
          <span>{{ expense.amount }}</span>
        </li>
      </ul>
    </div>

    <!-- Exchange rate -->
    <div v-if="expense.converted_amount" class="pa3 bb bb-gray">
      <h3 class="fw5 f5">
        <span class="mr2">
          🌍
        </span> {{ $t('dashboard.accounting_expense_detail_exchange_section') }}
      </h3>

      <ul class="list ma0 pl0">
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_exchange_value') }}</span>
          <span>{{ expense.converted_amount }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_exchange_rate') }}</span>
          <span>{{ expense.exchange_rate_explanation }}</span>
        </li>
      </ul>
    </div>

    <!-- Employee information -->
    <div class="pa3 bb bb-gray">
      <h3 class="fw5 f5">
        <span class="mr2">
          👨‍💻
        </span> {{ $t('dashboard.accounting_expense_detail_employee_section') }}
      </h3>

      <ul class="list ma0 pl0">
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_employee_section') }}</span>
          <span>
            <small-name-and-avatar
              v-if="expense.employee.id"
              :name="expense.employee.name"
              :avatar="expense.employee.avatar"
              :font-size="'f5'"
              :size="'18px'"
              :top="'0px'"
              :margin-between-name-avatar="'25px'"
            />
            <span v-else>{{ expense.employee.employee_name }}</span>
          </span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_employee_position') }}</span>
          <span v-if="expense.employee.position">{{ expense.employee.position }}</span>
          <span v-else>{{ $t('dashboard.accounting_expense_detail_employee_position_blank') }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_employee_status') }}</span>
          <span v-if="expense.employee.status">{{ expense.employee.status }}</span>
          <span v-else>{{ $t('dashboard.accounting_expense_detail_employee_status_blank') }}</span>
        </li>
      </ul>
    </div>

    <!-- Manager information -->
    <div v-if="expense.manager.name" class="pa3 bb bb-gray">
      <h3 class="fw5 f5">
        <span class="mr2">
          🧑‍✈️
        </span> {{ $t('dashboard.accounting_expense_detail_manager_section') }}
      </h3>

      <ul class="list ma0 pl0">
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_manager') }}</span>
          <span>
            <small-name-and-avatar
              v-if="expense.manager.id"
              :name="expense.manager.name"
              :avatar="expense.manager.avatar"
              :class="'gray'"
              :font-size="'f5'"
              :size="'18px'"
              :top="'0px'"
              :margin-between-name-avatar="'25px'"
            />
            <span v-else>{{ expense.manager.name }}</span>
          </span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.manager_expense_detail_employee_position') }}</span>
          <span v-if="expense.manager.position">{{ expense.manager.position }}</span>
          <span v-else>{{ $t('dashboard.manager_expense_detail_employee_position_blank') }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.manager_expense_detail_employee_status') }}</span>
          <span v-if="expense.manager.status">{{ expense.manager.status }}</span>
          <span v-else>{{ $t('dashboard.manager_expense_detail_employee_status_blank') }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_manager_decision') }}</span>
          <span v-if="expense.manager_approver_approved_at">{{ expense.manager_approver_approved_at }}</span>
          <span v-else></span>
        </li>
      </ul>
    </div>

    <!-- message when this employee doesnt have a manager -->
    <div v-else class="pa3 bb bb-gray">
      <h3 class="fw5 f5">
        <span class="mr2">
          🧑‍✈️
        </span> {{ $t('dashboard.accounting_expense_detail_manager_section') }}
      </h3>

      <p class="lh-copy i">{{ $t('dashboard.accounting_expense_detail_no_manager') }}</p>
    </div>

    <!-- Accountant information -->
    <div v-if="expense.accountant.name" class="pa3 bb bb-gray">
      <h3 class="fw5 f5">
        <span class="mr2">
          🕵️‍♀️
        </span> {{ $t('dashboard.accounting_expense_detail_accoutant_section') }}
      </h3>

      <ul class="list ma0 pl0">
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_accoutant_name') }}</span>
          <span>
            <small-name-and-avatar
              v-if="expense.accountant.id"
              :name="expense.accountant.name"
              :avatar="expense.accountant.avatar"
              :class="'gray'"
              :font-size="'f5'"
              :size="'18px'"
              :top="'0px'"
              :margin-between-name-avatar="'25px'"
            />
            <span v-else>{{ expense.accountant.name }}</span>
          </span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.manager_expense_detail_employee_position') }}</span>
          <span v-if="expense.accountant.position">{{ expense.accountant.position }}</span>
          <span v-else>{{ $t('dashboard.manager_expense_detail_employee_position_blank') }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.manager_expense_detail_employee_status') }}</span>
          <span v-if="expense.accountant.status">{{ expense.accountant.status }}</span>
          <span v-else>{{ $t('dashboard.manager_expense_detail_employee_status_blank') }}</span>
        </li>
        <li class="flex-ns justify-between mb1 bb-gray-hover pv2 ph1 br2">
          <span class="di-ns db mb0-ns mb2 gray">{{ $t('dashboard.accounting_expense_detail_manager_decision') }}</span>
          <span v-if="expense.accounting_approver_approved_at">{{ expense.accounting_approver_approved_at }}</span>
          <span v-else></span>
        </li>
      </ul>
    </div>

    <div v-if="canDelete" class="actions pa3 tc f6">
      <a class="c-delete pointer" @click.prevent="destroy()">{{ $t('dashboard.expense_delete') }}</a>
    </div>
  </div>
</template>

<script>
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    SmallNameAndAvatar,
  },

  props: {
    expense: {
      type: Object,
      default: null,
    },
    canDelete: {
      type: Boolean,
      default: true,
    },
  },

  methods: {
    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/employees/${this.expense.employee.id}/administration/expenses/${this.expense.id}`)
        .then(response => {
          localStorage.success = this.$t('account.expense_destroy_success');
          this.$inertia.visit(response.data.url);
        })
        .catch(error => {
        });
    },
  }
};

</script>
