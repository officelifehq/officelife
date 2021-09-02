<style lang="scss" scoped>
.box-bottom {
  border-bottom-left-radius: 11px;
  border-bottom-right-radius: 11px;
}

.warning {
  box-shadow: 0 0 0 1px #e3e8ee;
  background-color: #f7fafc;
}

.badge {
  padding: 1px 6px;
  border-radius: 4px;

  &.active {
    background-color: #cbf4c9;
  }

  &.closed {
    background-color: #e3e8ee;
  }
}
</style>

<template>
  <div>
    <div class="box bg-white mb4">
      <div class="pa3 flex justify-between items-center bb bb-gray">
        <!-- name + summary -->
        <div>
          <h2 class="mt0 mb2 relative fw4">
            {{ candidate.name }}
          </h2>

          <ul class="list pa0 ma0 f7 gray">
            <li class="di mr3">
              {{ $t('dashboard.job_opening_show_candidate_applied', { date: candidate.created_at }) }}
            </li>
            <li class="di mr3">{{ candidate.email }}</li>
          </ul>
        </div>

        <inertia-link v-if="! jobOpening.fulfilled" :href="candidate.url_hire" class="btn">{{ $t('dashboard.job_opening_show_candidate_hire') }}</inertia-link>
      </div>

      <div class="bg-gray pa3 f7 box-bottom">
        <p class="ma0">{{ $t('dashboard.job_opening_show_candidate_job_opening') }} <inertia-link :href="jobOpening.url">{{ jobOpening.title }}</inertia-link></p>
      </div>
    </div>

    <!-- has the candidate applied to other jobs in the company -->
    <div v-if="otherJobOpenings.length > 0" class="warning pa3 br3 mb4">
      <p class="ma0 mb3 f6"><span class="mr1">⚠️</span> {{ $t('dashboard.job_opening_show_other_jobs', { count: otherJobOpenings.length }) }}</p>
      <ul class="list ma0 pl0">
        <li v-for="job in otherJobOpenings" :key="job.id" class="mb2 mr2">
          <inertia-link :href="job.url" class="mr2">{{ job.title }}</inertia-link>
          <span v-if="job.active" class="badge f7 active">{{ $t('dashboard.job_opening_show_other_jobs_active') }}</span>
          <span v-else class="badge f7 closed">{{ $t('dashboard.job_opening_show_other_jobs_closed') }}</span>
        </li>
      </ul>
    </div>

    <!-- tab -->
    <div class="center br3 mb5 tc">
      <div class="cf dib btn-group">
        <inertia-link :href="candidate.url_cv" :class="{ 'selected': tab == 'cv' }" class="f6 fl ph3 pv2 dib pointer no-underline">
          {{ $t('dashboard.job_opening_show_candidate_tab_cv') }}
        </inertia-link>
        <inertia-link :href="candidate.url_stages" :class="{ 'selected': tab == 'recruiting' }" class="f6 fl ph3 pv2 dib pointer no-underline">
          {{ $t('dashboard.job_opening_show_candidate_tab_recruiting') }}
        </inertia-link>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    jobOpening: {
      type: Object,
      default: null,
    },
    candidate: {
      type: Object,
      default: null,
    },
    otherJobOpenings: {
      type: Object,
      default: null,
    },
    tab: {
      type: String,
      default: null,
    },
  },
};

</script>
