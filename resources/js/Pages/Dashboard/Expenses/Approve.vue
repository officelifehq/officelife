<style lang="scss" scoped>
.expense-actions {
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
        {{ $t('app.breadcrumb_employee_expense') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="cf pa3 pv4-ns bb bb-gray expense-actions">
          <!-- Actions about the expense -->
          <div v-if="!rejectMode" class="flex-ns justify-around">
            <loading-button :class="'btn w-auto-ns w-100 pv2 ph3 mb0-ns mb3'" :state="rejectLoadingState" :emoji="'👎'" :text="$t('app.reject')" :cypress-selector="'expense-reject-button'"
                            @click="showRejectedModal()"
            />
            <loading-button :class="'btn w-auto-ns w-100 pv2 ph3'" :state="loadingState" :emoji="'👍'" :text="$t('app.approve')" :cypress-selector="'expense-accept-button'"
                            @click="accept()"
            />
          </div>

          <!-- inline modal to reject the expense -->
          <div v-if="rejectMode" class="">
            <form @submit.prevent="submit()">
              <errors :errors="form.errors" />

              <text-area
                ref="editor"
                v-model="form.reason"
                :label="$t('dashboard.accounting_expense_detail_rejection_reason')"
                :required="true"
                :datacy="'rejection-reason-textarea'"
                @esc-key-pressed="rejectMode = false"
              />
              <p class="db lh-copy f6">
                <span class="mr2">👋</span> {{ $t('dashboard.accounting_expense_detail_visibility') }}
              </p>
              <p class="ma0">
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3 mr2'" :state="loadingState" :text="$t('app.reject')" :cypress-selector="'submit-rejection'" @click="reject()" />
                <a class="pointer" data-cy="expense-rejection-cancel-modal" @click.prevent="rejectMode = false">
                  {{ $t('app.cancel') }}
                </a>
              </p>
            </form>
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
        <div v-if="expense.manager.name" class="pa3">
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
        <div v-else class="pa3">
          <h3 class="fw5 f5">
            <span class="mr2">
              🧑‍✈️
            </span> {{ $t('dashboard.accounting_expense_detail_manager_section') }}
          </h3>

          <p class="lh-copy i">{{ $t('dashboard.accounting_expense_detail_no_manager') }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';
import TextArea from '@/Shared/TextArea';

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    SmallNameAndAvatar,
    TextArea,
    Errors,
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

  data() {
    return {
      loadingState: '',
      rejectLoadingState: '',
      rejectMode: false,
      errorTemplate: Error,
      form: {
        reason: null,
        errors: [],
      },
    };
  },

  methods: {
    accept() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/expenses/' + this.expense.id + '/accept')
        .then(response => {
          localStorage.success = this.$t('dashboard.accounting_expense_accepted');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/dashboard/expenses');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    showRejectedModal() {
      this.rejectMode = true;
    },

    reject() {
      this.rejectLoadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/dashboard/expenses/' + this.expense.id + '/reject', this.form)
        .then(response => {
          localStorage.success = this.$t('dashboard.accounting_expense_rejected');
          this.$inertia.visit('/' + this.$page.props.auth.company.id + '/dashboard/expenses');
        })
        .catch(error => {
          this.rejectLoadingState = null;
          this.form.errors = error.response.data;
        });
    }
  },
};

</script>
