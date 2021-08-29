<style lang="scss" scoped>
.news-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.news-item:last-child {
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
        {{ $t('app.breadcrumb_account_manage_company_news') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.company_news_title', { company: $page.props.auth.company.name}) }}
          </h2>

          <errors :errors="errors" />

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="localNews.length == 0 ? 'white' : ''">
              {{ $tc('account.company_news_number_news', localNews.length, { company: $page.props.auth.company.name, count: localNews.length}) }}
            </span>
            <inertia-link :href="'/' + $page.props.auth.company.id + '/account/news/create'" class="btn absolute-l relative dib-l db right-0" data-cy="add-news-button">
              {{ $t('account.company_news_cta') }}
            </inertia-link>
          </p>

          <!-- LIST OF EXISTING NEWS -->
          <ul v-show="localNews.length != 0" class="list pl0 mv0 center" data-cy="news-list" :data-cy-items="localNews.map(n => n.id)">
            <li v-for="singleNews in news" :key="singleNews.id" class="news-item pa3 br bl bb bb-gray bb-gray-hover">
              <h3 class="mt0">{{ singleNews.title }}</h3>

              <div class="parsed-content mb3" v-html="singleNews.parsed_content"></div>

              <!-- LIST OF ACTIONS FOR EACH NEWS -->
              <ul class="list pa0 ma0 di-ns db mt2 mt0-ns">
                <!-- DATE -->
                <span class="f7 mr1">
                  {{ $t('account.company_news_written_by', { name: singleNews.author.name, date: singleNews.localized_created_at }) }}
                </span>

                <!-- RENAME A NEWS -->
                <li class="di mr1 f7">
                  <inertia-link :href="'/' + $page.props.auth.company.id + '/account/news/' + singleNews.id + '/edit'" class="" :data-cy="'edit-news-button-' + singleNews.id">{{ $t('app.edit') }}</inertia-link>
                </li>

                <!-- DELETE A NEWS -->
                <li v-if="idToDelete == singleNews.id" class="di f7">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + singleNews.id" @click.prevent="destroy(singleNews.id)">
                    {{ $t('app.yes') }}
                  </a>
                  <a class="pointer" :data-cy="'list-delete-cancel-button-' + singleNews.id" @click.prevent="idToDelete = 0">
                    {{ $t('app.no') }}
                  </a>
                </li>
                <li v-else class="di f7">
                  <a class="bb b--dotted bt-0 bl-0 br-0 pointer c-delete" :data-cy="'list-delete-button-' + singleNews.id" @click.prevent="idToDelete = singleNews.id">{{ $t('app.delete') }}</a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="localNews.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              {{ $t('account.company_news_blank') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Errors from '@/Shared/Errors';

export default {
  components: {
    Layout,
    Breadcrumb,
    Errors,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    news: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      localNews: [],
      idToDelete: 0,
      errors: [],
    };
  },

  mounted() {
    this.localNews = this.news;

    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy(id) {
      this.errors = [];
      axios.delete(this.route('account_news.news.destroy', [this.$page.props.auth.company.id, id]))
        .then(response => {
          this.flash(this.$t('account.company_news_success_destroy'), 'success');

          this.idToDelete = 0;
          id = this.localNews.findIndex(x => x.id === id);
          this.localNews.splice(id, 1);
        })
        .catch(error => {
          this.errors = error.response.data;
        });
    },
  }
};

</script>
