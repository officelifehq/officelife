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
        <h2 class="pa3 mt5 center tc normal mb2">
          {{ $t('account.software_new_title', { name: $page.props.auth.company.name}) }}

          <help :url="$page.props.help_links.softwares" :top="'1px'" />
        </h2>

        <div class="cf pa3">
          <form @submit.prevent="submit">
            <div class="fl w-two-thirds-l w-100 ph2-ns ph0">
              <div v-if="form.errors" class="pa3">
                <errors :errors="form.errors" />
              </div>

              <!-- job position -->
              <select-box :id="'position'"
                          v-model="form.position"
                          :options="positions"
                          :name="'position'"
                          :errors="$page.props.errors.position"
                          :placeholder="$t('account.hardware_create_lend_name')"
                          :required="true"
                          :datacy="'employee-selector'"
                          :label="'What position is this job opening for?'"
              />

              <!-- Name -->
              <text-input :id="'name'"
                          v-model="form.name"
                          :name="'name'"
                          :errors="$page.props.errors.name"
                          :label="'Public name of the job opening'"
                          :help="'This is the job title that people will see.'"
                          :required="true"
                          :autofocus="true"
              />

              <!-- Description -->
              <text-area v-model="form.product_key"
                         :label="$t('account.software_new_product_key')"
                         :datacy="'news-content-textarea'"
                         :required="true"
                         :rows="10"
              />
            </div>

            <div class="fl w-third-l w-100">
              sdfasdf
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
import TextArea from '@/Shared/TextArea';
import Help from '@/Shared/Help';
import SelectBox from '@/Shared/Select';

export default {
  components: {
    Layout,
    TextInput,
    Errors,
    LoadingButton,
    TextArea,
    Help,
    SelectBox,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    positions: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        name: null,
        product_key: null,
        seats: null,
        licensed_to_name: null,
        licensed_to_email_address: null,
        purchase_amount: null,
        currency: null,
        website: null,
        purchased_date_year: null,
        purchased_date_month: null,
        purchased_date_day: null,
        errors: [],
      },
      loadingState: '',
      showPurchaseInformation: false,
      showPurchaseDateInformation: false,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/account/softwares`, this.form)
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
