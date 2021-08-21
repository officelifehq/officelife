<template>
  <div class="center br3 mb5 tc">
    <div class="cf dib btn-group">
      <inertia-link :href="stats.rejected.url" :class="{ 'selected': tab === 'rejected'}" class="f6 fl ph3 pv2 dib pointer no-underline">
        {{ $t('dashboard.job_opening_show_tab_rejected', {count: stats.rejected.count}) }}
      </inertia-link>
      <inertia-link :href="stats.to_sort.url" :class="{ 'selected': tab === 'to_sort'}" class="f6 fl ph3 pv2 dib pointer no-underline">
        {{ $t('dashboard.job_opening_show_tab_to_sort', {count: stats.to_sort.count}) }}
      </inertia-link>
      <inertia-link :href="stats.selected.url" :class="{ 'selected': tab === 'selected'}" class="f6 fl ph3 pv2 dib pointer no-underline">
        {{ $t('dashboard.job_opening_show_tab_selected', {count: stats.selected.count}) }}
      </inertia-link>
    </div>
  </div>
</template>


<script>
export default {
  props: {
    stats: {
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
