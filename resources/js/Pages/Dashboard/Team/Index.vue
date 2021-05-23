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
  <layout :notifications="notifications">
    <div class="ph2 ph0-ns">
      <dashboard-menu :employee="employee" />

      <div v-show="teams.length > 1" class="cf mw7 center mb3">
        <ul class="list mt0 mb3 pa0 center">
          <li class="di mr2 black-30">
            {{ $t('dashboard.team_viewing') }}
          </li>
          <li v-for="team in teams" :key="team.id" class="di team-item pa2 br2 pointer" :class="{ selected: currentTeam == team.id }" :data-cy="'team-selector-' + team.id ">
            <inertia-link :href="'/' + $page.props.auth.company.id + '/dashboard/team/' + team.id">
              {{ team.name }}
            </inertia-link>
          </li>
        </ul>
      </div>

      <div v-show="teams.length != 0">
        <worklogs
          :teams="teams"
          :worklog-dates="worklogDates"
          :worklog-entries="worklogEntries"
          :current-team="currentTeam"
          :current-date="currentDate"
          :company="company"
        />

        <upcoming-new-hires
          v-if="newHires.length > 0"
          :new-hires="newHires"
        />

        <upcoming-hiring-date-anniversaries
          v-if="hiringDateAnniversaries.length > 0"
          :hiring-date-anniversaries="hiringDateAnniversaries"
        />

        <birthdays
          :teams="teams"
          :birthdays="birthdays"
        />

        <work-from-home
          :teams="teams"
          :work-from-homes="workFromHomes"
        />

        <recent-ships
          :team="currentTeam"
          :recent-ships="recentShips"
        />
      </div>
    </div>
  </layout>
</template>

<script>
import Worklogs from '@/Pages/Dashboard/Team/Partials/Worklogs';
import Birthdays from '@/Pages/Dashboard/Team/Partials/Birthdays';
import WorkFromHome from '@/Pages/Dashboard/Team/Partials/WorkFromHome';
import RecentShips from '@/Pages/Dashboard/Team/Partials/RecentShips';
import UpcomingNewHires from '@/Pages/Dashboard/Team/Partials/UpcomingNewHires';
import UpcomingHiringDateAnniversaries from '@/Pages/Dashboard/Team/Partials/UpcomingHiringDateAnniversaries';
import Layout from '@/Shared/Layout';
import DashboardMenu from '@/Pages/Dashboard/Partials/DashboardMenu';

export default {
  components: {
    Layout,
    Worklogs,
    Birthdays,
    DashboardMenu,
    WorkFromHome,
    RecentShips,
    UpcomingHiringDateAnniversaries,
    UpcomingNewHires,
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
    birthdays: {
      type: Array,
      default: null
    },
    workFromHomes: {
      type: Array,
      default: null
    },
    recentShips: {
      type: Array,
      default: null
    },
    newHires: {
      type: Array,
      default: null
    },
    hiringDateAnniversaries: {
      type: Array,
      default: null
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
};
</script>
