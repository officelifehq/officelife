<style lang="scss" scoped>
li:last-child {
  margin-bottom: 0;
}
</style>

<template>
  <div class="mb4 relative">
    <span class="db fw5 mb2 relative">
      <span class="mr1">
        👨‍💻
      </span> {{ $t('employee.hardware_title') }}

      <help :url="$page.props.help_links.hardware" :datacy="'help-icon-hardware'" />
    </span>

    <div v-if="hardware.length > 0" class="br3 bg-white box z-1 pa3">
      <ul class="mv0 pl0 list">
        <li v-for="item in hardware" :key="item.id" class="mb2" :data-cy="'hardware-item-' + item.id">
          {{ item.name }}
        </li>
      </ul>
    </div>

    <!-- case of no hardware -->
    <div v-else class="br3 bg-white box z-1 pa3">
      <p class="mb0 mt0 lh-copy f6" data-cy="hardware-blank">
        {{ $t('employee.hardware_no_info') }}
      </p>
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
    hardware: {
      type: Array,
      default: null,
    },
  },
};
</script>
