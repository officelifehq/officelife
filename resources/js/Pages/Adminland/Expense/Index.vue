<style scoped>
.list li:last-child {
  border-bottom: 0;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account'"
                  :previous="$t('app.breadcrumb_account_home')"
                  :has-more="false"
      >
        {{ $t('app.breadcrumb_account_manage_expense_categories') }}
      </breadcrumb>

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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Categories from '@/Pages/Adminland/Expense/Partials/Categories';
import Employees from '@/Pages/Adminland/Expense/Partials/Employees';

export default {
  components: {
    Layout,
    Breadcrumb,
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
        this.$refs.newCategory.focus();
      });
    },
  }
};

</script>
