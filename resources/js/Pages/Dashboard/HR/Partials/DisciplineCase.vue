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

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}

.top-1 {
  top: 0.3rem;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        🚨
      </span> {{ $t('dashboard.hr_discipline_cases_index_title') }}

      <help :url="$page.props.help_links.discipline" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <div class="flex items-center pa3">
        <img loading="lazy" src="/img/streamline-icon-general-honor-3@100x100.png" width="100" alt="meeting" class="di-ns dn top-1 left-1 mr2" />

        <div class="mb4">
          <p class="pl0-ns pl3 pb2 pr3 mb2">
            {{ $t('dashboard.hr_discipline_cases_summary_blank') }}
          </p>

          <inertia-link :href="data.url.index" class="btn w-auto-ns w-100 mr2 pv2 ph3 mr3">{{ $t('dashboard.view_all_cases') }}</inertia-link>
        </div>
      </div>
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
    data: {
      type: Object,
      default: null,
    },
  },
};
</script>
