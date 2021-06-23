<style lang="scss" scoped>
.page-icon {
  width: 25px;
}

.page-item:not(:first-child) {
  padding-top: 0;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mb4 mw6 br3 center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company/kb'">{{ $t('app.breadcrumb_wiki_list') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_wiki_detail') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 relative z-1">
        <h2 class="tc normal mb2 lh-copy">
          {{ wiki.title }}
        </h2>

        <div class="cf center">
          <!-- LEFT COLUMN -->
          <div class="fl w-70-l w-100">
            <!-- list of pages -->
            <div v-if="wiki.pages.length > 0" class="br3 bg-white box z-1">
              <ul class="list pl0 ma0">
                <li v-for="page in wiki.pages" :key="page.id" class="pa3 flex page-item">
                  <svg class="page-icon gray mr2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                  </svg>

                  <div class="mb2 relative">
                    <inertia-link :href="page.url" class="f4 dib mb2">
                      {{ page.title }}
                    </inertia-link>

                    <p class="ma0 gray f6">{{ page.first_revision.name }}</p>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <inertia-link :href="wiki.urls.create" class="employee-name db">
              + add page
            </inertia-link>
            <div class="mb2 fw5 relative">
              <span class="mr1">
                💹
              </span> {{ $t('group.summary_stat') }}
            </div>

            <ul class="list pl0">
              <li class="pl2"><inertia-link :href="wiki.urls.delete" class="f6 gray c-delete">{{ $t('group.summary_delete') }}</inertia-link></li>
            </ul>
          </div>
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
    notifications: {
      type: Array,
      default: null,
    },
    wiki: {
      type: Object,
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
