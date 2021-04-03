<style lang="scss" scoped>
</style>

<template>
  <layout :notifications="notifications">
    <div class="ph2 ph5-ns">
      <!-- BREADCRUMB -->
      <div class="mt4-l mt1 mw6 br3 center breadcrumb relative z-0 f6 pb2" :class="{'bg-white box': teams.length == 0}">
        <ul class="list ph0 tc-l tl">
          <li class="di">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/company'">{{ $t('app.breadcrumb_company') }}</inertia-link>
          </li>
          <li class="di">
            {{ $t('app.breadcrumb_team_list') }}
          </li>
        </ul>
      </div>

      <div class="cf mw7 center" :class="{'bg-white box relative z-1': teams.length == 0}">
        <!-- list of teams -->
        <div v-for="team in teams" v-show="teams.length > 0" :key="team.id" class="bg-white box mb4 pa3">
          <div class="mb2">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/teams/' + team.id" class="mr1">{{ team.name }}</inertia-link>
            <span class="f7">
              ({{ $tc('team.index_count', team.employees.length, { count: team.employees.length}) }})
            </span>
          </div>

          <!-- team description -->
          <div class="parsed-content" v-html="team.parsed_description"></div>

          <!-- team members -->
          <ul v-show="team.employees.length > 0" class="list relative pl0 mb0">
            <li v-for="employee in team.employees" :key="employee.id" class="di relative">
              <avatar :avatar="employee.avatar" :url="employee.url" :size="23" :classes="'br-100 mr2'" />
            </li>
          </ul>
        </div>

        <!-- no teams yet in the account -->
        <div v-show="teams.length == 0">
          <p class="tc measure center mb4 lh-copy">
            {{ $t('team.team_list_blank') }}
          </p>

          <img loading="lazy" height="140" class="db center mb4" alt="no teams in account" src="/img/streamline-icon-designer-team-6@140x140.png" />
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
    teams: {
      type: Array,
      default: null,
    },
  },

  mounted() {
    if (localStorage.success) {
      flash(localStorage.success, 'success');
      localStorage.removeItem('success');
    }
  },

  methods: {
  }
};

</script>
