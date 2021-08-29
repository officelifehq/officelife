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

.note {
  background-color: #e3f2fd;
  color: #245981;

  svg {
    width: 36px;
  }
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/account/billing'"
                  :previous="$t('app.breadcrumb_account_manage_invoices')"
      >
        {{ $t('app.breadcrumb_account_show_invoices') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.billing_show_title', {date: invoice.month}) }}

            <help :url="$page.props.help_links.billing" :top="'1px'" />
          </h2>

          <div class="note flex mb4 pa3 br3 items-center">
            <svg class="mr2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="lh-copy ma0">{{ $t('account.billing_show_description', {month: invoice.month, max_employees: invoice.day_with_max_employees, number_active_employees: invoice.number_of_active_employees }) }}</p>
          </div>

          <!-- LIST OF DETAILS -->
          <ul class="list pl0 mv0 center ba br2 bb-gray">
            <li v-for="detail in invoice.details" :key="detail.id" class="invoice-item pv3 ph2 bb bb-gray bb-gray-hover">
              {{ detail.employee_name }} <span class="code gray f7">({{ detail.employee_email }})</span>
            </li>
          </ul>
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
    invoice: {
      type: Array,
      default: null,
    },
  },
};

</script>
