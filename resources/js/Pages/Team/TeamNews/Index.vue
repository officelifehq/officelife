<style lang="scss" scoped>
.news-information {
  img {
    top: 7px;
    width: 23px;
  }
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
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id">{{ team.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_team_show_team_news') }}
          </li>
        </ul>
      </div>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1">
        <div class="pa3 center">
          <h2 class="tc normal mb4">
            {{ $t('team.team_news_index', { name: team.name}) }}
          </h2>

          <!-- List of news -->
          <div v-for="newsItem in news" :key="newsItem.id" class="cf news" data-cy="news-list">
            <h3 class="mb1">
              {{ newsItem.title }}
            </h3>
            <div class="lh-copy mt2 br3 parsed-content" v-html="newsItem.parsed_content">
            </div>
            <div class="f6 relative news-information silver mb3">
              <img :src="newsItem.author.avatar" class="br-100 relative mr1" alt="avatar" />
              {{ $t('team.team_news_written_by_at', { name: newsItem.author.name, created_at: newsItem.localized_created_at }) }}

              <!-- edit -->
              <inertia-link :href="'/' + $page.auth.company.id + '/teams/' + team.id + '/news/' + newsItem.id + '/edit'" class="ml1 mr2" :data-cy="'edit-news-button-' + newsItem.id">{{ $t('app.edit') }}</inertia-link>

              <!-- delete -->
              <a v-if="idToDelete == 0" class="c-delete mr1 pointer" :data-cy="'delete-news-button-' + newsItem.id" @click.prevent="idToDelete = newsItem.id">{{ $t('app.delete') }}</a>

              <span v-if="idToDelete == newsItem.id">
                {{ $t('app.sure') }}
                <a class="c-delete mr1 pointer" :data-cy="'delete-news-button-confirm-' + newsItem.id" @click.prevent="destroy(newsItem.id)">
                  {{ $t('app.yes') }}
                </a>
                <a class="pointer" :data-cy="'delete-news-button-cancel-' + newsItem.id" @click.prevent="idToDelete = 0">
                  {{ $t('app.no') }}
                </a>
              </span>
            </div>
            <div class="tc mb3 green">
              ~
            </div>
          </div>

          <!-- Pagination -->
          <div class="center cf">
            <inertia-link v-show="paginator.previousPageUrl" class="fl dib" :href="paginator.previousPageUrl" title="Previous">
              &larr; {{ $t('app.previous') }}
            </inertia-link>
            <inertia-link v-show="paginator.nextPageUrl" class="fr dib" :href="paginator.nextPageUrl" title="Next">
              {{ $t('app.next') }} &rarr;
            </inertia-link>
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
    team: {
      type: Object,
      default: null,
    },
    news: {
      type: Array,
      default: null,
    },
    paginator: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      idToDelete: 0,
    };
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
    destroy(id) {
      axios.delete('/' + this.$page.auth.company.id + '/teams/' + this.team.id + '/news/' + id)
        .then(response => {
          flash(this.$t('team.team_news_destroy_success'), 'success');

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
