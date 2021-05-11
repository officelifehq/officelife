<style scoped>
input[type=checkbox] {
  top: 5px;
}
input[type=radio] {
  top: -2px;
}
</style>

<template>
  <layout :notifications="notifications">
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares'">{{ $t('app.breadcrumb_account_manage_softwares') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_add_software') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="">
          <h2 class="pa3 mt5 center tc normal mb2">
            {{ $t('account.software_new_title', { name: $page.props.auth.company.name}) }}
          </h2>

          <form @submit.prevent="submit">
            <div v-if="form.errors" class="pa3">
              <errors :errors="form.errors" />
            </div>

            <!-- Basic information -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('account.software_new_basic_information') }}</strong>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- Name -->
                <text-input :id="'name'"
                            v-model="form.name"
                            :name="'name'"
                            :errors="$page.props.errors.name"
                            :label="$t('account.software_new_name')"
                            :required="true"
                />

                <!-- Product key -->
                <text-input :id="'product_key'"
                            v-model="form.product_key"
                            :name="'product_key'"
                            :errors="$page.props.errors.product_key"
                            :label="$t('account.software_new_product_key')"
                            :required="true"
                />

                <!-- Seats -->
                <text-input :id="'seats'"
                            v-model="form.seats"
                            :name="'seats'"
                            :type="'number'"
                            :min="0"
                            :max="100000"
                            :errors="$page.props.errors.seats"
                            :label="$t('account.software_new_seats')"
                            :required="true"
                />
              </div>
            </div>

            <!-- Purchase information -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('account.software_new_purchase_information') }}</strong>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <!-- Website -->
                <text-input :id="'website'"
                            v-model="form.website"
                            :name="'website'"
                            :errors="$page.props.errors.website"
                            :type="'email'"
                            autocomplete="off"
                            :label="$t('account.software_new_website')"
                            :required="false"
                />

                <!-- Licensed to -->
                <text-input :id="'licensed_to'"
                            v-model="form.licensed_to"
                            :name="'licensed_to'"
                            :errors="$page.props.errors.licensed_to"
                            :label="$t('account.software_new_licensed_to')"
                            :required="false"
                />

                <!-- Licensed to email -->
                <text-input :id="'licensed_to_email'"
                            v-model="form.licensed_to_email"
                            :name="'licensed_to_email'"
                            :errors="$page.props.errors.licensed_to_email"
                            :type="'email'"
                            autocomplete="off"
                            :label="$t('account.software_new_licensed_to_email')"
                            :required="false"
                />

                <!-- Order number -->
                <text-input :id="'order_number'"
                            v-model="form.order_number"
                            :name="'order_number'"
                            :errors="$page.props.errors.order_number"
                            :label="$t('account.software_new_order_number')"
                            :required="false"
                />

                <!-- Purchase cost + currency -->
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- amount -->
                    <text-input :id="'purchase_amount'"
                                v-model="form.purchase_amount"
                                :name="'purchase_amount'"
                                :errors="$page.props.errors.purchase_amount"
                                :label="$t('dashboard.expense_create_amount')"
                                :required="false"
                                :type="'number'"
                                :step="'0.01'"
                                :min="0"
                                :max="1000000"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- currency -->
                    <select-box
                      :id="'currency'"
                      v-model="form.currency"
                      :options="currencies"
                      :name="'currency'"
                      :errors="$page.props.errors.currency"
                      :label="$t('dashboard.expense_create_currency')"
                      :custom-label-key="'code'"
                      :custom-value-key="'id'"
                      :placeholder="$t('dashboard.expense_create_currency')"
                      :required="false"
                      :value="form.currency"
                      :datacy="'expense-currency'"
                    />
                  </div>
                </div>
              </div>
            </div>


            <!-- Purchase date -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('account.software_new_purchased_date') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  {{ $t('employee.edit_information_timezone_help') }}
                </p>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- year -->
                    <text-input :id="'purchased_date_year'"
                                v-model="form.purchased_date_year"
                                :name="'purchased_date_year'"
                                :errors="$page.props.errors.purchased_date_year"
                                :label="$t('app.year')"
                                :required="false"
                                :type="'number'"
                                :min="1900"
                                :max="2050"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- month -->
                    <text-input :id="'purchased_date_month'"
                                v-model="form.purchased_date_month"
                                :name="'purchased_date_month'"
                                :errors="$page.props.errors.purchased_date_month"
                                :label="$t('app.month')"
                                :required="false"
                                :type="'number'"
                                :min="1"
                                :max="12"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- day -->
                    <text-input :id="'purchased_date_day'"
                                v-model="form.purchased_date_day"
                                :name="'purchased_date_day'"
                                :errors="$page.props.errors.purchased_date_day"
                                :label="$t('app.day')"
                                :required="false"
                                :type="'number'"
                                :min="1"
                                :max="31"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Expiration date -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('account.software_new_expiration_date') }}</strong>
                <p class="f7 silver lh-copy pr3-ns">
                  Does this software have an expiration date?
                </p>
              </div>
              <div class="fl-ns w-two-thirds-ns w-100">
                <div class="dt-ns dt--fixed di">
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- year -->
                    <text-input :id="'expiration_date_year'"
                                v-model="form.expiration_date_year"
                                :name="'expiration_date_year'"
                                :errors="$page.props.errors.expiration_date_year"
                                :label="$t('app.year')"
                                :required="false"
                                :type="'number'"
                                :min="1900"
                                :max="2050"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- month -->
                    <text-input :id="'expiration_date_month'"
                                v-model="form.expiration_date_month"
                                :name="'expiration_date_month'"
                                :errors="$page.props.errors.expiration_date_month"
                                :label="$t('app.month')"
                                :required="false"
                                :type="'number'"
                                :min="1"
                                :max="12"
                    />
                  </div>
                  <div class="dtc-ns pr2-ns pb0-ns w-100">
                    <!-- day -->
                    <text-input :id="'expiration_date_day'"
                                v-model="form.expiration_date_day"
                                :name="'expiration_date_day'"
                                :errors="$page.props.errors.expiration_date_day"
                                :label="$t('app.day')"
                                :required="false"
                                :type="'number'"
                                :min="1"
                                :max="31"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/hardware'" class="btn dib tc w-auto-ns w-100 mb2">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100 mb2'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-hardware-button'" />
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
import SelectBox from '@/Shared/Select';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
    SelectBox,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    currencies: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        name: null,
        product_key: null,
        sets: null,
        licensed_to: null,
        licensed_to_email: null,
        purchase_amount: null,
        currency: null,
        website: null,
        purchased_date_year: null,
        purchased_date_month: null,
        purchased_date_day: null,
        expiration_date_year: null,
        expiration_date_month: null,
        expiration_date_day: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/' + this.$page.props.auth.company.id + '/account/softwares', this.form)
        .then(response => {
          localStorage.success = this.$t('account.software_new_success');
          this.$inertia.visit(response.data.data);
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
