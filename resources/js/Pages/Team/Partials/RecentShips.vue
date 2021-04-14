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
        </span> {{ $t('team.recent_ship_title') }}

        <help :url="$page.props.help_links.team_recent_ship" :top="'2px'" />
      </span>

      <inertia-link v-if="teamMemberOrAtLeastHR" :href="'/' + $page.props.auth.company.id + '/teams/' + team.id + '/ships/create'" class="btn f5" data-cy="add-recent-ship-entry">{{ $t('team.recent_ship_list_cta') }}</inertia-link>
    </h3>

    <div class="mb4 bg-white box cf">
      <!-- list of employees -->
      <div v-show="recentShips.length > 0" class="">
        <div v-for="ship in recentShips" :key="ship.id" class="pa3 bb bb-gray w-100 flex justify-between ships-list" :data-cy="'ships-list-' + ship.id">
          <inertia-link :href="ship.url" class="ma0 pa0" :data-cy="'recent-ship-list-' + ship.id">{{ ship.title }}</inertia-link>
          <ul class="list ma0">
            <li v-for="employee in ship.employees" :key="employee.id" class="mr1 di">
              <avatar :avatar="employee.avatar" :url="employee.url" :size="17" :class="'br-100 relative mr1 dib-ns dn'" />
            </li>
          </ul>
        </div>

        <div class="ph3 pv2 tc f6 bb-gray">
          <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id + '/ships'" data-cy="view-all-ships">{{ $t('team.recent_ship_view_all') }}</inertia-link>
        </div>
      </div>

      <!-- blank state -->
      <div v-show="recentShips.length == 0" class="pa3 tc" data-cy="recent-ships-list-blank-state">
        <p class="mv0">{{ $t('team.recent_ship_list_blank') }}</p>
      </div>
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
    recentShips: {
      type: Array,
      default: null,
    },
    team: {
      type: Object,
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
