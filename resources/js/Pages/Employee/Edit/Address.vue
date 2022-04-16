<style lang="scss" scoped>
.edit-information-menu {
  .selected {
    color: #4d4d4f;
    border-width: 2px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/employees/' + employee.id"
                  :previous="employee.name"
      >
        {{ $t('app.breadcrumb_employee_edit') }}
      </breadcrumb>

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
              <li v-if="permissions.can_see_edit_contract_information_tab" class="di mr2">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/contract/edit'" data-cy="menu-contract-link" class="no-underline bb-0 ph3 pv2">
                  {{ $t('employee.edit_information_menu_contract') }}
                </inertia-link>
              </li>
              <li class="di">
                <inertia-link :href="'/' + $page.props.auth.company.id + '/employees/' + employee.id + '/address/edit'" data-cy="menu-address-link" class="no-underline bb-0 ph3 pv2 selected">
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
                <strong>{{ $t('employee.edit_information_address') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_address_help') }}
                </p>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- street -->
                <text-input :id="'street'"
                            v-model="form.street"
                            :name="'street'"
                            :errors="$page.props.errors.street"
                            :label="$t('employee.edit_information_street')"
                            :required="true"
                />

                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- city -->
                    <text-input :id="'city'"
                                v-model="form.city"
                                :name="'city'"
                                :errors="$page.props.errors.city"
                                :label="$t('employee.edit_information_city')"
                                :required="true"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- state -->
                    <text-input :id="'state'"
                                v-model="form.state"
                                :name="'state'"
                                :errors="$page.props.errors.state"
                                :label="$t('employee.edit_information_state')"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- postal code -->
                    <text-input :id="'postal_code'"
                                v-model="form.postal_code"
                                :name="'postal_code'"
                                :errors="$page.props.errors.postal_code"
                                :label="$t('employee.edit_information_postal_code')"
                                :required="true"
                    />
                  </div>
                </div>


                <label class="db mb-2">
                  {{ $t('employee.edit_information_country') }}
                </label>
                <a-select
                  v-model:value="form.country_id"
                  :placeholder="$t('app.choose')"
                  style="width: 200px"
                  :options="countries"
                  show-search
                  option-filter-prop="label"
                />
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
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-edit-employee-button'" />
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
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
    permissions: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        street: null,
        city: null,
        state: null,
        postal_code: null,
        country_id: null,
        errors: [],
      },
      existing_address: {
        street: '',
        city: '',
        state: '',
        postal_code: '',
        country_id: 0,
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    if (this.employee.address !== null) {
      this.form.city = this.employee.address.city;
      this.form.street = this.employee.address.street;
      this.form.state = this.employee.address.province;
      this.form.postal_code = this.employee.address.postal_code;
      this.form.country_id = this.employee.address.country.id;
    }
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/employees/${this.employee.id}/address/update`, this.form)
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
