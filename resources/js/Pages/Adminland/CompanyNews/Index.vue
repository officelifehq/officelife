<style scoped>
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
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard'">
              {{ $page.auth.company.name }}
            </inertia-link>
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.auth.company.id + '/account'">
              {{ $t('app.breadcrumb_account_home') }}
            </inertia-link>
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

          <p class="relative">
            <span class="dib mb3 di-l" :class="news.length == 0 ? 'white' : ''">{{ $tc('account.company_news_number_news', news.length, { company: $page.auth.company.name, count: news.length}) }}</span>
            <inertia-link class="btn absolute-l relative dib-l db right-0" data-cy="add-position-button">
              {{ $t('account.company_news_cta') }}
            </inertia-link>
          </p>

          <p>Here are all the news .</p>

          <!-- LIST OF EXISTING NEWS -->
          <ul v-show="news.length != 0" class="list pl0 mv0 center ba br2 bb-gray" data-cy="news-list">
            <li v-for="(news) in companyNews" :key="news.id" class="pv3 ph2 bb bb-gray bb-gray-hover">
              {{ news.title }}

              <!-- LIST OF ACTIONS FOR EACH POSITION -->
              <ul v-show="idToUpdate != news.id" class="list pa0 ma0 di-ns db fr-ns mt2 mt0-ns">
                <!-- RENAME A POSITION -->
                <li class="di mr2">
                  <a class="pointer" :data-cy="'list-rename-button-' + news.id" @click.prevent="displayUpdateModal(position) ; form.title = news.title">{{ $t('app.rename') }}</a>
                </li>

                <!-- DELETE A POSITION -->
                <li v-if="idToDelete == news.id" class="di">
                  {{ $t('app.sure') }}
                  <a class="c-delete mr1 pointer" :data-cy="'list-delete-confirm-button-' + news.id" @click.prevent="destroy(news.id)">{{ $t('app.yes') }}</a>
                  <a class="pointer" :data-cy="'list-delete-cancel-button-' + news.id" @click.prevent="idToDelete = 0">{{ $t('app.no') }}</a>
                </li>
                <li v-else class="di">
                  <a class="pointer" :data-cy="'list-delete-button-' + news.id" @click.prevent="idToDelete = news.id">{{ $t('app.delete') }}</a>
                </li>
              </ul>
            </li>
          </ul>

          <!-- BLANK STATE -->
          <div v-show="news.length == 0" class="pa3 mt5">
            <p class="tc measure center mb4 lh-copy">
              Do you need to broadcast an announcement or a news to every employee of Behaviour? You can do so here!
            </p>
            <img class="db center mb4" srcset="/img/company/account/blank-position-1x.png,
                                          /img/company/account/blank-position-2x.png 2x"
            />
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
    companyNews: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
    };
  },

  methods: {
    destroy(id) {
      axios.delete('/' + this.$page.auth.company.id + '/account/positions/' + id)
        .then(response => {
          this.$snotify.success(this.$t('account.position_success_destroy'), {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true,
          });

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
