<style lang="scss" scoped>

</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        🎤
      </span> {{ $t('company.hr_ama_widget_title') }}
    </span>

    <div class="br3 bg-white box z-1 relative">
      <div v-if="data.session" class="pa3 flex justify-between items-center">
        <div>
          <h2 class="fw4 mt0 mb0">
            {{ data.session.happened_at }}
          </h2>
          <p v-if="data.session.theme" class="f6 mt2 mb0">{{ data.session.theme }}</p>
        </div>

        <div class="tc">
          <inertia-link :href="data.session.url" class="btn db mb2">{{ $t('app.view') }}</inertia-link>
          <p class="mv0 gray f7">{{ $tc('company.hr_ama_widget_questions', data.session.questions_count, {count: data.session.questions_count} ) }}</p>
        </div>
      </div>

      <!-- blank state -->
      <div v-else class="pa3 flex justify-between items-center">
        <h2 class="fw4 f5 mt0 mb0 tc">
          {{ $t('company.hr_ama_widget_blank') }}
        </h2>
      </div>

      <div class="ph3 pv2 tc f6 bt bb-gray">
        <inertia-link :href="data.url_view_all">{{ $t('company.hr_ama_widget_view_all') }}</inertia-link>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    data: {
      type: Object,
      default: null,
    },
  },
};
</script>
