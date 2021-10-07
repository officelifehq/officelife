<style lang="scss" scoped>
.answer-item:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        📰
      </span> {{ $t('company.company_news_title') }}
    </span>

    <div class="br3 bg-white box z-1">
      <!-- list of questions -->
      <div v-if="news.count > 0">
        <ul class="list ma0 pa3">
          <li v-for="newsItem in news.news" :key="newsItem.id" class="answer-item mb3">
            <h3 class="f5 fw5 mt0 mb1 lh-copy">{{ newsItem.title }} <span class="f7 gray ml2">{{ newsItem.created_at }}</span></h3>
            <div class="gray parsed-content f6" v-html="newsItem.extract"></div>
          </li>
        </ul>
        <div class="ph3 pv2 tc f6 bt bb-gray">
          <inertia-link :href="news.view_all_url">{{ $t('company.news_view_all') }}</inertia-link>
        </div>
      </div>

      <!-- blank state -->
      <div v-else class="pa3 tc">
        <p class="mb0 mt0 lh-copy f6" data-cy="question-blank-state">{{ $t('company.news_index_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    news: {
      type: Object,
      default: null,
    },
  },
};
</script>
