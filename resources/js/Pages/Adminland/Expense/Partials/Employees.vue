<style lang="scss" scoped>
.employees-list div:last-child {
  border-bottom: 0;
}

.avatar {
  left: 1px;
  top: 5px;
  width: 23px;
}

.team-member {
  padding-left: 34px;

  .avatar {
    top: -2px;
  }
}
</style>

<template>
  <!-- Employees with rights to manage expenses -->
  <div>
    <!-- Headline -->
    <h3 class="relative adminland-headline fw4">
      <span class="dib mb3 di-l">
        <span class="mr1">
          🤾‍♂️
        </span> {{ $tc('account.expense_employees_headline') }}

        <help :url="$page.props.help_links.accountants" :datacy="'help-icon-accounts'" :top="'1px'" />
      </span>

      <!-- main cta -->
      <a v-if="!modal" class="btn absolute-l relative dib-l db right-0 f5" data-cy="show-edit-mode" @click.prevent="displayAddModal()">{{ $t('account.expense_employees_create_cta') }}</a>
      <a v-if="modal" class="btn absolute-l relative dib-l db right-0 f5" data-cy="hide-edit-mode" @click.prevent="modal = false">{{ $t('account.expense_employees_hide_cta') }}</a>
    </h3>

    <!-- search employees -->
    <div v-if="modal == true" class="bb bb-gray pt3">
      <form class="relative" @submit.prevent="search">
        <text-input :ref="'employeeInput'"
                    v-model="form.searchTerm"
                    :datacy="'potential-employees'"
                    :errors="$page.props.errors.name"
                    :label="$t('account.expense_employees_create_label')"
                    :placeholder="$t('team.recent_ship_new_credit_help')"
                    :required="true"
                    @keyup="search"
                    @update:model-value="search"
                    @esc-key-pressed="modal = false"
        />
        <ball-pulse-loader v-if="processingSearch" color="#5c7575" size="7px" />
      </form>

      <!-- search results -->
      <ul v-show="potentialEmployees.length > 0" class="list pl0 ba bb-gray bb-gray-hover">
        <li v-for="employee in potentialEmployees" :key="employee.id" class="relative pa2 bb bb-gray">
          {{ employee.name }}
          <a href="" class="fr f6" :data-cy="'employee-id-' + employee.id + '-add'" @click.prevent="add(employee)">{{ $t('app.add') }}</a>
        </li>
      </ul>

      <!-- no results found -->
      <ul v-show="potentialEmployees.length == 0 && form.searchTerm" class="list pl0 ba bb-gray bb-gray-hover">
        <li class="relative pa2 bb bb-gray">
          {{ $t('team.members_no_results') }}
        </li>
      </ul>
    </div>

    <!-- list of employees -->
    <div v-show="localEmployees.length > 0" class="ba bb-gray mt2 br2 employees-list">
      <div v-for="employee in localEmployees" :key="employee.id" class="pa2 db bb-gray bb bb-gray-hover" data-cy="employees-list">
        <span class="pl3 db relative team-member">
          <avatar :avatar="employee.avatar" :size="23" :class="'br-100 absolute avatar'" />

          <inertia-link :href="employee.url">{{ employee.name }}</inertia-link>

          <!-- remove -->
          <a v-if="modal" href="#" class="f6 bb b--dotted bt-0 bl-0 br-0 pointer c-delete dib fr" :data-cy="'remove-employee-' + employee.id" @click.prevent="remove(employee)">
            {{ $t('app.remove') }}
          </a>
        </span>
      </div>
    </div>

    <!-- BLANK STATE -->
    <div v-show="employees.length == 0" class="pa3 mt5">
      <p class="tc measure center mb4 lh-copy">
        👨‍🏫 {{ $t('account.expense_employees_blank') }}
      </p>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';
import TextInput from '@/Shared/TextInput';
import BallPulseLoader from 'vue-loaders/dist/loaders/ball-pulse';

export default {
  components: {
    Avatar,
    Help,
    TextInput,
    'ball-pulse-loader': BallPulseLoader.component
  },

  props: {
    employees: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
      modal: false,
      localEmployees: [],
      potentialEmployees: [],
      processingSearch: false,
      form: {
        searchTerm: null,
        employees: [],
        selectedEmployee: null,
        errors: [],
      },
    };
  },

  created() {
    this.localEmployees = this.employees;
  },

  methods: {
    resetForm() {
      this.potentialEmployees = [];
      this.form.searchTerm = null;
      this.form.employees = [];
      this.form.selectedEmployee = null;
    },

    displayAddModal() {
      this.modal = true;
      this.resetForm();

      this.$nextTick(() => {
        this.$refs.employeeInput.focus();
      });
    },

    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/account/expenses', this.form)
        .then(response => {
          this.flash(this.$t('account.employee_statuses_success_new'), 'success');

          this.loadingState = null;
          this.form.name = null;
          this.modal = false;
          this.categories.push(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },

    add(employee) {
      this.form.selectedEmployee = employee.id;

      axios.post('/' + this.$page.props.auth.company.id + '/account/expenses/employee', this.form)
        .then(response => {
          this.flash(this.$t('account.expense_employees_assign_success'), 'success');

          this.resetForm();
          this.localEmployees.unshift(response.data.data);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    remove(employee) {
      this.form.selectedEmployee = employee.id;

      axios.post('/' + this.$page.props.auth.company.id + '/account/expenses/removeEmployee', this.form)
        .then(response => {
          this.flash(this.$t('account.expense_employees_unassign_success'), 'success');

          this.resetForm();
          var changedId = this.localEmployees.findIndex(x => x.id === employee.id);
          this.localEmployees.splice(changedId, 1);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },

    search: _.debounce(
      function () {
        if (this.form.searchTerm != '') {
          this.processingSearch = true;

          axios.post(`/${this.$page.props.auth.company.id}/account/expenses/search`, this.form)
            .then(response => {
              this.potentialEmployees = _.filter(response.data.data, employee => _.every(this.localEmployees, e => employee.id !== e.id));
              this.processingSearch = false;
            })
            .catch(error => {
              this.form.errors = error.response.data;
              this.processingSearch = false;
            });
        } else {
          this.potentialEmployees = [];
        }
      }, 500),
  }
};

</script>
