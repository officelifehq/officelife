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
  <div class="mb5">
    <div class="cf mw7 center mb2 fw5">
      🚀 {{ $t('company.recent_ships_title') }}
    </div>

    <div class="cf mw7 center br3 mb4 bg-white box">
      <!-- list of employees -->
      <div v-show="ships.length > 0" class="">
        <div v-for="ship in ships" :key="ship.id" class="pa3 bb bb-gray w-100 flex justify-between ships-list" :data-cy="'ships-list-' + ship.id">
          <inertia-link :href="ship.url" class="ma0 pa0" :data-cy="'ship-list-item-' + ship.id">{{ ship.title }}</inertia-link>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="ships.length == 0" class="pa3 tc f6" data-cy="recent-ships-list-blank-state">
        <p class="mv0">{{ $t('dashboard.team_recent_ship_list_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    ships: {
      type: Array,
      default: null,
    },
  },
};

</script>
