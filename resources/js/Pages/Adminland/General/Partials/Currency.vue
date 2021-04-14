<style lang="scss" scoped>
.title {
  min-width: 230px;
}
</style>

<template>
  <div class="bb bb-gray">
    <h3 class="ph3 fw5">
      {{ $t('account.general_currency_information') }}

      <help :url="$page.props.help_links.account_general_currency" :datacy="'help-icon-general'" :top="'2px'" />
    </h3>

    <!-- currency used in the company -->
    <ul v-if="!editMode" class="list ph3">
      <li class="mb3 flex items-start">
        <span class="dib title">{{ $t('account.general_currency_information_table') }}</span>
        <span class="fw6" data-cy="currency-used">{{ localCurrency }}</span>
      </li>
    </ul>

    <div v-if="!editMode" class="ph3 mb3">
      <a data-cy="update-currency-company-button" class="btn tc relative dib" @click.prevent="displayEditMode()">
        {{ $t('account.general_currency_update') }}
      </a>
    </div>

    <!-- change currency -->
    <div v-if="editMode" class="ph3">
      <form @submit.prevent="submit">
        <errors :errors="form.errors" :class="'mb3'" />

        <ul class="list pl0">
          <li class="mb3 flex-ns items-center">
            <span class="dib title">{{ $t('account.general_currency_change_label') }}</span>
            <span class="fw6" data-cy="company-name">
              <select v-model="form.currency" data-cy="currency-selector">
                <option v-for="currency in currencies" :key="currency.code" :value="currency.code" v-html="currency.code">
                </option>
              </select>
            </span>
          </li>
        </ul>

        <!-- Actions -->
        <div class="mt4 mb3">
          <div class="flex-ns justify-between">
            <div>
              <a href="#" class="btn dib tc w-auto-ns w-100 pv2 ph3" data-cy="cancel-update-currency-company-button" @click.prevent="editMode = false">
                {{ $t('app.cancel') }}
              </a>
            </div>
            <loading-button :class="'btn add w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('app.update')" :cypress-selector="'submit-update-currency-company-button'" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Errors from '@/Shared/Errors';
import LoadingButton from '@/Shared/LoadingButton';
import Help from '@/Shared/Help';

export default {
  components: {
    Errors,
    Help,
    LoadingButton,
  },

  props: {
    information: {
      type: Object,
      default: null,
    },
    currencies: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localCurrency: '',
      editMode: false,
      form: {
        id: 0,
        currency: null,
        errors: [],
      },
      loadingState: '',
    };
  },

  created: function() {
    this.localCurrency = this.information.currency;
  },

  methods: {
    displayEditMode() {
      this.editMode = true;
      this.form.currency = this.localCurrency;
    },

    submit() {
      axios.post(`${this.$page.props.auth.company.id}/account/general/currency`, this.form)
        .then(response => {
          this.localCurrency = this.form.currency;
          this.editMode = false;
          this.flash(this.$t('account.general_currency_rename_success'), 'success');
        })
        .catch(error => {
          this.editMode = true;
          this.form.errors = error.response.data;
        });
    },
  }
};
</script>
