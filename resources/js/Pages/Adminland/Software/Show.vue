<style lang="scss" scoped>
.history-item {
  padding:  1px 6px;
}

.title {
  margin-bottom: -15px;

  p {
    background-color: #fff;
    padding-right: 4px;
    top: -27px;
  }
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
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/softwares'">{{ $t('app.breadcrumb_account_manage_software') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_show_software') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="normal mb3" data-cy="item-name">
            {{ software.name }}
          </h2>

          <ul>
            <li>{{ software.website }}</li>
            <li>{{ software.product_key }}</li>
            <li>{{ software.seats }}</li>
            <li>{{ software.licensed_to_name }}</li>
            <li>{{ software.licensed_to_email_address }}</li>
            <li>{{ software.order_number }}</li>
            <li>{{ software.purchase_amount }}</li>
            <li>{{ software.currency }}</li>
            <li>{{ software.converted_purchase_amount }}</li>
            <li>{{ software.converted_to_currency }}</li>
            <li>{{ software.purchased_at }}</li>
            <li>{{ software.converted_at }}</li>
            <li>{{ software.exchange_rate }}</li>
          </ul>

          <p v-if="software.serial_number" class="relative mb3" data-cy="item-serial-number">
            <svg class="relative mr1" style="top: 3px;" width="15" height="15" viewBox="0 0 15 15"
                 fill="none" xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M3.88889 11.1111H3.89611M3.88889 14C3.12271 14 2.38791 13.6956 1.84614 13.1539C1.30436 12.6121 1 11.8773 1 11.1111V2.44444C1 2.06135 1.15218 1.69395 1.42307 1.42307C1.69395 1.15218 2.06135 1 2.44444 1H5.33333C5.71642 1 6.08382 1.15218 6.35471 1.42307C6.6256 1.69395 6.77778 2.06135 6.77778 2.44444V11.1111C6.77778 11.8773 6.47341 12.6121 5.93164 13.1539C5.38987 13.6956 4.65507 14 3.88889 14V14ZM3.88889 14H12.5556C12.9386 14 13.306 13.8478 13.5769 13.5769C13.8478 13.306 14 12.9386 14 12.5556V9.66667C14 9.28358 13.8478 8.91618 13.5769 8.64529C13.306 8.3744 12.9386 8.22222 12.5556 8.22222H10.8634L3.88889 14ZM6.77778 4.13661L7.9745 2.93989C8.24537 2.6691 8.61271 2.51698 8.99572 2.51698C9.37874 2.51698 9.74607 2.6691 10.0169 2.93989L12.0601 4.98306C12.3309 5.25393 12.483 5.62126 12.483 6.00428C12.483 6.38729 12.3309 6.75463 12.0601 7.0255L5.93133 13.1536L6.77778 4.13661Z" stroke="#C9C4C4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ software.serial_number }}
          </p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
  },

  props: {
    software: {
      type: Object,
      default: null,
    },
    employees: {
      type: Array,
      default: null,
    },
    paginator: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      form: {
        name: null,
        serial: null,
        employee_id: null,
        lend_hardware: false,
        errors: [],
      },
      idToDelete: 0,
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
  }
};

</script>
