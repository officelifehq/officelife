<style lang="scss" scoped>
.date-icon {
  width: 15px;
  top: 2px;
  padding-left: 25px;
}

.folder-icon {
  width: 21px;
  top: 5px;
}
</style>

<template>
  <layout :notifications="notifications">
    <!-- header -->
    <header-component :statistics="statistics" />

    <div class="ph2 ph5-ns">
      <!-- central content -->
      <tab :tab="tab" />

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <div v-if="wikis.data.length > 0">
          <div class="mt4 mt5-l center section-btn relative mb5">
            <p>
              <span class="pr2">
                {{ $t('kb.index_title') }}
              </span>
              <inertia-link :href="wikis.url_create" class="btn absolute db-l dn">
                {{ $t('kb.index_cta') }}
              </inertia-link>
            </p>
          </div>

          <!-- list of wikis -->
          <ul class="mt2 list pl0">
            <li v-for="wiki in wikis.data" :key="wiki.id" class="w-100 bg-white box pa3 mb3 mr3 flex justify-between items-center">
              <div>
                <h2 class="fw4 f4 mt0 mb2 lh-copy relative">
                  <svg xmlns="http://www.w3.org/2000/svg" class="folder-icon relative mr2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                  </svg>
                  <inertia-link :href="wiki.url">{{ wiki.title }}</inertia-link>
                </h2>
                <ul class="gray f6 list ma0 pl0">
                  <li class="di relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="date-icon relative" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>

                    {{ $tc('kb.index_number_pages', wiki.count, {count: wiki.count}) }}
                  </li>
                  <li v-if="wiki.most_recent_page" class="di relative">
                    <svg class="date-icon relative mr2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>

                    <inertia-link :href="wiki.most_recent_page.url">{{ wiki.most_recent_page.title }}</inertia-link>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </div>

        <!-- blank state -->
        <div v-else class="tc">
          <img loading="lazy" src="/img/streamline-icon-content-ideas@140x140.png" alt="wiki symbol" height="140"
               width="140"
          />
          <p class="mb3">
            <span class="db mb4">{{ $t('kb.index_blank_title') }}</span>
            <inertia-link :href="wikis.url_create" class="btn dib">{{ $t('kb.index_cta') }}</inertia-link>
          </p>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Tab from '@/Pages/Company/Partials/Tab';
import HeaderComponent from '@/Pages/Company/Partials/Header';

export default {
  components: {
    Layout,
    Tab,
    HeaderComponent,
  },

  props: {
    tab: {
      type: String,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
    wikis: {
      type: Object,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
