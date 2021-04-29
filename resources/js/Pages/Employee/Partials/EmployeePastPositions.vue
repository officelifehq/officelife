<style lang="scss" scoped>
.position-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.position-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🧑‍🎓
      </span> {{ $t('employee.past_position_history_title') }}
    </span>

    <div class="br3 bg-white box z-1">
      <ul v-if="positions" data-cy="e-coffee-list" class="list pl0 ma0">
        <li v-for="entry in positions" :key="entry.id" class="pa3 bb bb-gray bb-gray-hover flex items-center justify-between position-item">
          <span>{{ entry.position }}</span>

          <span class="gray">
            {{ entry.started_at }}
            <span v-if="entry.ended_at"> - {{ entry.ended_at }}</span>
            <span v-else> - Present</span>
          </span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    positions: {
      type: Object,
      default: null,
    },
  },
};
</script>
