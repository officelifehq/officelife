<style scoped>
.list li:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="$route('dashboard.index', $page.props.auth.company.id)">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="$route('account.index', $page.props.auth.company.id)">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_expense_categories') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.expense_categories_title', { company: $page.props.auth.company.name}) }}
          </h2>

          <!-- EXPENSES CATEGORIES -->
          <categories
            :categories="categories"
          />

          <!-- Employees with rights to manage expenses -->
          <employees
            :employees="employees"
          />
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Categories from '@/Pages/Adminland/Expense/Partials/Categories';
import Employees from '@/Pages/Adminland/Expense/Partials/Employees';

export default {
  components: {
    Layout,
    Categories,
    Employees,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    categories: {
      type: Array,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      modal: false,
      addEmployeesMode: false,
      potentialEmployees: [],
      processingSearch: false,
      form: {
        name: null,
        errors: [],
      },
      employeeForm: {
        searchTerm: null,
        employees: [],
        errors: [],
      },
    };
  },

  methods: {
    displayAddModal() {
      this.modal = true;
      this.form.name = '';

      this.$nextTick(() => {
        this.$refs['newCategory'].$refs['input'].focus();
      });
    },
  }
};

</script>
