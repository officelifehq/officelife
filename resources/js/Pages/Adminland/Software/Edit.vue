<style scoped>
input[type=checkbox] {
  top: 5px;
}
input[type=radio] {
  top: -2px;
}
.plus-button {
  padding: 2px 7px 4px;
  margin-right: 4px;
  border-color: #60995c;
  color: #60995c;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/softwares'"
                  :previous="$t('app.breadcrumb_account_manage_softwares')"
      >
        {{ $t('app.breadcrumb_account_edit_software') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="">
          <h2 class="pa3 mt5 center tc normal mb2">
            {{ $t('account.software_edit_title') }}
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
                            :autofocus="true"
                />

                <!-- Product key -->
                <text-area v-model="form.product_key"
                           :label="$t('account.software_new_product_key')"
                           :datacy="'news-content-textarea'"
                           :required="true"
                           :rows="10"
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
                            :type="'text'"
                            autocomplete="off"
                            :label="$t('account.software_new_website')"
                            :required="false"
                />

                <!-- Licensed to -->
                <text-input :id="'licensed_to_name'"
                            v-model="form.licensed_to_name"
                            :name="'licensed_to_name'"
                            :errors="$page.props.errors.licensed_to_name"
                            :label="$t('account.software_new_licensed_to')"
                            :required="false"
                />

                <!-- Licensed to email -->
                <text-input :id="'licensed_to_email_address'"
                            v-model="form.licensed_to_email_address"
                            :name="'licensed_to_email_address'"
                            :errors="$page.props.errors.licensed_to_email_address"
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
                    <label class="db mb-2">
                      {{ $t('dashboard.expense_create_currency') }}
                    </label>
                    <a-select
                      v-model:value="form.currency"
                      :placeholder="$t('dashboard.expense_create_currency')"
                      style="width: 200px"
                      :options="currencies"
                      show-search
                      option-filter-prop="label"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Purchase date -->
            <div class="cf pa3 bb bb-gray pb4">
              <div class="fl-ns w-third-ns w-100 mb3 mb0-ns">
                <strong>{{ $t('account.software_new_purchase_date') }}</strong>
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

            <!-- Actions -->
            <div class="pa3">
              <div class="flex-ns justify-between">
                <div>
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares'" class="btn dib tc w-auto-ns w-100">
                    {{ $t('app.cancel') }}
                  </inertia-link>
                </div>
                <loading-button :class="'btn add w-auto-ns w-100'" :state="loadingState" :text="$t('app.add')" :cypress-selector="'submit-add-hardware-button'" />
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
import TextArea from '@/Shared/TextArea';

export default {
  components: {
    Layout,
    Breadcrumb,
    TextInput,
    Errors,
    LoadingButton,
    TextArea,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    software: {
      type: Object,
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
        licensed_to_name: null,
        licensed_to_email_address: null,
        purchase_amount: null,
        order_number: null,
        currency: null,
        website: null,
        purchased_date_year: null,
        purchased_date_month: null,
        purchased_date_day: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created() {
    this.form.name = this.software.name;
    this.form.product_key = this.software.product_key;
    this.form.seats = this.software.seats;
    this.form.licensed_to_name = this.software.licensed_to_name;
    this.form.licensed_to_email_address = this.software.licensed_to_email_address;
    this.form.purchase_amount = this.software.purchase_amount;
    this.form.currency = this.software.currency;
    this.form.order_number = this.software.order_number;
    this.form.website = this.software.website;
    this.form.purchased_date_year = this.software.purchased_date_year;
    this.form.purchased_date_month = this.software.purchased_date_month;
    this.form.purchased_date_day = this.software.purchased_date_day;
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.put(`/${this.$page.props.auth.company.id}/account/softwares/${this.software.id}`, this.form)
        .then(response => {
          localStorage.success = this.$t('account.software_edit_success');
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
