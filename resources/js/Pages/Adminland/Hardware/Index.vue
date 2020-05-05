<style style="scss" scoped>
  .question-item:last-child {
    border-bottom: 0;
    padding-bottom: 0;
  }
  .question-badge-inactive {
    background-color: #E2E4E8;
  }
  .question-badge-active {
    background-color: #52CF6E;
    color: #fff;
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
            <span class="dib mb3 di-l">
              This is the place to
            </span>
            <inertia-link :href="'/' + $page.auth.company.id + '/account/hardware/create'" class="btn absolute-l relative dib-l db right-0" data-cy="add-news-button">
              {{ $t('account.company_news_cta') }}
            </inertia-link>
          </p>
        </div>

        <!-- blank state -->
        <div v-if="!hardwareCollection" class="pa3 mt5">
          <p class="tc measure center mb4 lh-copy">
            {{ $t('account.company_news_blank') }}
          </p>
        </div>

        <!-- WHEN THERE ARE HARDWARE -->
        <ul v-if="hardwareCollection">
          <li v-for="item in hardwareCollection" :key="item.id">
            {{ item.name }}
          </li>
        </ul>
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
    hardware: {
      type: Array,
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
    }
  },

  methods: {
  }
};

</script>
