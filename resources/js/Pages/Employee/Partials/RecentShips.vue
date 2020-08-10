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
    <h3 class="db fw5 mb3 flex justify-between items-center">
      <span>
        <span class="mr1">
          🚀
        </span> {{ $t('employee.recent_ship_title') }}

        <help :url="$page.help_links.team_recent_ship" :top="'2px'" />
      </span>
    </h3>

    <div class="mb4 bg-white box cf">
      <!-- list of employees -->
      <div v-show="ships.length > 0">
        <div v-for="ship in ships" :key="ship.id" class="pa3 bb bb-gray w-100 flex justify-between ships-list">
          <inertia-link :href="ship.url" class="ma0 pa0" :data-cy="'ship-list-item-' + ship.id">{{ ship.title }}</inertia-link>
          <ul class="list ma0">
            <li v-for="employee in ship.employees" :key="employee.id" class="mr1 di">
              <inertia-link :href="employee.url" class="ship-avatar"><img loading="lazy" :src="employee.avatar" class="br-100 relative mr1 dib-ns dn" alt="avatar" :data-cy="'ship-list-' + ship.id + '-avatar-' + employee.id" /></inertia-link>
            </li>
          </ul>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="ships.length == 0" class="pa3" data-cy="recent-ships-list-blank-state">
        <p class="mb0 mt0 lh-copy f6">{{ $t('employee.recent_ship_list_blank') }}</p>
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
    ships: {
      type: Array,
      default: null,
    },
  },

  methods: {
  }
};

</script>
