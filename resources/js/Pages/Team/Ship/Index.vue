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
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 bg-white box center breadcrumb relative z-0 f6 pb2">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard'">{{ $t('app.breadcrumb_dashboard') }}</inertia-link>
          </li>
          <li class="di">
            ...
          </li>
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id">{{ team.name }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_team_show_recent_ships') }}
          </li>
        </ul>
      </div>

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
                <avatar :avatar="employee.avatar" :url="employee.url" :size="23" :classes="'br-100 relative mr1 dib-ns dn'" />
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
import Avatar from '@/Shared/Avatar';

export default {
  components: {
    Layout,
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
