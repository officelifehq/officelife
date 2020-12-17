<style lang="scss" scoped>
.edit-information-menu {
  .selected {
    color: #4d4d4f;
    border-width: 2px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" data-cy="breadcrumb-employee">{{ employee.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_employee_edit') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="">
          <h2 class="pa3 mt2 center tc normal mb2">
            {{ $t('employee.edit_information_title') }}
          </h2>

          <div class="cf w-100">
            <ul class="list pl0 db tc bb bb-gray pa2 edit-information-menu">
              <li class="di mr2">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/edit'" data-cy="menu-profile-link" class="no-underline bb-0 ph3 pv2">
                  {{ $t('employee.edit_information_menu') }}
                </inertia-link>
              </li>
              <li v-if="canSeeContractInfoTab" class="di mr2">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/contract/edit'" data-cy="menu-contract-link" class="no-underline bb-0 ph3 pv2 selected">
                  {{ $t('employee.edit_information_menu_contract') }}
                </inertia-link>
              </li>
              <li class="di">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/address/edit'" data-cy="menu-address-link" class="no-underline bb-0 ph3 pv2 ">
                  {{ $t('employee.edit_information_menu_address') }}
                </inertia-link>
              </li>
            </ul>
          </div>

          <form @submit.prevent="submit()">
            <errors :errors="form.errors" />

            <!-- Basic information -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('employee.edit_information_contract_section_date') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_contract_section_date_help') }}
                </p>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- year -->
                    <text-input :id="'year'"
                                v-model="form.year"
                                :name="'year'"
                                :errors="$page.props.errors.year"
                                :label="$t('employee.edit_information_year')"
                                :required="true"
                                :type="'number'"
                                :min="1900"
                                :max="employee.max_year"
                                :help="$t('employee.edit_information_year_help')"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- month -->
                    <text-input :id="'month'"
                                v-model="form.month"
                                :name="'month'"
                                :errors="$page.props.errors.month"
                                :label="$t('employee.edit_information_month')"
                                :required="true"
                                :type="'number'"
                                :min="1"
                                :max="12"
                                :help="$t('employee.edit_information_month_help')"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- day -->
                    <text-input :id="'day'"
                                v-model="form.day"
                                :name="'day'"
                                :errors="$page.props.errors.day"
                                :label="$t('employee.edit_information_day')"
                                :required="true"
                                :type="'number'"
                                :min="1"
                                :max="31"
                                :help="$t('employee.edit_information_day_help')"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="cf pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :classes="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-edit-contract-employee-button'" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    countries: {
      type: Array,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    canSeeContractInfoTab: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        year: null,
        month: null,
        day: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    if (this.employee.contract_renewed_at !== null) {
      this.form.year = this.employee.year;
      this.form.month = this.employee.month;
      this.form.day = this.employee.day;
    }
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/employees/${this.employee.id}/contract/update`, this.form)
        .then(response => {
          localStorage.success = this.$t('employee.edit_information_success');
          this.$inertia.visit(`/${this.$page.props.auth.company.id}/employees/${this.employee.id}`);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    }
  },
};

</script>
