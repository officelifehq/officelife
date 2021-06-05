<style lang="scss" scoped>
.entry-item:first-child {
  border-top-width: 1px;
  border-top-style: solid;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

.entry-item:last-child {
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
}

.avatar {
  left: 1px;
  top: 5px;
  width: 35px;
}

.team-member {
  padding-left: 44px;

  .avatar {
    top: 2px;
  }
}

.top-1 {
  top: 0.3rem;
}
</style>

<template>
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5 relative">
      <span class="mr1">
        🏔
      </span> {{ $t('dashboard.manager_contract_renewal_dates_title') }}

      <help :url="$page.props.help_links.contract_renewal_dashboard" />
    </div>

    <div class="cf mw7 center br3 mb3 bg-white box relative">
      <img loading="lazy" src="/img/streamline-icon-wet-floor-sign@140x140.png" width="90" alt="meeting" class="absolute-ns di-ns dn top-1 right-1" />

      <ul class="pr6-ns pl3 pb3 pt3 pr3 ma0">
        <li v-for="directReport in contractRenewals" :key="directReport.id" class="flex justify-between items-center br bl bb bb-gray bb-gray-hover pa3 entry-item">
          <!-- identity -->
          <div>
            <span class="pl3 db relative team-member">
              <avatar :avatar="directReport.avatar" :size="35" :class="'br-100 absolute avatar'" />
              <inertia-link :href="directReport.url" class="mb2">{{ directReport.name }}</inertia-link>
              <span class="title db f7 mt1">
                {{ directReport.position }}
              </span>
            </span>
          </div>

          <!-- call to action -->
          <div v-if="! directReport.contract_information.late" class="tr">
            <span class="db">{{ $t('dashboard.manager_contract_renewal_dates_ends_on', { date: directReport.contract_information.contract_renewed_at}) }}</span>
            <span class="f7">{{ $t('dashboard.manager_contract_renewal_dates_ends_range', directReport.contract_information.number_of_days, { count: directReport.contract_information.number_of_days}) }}</span>
          </div>
          <div v-else class="tr">
            <span>{{ $t('dashboard.manager_contract_renewal_dates_ended_on', { date: directReport.contract_information.contract_renewed_at}) }}</span>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Help,
    Avatar,
  },

  props: {
    employee: {
      type: Object,
      default: null,
    },
    contractRenewals: {
      type: Array,
      default: null,
    },
  },
};
</script>
