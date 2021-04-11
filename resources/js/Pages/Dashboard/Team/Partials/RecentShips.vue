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
      🚀 {{ $t('dashboard.team_recent_ships') }}
    </div>

    <div class="cf mw7 center br3 mb4 bg-white box">
      <!-- list of employees -->
      <div v-show="recentShips.length > 0" class="">
        <div v-for="ship in recentShips" :key="ship.id" class="pa3 bb bb-gray w-100 flex justify-between ships-list" :data-cy="'ships-list-' + ship.id">
          <inertia-link :href="ship.url" class="ma0 pa0" :data-cy="'ship-list-item-' + ship.id">{{ ship.title }}</inertia-link>
          <ul class="list ma0">
            <li v-for="employee in ship.employees" :key="employee.id" class="mr1 di">
              <avatar :avatar="employee.avatar" :url="employee.url" :size="17" :class="'br-100 relative mr1 dib-ns dn'" />
            </li>
          </ul>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="recentShips.length == 0" class="pa3 tc" data-cy="recent-ships-list-blank-state">
        <p class="mv0">{{ $t('dashboard.team_recent_ship_list_blank') }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Avatar,
  },

  props: {
    recentShips: {
      type: Array,
      default: null,
    },
    team: {
      type: Number,
      default: null,
    },
    userBelongsToTheTeam: {
      type: Boolean,
      default: false,
    }
  },

  computed: {
    teamMemberOrAtLeastHR() {
      if (this.$page.props.auth.employee.permission_level <= 200) {
        return true;
      }

      if (this.userBelongsToTheTeam === false) {
        return false;
      }

      return true;
    }
  }
};
</script>
