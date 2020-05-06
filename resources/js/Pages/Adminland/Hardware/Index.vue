<style style="scss" scoped>
  .chevron {
    top: 50%;
    transform: translateY(-50%);
    right: 14px;
  }

  .filter-active {
    background-color: #EBF4FF;
  }

  .hardware-list > li:hover {
    background-color: #f7f8f8;
  }

  .hardware-list > li:first-child {
    border-top-width: 1px;
    border-top-style: solid;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
  }

  .hardware-list > li:last-child {
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
  }
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">{{ $t('app.breadcrumb_account_home') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_account_manage_hardware') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.hardware_title') }}
          </h2>

          <p class="relative adminland-headline">
            <span v-if="hardwareCollection" class="dib mb3 di-l">
              {{ $t('account.hardware_description') }}
            </span>
            <inertia-link :href="'/' + $page.auth.company.id + '/account/hardware/create'" class="btn absolute-l relative dib-l db right-0" data-cy="add-news-button">
              {{ $t('account.hardware_cta') }}
            </inertia-link>
          </p>
        </div>

        <!-- blank state -->
        <div v-if="!hardwareCollection" class="pa3 mt3">
          <p class="tc measure center mb4 lh-copy">
            <span class="f3 tc db">👨‍💻</span>
            {{ $t('account.hardware_blank') }}
          </p>
        </div>

        <!-- WHEN THERE ARE HARDWARE -->
        <div v-if="hardwareCollection" class="cf pa3">
          <!-- sidebar -->
          <div class="fl w-third-ns w-100 ph2">
            <ul class="list ma0 pa0">
              <li class="pa2 mr2 filter-active br2">
                {{ $t('account.hardware_all_hardware') }} <span>({{ countHardwareTotal }})</span>
              </li>
              <li class="pa2 mr2">
                <inertia-link>{{ $t('account.hardware_available_hardware') }}</inertia-link> <span>({{ countHardwareNotLent }})</span>
              </li>
              <li class="pa2 mr2">
                <inertia-link>{{ $t('account.hardware_lent_hardware') }}</inertia-link> <span>({{ countHardwareLent }})</span>
              </li>
            </ul>
          </div>

          <!-- right part -->
          <div class="fl w-two-thirds-ns w-100">
            <ul class="list pl0 ma0 hardware-list">
              <li v-for="item in hardwareCollection" :key="item.id" class="pa3 bb bl br bb-gray relative pointer">
                <span class="db">{{ item.name }}</span>

                <!-- additional information -->
                <ul class="db list pl0 f6 mt2">
                  <!-- avatar -->
                  <li class="mr3 di">
                    <small-name-and-avatar
                      v-if="item.employee"
                      :name="item.employee.name"
                      :avatar="item.employee.avatar"
                      :classes="'gray'"
                      :size="'18px'"
                      :top="'0px'"
                      :margin-between-name-avatar="'25px'"
                    />

                    <span v-else class="gray f6 i">{{ $t('account.hardware_not_lent') }}</span>
                  </li>

                  <!-- serial number -->
                  <li class="di relative">
                    <svg class="relative mr1" style="top: 3px;" width="15" height="15" viewBox="0 0 15 15"
                         fill="none" xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M3.88889 11.1111H3.89611M3.88889 14C3.12271 14 2.38791 13.6956 1.84614 13.1539C1.30436 12.6121 1 11.8773 1 11.1111V2.44444C1 2.06135 1.15218 1.69395 1.42307 1.42307C1.69395 1.15218 2.06135 1 2.44444 1H5.33333C5.71642 1 6.08382 1.15218 6.35471 1.42307C6.6256 1.69395 6.77778 2.06135 6.77778 2.44444V11.1111C6.77778 11.8773 6.47341 12.6121 5.93164 13.1539C5.38987 13.6956 4.65507 14 3.88889 14V14ZM3.88889 14H12.5556C12.9386 14 13.306 13.8478 13.5769 13.5769C13.8478 13.306 14 12.9386 14 12.5556V9.66667C14 9.28358 13.8478 8.91618 13.5769 8.64529C13.306 8.3744 12.9386 8.22222 12.5556 8.22222H10.8634L3.88889 14ZM6.77778 4.13661L7.9745 2.93989C8.24537 2.6691 8.61271 2.51698 8.99572 2.51698C9.37874 2.51698 9.74607 2.6691 10.0169 2.93989L12.0601 4.98306C12.3309 5.25393 12.483 5.62126 12.483 6.00428C12.483 6.38729 12.3309 6.75463 12.0601 7.0255L5.93133 13.1536L6.77778 4.13661Z" stroke="#C9C4C4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <span v-if="item.serial_number">{{ item.serial_number }}</span>
                    <span v-else class="gray i">{{ $t('account.hardware_no_serial') }}</span>
                  </li>
                </ul>

                <svg class="chevron absolute" width="8" height="13" viewBox="0 0 8 13" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                ><path d="M1.375 1.5415L6.33333 6.49984L1.375 11.4582" stroke="#939090" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import SmallNameAndAvatar from '@/Shared/SmallNameAndAvatar';

export default {
  components: {
    Layout,
    SmallNameAndAvatar,
  },

  props: {
    hardware: {
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
      modal: false,
      renameMode: false,
      deletionMode: false,
      hardwareCollection: null,
      countHardwareLent: 0,
      countHardwareNotLent: 0,
      countHardwareTotal: 0,
      form: {
        title: null,
        active: false,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  created() {
    if (this.hardware) {
      this.hardwareCollection = this.hardware.hardware_collection;
      this.countHardwareLent = this.hardware.number_hardware_lent;
      this.countHardwareNotLent = this.hardware.number_hardware_not_lent;
      this.countHardwareTotal = this.countHardwareLent + this.countHardwareNotLent;
    }
  },

  methods: {
  }
};

</script>
