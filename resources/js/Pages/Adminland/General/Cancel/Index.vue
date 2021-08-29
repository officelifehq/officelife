<style lang="scss" scoped>
.check {
  color: #fb8444;
  top: 4px;
  width: 20px;
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
        {{ $t('app.breadcrumb_account_manage_cancel_account') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="mt5">
          <h2 class="tc normal mb4 pt3 relative">
            {{ $t('account.cancel_account_title') }}

            <help :url="$page.props.help_links.account_cancellation" :top="'1px'" />
          </h2>

          <div class="ph5 pv3 bb bb-gray">
            <p class="mb5">{{ $t('account.cancel_account_thanks') }}</p>

            <p class="fw6">{{ $t('account.cancel_account_please_note') }}</p>
            <ul class="list ma0 pa0">
              <li class="relative mb2-l lh-copy">
                <svg class="check relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ $t('account.cancel_account_company_account_closed') }}
              </li>
              <li class="relative mb2-l lh-copy">
                <svg class="check relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ $t('account.cancel_account_company_employee_deleted') }}
              </li>
              <li class="relative mb2-l lh-copy">
                <svg class="check relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ $t('account.cancel_account_company_data_deleted') }}
              </li>
              <li class="relative mb2-l lh-copy">
                <svg class="check relative" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ $t('account.cancel_account_company_subscription') }}
              </li>
            </ul>

            <p class="lh-copy">{{ $t('account.cancel_account_data_lost_forever') }}</p>
          </div>

          <!-- disable actions if instance is in demo mode -->
          <div v-if="$page.props.demo_mode" class="cf pa3 tc">
            <span class="mr1">
              ⚠️
            </span> {{ $t('app.demo_mode_deactivated') }}
          </div>

          <!-- actions -->
          <form v-if="!$page.props.demo_mode" class="cf pa3" @submit.prevent="destroy">
            <div class="flex-ns justify-between">
              <div>
                <inertia-link :href="'/' + $page.props.auth.company.id + '/account'" class="btn dib tc w-auto-ns w-100 pv2 ph3 mb0-ns mb2">
                  {{ $t('app.cancel') }}
                </inertia-link>
              </div>
              <loading-button :class="'btn destroy w-auto-ns w-100 pv2 ph3'" :state="loadingState" :text="$t('account.cancel_cta')" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import LoadingButton from '@/Shared/LoadingButton';
import Help from '@/Shared/Help';

export default {
  components: {
    Layout,
    Breadcrumb,
    LoadingButton,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      loadingState: '',
    };
  },

  methods: {
    destroy() {
      this.loadingState = 'loading';

      axios.delete(`/${this.$page.props.auth.company.id}/account/cancel`)
        .then(response => {
          this.$inertia.visit('/home');
        })
        .catch(error => {
          this.loadingState = null;
        });
    },
  }
};

</script>
