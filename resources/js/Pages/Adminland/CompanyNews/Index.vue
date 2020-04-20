<style lang="scss" scoped>
.list li:last-child {
  border-bottom: 0;
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
            {{ $t('app.breadcrumb_account_manage_company_news') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box restricted relative z-1">
        <div class="pa3 mt5">
          <h2 class="tc normal mb4">
            {{ $t('account.company_news_title', { company: $page.auth.company.name}) }}
          </h2>

          <p class="relative adminland-headline">
            <span class="dib mb3 di-l" :class="news.length == 0 ? 'white' : ''">
              {{ $tc('account.company_news_number_news', news.length, { company: $page.auth.company.name, count: news.length}) }}
            </span>
            <inertia-link :href="'/' + $page.auth.company.id + '/account/news/create'" class="btn absolute-l relative dib-l db right-0" data-cy="add-news-button">
              {{ $t('account.company_news_cta') }}
            </inertia-link>
          </p>

          <!-- LIST OF EXISTING NEWS -->
          <ul v-show="news.length != 0" class="list pl0 mv0 center" data-cy="news-list">
            <li v-for="singleNews in news" :key="singleNews.id" class="pb2 pt1 bb bb-gray bb-gray-hover">
              <h3>{{ singleNews.title }}</h3>

              <div class="parsed-content" v-html="singleNews.parsed_content"></div>

              <!-- LIST OF ACTIONS FOR EACH NEWS -->
              <ul class="list pa0 ma0 di-ns db mt2 mt0-ns">
                <!-- DATE -->
                <span class="f7 mr1">
                  {{ $t('account.company_news_written_by', { name: singleNews.author.name, date: singleNews.localized_created_at }) }}
                </span>

                <!-- RENAME A NEWS -->
                <li class="di mr1 f7">
                  <inertia-link :href="'/' + $page.auth.company.id + '/account/news/' + singleNews.id + '/edit'" class="" :data-cy="'edit-news-button-' + singleNews.id">
                    {{ $t('app.edit') }}
                  </inertia-link>
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
                  <a class="pointer" :data-cy="'list-delete-button-' + singleNews.id" @click.prevent="idToDelete = singleNews.id">
                    {{ $t('app.delete') }}
                  </a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="news.length == 0" class="pa3 mt5">
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

export default {
  components: {
    Layout,
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
      idToDelete: 0,
    };
  },

  methods: {
    destroy(id) {
      axios.delete('/' + this.$page.auth.company.id + '/account/news/' + id)
        .then(response => {
          flash(this.$t('account.company_news_success_destroy'), 'success');

          this.idToDelete = 0;
          id = this.news.findIndex(x => x.id === id);
          this.news.splice(id, 1);
        })
        .catch(error => {
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};

</script>
