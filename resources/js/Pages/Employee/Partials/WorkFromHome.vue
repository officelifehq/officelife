<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🏡
      </span> {{ $t('employee.work_from_home_title') }}
    </span>

    <div class="br3 bg-white box z-1">
      <div class="pa3">
        <p v-if="statistics.work_from_home_today" class="mb0 mt0 lh-copy f5" data-cy="work-from-home-today">
          {{ $t('employee.work_from_home_today') }}
        </p>
        <p v-else class="mb0 mt0 lh-copy f5" data-cy="work-from-home-not-today">
          {{ $t('employee.work_from_home_not_today') }}
        </p>
        <p v-if="statistics.number_times_this_year > 0" class="mb0 mt2 lh-copy f7 gray" data-cy="work-from-home-statistics">
          🎯 {{ $tc('employee.work_from_home_statistics', statistics.number_times_this_year, { count: statistics.number_times_this_year}) }}
        </p>
      </div>
      <div v-if="permissions.can_see_work_from_home_history" class="ph3 pv2 tc f6 bt bb-gray">
        <inertia-link :href="statistics.url" data-cy="view-all-work-from-home">{{ $t('employee.work_from_home_link') }}</inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    employee: {
      type: Object,
      default: null,
    },
    permissions: {
      type: Object,
      default: null,
    },
    statistics: {
      type: Object,
      default: null,
    },
  },
};
</script>
