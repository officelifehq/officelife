<style lang="scss" scoped>
.graffiti {
  background-image: url('/img/confetti.png');
  background-repeat: repeat-x;
  background-position: top;
}
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <breadcrumb :with-box="true"
                  :root-url="'/' + $page.props.auth.company.id + '/dashboard'"
                  :root="$t('app.breadcrumb_dashboard')"
                  :previous-url="'/' + $page.props.auth.company.id + '/teams/' + team.id"
                  :previous="team.name"
      >
        {{ $t('app.breadcrumb_team_show_recent_ships') }}
      </breadcrumb>

      <!-- BODY -->
      <div class="mw7 center br3 mb5 bg-white box relative z-1 graffiti">
        <div class="pa3 center">
          <h2 class="tc normal mb4 mt6">
            {{ $t('team.recent_ships_index', { name: team.name}) }}
          </h2>

          <!-- List of recent ships -->
          <div v-for="recentShip in ships" :key="recentShip.id" data-cy="recent-ships-list" class="flex justify-between ba bb-gray br3 bb-gray-hover pa3 mb3">
            <p class="pa0 ma0 f4"><inertia-link :href="recentShip.url">{{ recentShip.title }}</inertia-link></p>

            <!-- list of employees -->
            <ul class="list ma0">
              <li v-for="employee in recentShip.employees" :key="employee.id" class="mr1 di">
                <avatar :avatar="employee.avatar" :url="employee.url" :size="23" :class="'br-100 relative mr1 dib-ns dn'" />
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import Layout from '@/Shared/Layout';
import Breadcrumb from '@/Shared/Layout/Breadcrumb';
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
    Breadcrumb,
    Avatar,
  },

  props: {
    notifications: {
      type: Array,
      default: null,
    },
    team: {
      type: Object,
      default: null,
    },
    ships: {
      type: Array,
      default: null,
    },
  },

  data() {
    return {
      idToDelete: 0,
    };
  },

  mounted() {
    if (localStorage.success) {
      this.flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },
};

</script>
