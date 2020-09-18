<style lang="scss" scoped>
.ship-avatar {
  img {
    width: 17px;
  }

  text-decoration: none;
  border-bottom: none;
}

.ships-list:last-child {
  border-bottom: 0;
}
</style>

<template>
  <div>
    <span class="db fw5 mb2">
      <span class="mr1">
        🏔
      </span> {{ $t('employee.one_on_one_title') }}

      <help :url="$page.help_links.one_on_ones" :top="'2px'" />
    </span>

    <div class="mb4 bg-white box cf">
      <div v-show="oneOnOnes.entries.length > 0">
        <div v-for="oneOnOne in oneOnOnes.entries" :key="oneOnOne.id" class="pa3 bb bb-gray w-100 flex justify-between ships-list">
          <inertia-link :href="oneOnOne.url" class="ma0 pa0" :data-cy="'entry-item-' + oneOnOne.id">{{ $t('employee.one_on_one_detail', { date: oneOnOne.happened_at, name: oneOnOne.manager.name }) }}</inertia-link>
        </div>
        <div class="ph3 pv2 tc f6">
          <inertia-link :href="oneOnOnes.view_all_url" data-cy="view-all-one-on-ones">{{ $t('employee.one_on_one_view_all') }}</inertia-link>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="oneOnOnes.entries.length == 0" class="pa3" data-cy="list-blank-state">
        <p class="mb0 mt0 lh-copy f6">{{ $t('employee.one_on_one_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import Help from '@/Shared/Help';

export default {
  components: {
    Help
  },

  props: {
    oneOnOnes: {
      type: Object,
      default: null,
    },
  },

  methods: {
  }
};

</script>
