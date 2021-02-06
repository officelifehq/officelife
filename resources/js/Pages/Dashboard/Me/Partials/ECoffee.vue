<style lang="scss" scoped>
.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        ☕️
      </span> E-coffee

      <help :url="$page.props.help_links.one_on_ones" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <img loading="lazy" src="/img/streamline-icon-work-desk-sofa-1-3@140x140.png" width="90" alt="meeting" class="judge absolute-ns di-ns dn top-1 left-1" />

      You have been matched with {{ ecoffee.otherEmployee }}
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
    employee: {
      type: Object,
      default: null,
    },
    ecoffee: {
      type: Array,
      default: null,
    },
  },
};
</script>
