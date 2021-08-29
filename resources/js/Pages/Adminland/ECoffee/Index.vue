<style lang="scss" scoped>
.status-active {
  background-color: #dcf7ee;

  .dot {
    background-color: #00b760;
  }
}

.status-inactive {
  background-color: #ffe9e3;

  .dot {
    background-color: #ff3400;
  }
}

.dot {
  height: 8px;
  top: 3px;
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
        {{ $t('app.breadcrumb_account_manage_e_coffee') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.ecoffee_title') }}

            <help :url="$page.props.help_links.ecoffee" :datacy="'help-icon-general'" :top="'2px'" />
          </h2>

          <div class="relative">
            <img loading="lazy" src="/img/streamline-icon-workspace-imac-coffee-1@140x140.png" alt="add email symbol" class="absolute left-1 mr1" height="80"
                 width="80"
            />

            <div class="ml6">
              <p class="lh-copy">{{ $t('account.ecoffee_desc') }}</p>

              <p v-if="localECoffee.enabled" data-cy="message-enable" class="status-active dib pa3 br3">
                <span class="br3 f7 fw3 ph2 pv1 dib relative mr1 dot"></span>
                {{ $t('account.ecoffee_enabled') }}
              </p>

              <p v-if="!localECoffee.enabled" data-cy="message-disable" class="status-inactive dib pa3 br3">
                <span class="br3 f7 fw3 ph2 pv1 dib relative mr1 dot"></span>
                {{ $t('account.ecoffee_disabled') }}
              </p>

              <form @submit.prevent="toggleProcess">
                <errors :errors="form.errors" />

                <loading-button v-if="!localECoffee.enabled" :class="'btn w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.enable')" :cypress-selector="'enable-ecoffee-process'" />
                <loading-button v-if="localECoffee.enabled" :class="'btn w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="$t('app.disable')" :cypress-selector="'disable-ecoffee-process'" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton';
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Errors from '@/Shared/Errors';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    Errors,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    ecoffee: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      loadingState: null,
      localECoffee: null,
      form: {
        errors: [],
      },
    };
  },

  created() {
    this.localECoffee = this.ecoffee;
  },

  methods: {
    toggleProcess() {
      this.loadingState = 'loading';

      axios.post(`${this.$page.props.auth.company.id}/account/ecoffee`, this.form)
        .then(response => {
          this.loadingState = null;
          this.localECoffee = response.data.data;
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
