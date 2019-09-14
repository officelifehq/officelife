<style lang="scss" scoped>
.team-item {
  border-width: 1px;
  border-color: transparent;

  &.selected {
    background-color: #e1effd;
    color: #3682df;
  }

  &:not(:last-child) {
    margin-right: 15px;
  }
}
</style>

<template>
  <layout title="Home" :notifications="notifications">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center">
        <h2 class="tc fw5">
          {{ company.name }}
        </h2>
      </div>

      <div class="cf mw7 center br3 mb5 tc">
        <div class="cf dib btn-group">
          <inertia-link :href="'/' + company.id + '/dashboard/me'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'me')}">
            Me
          </inertia-link>
          <inertia-link :href="'/' + company.id + '/dashboard/team'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'team')}" data-cy="dashboard-team-tab">
            My team
          </inertia-link>
          <inertia-link :href="'/' + company.id + '/dashboard/company'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'company')}" data-cy="dashboard-company-tab">
            My company
          </inertia-link>
          <inertia-link :href="'/' + company.id + '/dashboard/hr'" class="f6 fl ph3 pv2 dib pointer" :class="{'selected':($page.auth.user.default_dashboard_view == 'hr')}" data-cy="dashboard-hr-tab">
            HR area
          </inertia-link>
        </div>
      </div>

      <div v-show="teams.length > 1" class="cf mw7 center mb3">
        <ul class="list mt0 mb3 pa0 center">
          <li class="di mr2 black-30">
            {{ $t('dashboard.team_viewing') }}
          </li>
          <li v-for="team in teams" :key="team.id" class="di team-item pa2 br2 pointer" :class="{ selected: currentTeam == team.id }" :data-cy="'team-selector-' + team.id ">
            <inertia-link :href="'/' + $page.auth.company.id + '/dashboard/team/' + team.id">
              {{ team.name }}
            </inertia-link>
          </li>
        </ul>
      </div>

      <!-- When there is no team associated with this person -->
      <div v-show="teams.length == 0" class="cf mw7 center br3 mb3 bg-white box">
        <div class="pa3 tc">
          {{ $t('dashboard.team_no_team_yet') }}
        </div>
      </div>

      <!-- Only when there are teams -->
      <my-team-worklogs
        :teams="teams"
        :worklog-dates="worklogDates"
        :worklog-entries="worklogEntries"
        :current-team="currentTeam"
        :current-date="currentDate"
        :company="company"
      />

      <div v-show="teams.length != 0" class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <h2>Team</h2>
          <ul>
            <li>team agenda</li>
            <li>anniversaires</li>
            <li>latest news</li>
            <li>view who is at work or from home</li>
            <li>view team activities</li>
            <li>managers: view direct reports</li>
            <li>manager: view time off requests</li>
            <li>manager: view morale</li>
            <li>manager: expense approval</li>
            <li>manager: one on one</li>
            <li>revue 360 de son boss ou d'employées</li>
            <li>gérer les renouvellements de contrats des équipes temporaires</li>
          </ul>
        </div>
      </div>

      <div v-show="teams.length != 0" class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="pa3">
          <h2>Me</h2>
          <ul>
            <li>View holidays</li>
            <li>view activities</li>
            <li>Book time off</li>
            <li>Log morale</li>
            <li>Log an expense</li>
            <li>View one on ones</li>
            <li>View all my tasks</li>
          </ul>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>
import MyTeamWorklogs from '@/Pages/Dashboard/MyTeamWorklogs';
import Layout from '@/Shared/Layout';

export default {
  components: {
    Layout,
    MyTeamWorklogs,
  },

  props: {
    company: {
      type: Object,
      default: null,
    },
    employee: {
      type: Object,
      default: null,
    },
    teams: {
      type: Array,
      default: null,
    },
    worklogDates: {
      type: Array,
      default: null,
    },
    worklogEntries: {
      type: Array,
      default: null,
    },
    currentDate: {
      type: String,
      default: null,
    },
    currentTeam: {
      type: Number,
      default: null,
    },
    notifications: {
      type: Array,
      default: null,
    },
    ownerPermissionLevel: {
      type: Number,
      default: 0,
    },
  },

  methods: {
  }
};
</script>
