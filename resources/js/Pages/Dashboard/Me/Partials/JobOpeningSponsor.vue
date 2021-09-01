<style lang="scss" scoped>
.job-opening-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.job-opening-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.reference-number {
  padding: 2px 6px;
  border-radius: 6px;
  top: -4px;
  background-color: #ddf4ff;
  color: #0969da;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      <span class="mr1">
        🧑‍🎓
      </span> {{ $t('dashboard.job_opening_sponsor_title') }}

      <help :url="$page.props.help_links.worklogs" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box">
      <ul class="list ma0 pl0">
        <li v-for="jobOpening in jobOpenings" :key="jobOpening.id" class="pa3 job-opening-item bb bb-gray bb-gray-hover">
          <div>
            <div :class="jobOpening.team ? 'mb2': ''" class="db">
              <inertia-link :href="jobOpening.url" class="mr2">{{ jobOpening.title }}</inertia-link>
              <span v-if="jobOpening.reference_number" class="reference-number f7 code fw4">{{ jobOpening.reference_number }}</span>
            </div>

            <p v-if="jobOpening.team" class="ma0 f7 gray">For <inertia-link :href="jobOpening.team.url">{{ jobOpening.team.name }}</inertia-link></p>
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
    jobOpenings: {
      type: Object,
      default: null,
    },
  },
};
</script>
