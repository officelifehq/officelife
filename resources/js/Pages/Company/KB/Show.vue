<style lang="scss" scoped>
.page-icon {
  width: 25px;
}

.page-item:not(:first-child) {
  padding-top: 0;
}

.folder-icon {
  width: 26px;
  top: 6px;
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph5-ns">
      <breadcrumb
        :root-url="'/' + $page.props.auth.company.id + '/company/kb'"
        :root="$t('app.breadcrumb_kb_list')"
        :has-more="false"
      >
        {{ $t('app.breadcrumb_wiki_detail') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw8 center br3 mb5 relative z-1">
        <h2 class="normal mb2 lh-copy relative f3">
          <svg xmlns="http://www.w3.org/2000/svg" class="folder-icon relative mr1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
          </svg>
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

                    <ul class="ma0 gray f7 pl0 list">
                      <li class="di mr2">{{ $t('kb.show_written_by', {name: page.first_revision.name, date: page.first_revision.created_at}) }}</li>
                      <li class="di"><span class="mr1">•</span> {{ $t('kb.show_edited_by', {name: page.last_revision.name, date: page.last_revision.created_at}) }}</li>
                    </ul>
                  </div>
                </li>
              </ul>
            </div>

            <!-- blank state -->
            <div v-else class="br3 bg-white box z-1 pa4 tc">
              <img loading="lazy" src="/img/streamline-icon-app-content@140x140.png" alt="" height="140"
                   width="140"
              />
              <p class="mb3">
                <span class="db mb4">{{ $t('kb.show_blank_state') }}</span>
              </p>
            </div>
          </div>

          <!-- RIGHT COLUMN -->
          <div class="fl w-30-l w-100 pl4-l">
            <inertia-link :href="wiki.urls.create" class="btn relative dib-l db right-0">
              {{ $t('kb.show_cta') }}
            </inertia-link>

            <ul class="list pl0 f7">
              <li class="mb2"><inertia-link :href="wiki.urls.edit" class="bb b--dotted bt-0 bl-0 br-0 pointer">{{ $t('app.edit') }}</inertia-link></li>
              <li v-if="!showDeleteMode"><a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" @click="showDeleteMode = true">{{ $t('kb.index_delete') }}</a></li>
              <li v-else>
                {{ $t('app.sure') }}
                <a class="c-delete mr1 pointer" @click.prevent="destroy()">
                  {{ $t('app.yes') }}
                </a>
                <a class="pointer" @click.prevent="showDeleteMode = false">
                  {{ $t('app.no') }}
                </a>
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
import Breadcrumb from '@/Shared/Layout/Breadcrumb';

export default {
  components: {
    Layout,
    Breadcrumb,
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

  data() {
    return {
      showDeleteMode: false,
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy() {
      axios.delete(`/${this.$page.props.auth.company.id}/company/kb/${this.wiki.id}`)
        .then(response => {
          localStorage.success = this.$t('kb.show_destroyed_success');
          this.$inertia.visit(response.data.data.url);
        })
        .catch(error => {
          this.form.errors = error.response.data;
        });
    },
  }
};

</script>
