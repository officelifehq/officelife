<style lang="scss" scoped>
.edit-information-menu {
  .selected {
    color: #4d4d4f;
    border-width: 2px;
  }
}

.list li:last-child {
  border-bottom: 0;
}

.rate-inactive {
  background-color: #E2E4E8;
}

.rate-active {
  background-color: #52CF6E;
  color: #fff;
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

            <!-- Contract renewal date -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('employee.edit_information_contract_section_date') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_contract_section_date_help') }}

                  <help :url="$page.props.help_links.managing_external_employees" />
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

            <!-- Contract rate -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('employee.edit_contract_rate_title') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_contract_rate_help') }}

                  <help :url="$page.props.help_links.managing_external_employees" />
                </p>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100 relative">
                <!-- cta and description -->
                <div v-if="!addRateMode" class="db relative">
                  <span class="mt2 mb3 dib gray">
                    {{ $t('employee.edit_contract_rate_active') }}
                  </span>
                  <a class="btn absolute dib-l db right-0" data-cy="add-rate-button" @click.prevent="showAddRateMode">
                    {{ $t('employee.edit_contract_rate_add_cta') }}
                  </a>
                </div>

                <!-- add a rate -->
                <form v-show="addRateMode" class="mb3 pa3 ba br2 bb-gray bg-gray" @submit.prevent="storeRate">
                  <errors :errors="form.errors" />

                  <div class="cf">
                    <div class="fl w-100 w-70-ns mb0-ns">
                      <text-input
                        :ref="'newRate'"
                        v-model="form.rate"
                        :type="'number'"
                        :min="1"
                        :max="10000"
                        :errors="$page.props.errors.name"
                        :datacy="'add-rate-input'"
                        required
                        :placeholder="$t('account.employee_statuses_placeholder')"
                        :extra-class-upper-div="'w-30 dib'"
                        @esc-key-pressed="hideAddRateMode"
                      />
                      <span class="di gray">
                        {{ $t('employee.edit_contract_rate_add_desc', { currency: rates.company_currency} ) }}
                      </span>
                    </div>
                    <div class="fl w-30-ns w-100 tr">
                      <a class="btn dib-l db mb2 mb0-ns" @click.prevent="addRateMode = false ; form.name = ''">
                        {{ $t('app.cancel') }}
                      </a>
                      <loading-button :class="'btn add w-auto-ns w-100 mb2 mb0-ns pv2 ph3'" data-cy="modal-add-rate-cta" :state="loadingState" :text="$t('app.add')" />
                    </div>
                  </div>
                </form>

                <!-- list of rates -->
                <ul data-cy="contract-rates-list" :data-cy-items="localRates.map(r => r.id)" class="list pl0 mv0 center ba br2 bb-gray">
                  <!-- list of past and current rates -->
                  <li v-for="rate in localRates" :key="rate.id" :data-cy="'rate-item-' + rate.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
                    {{ $t('employee.edit_contract_rate_desc', { rate: rate.rate, currency: rates.company_currency} ) }}

                    <!-- status -->
                    <div v-if="rate.active" class="br3 rate-active f7 ph2 di">active</div>
                    <div v-else class="br3 rate-inactive f7 ph2 di">inactive</div>

                    <ul class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns f6">
                      <!-- delete an existing rate -->
                      <li v-if="rateIdToDestroy == rate.id" class="di">
                        {{ $t('app.sure') }}
                        <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + rate.id" @click.prevent="destroyRate(rate.id)">{{ $t('app.yes') }}</a>
                        <a class="pointer" :data-cy="'list-delete-cancel-button-' + rate.id" @click.prevent="rateIdToDestroy = 0">{{ $t('app.no') }}</a>
                      </li>
                      <li v-else class="di">
                        <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'list-delete-button-' + rate.id" @click.prevent="showDestroyRateMode(rate.id)">
                          {{ $t('app.delete') }}
                        </a>
                      </li>
                    </ul>
                  </li>

                  <!-- no rates -->
                  <li v-if="localRates.length == 0" class="pv3 ph2 bb bb-gray bb-gray-hover">{{ $t('employee.edit_contract_rate_blank_state') }}</li>
                </ul>
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
                <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.save')" :cypress-selector="'submit-edit-contract-employee-button'" />
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
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
    Help,
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
    rates: {
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
        year: null,
        month: null,
        day: null,
        rate: 0,
        errors: [],
      },
      localRates: null,
      addRateMode: false,
      loadingState: '',
      loadingRateState: '',
      errorTemplate: Error,
      rateIdToDestroy: 0,
    };
  },

  created() {
    this.localRates = this.rates.rates;

    if (this.employee.contract_renewed_at !== null) {
      this.form.year = this.employee.year;
      this.form.month = this.employee.month;
      this.form.day = this.employee.day;
    }
  },

  methods: {
    showAddRateMode() {
      this.form.rate = 0;
      this.addRateMode = true;

      this.$nextTick(() => {
        this.$refs.newRate.focus();
      });
    },

    showDestroyRateMode(id) {
      this.rateIdToDestroy = id;
    },

    hideAddRateMode() {
      this.addRateMode = false;
    },

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
    },

    storeRate() {
      this.loadingRateState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/employees/${this.employee.id}/rate/store`, this.form)
        .then(response => {
          this.flash(this.$t('employee.edit_contract_rate_add_success'), 'success');

          this.loadingRateState = null;

          // set the current active rate as inactive
          var id = this.localRates.findIndex(x => x.active === true);
          if (id != -1) {
            this.localRates[id].active = false;
          }

          // add the newly created rate and put it at the top of the list
          this.localRates.unshift(response.data.data);
          this.hideAddRateMode();
        })
        .catch(error => {
          this.loadingRateState = null;
          this.form.errors = error.response.data;
        });
    },

    destroyRate(rateId) {
      axios.delete(`/${this.$page.props.auth.company.id}/employees/${this.employee.id}/rate/${rateId}`)
        .then(response => {
          this.flash(this.$t('employee.edit_contract_rate_destroy_success'), 'success');

          // set the current active rate as inactive
          var id = this.localRates.findIndex(x => x.id === rateId);
          this.localRates.splice(id, 1);
        })
        .catch(error => {
          this.loadingRateState = null;
          this.form.errors = error.response.data;
        });
    },
  },
};

</script>
