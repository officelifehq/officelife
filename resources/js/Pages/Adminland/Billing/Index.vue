<style lang="scss" scoped>
.list li:last-child {
  border-bottom: 0;
}

.invoice-item:first-child {
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.invoice-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
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
        {{ $t('app.breadcrumb_account_manage_invoices') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <!-- BILLING -->
          <h2 class="tc normal mb4">
            {{ $t('account.billing_index_title') }}

            <help :url="$page.props.help_links.billing" :top="'1px'" />
          </h2>

          <p class="mb2 lh-copy">OfficeLife requires a licence key to be fully functional, that you can purchase on our subscription portal.</p>

          <p class="mb4"><a :href="localData.billing_information.customer_portal_url" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">Get your licence key</a></p>

          <div class="flex bb-gray ba br2 mb5">
            <ul class="list ma0 pa0 w-100">
              <!-- licence key -->
              <li v-if="!enterLicenceKeyMode" class="bb-gray bb pa3 flex items-center">
                <span class="fw5 mr2">Licence key:</span>
                <span v-if="localData.billing_information.licence_key" class="truncate w5 dib">{{ localData.billing_information.licence_key }}</span>
                <span v-else class="bb b--dotted bt-0 bl-0 br-0 pointer" @click="enterLicenceKeyMode = true">+ Enter your licence key</span>
              </li>

              <!-- enter a licence key -->
              <li v-if="enterLicenceKeyMode">
                <form class="pa3 bb-gray bb" @submit.prevent="store">
                  <errors :errors="form.errors" />

                  <!-- Title -->
                  <text-input :id="'title'"
                              v-model="form.licence_key"
                              :name="'title'"
                              :errors="$page.props.errors.title"
                              :label="$t('account.billing_enter_licence_key_title')"
                              :help="$t('account.billing_enter_licence_key_help')"
                              :required="true"
                  />

                  <!-- Actions -->
                  <div class="mt4">
                    <div class="flex-ns justify-between">
                      <div>
                        <span class="btn dib tc w-auto-ns w-100 mb2 pv2 ph3" @click="enterLicenceKeyMode = false">
                          {{ $t('app.cancel') }}
                        </span>
                      </div>
                      <loading-button :class="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.save')" />
                    </div>
                  </div>
                </form>
              </li>

              <!-- frequency -->
              <li v-if="localData.billing_information.licence_key" class="bb-gray bb pa3">
                <span class="fw5 mr2">Subscription type:</span> <span>{{ localData.billing_information.frequency }}</span>
              </li>

              <!-- renewed at -->
              <li v-if="localData.billing_information.licence_key" class="bb-gray bb pa3">
                <span class="fw5 mr2">Will renew at:</span> <span>{{ localData.billing_information.valid_until_at }}</span>
              </li>

              <!-- number of seats -->
              <li v-if="localData.billing_information.licence_key" class="bb-gray bb pa3">
                <span class="fw5 mr2">Number of seats in the instance:</span> <span>{{ localData.billing_information.seats }}</span>
              </li>

              <!-- purchaser email -->
              <li v-if="localData.billing_information.licence_key" class="bb-gray pa3">
                <span class="fw5 mr2">Purchased by:</span> <span>{{ localData.billing_information.purchaser_email }}</span>
              </li>
            </ul>
          </div>

          <!-- INVOICES TITLE -->
          <h2 class="tc normal mb4">
            {{ $t('account.invoices_index_title') }}

            <help :url="$page.props.help_links.billing" :top="'1px'" />
          </h2>

          <!-- LIST OF INVOICES -->
          <ul v-if="localData.invoices.length != 0" class="list pl0 mv0 center ba br2 bb-gray">
            <li v-for="invoice in localData.invoices" :key="invoice.id" class="invoice-item pv3 ph2 bb bb-gray bb-gray-hover flex items-center justify-between">
              <div>
                <span class="fw5 db mb1">{{ invoice.month }}</span>
                <span class="gray f6">{{ $t('account.invoices_index_month', {number: invoice.number_of_active_employees}) }}</span>
              </div>

              <inertia-link :href="invoice.url" class="f6 mr2 dib">{{ $t('app.view') }}</inertia-link>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="localData.invoices.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.billing_show_blank') }}
            </p>
            <img loading="lazy" class="db center mb4" alt="add a position symbol" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x"
            />
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Help from '@/Shared/Help';
import TextInput from '@/Shared/TextInput';
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';

export default {
  components: {
    Layout,
    Breadcrumb,
    Help,
    TextInput,
    Errors,
    LoadingButton,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    data: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      enterLicenceKeyMode: false,
      localData: null,
      form: {
        licence_key: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    this.localData = this.data;
  },

  methods: {
    store() {
      this.loadingState = 'loading';

      axios.post(`/${this.$page.props.auth.company.id}/account/billing`, this.form)
        .then(response => {
          this.loadingState = null;
          this.flash(this.$t('account.billing_enter_licence_key_success'), 'success');
          this.localData = response.data.data;
          this.enterLicenceKeyMode = false;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
