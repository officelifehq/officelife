<style lang="scss" scoped>
</style>

<template>
  <div class="mb5" data-cy="contract-renewal">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        🏔
      </span> {{ $t('dashboard.contract_renewal_date_title') }}

      <help :url="$page.props.help_links.one_on_ones" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <img loading="lazy" src="/img/streamline-icon-wet-floor-sign@140x140.png" width="60" alt="meeting" class="judge absolute-ns di-ns dn top-1 right-1" />

      <p class="pa3">{{ $t('dashboard.contract_renewal_date', { date: contractRenewal.contract_renewed_at, count: contractRenewal.number_of_days }) }}</p>
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
    contractRenewal: {
      type: Array,
      default: null,
    },
  },
};
</script>
