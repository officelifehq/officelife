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
          <h2 class="tc normal mb4">
            {{ $t('account.billing_index_title') }}

            <help :url="$page.props.help_links.billing" :top="'1px'" />
          </h2>

          <!-- LIST OF INVOICES -->
          <ul v-if="invoices.length != 0" class="list pl0 mv0 center ba br2 bb-gray">
            <li v-for="invoice in invoices" :key="invoice.id" class="invoice-item pv3 ph2 bb bb-gray bb-gray-hover flex items-center justify-between">
              <div>
                <span class="fw5 db mb1">{{ invoice.month }}</span>
                <span class="gray f6">{{ $t('account.billing_index_month', {number: invoice.number_of_active_employees}) }}</span>
              </div>

              <inertia-link :href="invoice.url" class="f6 mr2 dib">{{ $t('app.view') }}</inertia-link>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="invoices.length == 0" class="pa3 mt5">
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

export default {
  components: {
    Layout,
    Breadcrumb,
    Help,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    invoices: {
      type: Array,
      default: null,
    },
  },
};

</script>
