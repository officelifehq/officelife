<template>
  <div class="mb4">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        🥇
      </span> Open job positions
    </span>

    <div class="br3 bg-white box z-1 relative">
      <div v-for="jobOpening in data.jobOpenings" :key="jobOpening.id" class="pa3 flex justify-between items-center">
        <div>
          <h2 class="fw4 mt0 mb0">
            {{ jobOpening.title }}
          </h2>
        </div>
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
