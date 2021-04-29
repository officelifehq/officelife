<style lang="scss" scoped>
.ecoffee-item:first-child:hover {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.ecoffee-item:last-child {
  border-bottom: 0;

  &:hover {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
  }
}

.avatar {
  top: 1px;
  left: 0;
  width: 35px;
}

.employee-name,
.position {
  padding-left: 42px;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2">
      <span class="mr1">
        🎓
      </span> Position history in the company

      <help :url="$page.props.help_links.ecoffee" />
    </span>

    <div class="br3 bg-white box z-1">
      <ul v-if="positions" data-cy="e-coffee-list" class="list pl0 ma0">
        <li v-for="entry in positions" :key="entry.id" class="">
          {{ entry.position }} ({{ entry.started_at }} --> <span v-if="entry.ended_at">{{ entry.ended_at }}</span>)
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
    positions: {
      type: Object,
      default: null,
    },
  },
};
</script>
