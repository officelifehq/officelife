<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.illustration {
  top: 43px;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        💺
      </span> Recruiting

      <help :url="$page.props.help_links.one_on_ones" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative pa3">
      <p class="mt0 mb2 f6 gray">You've been asked to help in the recruiting process of the candidates below.</p>

      <img loading="lazy" src="/img/streamline-icon-user-rating-star-4@100x100.png" width="90" alt="meeting" class="illustration absolute-ns di-ns dn top-1 left-1" />

      <ul class="pl6-ns pl3 pb3 pt3 pr3 ma0 list">
        <li v-for="jobOpening in jobOpenings" :key="jobOpening.id" class="flex justify-between items-center br bl bb bb-gray bb-gray-hover pa3 entry-item">
          <div>
            <span class="db mb0">{{ jobOpening.candidate.name }}</span>
            <span class="f7 gray">{{ jobOpening.title }}</span>
          </div>

          <!-- call to action -->
          <div class="tr">
            <inertia-link :href="'manager.entry.url'" class="btn dib-l db">{{ $t('dashboard.one_on_ones_cta') }}</inertia-link>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    jobOpenings: {
      type: Array,
      default: null,
    },
  },
};
</script>
