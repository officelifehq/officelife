<style lang="scss" scoped>
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/expenses'">Expenses</inertia-link>
          </li>
          <li class="di">
            Expense details
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="">
          <h2 class="pa3 mt2 center tc normal mb2">
            {{ expense.title }}
          </h2>
          <p class="f5 tc">{{ expense.amount }}</p>

          <ul>
            <li>Submitted by {{ expense.employee.name }} on {{ expense.created_at }}</li>

            <!-- manager approval -->
            <li v-if="expense.manager && expense.status == 'manager_approval'">Waiting for {{ expense.manager.name }} approval</li>
            <li v-if="expense.manager && expense.status == 'rejected_by_manager'">Rejected by {{ expense.manager.name }} on </li>
            <li v-if="!expense.manager">No manager approval needed</li>

            <!-- accounting approval -->
            <li v-if="expense.status == 'accepted'">Accepted</li>
            <li v-if="expense.status == 'accounting_approval' || expense.status == 'manager_approval'">Waiting for accounting approval</li>
            <li v-if="expense.status == 'rejected_by_accounting'">Rejected by {{ }}</li>
          </ul>

          <div class="cf pa3">
            <div class="flex-ns justify-between">
              <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3 destroy'" :state="rejectLoadingState" :text="$t('app.reject')" :cypress-selector="'submit-edit-employee-button'" @click="reject()" />
              <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.approve')" :cypress-selector="'submit-edit-employee-button'" @click="accept()" />
            </div>
          </div>

          <p>Current status: {{ expense.status }}</p>
          <p>Title: {{ expense.title }}</p>
          <p>Employee: {{ expense.employee.name }}</p>
          <p>Submitted: {{ expense.created_at }}</p>
          <p>Expense at: {{ expense.expensed_at }}</p>
          <p>Amount: {{ expense.amount }}</p>
          <p>Converted amount: {{ expense.converted_amount }}</p>
          <p>Converted at: {{ expense.converted_at }}</p>
          <p>exchange rate: {{ expense.exchange_rate }}</p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    LoadingButton,
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
      errorTemplate: Error,
    };
  },

  methods: {
    reject() {
      this.rejectLoadingState = 'loading';

      axios.post('/' + this.$page.auth.company.id + '/dashboard/expense/' + this.employee.id + '/address/update', this.form)
        .then(response => {
          localStorage.success = this.$t('employee.edit_information_success');
          this.$inertia.visit('/' + this.$page.auth.company.id + '/employees/' + this.employee.id);
        })
        .catch(error => {
          this.rejectLoadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    }
  },
};

</script>
