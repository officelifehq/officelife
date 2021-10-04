<style lang="scss" scoped>
.opening-item {
  &:first-child:hover {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  &:last-child {
    border-bottom: 0;

    &:hover {
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
  }
}

.icon-view {
  border-bottom: 0;

  svg {
    width: 15px;
  }

  &:hover {
    border-bottom: 0;
  }
}
</style>

<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        🥇
      </span> {{ $t('company.hr_job_openings_title') }}
    </span>

    <div class="br3 bg-white box z-1 relative">
      <div v-for="jobOpening in data.jobOpenings" :key="jobOpening.id" class="pa3 flex justify-between items-center bb bb-gray bb-gray-hover opening-item">
        <div>
          <p class="fw4 mt0 mb1">
            {{ jobOpening.title }}
          </p>
          <p v-if="jobOpening.team" class="ma0 f7 gray"><inertia-link :href="jobOpening.team.url">{{ jobOpening.team.name }}</inertia-link></p>
        </div>

        <inertia-link :href="jobOpening.url" class="icon-view no-underline">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
          </svg>
        </inertia-link>
      </div>

      <div class="ph3 pv2 tc f6">
        <inertia-link :href="data.url_view_all">{{ $t('company.hr_job_openings_view_all', {count: data.count}) }}</inertia-link>
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
